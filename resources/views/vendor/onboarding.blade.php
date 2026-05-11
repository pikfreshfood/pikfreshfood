@extends('layouts.app')

@section('title', ($vendor ? 'Edit Shop Information' : 'Vendor Onboarding') . ' - PikFreshFood')

@section('styles')
<style>
    .onboarding-container { max-width: 620px; margin: 40px auto; background: white; padding: 40px; border-radius: 16px; box-shadow: 0 8px 26px rgba(0,0,0,0.08); }
    .onboarding-container h1 { text-align: center; color: #27ae60; margin-bottom: 10px; }
    .onboarding-description { text-align: center; color: #7f8c8d; margin-bottom: 30px; line-height: 1.5; }
    .onboarding-form { display: flex; flex-direction: column; }
    .onboarding-input { margin-bottom: 15px; padding: 12px; border: 1px solid #ddd; border-radius: 10px; font-size: 14px; }
    .onboarding-button { background: #27ae60; color: white; padding: 15px; border: none; border-radius: 10px; cursor: pointer; font-size: 16px; font-weight: bold; }
    .onboarding-button:hover { background: #229954; }
    .location-note { font-size: 12px; color: #7f8c8d; margin-top: 5px; }
    .onboarding-link { text-align: center; margin-top: 16px; }
    .onboarding-link a { color: #27ae60; text-decoration: none; font-weight: 700; }
    .inline-actions { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px; }
    .ghost-btn { padding: 10px 14px; border-radius: 10px; border:1px solid #ddd; background:#f7f7f7; cursor:pointer; font-weight:700; }
    .preview-avatar { width: 84px; height: 84px; object-fit: cover; border-radius: 50%; margin: 0 auto 16px; display:block; background:#eef3ef; }
</style>
@endsection

@section('content')
<div class="onboarding-container">
    <h1>{{ $vendor ? 'Edit Shop Information' : 'Become a Vendor' }}</h1>
    <p class="onboarding-description">
        {{ $vendor ? 'Update your shop details any time to keep your storefront accurate and easy for customers to find.' : 'Fill in your shop details to start selling on PikFreshFood.' }}
    </p>

    <form action="{{ $vendor ? route('vendor.profile.update') : route('vendor.store') }}" method="POST" class="onboarding-form" enctype="multipart/form-data">
        @csrf
        @if($vendor)
            @method('PUT')
        @endif

        <img id="vendorProfilePreview" class="preview-avatar" src="{{ $vendor?->profile_image ? \App\Support\PublicStorage::url($vendor->profile_image) : 'https://placehold.co/160x160?text=Shop' }}" alt="Vendor profile preview">

        <input type="file" name="profile_image" id="vendorProfileImage" class="onboarding-input" accept="image/*">
        <input type="text" name="shop_name" value="{{ old('shop_name', $vendor?->shop_name) }}" placeholder="Shop Name" class="onboarding-input" required>
        <input type="text" name="description" value="{{ old('description', $vendor?->description) }}" placeholder="Shop Description" class="onboarding-input">
        <input type="text" name="phone" value="{{ old('phone', $vendor?->phone) }}" placeholder="Phone Number" class="onboarding-input" required>
        <input type="text" name="address" value="{{ old('address', $vendor?->address) }}" placeholder="Shop Address" class="onboarding-input" required>
        <div class="inline-actions">
            <button type="button" class="ghost-btn" id="detectVendorLocation">Use My Current Location</button>
            <a href="{{ route('map.index') }}" class="ghost-btn" style="text-decoration:none; color:inherit;">Open Map View</a>
        </div>
        <input type="number" step="any" name="latitude" id="vendorLatitude" value="{{ old('latitude', $vendor?->latitude) }}" placeholder="Latitude (e.g. 6.5244)" class="onboarding-input" required>
        <input type="number" step="any" name="longitude" id="vendorLongitude" value="{{ old('longitude', $vendor?->longitude) }}" placeholder="Longitude (e.g. 3.3792)" class="onboarding-input" required>
        <div class="location-note">Allow GPS or paste the coordinates manually to power nearby discovery and the live map.</div>
        <button type="submit" class="onboarding-button">{{ $vendor ? 'Save Shop Information' : 'Create Shop' }}</button>
    </form>

    @if(!$vendor)
        <div class="onboarding-link">
            <a href="{{ route('profile.edit') }}">Return to profile</a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const imageInput = document.getElementById('vendorProfileImage');
        const preview = document.getElementById('vendorProfilePreview');
        const detectButton = document.getElementById('detectVendorLocation');
        const latitudeInput = document.getElementById('vendorLatitude');
        const longitudeInput = document.getElementById('vendorLongitude');

        imageInput.addEventListener('change', function () {
            const file = imageInput.files && imageInput.files[0];
            if (!file) {
                return;
            }

            preview.src = URL.createObjectURL(file);
        });

        detectButton.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('GPS location is not supported on this device.');
                return;
            }

            navigator.geolocation.getCurrentPosition(function (position) {
                latitudeInput.value = position.coords.latitude.toFixed(6);
                longitudeInput.value = position.coords.longitude.toFixed(6);
            }, function () {
                alert('We could not detect your current location.');
            });
        });
    })();
</script>
@endsection
