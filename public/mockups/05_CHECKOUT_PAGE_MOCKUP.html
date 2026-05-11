<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - PikFreshFood</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #333; padding-bottom: 100px; }
        
        .header { background: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .back-btn { cursor: pointer; font-size: 24px; }
        .header-title { font-weight: bold; }
        
        .progress-steps { display: flex; padding: 15px; background: white; gap: 8px; margin-bottom: 10px; }
        .step { flex: 1; text-align: center; }
        .step-number { display: inline-block; width: 30px; height: 30px; background: #ecf0f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px; }
        .step.active .step-number { background: #27ae60; color: white; }
        .step-label { font-size: 10px; margin-top: 4px; color: #7f8c8d; }
        
        .section { background: white; margin-bottom: 10px; padding: 15px; }
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .section-subtitle { font-size: 12px; color: #7f8c8d; margin-bottom: 10px; }
        
        .cart-item { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid #ecf0f1; }
        .cart-item:last-child { border-bottom: none; }
        .item-image { font-size: 40px; width: 50px; height: 50px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; }
        .item-details { flex: 1; }
        .item-name { font-weight: 500; font-size: 13px; }
        .item-vendor { font-size: 11px; color: #7f8c8d; }
        .item-qty { font-size: 12px; color: #7f8c8d; margin-top: 4px; }
        .item-price { font-weight: bold; color: #27ae60; }
        .item-remove { cursor: pointer; color: #e74c3c; font-size: 20px; }
        
        .address-box { background: #f8f9fa; padding: 12px; border-radius: 6px; border-left: 3px solid #27ae60; }
        .address-label { font-size: 11px; color: #7f8c8d; margin-bottom: 2px; }
        .address-text { font-weight: 500; margin-bottom: 8px; }
        .address-change { color: #27ae60; cursor: pointer; font-size: 12px; text-decoration: underline; }
        
        .delivery-options { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 12px; }
        .option-card { border: 2px solid #ecf0f1; border-radius: 6px; padding: 12px; cursor: pointer; text-align: center; transition: all 0.2s; }
        .option-card.selected { border-color: #27ae60; background: #f0fdf4; }
        .option-icon { font-size: 24px; }
        .option-name { font-size: 12px; font-weight: 500; margin-top: 4px; }
        .option-time { font-size: 11px; color: #7f8c8d; }
        .option-price { font-weight: bold; color: #27ae60; margin-top: 4px; }
        
        .payment-option { display: flex; align-items: center; gap: 10px; padding: 12px 0; border-bottom: 1px solid #ecf0f1; cursor: pointer; }
        .payment-option:last-child { border-bottom: none; }
        .payment-radio { width: 20px; height: 20px; border: 2px solid #ecf0f1; border-radius: 50%; cursor: pointer; }
        .payment-radio.checked { background: #27ae60; border-color: #27ae60; }
        .payment-icon { font-size: 24px; }
        .payment-info { flex: 1; }
        .payment-name { font-weight: 500; font-size: 13px; }
        .payment-desc { font-size: 11px; color: #7f8c8d; }
        
        .order-summary { background: #f8f9fa; padding: 15px; border-radius: 6px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
        .summary-row.total { border-top: 1px solid #ddd; padding-top: 8px; font-weight: bold; font-size: 14px; color: #27ae60; }
        
        .promo-section { display: flex; gap: 8px; margin-top: 12px; }
        .promo-input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; }
        .promo-btn { padding: 10px 15px; background: #ecf0f1; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 12px; }
        
        .checkout-btn { width: 100%; padding: 14px; background: #27ae60; color: white; border: none; border-radius: 6px; font-weight: bold; font-size: 14px; cursor: pointer; margin-top: 12px; }
        .checkout-btn:hover { background: #229954; }
        
        .bottom-bar { position: fixed; bottom: 0; width: 100%; background: white; padding: 12px 15px; box-shadow: 0 -2px 8px rgba(0,0,0,0.1); border-top: 1px solid #ecf0f1; }
        .final-total { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .final-amount { font-weight: bold; font-size: 18px; color: #27ae60; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="back-btn">←</div>
        <div class="header-title">Checkout</div>
        <div style="width: 24px;"></div>
    </div>
    
    <!-- Progress Steps -->
    <div class="progress-steps">
        <div class="step active">
            <div class="step-number">✓</div>
            <div class="step-label">Cart</div>
        </div>
        <div class="step active">
            <div class="step-number">2</div>
            <div class="step-label">Address</div>
        </div>
        <div class="step">
            <div class="step-number">3</div>
            <div class="step-label">Payment</div>
        </div>
        <div class="step">
            <div class="step-number">4</div>
            <div class="step-label">Review</div>
        </div>
    </div>
    
    <!-- Order Summary -->
    <div class="section">
        <div class="section-title">📦 Order Review</div>
        <div class="cart-item">
            <div class="item-image">🍅</div>
            <div class="item-details">
                <div class="item-name">Fresh Tomatoes (1kg)</div>
                <div class="item-vendor">From: Mama Chidi's Farm</div>
                <div class="item-qty">Qty: 2</div>
            </div>
            <div style="text-align: right;">
                <div class="item-price">₦1,000</div>
                <div class="item-remove">✕</div>
            </div>
        </div>
        <div class="cart-item">
            <div class="item-image">🥬</div>
            <div class="item-details">
                <div class="item-name">Fresh Vegetables</div>
                <div class="item-vendor">From: Sister Blessing</div>
                <div class="item-qty">Qty: 1</div>
            </div>
            <div style="text-align: right;">
                <div class="item-price">₦500</div>
                <div class="item-remove">✕</div>
            </div>
        </div>
        <div class="cart-item">
            <div class="item-image">🍆</div>
            <div class="item-details">
                <div class="item-name">Garden Eggs</div>
                <div class="item-vendor">From: Mama Chidi's Farm</div>
                <div class="item-qty">Qty: 1</div>
            </div>
            <div style="text-align: right;">
                <div class="item-price">₦300</div>
                <div class="item-remove">✕</div>
            </div>
        </div>
    </div>
    
    <!-- Delivery Address -->
    <div class="section">
        <div class="section-title">📍 Delivery Address</div>
        <div class="address-box">
            <div class="address-label">DELIVERY TO</div>
            <div class="address-text">24 Olanrewaju Street, Yaba, Lagos 101001</div>
            <div class="address-change">Change Address</div>
        </div>
    </div>
    
    <!-- Delivery Method -->
    <div class="section">
        <div class="section-title">🚚 Delivery Method</div>
        <div class="delivery-options">
            <div class="option-card selected">
                <div class="option-icon">⚡</div>
                <div class="option-name">Express</div>
                <div class="option-time">20-30 mins</div>
                <div class="option-price">Free</div>
            </div>
            <div class="option-card">
                <div class="option-icon">🚚</div>
                <div class="option-name">Standard</div>
                <div class="option-time">45-60 mins</div>
                <div class="option-price">Free</div>
            </div>
        </div>
    </div>
    
    <!-- Payment Method -->
    <div class="section">
        <div class="section-title">💳 Payment Method</div>
        <div class="payment-option">
            <div class="payment-radio checked"></div>
            <div class="payment-icon">💳</div>
            <div class="payment-info">
                <div class="payment-name">Debit Card</div>
                <div class="payment-desc">Visa, Mastercard, Verve</div>
            </div>
        </div>
        <div class="payment-option">
            <div class="payment-radio"></div>
            <div class="payment-icon">🏦</div>
            <div class="payment-info">
                <div class="payment-name">Bank Transfer</div>
                <div class="payment-desc">Direct to vendor account</div>
            </div>
        </div>
        <div class="payment-option">
            <div class="payment-radio"></div>
            <div class="payment-icon">👛</div>
            <div class="payment-info">
                <div class="payment-name">PikFresh Wallet</div>
                <div class="payment-desc">Balance: ₦2,500</div>
            </div>
        </div>
        <div class="payment-option">
            <div class="payment-radio"></div>
            <div class="payment-icon">📱</div>
            <div class="payment-info">
                <div class="payment-name">Mobile Money</div>
                <div class="payment-desc">MTN, Airtel, Glo</div>
            </div>
        </div>
    </div>
    
    <!-- Promo Code -->
    <div class="section">
        <div class="section-title">🎟️ Promo Code</div>
        <div class="promo-section">
            <input type="text" class="promo-input" placeholder="Enter promo code...">
            <button class="promo-btn">Apply</button>
        </div>
    </div>
    
    <!-- Order Summary -->
    <div class="section">
        <div class="section-title">💰 Order Summary</div>
        <div class="order-summary">
            <div class="summary-row">
                <span>Subtotal (3 items)</span>
                <span>₦1,800</span>
            </div>
            <div class="summary-row">
                <span>Delivery Fee</span>
                <span>Free</span>
            </div>
            <div class="summary-row">
                <span>Platform Fee</span>
                <span>₦25</span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span>₦1,825</span>
            </div>
        </div>
        <button class="checkout-btn">✓ Proceed to Payment</button>
    </div>
    
    <div style="height: 120px;"></div>
    
    <!-- Bottom Bar -->
    <div class="bottom-bar">
        <div class="final-total">
            <span>🛒 3 items | 2 vendors</span>
            <span class="final-amount">₦1,825</span>
        </div>
        <button class="checkout-btn">Complete Order</button>
    </div>
</body>
</html>