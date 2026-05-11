@extends('layouts.app')

@section('title', 'FAQ - PikFreshFood')

@section('content')
<div style="max-width:900px;margin:20px auto;padding:0 16px;">
    <div class="product-container" style="padding:24px;">
        <h1 style="margin-bottom:20px;">Frequently Asked Questions</h1>
        
        <div style="display: grid; gap: 24px;">
            <!-- General Questions -->
            <div>
                <h3 style="color: var(--primary-color); margin-bottom: 12px; border-bottom: 2px solid var(--primary-color); display: inline-block;">General Questions</h3>
                
                <div style="margin-top: 16px;">
                    <strong style="display: block; color: var(--text-color); margin-bottom: 4px;">What is PikFreshFood?</strong>
                    <p style="line-height: 1.6; color: var(--muted-color);">PikFreshFood is a marketplace that connects you with local fresh food vendors, market sellers, and home-based food businesses in your neighborhood for easy discovery and delivery.</p>
                </div>

                <div style="margin-top: 16px;">
                    <strong style="display: block; color: var(--text-color); margin-bottom: 4px;">How do I place an order?</strong>
                    <p style="line-height: 1.6; color: var(--muted-color);">Browse products from nearby vendors, add items to your cart, and proceed to checkout. You can chat directly with vendors if you have specific questions about a product.</p>
                </div>
            </div>

            <!-- Vendor Questions -->
            <div>
                <h3 style="color: var(--primary-color); margin-bottom: 12px; border-bottom: 2px solid var(--primary-color); display: inline-block;">For Vendors</h3>
                
                <div style="margin-top: 16px;">
                    <strong style="display: block; color: var(--text-color); margin-bottom: 4px;">How can I sell on PikFreshFood?</strong>
                    <p style="line-height: 1.6; color: var(--muted-color);">Register as a vendor on our platform, complete your shop profile, and start listing your products. We offer different subscription plans to help you reach more customers.</p>
                </div>

                <div style="margin-top: 16px;">
                    <strong style="display: block; color: var(--text-color); margin-bottom: 4px;">What are the subscription plans?</strong>
                    <p style="line-height: 1.6; color: var(--muted-color);">We have Basic, Pro, and Enterprise plans tailored to different business sizes. Each plan offers various levels of product listings and marketing tools.</p>
                </div>
            </div>

            <!-- Delivery & Payments -->
            <div>
                <h3 style="color: var(--primary-color); margin-bottom: 12px; border-bottom: 2px solid var(--primary-color); display: inline-block;">Delivery & Payments</h3>
                
                <div style="margin-top: 16px;">
                    <strong style="display: block; color: var(--text-color); margin-bottom: 4px;">Who handles the delivery?</strong>
                    <p style="line-height: 1.6; color: var(--muted-color);">Depending on the vendor, delivery may be handled by the vendor directly or through our partnered delivery services. Delivery details are specified on each product page.</p>
                </div>

                <div style="margin-top: 16px;">
                    <strong style="display: block; color: var(--text-color); margin-bottom: 4px;">What payment methods are accepted?</strong>
                    <p style="line-height: 1.6; color: var(--muted-color);">We accept various payment methods including debit/credit cards, bank transfers, and local digital wallets.</p>
                </div>
            </div>

            <!-- Support -->
            <div style="background: var(--bg-color); padding: 20px; border-radius: 12px; border: 1px dashed var(--border-color); margin-top: 20px;">
                <h3 style="color: var(--text-color); margin-bottom: 8px;">Still have questions?</h3>
                <p style="color: var(--muted-color); margin-bottom: 12px;">Our support team is always ready to help you with any issues or inquiries.</p>
                <a href="{{ route('contact-us') }}" class="checkout-btn" style="text-decoration: none; display: inline-block; border: none;">Contact Support</a>
            </div>
        </div>
    </div>
</div>
@endsection
