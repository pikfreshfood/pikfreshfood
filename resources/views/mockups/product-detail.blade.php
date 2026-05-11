<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail - Fresh Tomatoes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #333; padding-bottom: 100px; }
        
        .header { background: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .back-btn { cursor: pointer; font-size: 24px; }
        .header-icons { display: flex; gap: 15px; font-size: 20px; cursor: pointer; }
        
        .product-image { width: 100%; height: 280px; background: linear-gradient(135deg, #ff6b6b 0%, #ff8787 100%); display: flex; align-items: center; justify-content: center; font-size: 100px; }
        
        .product-info { background: white; padding: 20px; margin-bottom: 10px; }
        .product-title { font-size: 22px; font-weight: bold; margin-bottom: 8px; }
        .product-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .product-price { font-size: 24px; font-weight: bold; color: #27ae60; }
        .product-rating { color: #f39c12; }
        
        .vendor-card { background: #f8f9fa; padding: 12px; border-radius: 6px; margin-bottom: 10px; cursor: pointer; }
        .vendor-header { display: flex; align-items: center; gap: 12px; }
        .vendor-avatar { font-size: 40px; }
        .vendor-info { flex: 1; }
        .vendor-name { font-weight: bold; font-size: 14px; }
        .vendor-distance { font-size: 13px; color: #7f8c8d; }
        .vendor-badge { background: #27ae60; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; }
        
        .description-section { background: white; padding: 20px; margin-bottom: 10px; }
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 12px; }
        .description-text { line-height: 1.6; font-size: 14px; color: #555; }
        
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px; }
        .detail-item { background: #f8f9fa; padding: 12px; border-radius: 6px; text-align: center; }
        .detail-label { font-size: 12px; color: #7f8c8d; }
        .detail-value { font-weight: bold; margin-top: 4px; }
        
        .reviews-section { background: white; padding: 20px; margin-bottom: 10px; }
        .review-item { border-bottom: 1px solid #ecf0f1; padding: 12px 0; }
        .review-item:last-child { border-bottom: none; }
        .reviewer-name { font-weight: bold; font-size: 13px; }
        .reviewer-rating { color: #f39c12; font-size: 12px; }
        .review-text { font-size: 13px; color: #555; margin-top: 6px; line-height: 1.5; }
        .reviewer-date { font-size: 11px; color: #95a5a6; margin-top: 4px; }
        
        .quantity-selector { background: white; padding: 20px; margin-bottom: 10px; }
        .qty-controls { display: flex; align-items: center; gap: 12px; margin-top: 10px; }
        .qty-btn { background: #ecf0f1; border: none; width: 40px; height: 40px; border-radius: 4px; cursor: pointer; font-size: 18px; font-weight: bold; }
        .qty-display { min-width: 50px; text-align: center; font-weight: bold; font-size: 16px; }
        
        .action-buttons { position: fixed; bottom: 0; width: 100%; background: white; padding: 15px; display: flex; gap: 10px; box-shadow: 0 -2px 8px rgba(0,0,0,0.1); border-top: 1px solid #ecf0f1; }
        .btn { flex: 1; padding: 14px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; }
        .btn-wishlist { background: #f0f0f0; color: #333; }
        .btn-cart { background: #ecf0f1; color: #333; }
        .btn-buy { background: #27ae60; color: white; }
        
        .delivery-info { background: #e8f8f5; padding: 12px; border-radius: 6px; border-left: 3px solid #27ae60; margin-top: 12px; }
        .delivery-time { font-weight: bold; color: #27ae60; }
        .delivery-desc { font-size: 12px; color: #555; margin-top: 4px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="back-btn">←</div>
        <div class="header-icons">
            <div>🔗</div>
            <div>⋮</div>
        </div>
    </div>
    
    <!-- Product Image -->
    <div class="product-image">🍅</div>
    
    <!-- Product Info -->
    <div class="product-info">
        <div class="product-title">Fresh Ripe Tomatoes (1kg)</div>
        <div class="product-meta">
            <div class="product-price">₦500</div>
            <div class="product-rating">⭐ 4.8 (247 reviews)</div>
        </div>
        
        <!-- Vendor Card -->
        <div class="vendor-card">
            <div class="vendor-header">
                <div class="vendor-avatar">👩</div>
                <div class="vendor-info">
                    <div class="vendor-name">Mama Chidi's Farm Fresh</div>
                    <div class="vendor-distance">300m away • Just opened</div>
                </div>
                <div class="vendor-badge">OPEN NOW</div>
            </div>
        </div>
        
        <!-- Delivery Info -->
        <div class="delivery-info">
            <div class="delivery-time">🚚 Delivery in 20-30 mins</div>
            <div class="delivery-desc">Free delivery available • Fresh within 24hrs harvested</div>
        </div>
    </div>
    
    <!-- Description -->
    <div class="description-section">
        <div class="section-title">About This Product</div>
        <div class="description-text">
            Premium, farm-fresh red tomatoes picked today morning. Perfect for cooking soups, sauces, or eating fresh. 
            These tomatoes are locally grown in Lagos and delivered fresh to your doorstep. No pesticides, naturally ripened.
        </div>
        
        <div class="details-grid">
            <div class="detail-item">
                <div class="detail-label">Harvested</div>
                <div class="detail-value">Today</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Freshness</div>
                <div class="detail-value">Grade A</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Weight</div>
                <div class="detail-value">1 kg</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Best For</div>
                <div class="detail-value">Cooking</div>
            </div>
        </div>
    </div>
    
    <!-- Reviews -->
    <div class="reviews-section">
        <div class="section-title">Customer Reviews</div>
        <div class="review-item">
            <div class="reviewer-name">Chioma ⭐⭐⭐⭐⭐</div>
            <div class="review-text">Very fresh and tasty. Arrived on time. Will definitely order again!</div>
            <div class="reviewer-date">2 days ago</div>
        </div>
        <div class="review-item">
            <div class="reviewer-name">Tunde ⭐⭐⭐⭐</div>
            <div class="review-text">Good quality. A couple of them were slightly soft but overall good.</div>
            <div class="reviewer-date">1 week ago</div>
        </div>
        <div class="review-item">
            <div class="reviewer-name">Folake ⭐⭐⭐⭐⭐</div>
            <div class="review-text">Perfect for my soup! Much better than market prices. Recommend!</div>
            <div class="reviewer-date">2 weeks ago</div>
        </div>
    </div>
    
    <!-- Quantity Selector -->
    <div class="quantity-selector">
        <div class="section-title">Select Quantity</div>
        <div style="font-size: 13px; color: #7f8c8d; margin-bottom: 10px;">In stock: 45 available</div>
        <div class="qty-controls">
            <button class="qty-btn">−</button>
            <div class="qty-display">1</div>
            <button class="qty-btn">+</button>
            <div style="flex: 1; text-align: right; font-size: 13px;">
                <div style="color: #7f8c8d;">Total</div>
                <div style="font-weight: bold; font-size: 16px; color: #27ae60;">₦500</div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="action-buttons">
        <button class="btn btn-wishlist">❤️ Save</button>
        <button class="btn btn-cart">🛒 Add to Cart</button>
        <button class="btn btn-buy">Buy Now</button>
    </div>
    
    <div style="height: 70px;"></div>
</body>
</html>