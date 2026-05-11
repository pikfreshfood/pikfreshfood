@php
    $pageTitle = 'PikFreshFood Mockup Flow';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #1d1d1f; }
        .page-wrap { max-width: 1100px; margin: 0 auto; padding: 30px 20px; }
        .header { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; color: #27ae60; margin: 0; }
        .header p { margin: 4px 0 0; color: #586069; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; }
        .card { background: white; border: 1px solid #e1e4e8; border-radius: 16px; padding: 18px; transition: transform .2s ease, box-shadow .2s ease; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 14px 28px rgba(39, 44, 49, 0.08); }
        .card-title { font-size: 16px; font-weight: 700; margin-bottom: 8px; color: #1d1d1f; }
        .card-text { font-size: 13px; color: #6b7280; margin-bottom: 12px; line-height: 1.5; }
        .card-link { display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #27ae60; font-weight: 700; }
        .card-link:hover { text-decoration: underline; }
        .note { margin-top: 24px; font-size: 13px; color: #52525b; }
        .button { display: inline-flex; padding: 10px 16px; border-radius: 999px; border: 1px solid #27ae60; color: #27ae60; text-decoration: none; font-weight: 700; margin-top: 18px; }
        .button:hover { background: rgba(39, 174, 96, 0.08); }
    </style>
</head>
<body>
    <div class="page-wrap">
        <div class="header">
            <div>
                <h1>PikFreshFood UI Flow</h1>
                <p>Access all mockup pages from the UI flow folder directly in Laravel.</p>
            </div>
            <a class="button" href="/">Go to Laravel Welcome</a>
        </div>

        <div class="cards">
            @foreach ($mockups as $slug => $mockup)
                <div class="card">
                    <div class="card-title">{{ $mockup['title'] }}</div>
                    <div class="card-text">Internal view: <strong>{{ $mockup['view'] }}</strong></div>
                    <a class="card-link" href="{{ route('mockups.show', ['slug' => $slug]) }}">View page →</a>
                </div>
            @endforeach
        </div>

        <p class="note">Each page is rendered directly from the copied HTML mockup files in <strong>public/mockups</strong>. This keeps the UI flow inside the Laravel project while preserving the original mockup HTML.</p>
    </div>
</body>
</html>
