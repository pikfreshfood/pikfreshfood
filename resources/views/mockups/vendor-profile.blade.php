<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Profile - Mama Chidi's Farm Fresh</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #333; padding-bottom: 100px; }
        
        .header { background: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .back-btn { cursor: pointer; font-size: 24px; }
        .header-icons { display: flex; gap: 15px; font-size: 20px; cursor: pointer; }
        
        .vendor-header { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 30px 20px; text-align: center; }
        .vendor-avatar { font-size: 60px; margin-bottom: 10px; }
        .vendor-name { font-size: 20px; font-weight: bold; margin-bottom: 5px; }
        .vendor-badges { display: flex; gap: 8px; justify-content: center; margin-bottom: 10px; flex-wrap: wrap; }
        .badge { background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 12px; font-size: 12px; }
        .vendor-stats { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-top: 15px; }
        .stat { text-align: center; }
        .stat-number { font-weight: bold; font-size: 16px; }
        .stat-label { font-size: 12px; opacity: 0.9; }
        
        .action-buttons { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 15px; background: white; margin-bottom: 10px; }
        .btn { padding: 12px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 13px; }
        .btn-subscribe { background: #27ae60; color: white; }
        .btn-message { background: #ecf0f1; color: #333; }
        
        .info-section { background: white; padding: 20px; margin-bottom: 10px; }
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 12px; border-left: 4px solid #27ae60; padding-left: 10px; }
        .info-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #ecf0f1; }
        .info-item:last-child { border-bottom: none; }
        .info-label { color: #7f8c8d; font-size: 13px; }
        .info-value { font-weight: 500; }
        
        .delivery-zones { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .zone-badge { background: #f0f0f0; padding: 6px 12px; border-radius: 4px; font-size: 12px; }
        
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; padding: 0; }
        .product-card { background: white; border-radius: 6px; overflow: hidden; cursor: pointer; transition: transform 0.2s; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .product-image { width: 100%; height: 100px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); display: flex; align-items: center; justify-content: center; font-size: 40px; }
        .product-name { padding: 10px; font-size: 12px; font-weight: 500; }
        .product-price { padding: 0 10px; color: #27ae60; font-weight: bold; }
        .product-status { padding: 0 10px 10px; font-size: 11px; color: #27ae60; }
        
        .reviews-section { background: white; padding: 20px; margin-bottom: 10px; }
        .review-item { padding: 12px 0; border-bottom: 1px solid #ecf0f1; }
        .review-item:last-child { border-bottom: none; }
        .reviewer { display: flex; gap: 10px; }
        .reviewer-avatar { font-size: 30px; }
        .review-content { flex: 1; }
        .reviewer-name { font-weight: bold; font-size: 13px; }
        .reviewer-rating { color: #f39c12; font-size: 12px; }
        .review-text { font-size: 12px; color: #555; margin-top: 4px; line-height: 1.5; }
        
        .subscription-card { background: linear-gradient(135deg, #fff3cd 0%, #ffe0a3 100%); padding: 20px; margin: 15px; border-radius: 8px; }
        .sub-title { font-weight: bold; font-size: 14px; margin-bottom: 8px; }
        .sub-benefits { list-style: none; font-size: 12px; margin-bottom: 12px; line-height: 1.8; }
        .sub-benefits li::before { content: "✓ "; color: #27ae60; font-weight: bold; }
        .sub-btn { background: #f39c12; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; width: 100%; }
        
        .action-bar { position: fixed; bottom: 0; width: 100%; background: white; padding: 12px; display: flex; gap: 10px; box-shadow: 0 -2px 8px rgba(0,0,0,0.1); border-top: 1px solid #ecf0f1; }
        .action-bar-btn { flex: 1; padding: 12px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-order { background: #27ae60; color: white; }
        .btn-call { background: #ecf0f1; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="back-btn">←</div>
        <div class="header-icons">
            <div>❤️</div>
            <div>⋮</div>
        </div>
    </div>
    
    <!-- Vendor Header -->
    <div class="vendor-header">
        <div class="vendor-avatar">👩</div>
        <div class="vendor-name">Mama Chidi's Farm Fresh</div>
        <div class="vendor-badges">
            <div class="badge">✓ Verified</div>
            <div class="badge">⭐ Top Rated</div>
            <div class="badge">🎯 Featured</div>
        </div>
        <div class="vendor-stats">
            <div class="stat">
                <div class="stat-number">4.9</div>
                <div class="stat-label">Rating</div>
            </div>
            <div class="stat">
                <div class="stat-number">1.2k</div>
                <div class="stat-label">Reviews</div>
            </div>
            <div class="stat">
                <div class="stat-number">300m</div>
                <div class="stat-label">Distance</div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="action-buttons">
        <button class="btn btn-subscribe">⭐ Subscribe Pro</button>
        <button class="btn btn-message">💬 Message Vendor</button>
    </div>
    
    <!-- Vendor Info -->
    <div class="info-section">
        <div class="section-title">About Vendor</div>
        <div class="info-item">
            <div class="info-label">Operating Hours</div>
            <div class="info-value">6AM - 8PM Daily</div>
        </div>
        <div class="info-item">
            <div class="info-label">Joined</div>
            <div class="info-value">2 years ago</div>
        </div>
        <div class="info-item">
            <div class="info-label">Total Orders</div>
            <div class="info-value">3,400+</div>
        </div>
        <div class="info-item">
            <div class="info-label">Response Time</div>
            <div class="info-value">Usually replies in 2 mins</div>
        </div>
    </div>
    
    <!-- Delivery Info -->
    <div class="info-section">
        <div class="section-title">Delivery & Pickup</div>
        <div class="info-item">
            <div class="info-label">Delivery Time</div>
            <div class="info-value">20-30 mins</div>
        </div>
        <div class="info-item">
            <div class="info-label">Delivery Fee</div>
            <div class="info-value">Free for ₦500+ orders</div>
        </div>
        <div class="info-item">
            <div class="info-label">Service Zones</div>
        </div>
        <div class="delivery-zones">
            <div class="zone-badge">Yaba</div>
            <div class="zone-badge">Ikoyi</div>
            <div class="zone-badge">Lekki</div>
            <div class="zone-badge">Ikeja</div>
        </div>
    </div>
    
    <!-- Subscription Offer -->
    <div class="subscription-card">
        <div class="sub-title">🌟 Weekly Delivery Pro Plan</div>
        <ul class="sub-benefits">
            <li>Free delivery every order</li>
            <li>5% extra discount</li>
            <li>Priority support</li>
            <li>Exclusive items first access</li>
        </ul>
        <button class="sub-btn">Subscribe - ₦999/month</button>
    </div>
    
    <!-- Products Section -->
    <div class="info-section">
        <div class="section-title">Featured Products</div>
        <div class="products-grid">
            <div class="product-card">
                <div class="product-image">🍅</div>
                <div class="product-name">Fresh Tomatoes</div>
                <div class="product-price">₦500</div>
                <div class="product-status">In stock</div>
            </div>
            <div class="product-card">
                <div class="product-image">🥬</div>
                <div class="product-name">Vegetables</div>
                <div class="product-price">₦300</div>
                <div class="product-status">In stock</div>
            </div>
            <div class="product-card">
                <div class="product-image">🥦</div>
                <div class="product-name">Broccoli</div>
                <div class="product-price">₦400</div>
                <div class="product-status">In stock</div>
            </div>
            <div class="product-card">
                <div class="product-image">🍆</div>
                <div class="product-name">Garden Eggs</div>
                <div class="product-price">₦300</div>
                <div class="product-status">In stock</div>
            </div>
            <div class="product-card">
                <div class="product-image">🥕</div>
                <div class="product-name">Carrots</div>
                <div class="product-price">₦250</div>
                <div class="product-status">In stock</div>
            </div>
            <div class="product-card">
                <div class="product-image">🧅</div>
                <div class="product-name">Onions</div>
                <div class="product-price">₦200</div>
                <div class="product-status">In stock</div>
            </div>
        </div>
    </div>
    
    <!-- Reviews -->
    <div class="reviews-section">
        <div class="section-title">Customer Reviews</div>
        <div class="review-item">
            <div class="reviewer">
                <div class="reviewer-avatar">👩</div>
                <div class="review-content">
                    <div class="reviewer-name">Chioma O. ⭐⭐⭐⭐⭐</div>
                    <div class="review-text">Best vendor in the area! Always fresh, fast delivery, great customer service.</div>
                </div>
            </div>
        </div>
        <div class="review-item">
            <div class="reviewer">
                <div class="reviewer-avatar">👨</div>
                <div class="review-content">
                    <div class="reviewer-name">Tunde A. ⭐⭐⭐⭐⭐</div>
                    <div class="review-text">Reliable vendor. Never disappointed. Prices are fair and quality is consistent.</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Bar -->
    <div class="action-bar">
        <button class="action-bar-btn btn-order">🛒 Order Now</button>
        <button class="action-bar-btn btn-call">📞 Call Vendor</button>
    </div>
    
    <div style="height: 70px;"></div>
</body>
</html>