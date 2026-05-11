@extends('layouts.app')

@section('title', 'Privacy Policy - PikFreshFood')

@section('content')
<div style="max-width:900px;margin:20px auto;padding:0 16px;">
    <div class="product-container" style="padding:24px;">
        <h1 style="margin-bottom:24px;">Privacy Policy</h1>
        
        <div style="line-height: 1.8; color: var(--muted-color);">
            <p style="margin-bottom: 20px;">At PikFreshFood, we respect your privacy and are committed to protecting your personal data. This Privacy Policy explains how we collect, use, and safeguard your information when you use our services.</p>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">1. Information We Collect</h3>
                <p>We collect information that you provide directly to us when you create an account, place an order, or contact us. This may include your name, email address, phone number, delivery address, and payment information.</p>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">2. How We Use Your Information</h3>
                <ul style="padding-left: 20px; margin-top: 10px;">
                    <li>To process and fulfill your orders.</li>
                    <li>To communicate with you about your account and orders.</li>
                    <li>To improve our platform and services.</li>
                    <li>To send you promotional communications (if you opt-in).</li>
                    <li>To ensure the security of our platform and prevent fraud.</li>
                </ul>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">3. Information Sharing</h3>
                <p>We share your information with vendors only to the extent necessary to fulfill your orders. We do not sell your personal data to third parties. We may share data with service providers who assist in our operations (e.g., payment processors, delivery services).</p>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">4. Data Security</h3>
                <p>We implement appropriate technical and organizational measures to protect your personal data against unauthorized access, loss, or alteration.</p>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">5. Your Rights</h3>
                <p>You have the right to access, correct, or delete your personal information. You can manage your account settings or contact our support team for assistance with your data rights.</p>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">6. Cookies</h3>
                <p>We use cookies and similar tracking technologies to enhance your experience on our platform and analyze site traffic.</p>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">7. Contact Us</h3>
                <p>If you have any questions about this Privacy Policy, please contact our data protection team at <a href="mailto:privacy@pikfreshfood.com" style="color: var(--primary-color);">privacy@pikfreshfood.com</a>.</p>
            </div>

            <div style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 40px;">
                <p style="font-style: italic; font-size: 0.9rem;">Last Updated: {{ date('F d, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
