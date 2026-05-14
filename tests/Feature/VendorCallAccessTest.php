<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorCallAccessTest extends TestCase
{
    use RefreshDatabase;

    private function createVendorAccount(string $shopName): array
    {
        $user = User::factory()->create([
            'role' => 'vendor',
        ]);

        $vendor = Vendor::query()->create([
            'user_id' => $user->id,
            'shop_name' => $shopName,
            'phone' => '08012345678',
            'address' => 'Market Road',
            'latitude' => 6.5243793,
            'longitude' => 3.3792057,
        ]);

        return [$user, $vendor];
    }

    public function test_vendor_can_start_call_to_another_vendor(): void
    {
        [$caller] = $this->createVendorAccount('Caller Shop');
        [, $receiverVendor] = $this->createVendorAccount('Receiver Shop');

        $response = $this
            ->actingAs($caller)
            ->postJson(route('vendor.call.online', $receiverVendor), [
                'type' => 'audio',
            ]);

        $response
            ->assertOk()
            ->assertJsonStructure(['call_url', 'call_type']);

        $this->assertDatabaseHas('call_invites', [
            'vendor_id' => $receiverVendor->id,
            'buyer_id' => $caller->id,
            'call_type' => 'audio',
            'status' => 'ringing',
        ]);
    }

    public function test_vendor_cannot_start_call_to_own_vendor_account(): void
    {
        [$caller, $callerVendor] = $this->createVendorAccount('Caller Shop');

        $response = $this
            ->actingAs($caller)
            ->postJson(route('vendor.call.online', $callerVendor), [
                'type' => 'video',
            ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'You cannot call your own vendor account.',
            ]);

        $this->assertDatabaseMissing('call_invites', [
            'vendor_id' => $callerVendor->id,
            'buyer_id' => $caller->id,
        ]);
    }
}
