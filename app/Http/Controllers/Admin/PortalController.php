<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminBarcode;
use App\Models\Message;
use App\Models\Product;
use App\Models\SupportChatMessage;
use App\Models\SupportChatThread;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PortalController extends Controller
{
    protected function serializeSupportMessage(SupportChatMessage $message): array
    {
        return [
            'id' => $message->id,
            'message' => $message->message,
            'sender_type' => $message->sender_type,
            'sender_name' => $message->sender_name,
            'time' => $message->created_at->diffForHumans(),
            'sent_at' => $message->created_at?->format('d M Y, H:i'),
        ];
    }

    protected function supportThreadPayload(SupportChatThread $thread): array
    {
        $thread->loadMissing('user:id,name,email');

        $thread->messages()
            ->whereIn('sender_type', ['guest', 'user'])
            ->where('is_read_by_admin', false)
            ->update(['is_read_by_admin' => true]);

        $thread->load(['messages' => function ($query) {
            $query->orderBy('created_at');
        }]);

        return [
            'thread' => [
                'id' => $thread->id,
                'name' => $thread->user?->name ?: $thread->guest_name ?: 'Guest visitor',
                'email' => $thread->user?->email ?: $thread->guest_email ?: '',
            ],
            'thread_signature' => optional($thread->messages->last())->id . ':' . $thread->messages->count(),
            'messages' => $thread->messages->map(fn (SupportChatMessage $message) => $this->serializeSupportMessage($message))->values(),
        ];
    }

    private function qrMatrixToSvg(array $rows, int $moduleSize = 8, int $paddingModules = 2): string
    {
        $dimension = count($rows);
        $padding = $paddingModules * $moduleSize;
        $size = ($dimension * $moduleSize) + ($padding * 2);

        $rects = [];
        foreach ($rows as $y => $row) {
            $chars = str_split((string) $row);
            foreach ($chars as $x => $bit) {
                if ($bit !== '1') {
                    continue;
                }

                $rx = $padding + ($x * $moduleSize);
                $ry = $padding + ($y * $moduleSize);
                $rects[] = '<rect x="' . $rx . '" y="' . $ry . '" width="' . $moduleSize . '" height="' . $moduleSize . '" fill="#000"/>';
            }
        }

        return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '">'
            . '<rect width="100%" height="100%" fill="#fff"/>'
            . implode('', $rects)
            . '</svg>';
    }

    protected function ensurePermission(Request $request, string $section): void
    {
        abort_unless($request->user()->hasAdminPermission($section), 403);
    }

    public function profile(): View
    {
        $this->ensurePermission(request(), 'profile');

        $adminUsers = User::query()
            ->where(function ($query) {
                $query->where('role', 'admin')
                    ->orWhereNotNull('admin_role');
            })
            ->latest()
            ->paginate(8, ['id', 'name', 'email', 'admin_role', 'created_at']);

        return view('admin.profile', [
            'adminUsers' => $adminUsers,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $this->ensurePermission($request, 'profile');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $request->user()->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        $this->ensurePermission($request, 'profile');
        abort_unless($request->user()->adminRole() === 'super_admin', 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'admin_role' => ['required', 'in:super_admin,manager,support,finance'],
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => DB::getDriverName() === 'sqlite' ? 'buyer' : 'admin',
            'admin_role' => $validated['admin_role'],
        ]);

        return back()->with('success', 'New admin created successfully.');
    }

    public function products(): View
    {
        $this->ensurePermission(request(), 'products');

        $products = Product::query()
            ->with('vendor:id,shop_name')
            ->latest()
            ->paginate(15);

        return view('admin.products', compact('products'));
    }

    public function shops(): View
    {
        $this->ensurePermission(request(), 'shops');

        $shops = Vendor::query()
            ->with('user:id,name,email')
            ->latest()
            ->paginate(15);

        return view('admin.shops', compact('shops'));
    }

    public function subscriptions(): View
    {
        $this->ensurePermission(request(), 'subscriptions');

        $vendors = Vendor::query()
            ->with('user:id,name,email')
            ->orderByDesc('subscription_expires_at')
            ->paginate(15);

        return view('admin.subscriptions', compact('vendors'));
    }

    public function support(): View
    {
        $this->ensurePermission(request(), 'support');

        return view('admin.support');
    }

    public function supportThreads(Request $request)
    {
        $this->ensurePermission($request, 'support');

        $threads = SupportChatThread::query()
            ->with(['user:id,name,email', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get()
            ->map(function (SupportChatThread $thread) {
                $latest = $thread->messages->first();

                return [
                    'id' => $thread->id,
                    'name' => $thread->user?->name ?: $thread->guest_name ?: 'Guest visitor',
                    'email' => $thread->user?->email ?: $thread->guest_email ?: '',
                    'preview' => $latest?->message ?: '',
                    'latest_time' => $latest?->created_at?->diffForHumans() ?: '',
                    'unread_count' => $thread->messages()
                        ->whereIn('sender_type', ['guest', 'user'])
                        ->where('is_read_by_admin', false)
                        ->count(),
                ];
            })
            ->values();

        return response()->json([
            'threads' => $threads,
        ]);
    }

    public function supportThread(Request $request, SupportChatThread $thread)
    {
        $this->ensurePermission($request, 'support');

        return response()->json($this->supportThreadPayload($thread));
    }

    public function replyToSupportThread(Request $request, SupportChatThread $thread)
    {
        $this->ensurePermission($request, 'support');

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
        ]);

        $message = SupportChatMessage::query()->create([
            'thread_id' => $thread->id,
            'sender_type' => 'admin',
            'admin_id' => $request->user()->id,
            'sender_name' => $request->user()->name,
            'message' => trim($validated['message']),
            'is_read_by_admin' => true,
            'is_read_by_client' => false,
        ]);

        $thread->update(['last_message_at' => $message->created_at]);

        return response()->json([
            'message' => 'Reply sent successfully.',
            ...$this->supportThreadPayload($thread->fresh()),
        ]);
    }

    public function emails(): View
    {
        $this->ensurePermission(request(), 'emails');

        $users = User::query()
            ->latest()
            ->paginate(15, ['id', 'name', 'email', 'role', 'created_at']);

        return view('admin.emails', compact('users'));
    }

    public function barcodes(): View
    {
        $this->ensurePermission(request(), 'barcodes');

        $barcodes = AdminBarcode::query()
            ->with('creator:id,name')
            ->latest()
            ->paginate(12);

        return view('admin.barcodes', compact('barcodes'));
    }

    public function storeBarcode(Request $request): RedirectResponse
    {
        $this->ensurePermission($request, 'barcodes');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'background_information' => ['required', 'string', 'max:800'],
        ]);

        do {
            $barcodeValue = 'PFF-' . strtoupper(Str::random(8));
        } while (AdminBarcode::query()->where('barcode_value', $barcodeValue)->exists());

        $scanText = trim($validated['background_information']);
        if ($scanText === '') {
            $scanText = $barcodeValue;
        }

        $qrlibPath = base_path('phpqrcode/qrlib.php');
        if (! file_exists($qrlibPath)) {
            return back()
                ->withInput()
                ->withErrors(['background_information' => 'phpqrcode library was not found in /phpqrcode.']);
        }

        require_once $qrlibPath;

        if (! class_exists('QRcode')) {
            return back()
                ->withInput()
                ->withErrors(['background_information' => 'QRcode class is not available from phpqrcode library.']);
        }

        try {
            if (! Storage::disk('public')->exists('barcodes')) {
                Storage::disk('public')->makeDirectory('barcodes');
            }

            $matrix = \QRcode::text($scanText, false, QR_ECLEVEL_M, 4, 2);
            if (! is_array($matrix) || empty($matrix)) {
                throw new \RuntimeException('QR matrix generation failed.');
            }

            $svg = $this->qrMatrixToSvg($matrix, 8, 2);
            $barcodePath = 'barcodes/' . $barcodeValue . '.svg';
            Storage::disk('public')->put($barcodePath, $svg);
        } catch (\Throwable $exception) {
            return back()
                ->withInput()
                ->withErrors(['background_information' => 'Could not generate QR code image using phpqrcode.']);
        }

        AdminBarcode::query()->create([
            'title' => $validated['title'],
            'barcode_value' => $barcodeValue,
            'scan_text' => $scanText,
            'background_information' => $validated['background_information'],
            'barcode_path' => $barcodePath,
            'created_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Barcode created and saved successfully.');
    }

    public function destroyBarcode(Request $request, AdminBarcode $barcode): RedirectResponse
    {
        $this->ensurePermission($request, 'barcodes');

        Storage::disk('public')->delete($barcode->barcode_path);
        $barcode->delete();

        return back()->with('success', 'Barcode deleted successfully.');
    }

    public function downloadBarcode(Request $request, AdminBarcode $barcode): StreamedResponse|RedirectResponse
    {
        $this->ensurePermission($request, 'barcodes');

        if (! Storage::disk('public')->exists($barcode->barcode_path)) {
            return back()->withErrors(['background_information' => 'QR image file was not found for download.']);
        }

        $extension = strtolower(pathinfo($barcode->barcode_path, PATHINFO_EXTENSION)) ?: 'svg';
        $safeName = Str::slug($barcode->title ?: $barcode->barcode_value);
        if ($safeName === '') {
            $safeName = strtolower($barcode->barcode_value);
        }

        $downloadName = $safeName . '-' . strtolower($barcode->barcode_value) . '.' . $extension;

        return Storage::disk('public')->download($barcode->barcode_path, $downloadName);
    }
}
