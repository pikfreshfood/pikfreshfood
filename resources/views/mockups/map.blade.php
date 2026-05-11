@extends('layouts.app')

@section('title', 'Map View - PikFreshFood')

@section('styles')
<style>
    .main-content { padding-bottom: 86px; }
    .map-page { min-height: calc(100vh - 150px); }
    .map-container { width: 100%; height: 60vh; background: linear-gradient(135deg, var(--map-gradient-1) 0%, var(--map-gradient-2) 100%); position: relative; }
    .map-marker { position: absolute; width: 40px; height: 40px; background: white; border: 3px solid var(--primary-color); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); cursor: pointer; }
    .user-marker { position: absolute; width: 30px; height: 30px; top: 50%; left: 50%; transform: translate(-50%, -50%); background: var(--primary-color); border: 3px solid white; border-radius: 50%; box-shadow: 0 0 0 4px var(--primary-color); }
    .user-marker::after { content: ''; position: absolute; width: 100%; height: 100%; border: 2px solid var(--primary-color); border-radius: 50%; animation: pulse 2s infinite; }
    @keyframes pulse { 0% { width: 100%; height: 100%; opacity: 1; } 100% { width: 200%; height: 200%; opacity: 0; } }

    .map-label { position: absolute; padding: 6px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; box-shadow: 0 2px 6px rgba(0,0,0,0.1); white-space: nowrap; }
    body.safplace-theme .map-label { background: #ffc107 !important; color: #1a3a2a !important; }

    .bottom-sheet { position: fixed; bottom: 64px; width: 100%; background: var(--bottom-sheet-bg); border-radius: 16px 16px 0 0; box-shadow: 0 -2px 16px rgba(0,0,0,0.1); max-height: 32vh; overflow-y: auto; z-index: 50; }
    .bottom-sheet-handle { width: 40px; height: 4px; background: #ddd; border-radius: 2px; margin: 10px auto 15px; }
    .bottom-sheet-header { padding: 0 15px 10px; font-size: 14px; font-weight: bold; color: var(--text-color); }

    .vendor-list { display: flex; flex-direction: column; gap: 10px; padding: 10px 15px; }
    .vendor-item { border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s; border: 2px solid transparent; }
    .vendor-item:hover { border-color: var(--primary-color); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .vendor-item-header { display: flex; gap: 10px; align-items: flex-start; }
    .vendor-icon { font-size: 30px; }
    .vendor-item-info { flex: 1; }
    .vendor-name { font-weight: bold; font-size: 13px; }
    .vendor-distance { font-size: 11px; }
    .vendor-rating { font-size: 11px; color: #f39c12; }
    .vendor-products { font-size: 11px; margin-top: 4px; }
    .vendor-action { text-align: right; }
    .action-btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 11px; }

    .map-controls { position: absolute; right: 15px; top: 20px; display: flex; flex-direction: column; gap: 8px; z-index: 10; }
    .control-btn { width: 40px; height: 40px; background: white; border: 1px solid #ddd; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 16px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
</style>
@endsection

@section('content')
<div class="map-page">
    <div class="map-container">
        <div class="user-marker"></div>

        <div class="map-marker" style="top: 20%; left: 25%;">V1</div>
        <div class="map-label" style="top: 18%; left: 27%;">Mama Chidi</div>

        <div class="map-marker" style="top: 35%; left: 65%;">V2</div>
        <div class="map-label" style="top: 33%; left: 67%;">Uncle Emeka</div>

        <div class="map-marker" style="top: 60%; left: 40%;">V3</div>
        <div class="map-label" style="top: 58%; left: 42%;">Sister Blessing</div>

        <div class="map-marker" style="top: 70%; left: 75%;">V4</div>
        <div class="map-label" style="top: 68%; left: 77%;">Brother Tunde</div>

        <div class="map-controls">
            <div class="control-btn">+</div>
            <div class="control-btn">-</div>
            <div class="control-btn">Go</div>
        </div>
    </div>

    <div class="bottom-sheet">
        <div class="bottom-sheet-handle"></div>
        <div class="bottom-sheet-header">Nearby Vendors (4 found)</div>
        <div class="vendor-list">
            <div class="vendor-item">
                <div class="vendor-item-header">
                    <div class="vendor-icon">1</div>
                    <div class="vendor-item-info">
                        <div class="vendor-name">Mama Chidi's Farm Fresh</div>
                        <div class="vendor-distance">300m away • 20 mins</div>
                        <div class="vendor-rating">4.9 (1200 reviews)</div>
                        <div class="vendor-products">Tomatoes • Vegetables • Garden Eggs</div>
                    </div>
                    <div class="vendor-action">
                        <button class="action-btn">View</button>
                    </div>
                </div>
            </div>

            <div class="vendor-item">
                <div class="vendor-item-header">
                    <div class="vendor-icon">2</div>
                    <div class="vendor-item-info">
                        <div class="vendor-name">Uncle Emeka's Organic</div>
                        <div class="vendor-distance">550m away • 25 mins</div>
                        <div class="vendor-rating">4.7 (890 reviews)</div>
                        <div class="vendor-products">Grains • Nuts • Rice</div>
                    </div>
                    <div class="vendor-action">
                        <button class="action-btn">View</button>
                    </div>
                </div>
            </div>

            <div class="vendor-item">
                <div class="vendor-item-header">
                    <div class="vendor-icon">3</div>
                    <div class="vendor-item-info">
                        <div class="vendor-name">Sister Blessing's Fresh Foods</div>
                        <div class="vendor-distance">420m away • 22 mins</div>
                        <div class="vendor-rating">4.8 (1050 reviews)</div>
                        <div class="vendor-products">All Vegetables • Fruits • Condiments</div>
                    </div>
                    <div class="vendor-action">
                        <button class="action-btn">View</button>
                    </div>
                </div>
            </div>

            <div class="vendor-item">
                <div class="vendor-item-header">
                    <div class="vendor-icon">4</div>
                    <div class="vendor-item-info">
                        <div class="vendor-name">Brother Tunde - Premium</div>
                        <div class="vendor-distance">650m away • 30 mins</div>
                        <div class="vendor-rating">4.6 (756 reviews)</div>
                        <div class="vendor-products">Seafood • Poultry • Meat</div>
                    </div>
                    <div class="vendor-action">
                        <button class="action-btn">View</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
