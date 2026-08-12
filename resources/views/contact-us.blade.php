@extends('layouts.app')

@section('title', 'Contact Us - PikFreshFood')

@section('content')
<div style="max-width:900px;margin:20px auto;padding:0 16px;">
    <div class="product-container" style="padding:24px;">
        <h1 style="margin-bottom:16px;">Contact Us</h1>
        <p style="line-height:1.7;color:var(--muted-color);margin-bottom:24px;">
            Have questions about your order, want to partner with us as a vendor, or just want to say hello? Our team is here to help you.
        </p>

        @if (session('success'))
            <div role="status" style="margin-bottom: 20px; padding: 12px; border-radius: 8px; background: #dcfce7; color: #166534;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" style="margin-bottom: 20px; padding: 12px; border-radius: 8px; background: #fee2e2; color: #991b1b;">
                Please correct the highlighted details and try again.
            </div>
        @endif

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px; margin-bottom: 32px;">
            <div>
                <h3 style="margin-bottom: 12px; color: var(--text-color);">Get in Touch</h3>
                <div style="margin-bottom: 16px;">
                    <strong style="display: block; color: var(--text-color);">Support Email:</strong>
                    <span style="color: var(--muted-color);">support@pikfreshfood.com</span>
                </div>
                <div style="margin-bottom: 16px;">
                    <strong style="display: block; color: var(--text-color);">Vendor Inquiries:</strong>
                    <span style="color: var(--muted-color);">vendors@pikfreshfood.com</span>
                </div>
                <div style="margin-bottom: 16px;">
                    <strong style="display: block; color: var(--text-color);">Phone Support:</strong>
                    <span style="color: var(--muted-color);">+234 800 PIK FRESH (0800 000 0000)</span>
                </div>
                <div style="margin-bottom: 16px;">
                    <strong style="display: block; color: var(--text-color);">Address:</strong>
                    <span style="color: var(--muted-color);">Abuja, Nigeria.</span>
                </div>
            </div>

            <div>
                <h3 style="margin-bottom: 12px; color: var(--text-color);">Send us a Message</h3>
                <form action="{{ route('contact-us.store') }}" method="POST" style="display: grid; gap: 12px;">
                    @csrf
                    <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" placeholder="Your Name" style="padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color);" required>
                    <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" placeholder="Your Email" style="padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color);" required>
                    <select name="subject" style="padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color);">
                        <option value="General Inquiry" @selected(old('subject') === 'General Inquiry')>General Inquiry</option>
                        <option value="Order Support" @selected(old('subject') === 'Order Support')>Order Support</option>
                        <option value="Vendor Registration" @selected(old('subject') === 'Vendor Registration')>Vendor Registration</option>
                        <option value="Technical Issue" @selected(old('subject') === 'Technical Issue')>Technical Issue</option>
                    </select>
                    <textarea name="message" placeholder="Your Message" rows="4" style="padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color);" required>{{ old('message') }}</textarea>
                    <button type="submit" class="checkout-btn" style="width: 100%; border: none; cursor: pointer;">Send Message</button>
                </form>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-color); padding-top: 24px;">
            <h3 style="margin-bottom: 12px; color: var(--text-color);">Working Hours</h3>
            <p style="color: var(--muted-color);">Monday - Friday: 8:00 AM - 6:00 PM</p>
            <p style="color: var(--muted-color);">Saturday: 9:00 AM - 4:00 PM</p>
            <p style="color: var(--muted-color);">Sunday: Closed (Live chat available for urgent order issues)</p>
        </div>
    </div>
</div>
@endsection
