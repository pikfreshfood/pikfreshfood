@extends('layouts.app')

@section('title', 'Map View - PikFreshFood')

@section('styles')
<style>
    .map-page { max-width: 1180px; margin: 30px auto; padding: 0 16px; }
    .map-hero { display: flex; justify-content: space-between; gap: 16px; align-items: end; margin-bottom: 18px; flex-wrap: wrap; }
    .map-hero h1 { color: var(--text-color); margin-bottom: 6px; }
    .map-hero p { color: var(--muted-color); max-width: 620px; }
    .map-pill-row { display: flex; flex-wrap: wrap; gap: 10px; }
    .map-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        color: var(--text-color);
        font-size: 0.9rem;
    }
    .map-layout { display: grid; grid-template-columns: minmax(0, 2fr) minmax(300px, 1fr); gap: 18px; }
    .map-stage {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 22px;
        overflow: hidden;
        min-height: 520px;
        position: relative;
        position: sticky;
        top: calc(var(--map-header-offset, 84px) + 16px);
        align-self: start;
    }
    .map-canvas { height: 100%; min-height: 520px; }
    .map-status {
        position: absolute;
        left: 16px;
        top: 16px;
        z-index: 500;
        padding: 10px 14px;
        border-radius: 999px;
        background: rgba(17, 24, 39, 0.82);
        color: #fff;
        font-size: 0.88rem;
        backdrop-filter: blur(10px);
    }
    .map-sidebar {
        display: grid;
        gap: 16px;
        align-content: start;
    }
    .map-panel {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 18px;
    }
    .map-panel h2 {
        margin: 0 0 8px;
        font-size: 1.05rem;
        color: var(--text-color);
    }
    .map-panel p {
        margin: 0;
        color: var(--muted-color);
        line-height: 1.5;
    }
    .map-nearby-list,
    .map-vendor-list {
        display: grid;
        gap: 12px;
        margin-top: 14px;
    }
    .map-vendor-card {
        display: block;
        text-decoration: none;
        color: inherit;
        background: color-mix(in srgb, var(--primary-color) 4%, var(--bottom-sheet-bg));
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 14px;
    }
    .map-vendor-top {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }
    .map-vendor-avatar {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: color-mix(in srgb, var(--primary-color) 12%, white);
        color: var(--primary-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
        font-weight: 800;
    }
    .map-vendor-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .map-vendor-meta {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: start;
    }
    .map-vendor-name {
        margin: 0;
        font-size: 0.98rem;
        color: var(--text-color);
    }
    .map-vendor-distance {
        color: var(--primary-color);
        font-weight: 800;
        white-space: nowrap;
        font-size: 0.9rem;
    }
    .map-vendor-card p {
        margin-top: 8px;
        font-size: 0.9rem;
    }
    .map-empty {
        margin-top: 14px;
        padding: 14px;
        border-radius: 14px;
        background: color-mix(in srgb, var(--border-color) 40%, transparent);
        color: var(--muted-color);
    }
    .vendor-popup {
        min-width: 190px;
    }
    .vendor-popup strong {
        display: block;
        margin-bottom: 6px;
        color: #111827;
    }
    .vendor-popup span {
        display: block;
        color: #4b5563;
        font-size: 0.9rem;
        margin-bottom: 4px;
    }
    .vendor-popup a {
        color: #0f766e;
        font-weight: 700;
        text-decoration: none;
    }
    .leaflet-popup-content-wrapper { border-radius: 16px; }
    .viewer-marker {
        width: 18px;
        height: 18px;
        border-radius: 999px;
        background: #2563eb;
        border: 3px solid rgba(255, 255, 255, 0.96);
        box-shadow: 0 0 0 8px rgba(37, 99, 235, 0.18);
    }
    .vendor-marker {
        width: 18px;
        height: 18px;
        border-radius: 999px;
        background: #f97316;
        border: 3px solid rgba(255, 255, 255, 0.96);
        box-shadow: 0 0 0 8px rgba(249, 115, 22, 0.16);
    }
    .vendor-marker.nearby {
        background: #16a34a;
        box-shadow: 0 0 0 10px rgba(22, 163, 74, 0.18);
    }
    @media (max-width: 980px) {
        .map-layout { grid-template-columns: 1fr; }
        .map-stage { position: relative; top: 0; }
        .map-stage,
        .map-canvas { min-height: 420px; }
    }
</style>
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""
>
@endsection

@section('content')
<div
    class="map-page"
    data-map-config='@json($mapConfig)'
>
    <div class="map-hero">
        <div>
            <h1>Vendor Map</h1>
        </div>
        <div class="map-pill-row">
            <span class="map-pill" id="viewerLabelPill">You: {{ $viewer['label'] }}</span>
            <span class="map-pill" id="vendorCountPill">{{ $vendors->count() }} live vendors mapped</span>
            <span class="map-pill" id="nearbyCountPill">{{ $nearbyVendors->count() }} vendors within 3 km</span>
        </div>
    </div>

    <div class="map-layout">
        <div class="map-stage">
            <div id="mapStatus" class="map-status">Loading map...</div>
            <div id="vendorMap" class="map-canvas" aria-label="Vendor map"></div>
        </div>

        <div class="map-sidebar">
            <div class="map-panel">
                <h2>Nearby vendors</h2>
                <p>These vendors are currently within 3 km of your saved or detected location.</p>

                <div id="nearbyEmptyState" class="map-empty" @if(!$nearbyVendors->isEmpty()) style="display:none;" @endif>No live vendors are within 3 km yet. Allow location access to refresh the map with your current position.</div>

                <div id="nearbyVendorList" class="map-nearby-list" @if($nearbyVendors->isEmpty()) style="display:none;" @endif>
                        @foreach($nearbyVendors as $vendor)
                            <a href="{{ route('vendor.show', $vendor) }}" class="map-vendor-card">
                                <div class="map-vendor-top">
                                    <span class="map-vendor-avatar" aria-hidden="true">
                                        @if($vendor->profile_image)
                                            <img src="{{ \App\Support\PublicStorage::url($vendor->profile_image) }}" alt="{{ $vendor->shop_name }}">
                                        @else
                                            {{ strtoupper(substr($vendor->shop_name, 0, 1)) }}
                                        @endif
                                    </span>
                                    <div class="map-vendor-meta">
                                        <div>
                                            <h3 class="map-vendor-name">{{ $vendor->shop_name }}</h3>
                                            <p>{{ $vendor->products->count() }} products available</p>
                                        </div>
                                        <span class="map-vendor-distance">{{ number_format($vendor->distance_km, 1) }} km</span>
                                    </div>
                                </div>
                                <p>{{ $vendor->address }}</p>
                            </a>
                        @endforeach
                </div>
            </div>

            <div class="map-panel">
                <h2>All mapped vendors</h2>
                <p>Green pins are within 3 km. Orange pins are farther away.</p>

                <div id="allVendorList" class="map-vendor-list">
                    @foreach($vendors->take(8) as $vendor)
                        <a href="{{ route('vendor.show', $vendor) }}" class="map-vendor-card">
                            <div class="map-vendor-meta">
                                <h3 class="map-vendor-name">{{ $vendor->shop_name }}</h3>
                                <span class="map-vendor-distance">{{ number_format($vendor->distance_km, 1) }} km</span>
                            </div>
                            <p>{{ $vendor->address }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""
></script>
<script>
    (function () {
        const root = document.querySelector('.map-page[data-map-config]');
        const header = document.querySelector('.header');

        if (!root || typeof L === 'undefined') {
            return;
        }

        const syncHeaderOffset = () => {
            const headerHeight = header ? header.offsetHeight : 84;
            document.documentElement.style.setProperty('--map-header-offset', `${headerHeight}px`);
        };

        syncHeaderOffset();
        window.addEventListener('resize', syncHeaderOffset);

        const config = JSON.parse(root.dataset.mapConfig);
        const viewer = config.viewer;
        const vendors = config.vendors || [];
        const mapStatus = document.getElementById('mapStatus');
        const viewerLabelPill = document.getElementById('viewerLabelPill');
        const vendorCountPill = document.getElementById('vendorCountPill');
        const nearbyCountPill = document.getElementById('nearbyCountPill');
        const nearbyVendorList = document.getElementById('nearbyVendorList');
        const allVendorList = document.getElementById('allVendorList');
        const nearbyEmptyState = document.getElementById('nearbyEmptyState');
        const map = L.map('vendorMap', {
            scrollWheelZoom: true,
        }).setView([viewer.latitude, viewer.longitude], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const userIcon = L.divIcon({
            className: '',
            html: '<div class="viewer-marker"></div>',
            iconSize: [18, 18],
            iconAnchor: [9, 9],
        });

        const makeVendorIcon = (isNearby) => L.divIcon({
            className: '',
            html: `<div class="vendor-marker${isNearby ? ' nearby' : ''}"></div>`,
            iconSize: [18, 18],
            iconAnchor: [9, 9],
        });

        const escapeHtml = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');

        const toRadians = (value) => value * (Math.PI / 180);

        const distanceBetween = (lat1, lng1, lat2, lng2) => {
            const earthRadius = 6371;
            const latDelta = toRadians(lat2 - lat1);
            const lngDelta = toRadians(lng2 - lng1);
            const a = Math.sin(latDelta / 2) ** 2
                + Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) * Math.sin(lngDelta / 2) ** 2;

            return Number((earthRadius * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)))).toFixed(1));
        };

        const renderAvatar = (vendor) => {
            if (vendor.profile_image_url) {
                return `<img src="${escapeHtml(vendor.profile_image_url)}" alt="${escapeHtml(vendor.name)}">`;
            }

            return escapeHtml((vendor.name || '?').charAt(0).toUpperCase());
        };

        const renderNearbyCard = (vendor) => `
            <a href="${escapeHtml(vendor.url)}" class="map-vendor-card">
                <div class="map-vendor-top">
                    <span class="map-vendor-avatar" aria-hidden="true">${renderAvatar(vendor)}</span>
                    <div class="map-vendor-meta">
                        <div>
                            <h3 class="map-vendor-name">${escapeHtml(vendor.name)}</h3>
                            <p>${vendor.products_count} products available</p>
                        </div>
                        <span class="map-vendor-distance">${Number(vendor.distance_km).toFixed(1)} km</span>
                    </div>
                </div>
                <p>${escapeHtml(vendor.address || 'No address added yet')}</p>
            </a>
        `;

        const renderAllVendorCard = (vendor) => `
            <a href="${escapeHtml(vendor.url)}" class="map-vendor-card">
                <div class="map-vendor-meta">
                    <h3 class="map-vendor-name">${escapeHtml(vendor.name)}</h3>
                    <span class="map-vendor-distance">${Number(vendor.distance_km).toFixed(1)} km</span>
                </div>
                <p>${escapeHtml(vendor.address || 'No address added yet')}</p>
            </a>
        `;

        const popupMarkup = (vendor) => `
            <div class="vendor-popup">
                <strong>${escapeHtml(vendor.name)}</strong>
                <span>${escapeHtml(vendor.address || 'No address added yet')}</span>
                <span>${Number(vendor.distance_km).toFixed(1)} km away</span>
                <span>${vendor.products_count} products${vendor.rating ? ` | Rating ${vendor.rating}` : ''}</span>
                <a href="${escapeHtml(vendor.url)}">View vendor</a>
            </div>
        `;

        let viewerMarker = L.marker([viewer.latitude, viewer.longitude], { icon: userIcon }).addTo(map);
        let viewerCircle = L.circle([viewer.latitude, viewer.longitude], {
            radius: 3000,
            color: '#16a34a',
            weight: 1.5,
            fillColor: '#16a34a',
            fillOpacity: 0.08,
        }).addTo(map);

        const vendorMarkers = new Map();

        vendors.forEach((vendor) => {
            const marker = L.marker([vendor.latitude, vendor.longitude], {
                icon: makeVendorIcon(Number(vendor.distance_km) <= 3),
            }).addTo(map).bindPopup(popupMarkup(vendor));

            vendorMarkers.set(vendor.id, marker);
        });

        const updateUi = (viewerState) => {
            const sortedVendors = vendors
                .map((vendor) => ({
                    ...vendor,
                    distance_km: distanceBetween(
                        Number(viewerState.latitude),
                        Number(viewerState.longitude),
                        Number(vendor.latitude),
                        Number(vendor.longitude)
                    ),
                }))
                .sort((a, b) => a.distance_km - b.distance_km);

            const nearbyVendors = sortedVendors.filter((vendor) => vendor.distance_km <= 3);

            viewerMarker
                .setLatLng([viewerState.latitude, viewerState.longitude])
                .bindPopup(`<div class="vendor-popup"><strong>You are here</strong><span>${escapeHtml(viewerState.label)}</span></div>`);

            viewerCircle.setLatLng([viewerState.latitude, viewerState.longitude]);

            sortedVendors.forEach((vendor) => {
                const marker = vendorMarkers.get(vendor.id);

                if (!marker) {
                    return;
                }

                marker.setIcon(makeVendorIcon(vendor.distance_km <= 3));
                marker.setPopupContent(popupMarkup(vendor));
            });

            if (viewerLabelPill) {
                viewerLabelPill.textContent = `You: ${viewerState.label}`;
            }

            if (vendorCountPill) {
                vendorCountPill.textContent = `${sortedVendors.length} live vendors mapped`;
            }

            if (nearbyCountPill) {
                nearbyCountPill.textContent = `${nearbyVendors.length} vendors within 3 km`;
            }

            if (nearbyVendorList) {
                nearbyVendorList.innerHTML = nearbyVendors.map(renderNearbyCard).join('');
                nearbyVendorList.style.display = nearbyVendors.length ? 'grid' : 'none';
            }

            if (nearbyEmptyState) {
                nearbyEmptyState.style.display = nearbyVendors.length ? 'none' : 'block';
            }

            if (allVendorList) {
                allVendorList.innerHTML = sortedVendors.slice(0, 8).map(renderAllVendorCard).join('');
            }

            const bounds = L.latLngBounds([[viewerState.latitude, viewerState.longitude]]);
            sortedVendors.forEach((vendor) => bounds.extend([vendor.latitude, vendor.longitude]));

            if (sortedVendors.length > 0) {
                map.fitBounds(bounds.pad(0.2));
            } else {
                map.setView([viewerState.latitude, viewerState.longitude], 13);
            }

            if (mapStatus) {
                mapStatus.textContent = `Showing ${nearbyVendors.length} vendors within 3 km of ${viewerState.label}.`;
            }
        };

        updateUi(viewer);

        if (!navigator.geolocation) {
            if (mapStatus) {
                mapStatus.textContent = 'Geolocation is not supported on this browser. Showing your saved location instead.';
            }
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = Number(position.coords.latitude.toFixed(6));
                const lng = Number(position.coords.longitude.toFixed(6));
                const currentLat = Number(viewer.latitude).toFixed(6);
                const currentLng = Number(viewer.longitude).toFixed(6);

                if (String(lat) === String(currentLat) && String(lng) === String(currentLng)) {
                    if (mapStatus) {
                        mapStatus.textContent = `Showing vendors within 3 km of your current location.`;
                    }
                    return;
                }

                if (mapStatus) {
                    mapStatus.textContent = 'Updating vendors near your current location...';
                }

                viewer.latitude = lat;
                viewer.longitude = lng;
                viewer.label = 'your current location';
                updateUi(viewer);
            },
            function () {
                if (mapStatus) {
                    mapStatus.textContent = 'Location access was blocked, so the map is using your saved location.';
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000,
            }
        );
    })();
</script>
@endsection
