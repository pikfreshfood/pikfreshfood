@extends('layouts.app')

@section('title', 'Contact Us - PikFreshFood')

@section('content')
<div style="max-width:900px;margin:20px auto;padding:0 16px;">
    <a href="{{ route('home') }}" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none; color:var(--muted-color); font-weight:700; font-size:0.9rem; margin-bottom:14px;">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 12h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Back to Home
    </a>
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
                    <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" placeholder="Your Name" style="padding: 12px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color); font-size:0.95rem;" required>
                    <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" placeholder="Your Email" style="padding: 12px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color); font-size:0.95rem;" required>
                    <select name="subject" style="padding: 12px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color); font-size:0.95rem;">
                        <option value="General Inquiry" @selected(old('subject') === 'General Inquiry')>General Inquiry</option>
                        <option value="Order Support" @selected(old('subject') === 'Order Support')>Order Support</option>
                        <option value="Vendor Registration" @selected(old('subject') === 'Vendor Registration')>Vendor Registration</option>
                        <option value="Technical Issue" @selected(old('subject') === 'Technical Issue')>Technical Issue</option>
                    </select>
                    <textarea name="message" placeholder="Your Message" rows="4" style="padding: 12px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color); font-size:0.95rem; resize:vertical;" required>{{ old('message') }}</textarea>
                    <button type="submit" style="width:100%; min-height:52px; border:none; border-radius:12px; background:linear-gradient(135deg, var(--primary-color) 0%, #1e8a4a 100%); color:white; font-weight:800; font-size:0.98rem; display:inline-flex; align-items:center; justify-content:center; gap:10px; cursor:pointer; box-shadow:0 8px 18px rgba(22,132,71,0.24); transition:transform 0.14s, box-shadow 0.14s; position:relative; overflow:hidden;">
                        <span style="position:absolute; left:0; top:0; bottom:0; width:52px; background:var(--secondary-color); display:grid; place-items:center; border-radius:12px 0 0 12px;">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" aria-hidden="true"><path d="M22 2L11 13" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span style="margin-left:28px;">Send Message</span>
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true" style="opacity:0.9;"><path d="M5 12h14M13 6l6 6-6 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <div style="display:flex; align-items:center; justify-content:center; gap:8px; color:var(--muted-color); font-size:0.82rem; margin-top:2px;">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" aria-hidden="true"><path d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" stroke="currentColor" stroke-width="1.7"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        We reply within 24 hours • Your data is secure
                    </div>
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
