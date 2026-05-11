<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #333; padding-bottom: 100px; }
        
        .header { background: white; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .vendor-info { display: flex; gap: 10px; align-items: center; }
        .vendor-avatar { font-size: 40px; }
        .vendor-name { font-weight: bold; } 
        .vendor-status { font-size: 12px; color: #27ae60; }
        .status-toggle { cursor: pointer; }
        
        .section { background: white; margin: 10px 0; padding: 15px; }
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 15px; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        .stat-card { background: linear-gradient(135deg, #f0f0f0 0%, #e8e8e8 100%); padding: 15px; border-radius: 8px; text-align: center; }
        .stat-card.sales { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; }
        .stat-card.revenue { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; }
        .stat-card.orders { background: linear-gradient(135deg, #f39c12 0%, #d68910 100%); color: white; }
        .stat-card.rating { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; }
        .stat-number { font-size: 20px; font-weight: bold; }
        .stat-label { font-size: 11px; margin-top: 4px; opacity: 0.9; }
        
        .action-buttons { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px; }
        .btn { padding: 12px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 13px; }
        .btn-primary { background: #27ae60; color: white; }
        .btn-secondary { background: #ecf0f1; }
        
        .orders-section { }
        .order-item { background: #f8f9fa; padding: 12px; border-radius: 6px; margin-bottom: 10px; border-left: 4px solid #27ae60; }
        .order-header { display: flex; justify-content: space-between; align-items: center; }
        .order-id { font-weight: bold; font-size: 13px; }
        .order-status { background: #27ae60; color: white; padding: 4px 10px; border-radius: 12px; font-size: 11px; }
        .order-status.pending { background: #f39c12; }
        .order-details { font-size: 12px; color: #7f8c8d; margin-top: 8px; }
        .order-actions { display: flex; gap: 8px; margin-top: 8px; }
        .action-link { color: #27ae60; text-decoration: none; font-size: 12px; cursor: pointer; }
        
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 10px; }
        .product-card { background: white; border: 1px solid #ecf0f1; border-radius: 6px; padding: 10px; text-align: center; cursor: pointer; }
        .product-image { font-size: 40px; margin-bottom: 6px; }
        .product-name { font-size: 11px; font-weight: 500; margin-bottom: 4px; }
        .product-price { color: #27ae60; font-weight: bold; font-size: 12px; }
        .product-stock { font-size: 10px; color: #7f8c8d; }
        
        .chart-placeholder { background: #f8f9fa; height: 200px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #7f8c8d; }
        
        .wallet-section { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 20px; border-radius: 8px; }
        .wallet-label { font-size: 12px; opacity: 0.9; }
        .wallet-balance { font-size: 28px; font-weight: bold; margin: 10px 0; }
        .wallet-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 12px; }
        .wallet-btn { padding: 10px; background: rgba(255,255,255,0.2); border: 1px solid white; color: white; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold; }
        
        .profile-links { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
        .link-card { background: white; padding: 12px; border-radius: 6px; text-align: center; border: 1px solid #ecf0f1; cursor: pointer; }
        .link-icon { font-size: 24px; }
        .link-text { font-size: 11px; margin-top: 4px; font-weight: 500; }
        
        .bottom-nav { position: fixed; bottom: 0; width: 100%; background: white; border-top: 1px solid #ddd; display: flex; justify-content: space-around; padding: 8px 0; }
        .nav-item { text-align: center; padding: 8px; flex: 1; font-size: 12px; cursor: pointer; }
        .nav-item.active { color: #27ae60; font-weight: bold; }
        .nav-icon { font-size: 20px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-top">
            <div class="vendor-info">
                <div class="vendor-avatar">👩</div>
                <div>
                    <div class="vendor-name">Mama Chidi's Farm Fresh</div>
                    <div class="vendor-status">🟢 Live & Active</div>
                </div>
            </div>
            <div class="status-toggle" style="font-size: 24px; cursor: pointer;">⚙️</div>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="section">
        <div class="section-title">Today's Performance</div>
        <div class="stats-grid">
            <div class="stat-card sales">
                <div class="stat-number">12</div>
                <div class="stat-label">Orders</div>
            </div>
            <div class="stat-card revenue">
                <div class="stat-number">₦8.5k</div>
                <div class="stat-label">Revenue</div>
            </div>
            <div class="stat-card orders">
                <div class="stat-number">97%</div>
                <div class="stat-label">Fulfillment</div>
            </div>
            <div class="stat-card rating">
                <div class="stat-number">4.9</div>
                <div class="stat-label">Rating</div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="section">
        <div class="action-buttons">
            <button class="btn btn-primary">➕ Add Product</button>
            <button class="btn btn-secondary">📊 Analytics</button>
        </div>
    </div>
    
    <!-- Pending Orders -->
    <div class="section">
        <div class="section-title">🔔 Pending Orders (3)</div>
        <div class="order-item">
            <div class="order-header">
                <div class="order-id">Order #1001</div>
                <div class="order-status pending">PENDING</div>
            </div>
            <div class="order-details">
                🍅 Fresh Tomatoes × 2, 🥬 Vegetables × 1 | ₦1,300 | Customer: Chioma O.
            </div>
            <div class="order-actions">
                <a href="#" class="action-link">✓ Accept</a>
                <a href="#" class="action-link">Message</a>
                <a href="#" class="action-link">Details →</a>
            </div>
        </div>
        
        <div class="order-item">
            <div class="order-header">
                <div class="order-id">Order #1000</div>
                <div class="order-status">PREPARING</div>
            </div>
            <div class="order-details">
                🍆 Garden Eggs × 1 | ₦300 | Customer: Tunde A. | Ready in 10 mins
            </div>
            <div class="order-actions">
                <a href="#" class="action-link">Mark Ready</a>
                <a href="#" class="action-link">Details →</a>
            </div>
        </div>
        
        <div class="order-item">
            <div class="order-header">
                <div class="order-id">Order #999</div>
                <div class="order-status">READY FOR PICKUP</div>
            </div>
            <div class="order-details">
                🥦 Vegetables × 3, 🍅 Tomatoes × 1 | ₦1,200 | Waiting for delivery partner
            </div>
            <div class="order-actions">
                <a href="#" class="action-link">Track Delivery</a>
                <a href="#" class="action-link">Details →</a>
            </div>
        </div>
    </div>
    
    <!-- Featured Products -->
    <div class="section">
        <div class="section-title">📦 Your Products</div>
        <div class="products-grid">
            <div class="product-card">
                <div class="product-image">🍅</div>
                <div class="product-name">Fresh Tomatoes</div>
                <div class="product-price">₦500</div>
                <div class="product-stock">45 in stock</div>
            </div>
            <div class="product-card">
                <div class="product-image">🥬</div>
                <div class="product-name">Vegetables</div>
                <div class="product-price">₦300</div>
                <div class="product-stock">32 in stock</div>
            </div>
            <div class="product-card">
                <div class="product-image">🍆</div>
                <div class="product-name">Garden Eggs</div>
                <div class="product-price">₦300</div>
                <div class="product-stock">28 in stock</div>
            </div>
            <div class="product-card">
                <div class="product-image">🥕</div>
                <div class="product-name">Carrots</div>
                <div class="product-price">₦250</div>
                <div class="product-stock">15 in stock</div>
            </div>
            <div class="product-card">
                <div class="product-image">🧅</div>
                <div class="product-name">Onions</div>
                <div class="product-price">₦200</div>
                <div class="product-stock">50 in stock</div>
            </div>
            <div class="product-card">
                <div class="product-image">🥦</div>
                <div class="product-name">Broccoli</div>
                <div class="product-price">₦400</div>
                <div class="product-stock">12 in stock</div>
            </div>
        </div>
    </div>
    
    <!-- Wallet & Earnings -->
    <div class="section">
        <div class="wallet-section">
            <div class="wallet-label">💰 Available Balance</div>
            <div class="wallet-balance">₦24,500</div>
            <div class="wallet-actions">
                <button class="wallet-btn">💸 Withdraw</button>
                <button class="wallet-btn">📋 History</button>
            </div>
        </div>
    </div>
    
    <!-- Profile Quick Links -->
    <div class="section">
        <div class="profile-links">
            <div class="link-card">
                <div class="link-icon">⭐</div>
                <div class="link-text">My Reviews</div>
            </div>
            <div class="link-card">
                <div class="link-icon">📸</div>
                <div class="link-text">Add Photos</div>
            </div>
            <div class="link-card">
                <div class="link-icon">📹</div>
                <div class="link-text">Add Videos</div>
            </div>
        </div>
    </div>
    
    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-item active">
            <div class="nav-icon">🏠</div>
            <div>Dashboard</div>
        </div>
        <div class="nav-item">
            <div class="nav-icon">📦</div>
            <div>Products</div>
        </div>
        <div class="nav-item">
            <div class="nav-icon">📋</div>
            <div>Orders</div>
        </div>
        <div class="nav-item">
            <div class="nav-icon">💬</div>
            <div>Messages</div>
        </div>
        <div class="nav-item">
            <div class="nav-icon">👤</div>
            <div>Profile</div>
        </div>
    </div>
    
    <div style="height: 70px;"></div>
</body>
</html>