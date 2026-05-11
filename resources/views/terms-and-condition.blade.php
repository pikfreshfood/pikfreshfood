@extends('layouts.app')

@section('title', 'Terms and Conditions - PikFreshFood')

@section('content')
<div style="max-width:900px;margin:20px auto;padding:0 16px;">
    <div class="product-container" style="padding:24px;">
        <h1 style="margin-bottom:24px;">Terms and Conditions</h1>
        
        <div style="line-height: 1.8; color: var(--muted-color);">
            <p style="margin-bottom: 20px;">Welcome to PikFreshFood. These Terms and Conditions govern your use of our website and mobile application. By accessing or using our services, you agree to be bound by these terms.</p>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">1. User Accounts</h3>
                <p>To use most features of PikFreshFood, you must register for an account. You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account.</p>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">2. Marketplace Platform</h3>
                <p>PikFreshFood provides a platform for buyers and vendors to interact. We do not own or sell the products listed by vendors. Any purchase agreement is directly between the buyer and the vendor.</p>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">3. Vendor Obligations</h3>
                <p>Vendors must provide accurate product information, maintain high standards of food safety, and fulfill orders in a timely manner. Vendors are responsible for the quality of the products they sell.</p>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">4. Payments and Fees</h3>
                <p>Buyers agree to pay the listed price for products plus any applicable delivery fees. Vendors agree to the platform fees associated with their chosen subscription plan.</p>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">5. Cancellations and Refunds</h3>
                <p>Refund policies are determined by the individual vendor, subject to our general guidelines. In case of disputes, PikFreshFood may act as a mediator but is not liable for vendor defaults.</p>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">6. Prohibited Activities</h3>
                <p>Users may not use the platform for any illegal activities, including selling prohibited items, fraud, or harassment of other users.</p>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="color: var(--text-color); margin-bottom: 12px;">7. Changes to Terms</h3>
                <p>We reserve the right to modify these terms at any time. Your continued use of the platform after changes are posted constitutes your acceptance of the new terms.</p>
            </div>

            <div style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 40px;">
                <p style="font-style: italic; font-size: 0.9rem;">Last Updated: {{ date('F d, Y') }}</p>
                <p style="font-size: 0.9rem;">If you have any questions regarding these terms, please <a href="{{ route('contact-us') }}" style="color: var(--primary-color);">contact us</a>.</p>
            </div>
        </div>
    </div>
</div>
@endsection
