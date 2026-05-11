@extends('admin.layouts.app')

@section('title', 'Admin Barcodes - PikFreshFood')
@section('page_title', 'Barcodes')
@section('page_copy', 'Create scan QR codes, store background information, and manage generated QR images')

@section('styles')
.grid {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    gap: 12px;
}
.panel {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 14px;
}
.panel-title {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.panel-title svg {
    width: 18px;
    height: 18px;
    stroke: var(--dark-soft);
    fill: none;
    stroke-width: 1.9;
    stroke-linecap: round;
    stroke-linejoin: round;
}
.form-grid {
    display: grid;
    gap: 10px;
}
label {
    display: block;
    margin-bottom: 5px;
    font-size: 0.82rem;
    color: var(--muted);
    font-weight: 700;
}
input[type="text"], textarea {
    width: 100%;
    border: 1px solid var(--line);
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 0.9rem;
    background: #fff;
    color: var(--text);
}
textarea {
    min-height: 120px;
    resize: vertical;
}
.btn {
    min-height: 42px;
    padding: 0 14px;
    border-radius: 10px;
    border: 1px solid #6cbbe6;
    background: var(--accent-soft);
    color: #08334d;
    font-weight: 800;
    cursor: pointer;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    text-align: left;
    padding: 10px;
    border-bottom: 1px solid var(--line);
    font-size: 0.84rem;
    vertical-align: top;
}
th {
    color: var(--muted);
    font-size: 0.74rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.barcode-preview {
    width: 200px;
    max-width: 100%;
    border: 1px solid var(--line);
    border-radius: 8px;
    background: #fff;
}
.pill {
    display: inline-flex;
    border: 1px solid #c8d8f2;
    background: #f1f7ff;
    color: #21497a;
    border-radius: 999px;
    padding: 4px 9px;
    font-size: 0.72rem;
    font-weight: 800;
}
.btn-danger {
    min-height: 34px;
    padding: 0 10px;
    border-radius: 8px;
    border: 1px solid #e19696;
    background: #ffe7e7;
    color: #8f2323;
    font-weight: 800;
    cursor: pointer;
}
.btn-download {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 34px;
    padding: 0 10px;
    border-radius: 8px;
    border: 1px solid #8abbe0;
    background: #e9f5ff;
    color: #0f4870;
    font-weight: 800;
    cursor: pointer;
    text-decoration: none;
    margin-bottom: 6px;
}
.btn-download.jpg {
    border-color: #d7b77f;
    background: #fff6e9;
    color: #6d4a10;
}
.hint {
    color: var(--muted);
    font-size: 0.8rem;
    line-height: 1.45;
}
@media (max-width: 1100px) {
    .grid {
        grid-template-columns: 1fr;
    }
}
@endsection

@section('content')
<section class="grid">
    <article class="panel">
        <h3 class="panel-title">
            <svg viewBox="0 0 24 24"><path d="M4 7v10"></path><path d="M7 7v10"></path><path d="M11 7v10"></path><path d="M15 7v10"></path><path d="M18 7v10"></path><path d="M20 7v10"></path><path d="M3 7h18"></path><path d="M3 17h18"></path></svg>
            Create Barcode
        </h3>

        <form method="POST" action="{{ route('admin.barcodes.store') }}" class="form-grid">
            @csrf
            <div>
                <label for="barcodeTitle">QR Title</label>
                <input type="text" id="barcodeTitle" name="title" value="{{ old('title') }}" maxlength="140" required>
            </div>
            <div>
                <label for="barcodeInfo">Background Information</label>
                <textarea id="barcodeInfo" name="background_information" maxlength="800" required>{{ old('background_information') }}</textarea>
            </div>
            <p class="hint">
                A unique barcode ID will be auto-generated when you submit.
                The QR image is then saved to the database and storage for scan use.
                Scan output is generated from your background information.
            </p>
            <button type="submit" class="btn">Generate & Save QR</button>
        </form>
    </article>

    <article class="panel">
        <h3 class="panel-title">
            <svg viewBox="0 0 24 24"><path d="M4 7v10"></path><path d="M7 7v10"></path><path d="M11 7v10"></path><path d="M15 7v10"></path><path d="M18 7v10"></path><path d="M20 7v10"></path><path d="M3 7h18"></path><path d="M3 17h18"></path></svg>
            QR List
        </h3>

        <table>
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Details</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barcodes as $barcode)
                    <tr>
                        <td>
                            <img class="barcode-preview" src="{{ \App\Support\PublicStorage::url($barcode->barcode_path) }}" alt="QR {{ $barcode->barcode_value }}">
                        </td>
                        <td>
                            <div style="font-weight:800; margin-bottom:4px;">{{ $barcode->title }}</div>
                            <div style="margin-bottom:6px;"><span class="pill">{{ $barcode->barcode_value }}</span></div>
                            @if($barcode->scan_text)
                                <div style="margin-bottom:6px;"><span class="pill">Scan: {{ $barcode->scan_text }}</span></div>
                            @endif
                            <div style="color:var(--muted); line-height:1.4; margin-bottom:6px;">
                                {{ \Illuminate\Support\Str::limit($barcode->background_information, 140) }}
                            </div>
                            <div style="color:var(--muted); font-size:0.78rem;">
                                By {{ $barcode->creator?->name ?? 'Admin' }} • {{ $barcode->created_at?->format('d M, Y h:i A') }}
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.barcodes.download', $barcode) }}" class="btn-download">Download</a>
                            <button
                                type="button"
                                class="btn-download jpg js-download-jpg"
                                data-image-url="{{ \App\Support\PublicStorage::url($barcode->barcode_path) }}"
                                data-file-name="{{ \Illuminate\Support\Str::slug($barcode->title ?: $barcode->barcode_value) ?: strtolower($barcode->barcode_value) }}-{{ strtolower($barcode->barcode_value) }}.jpg"
                            >
                                Download JPG
                            </button>
                            <form method="POST" action="{{ route('admin.barcodes.destroy', $barcode) }}" onsubmit="return confirm('Delete this barcode?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No barcodes created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:12px;">
            {{ $barcodes->links() }}
        </div>
    </article>
</section>
@endsection

@section('scripts')
<script>
    (function () {
        const buttons = document.querySelectorAll('.js-download-jpg');
        if (!buttons.length) {
            return;
        }

        const downloadBlob = function (blob, fileName) {
            const link = document.createElement('a');
            const objectUrl = URL.createObjectURL(blob);
            link.href = objectUrl;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            link.remove();
            URL.revokeObjectURL(objectUrl);
        };

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                const imageUrl = button.getAttribute('data-image-url');
                const fileName = button.getAttribute('data-file-name') || 'qr-code.jpg';
                if (!imageUrl) {
                    return;
                }

                const img = new Image();
                img.crossOrigin = 'anonymous';

                img.onload = function () {
                    const canvas = document.createElement('canvas');
                    canvas.width = img.naturalWidth || 360;
                    canvas.height = img.naturalHeight || 360;
                    const ctx = canvas.getContext('2d');
                    if (!ctx) {
                        return;
                    }

                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(img, 0, 0);

                    canvas.toBlob(function (blob) {
                        if (!blob) {
                            return;
                        }
                        downloadBlob(blob, fileName);
                    }, 'image/jpeg', 0.96);
                };

                img.onerror = function () {};
                img.src = imageUrl;
            });
        });
    })();
</script>
@endsection
