<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#f4c400">
    <title>@yield('title', 'PikFreshFood')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary-color: #1f4a36;
            --header-bg: #f4c400;
            --text-color: #111;
            --page-bg: #fff;
            --surface-bg: #fff;
            --surface-alt: #fbf6d7;
            --secondary-background: #1f4a36;
            --map-gradient-1: #f4c400;
            --map-gradient-2: #1f4a36;
            --bottom-sheet-bg: #fff;
            --vendor-bg: #fff;
            --border-color: #d9d9d9;
            --muted-color: #4f4f4f;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        html, body { min-height: 100%; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            background: var(--page-bg);
            transition: background 0.3s ease, color 0.3s ease;
        }

        a { color: inherit; }

        .header {
            background: linear-gradient(135deg, #1f7a43 0%, #4ea95f 28%, #f4c400 72%, #ffd95a 100%);
            padding: 12px 15px;
            box-shadow: 0 2px 8px var(--shadow-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header.hidden { display: none; }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            width: min(100vw, 580px);
            height: clamp(104px, 15.6vw, 136px);
            padding: 0;
            overflow: visible;
            border-radius: 0;
            background: transparent;
            border: 0;
            box-shadow: none;
        }

        .logo img {
            display: block;
            width: 100%;
            height: 100%;
            max-width: 100%;
            object-fit: contain;
            object-position: center;
            clip-path: none;
        }

        .desktop-nav,
        .desktop-auth-links {
            display: none;
        }

        .desktop-nav {
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            row-gap: 8px;
        }

        .desktop-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            position: relative;
            text-decoration: none;
            color: #000000;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 8px 10px;
            border: 1px solid rgba(16, 33, 11, 0.16);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.32);
        }

        .desktop-nav-link svg,
        .desktop-auth-link svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .desktop-nav-link svg.fill-soft {
            fill: currentColor;
            stroke: currentColor;
        }

        .desktop-menu-badge {
            margin-left: 4px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #e74c3c;
            color: #fff;
            font-size: 0.68rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .desktop-nav-link.is-active {
            color: #1f7a43;
            border-color: rgba(16, 33, 11, 0.28);
            background: rgba(255, 255, 255, 0.52);
        }

        .desktop-auth-links {
            align-items: center;
            gap: 10px;
        }

        .desktop-auth-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #000000;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 8px 10px;
            border: 1px solid rgba(16, 33, 11, 0.16);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.32);
            position: relative;
        }

        .desktop-auth-link.icon-only {
            width: 38px;
            height: 38px;
            padding: 0;
            border-radius: 50%;
            border: 1px solid var(--border-color);
            justify-content: center;
            background: #fff;
            border-bottom-width: 1px;
        }

        .desktop-auth-link.icon-only:hover,
        .desktop-auth-link.icon-only.is-active {
            border-color: color-mix(in srgb, var(--primary-color) 45%, white 55%);
            background: color-mix(in srgb, var(--primary-color) 10%, white 90%);
        }

        .desktop-auth-link.icon-only .desktop-menu-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            margin-left: 0;
        }

        .desktop-auth-link.primary {
            color: #000000;
            background: rgba(255, 255, 255, 0.32);
            border-color: rgba(16, 33, 11, 0.16);
        }

        .desktop-nav-link:hover,
        .desktop-auth-link:hover,
        .desktop-auth-link.is-active {
            color: #1f7a43;
            border-color: rgba(16, 33, 11, 0.28);
            background: rgba(255, 255, 255, 0.52);
        }

        .desktop-footer {
            display: none;
        }

        .desktop-footer-inner {
            display: grid;
            gap: 14px;
        }

        .desktop-footer-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .desktop-footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .desktop-footer-copy {
            color: rgba(0, 0, 0, 0.82);
            font-size: 0.9rem;
        }

        .desktop-footer-links {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .desktop-footer-link {
            text-decoration: none;
            color: #000;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .desktop-footer-link:hover {
            color: #1f7a43;
        }

        .desktop-footer-social {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.24);
        }

        .desktop-footer-social-title {
            color: #000;
            font-size: 0.86rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .desktop-footer-social-links {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .desktop-footer-social-link {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1px solid rgba(0, 0, 0, 0.28);
            background: rgba(255, 255, 255, 0.34);
            color: #000;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .desktop-footer-social-link svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .desktop-footer-social-link:hover {
            color: #1f7a43;
            background: rgba(255, 255, 255, 0.5);
        }

        .desktop-footer-news h4 {
            margin: 0 0 8px;
            color: #000;
            font-size: 1rem;
            font-weight: 800;
        }

        .desktop-footer-news p {
            margin: 0 0 6px;
            color: rgba(0, 0, 0, 0.78);
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .desktop-footer-checkline {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin: 8px 0 10px;
            color: #000;
            font-size: 0.88rem;
            line-height: 1.45;
        }

        .desktop-footer-checkline input[type="checkbox"] {
            margin-top: 2px;
            accent-color: #fff;
        }

        .desktop-footer-subscribe {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .desktop-footer-subscribe input {
            min-height: 40px;
            min-width: 280px;
            padding: 0 12px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
        }

        .desktop-footer-subscribe input::placeholder {
            color: rgba(255, 255, 255, 0.65);
        }

        .desktop-footer-subscribe button {
            min-height: 40px;
            padding: 0 14px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.28);
            background: #fff;
            color: #000;
            font-weight: 800;
            cursor: pointer;
        }

        .desktop-footer-app-panel {
            min-width: 270px;
            max-width: 340px;
            display: grid;
            gap: 10px;
            justify-items: start;
        }

        .desktop-footer-app {
            color: #000;
            font-size: 0.9rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .desktop-footer-app-copy {
            color: rgba(0, 0, 0, 0.78);
            font-weight: 600;
            font-size: 0.85rem;
        }

        .desktop-playstore-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-height: 42px;
            padding: 7px 12px;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.28);
            background: rgba(255, 255, 255, 0.32);
            color: #000;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.84rem;
        }

        .desktop-playstore-badge svg {
            width: 20px;
            height: 20px;
            flex: 0 0 auto;
        }

        .search-section {
            display: flex;
            gap: 8px;
            align-items: center;
            width: 100%;
            max-width: 100%;
            margin-top: 6px;
        }

        .global-search-form {
            flex: 1 1 100%;
            max-width: 100%;
            flex: 1;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .search-autocomplete {
            position: relative;
            width: 100%;
            min-width: 0;
            flex: 1;
        }

        .search-bar {
            width: 100%;
            flex: 1;
            min-height: 40px;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 14px;
            background: white;
            color: var(--text-color);
        }

        .search-suggest-list {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.16);
            z-index: 1200;
            display: none;
            max-height: 320px;
            overflow-y: auto;
        }

        .search-suggest-list.is-open {
            display: block;
        }

        .search-suggest-link {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            padding: 10px 12px;
            text-decoration: none;
            color: var(--text-color);
            border-bottom: 1px solid #eef2f5;
        }

        .search-suggest-link:last-child {
            border-bottom: 0;
        }

        .search-suggest-link:hover {
            background: #f7fbff;
        }

        .search-suggest-title {
            font-weight: 700;
            font-size: 0.9rem;
            color: #0f1a34;
            line-height: 1.3;
        }

        .search-suggest-subtitle {
            font-size: 0.8rem;
            color: #67758f;
            margin-top: 2px;
            line-height: 1.3;
        }

        .search-suggest-type {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #1f7a43;
            background: rgba(31, 122, 67, 0.1);
            border: 1px solid rgba(31, 122, 67, 0.2);
            padding: 3px 6px;
            border-radius: 999px;
            align-self: center;
            white-space: nowrap;
        }

        .search-submit {
            width: 40px;
            min-height: 40px;
            padding: 0;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: #fff;
            color: var(--text-color);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .search-submit svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .header-action {
            width: 40px;
            min-height: 40px;
            padding: 0;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .header-action svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .mobile-login-link {
            min-height: 38px;
            padding: 0 12px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.48);
            background: rgba(255, 255, 255, 0.18);
            color: #000000;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }
        .mobile-login-link svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .mobile-login-link.is-active {
            color: #1f7a43;
            background: rgba(255, 255, 255, 0.3);
        }
        .mobile-login-link:hover {
            color: #1f7a43;
            background: rgba(255, 255, 255, 0.3);
        }

        .header-tools { display: flex; align-items: center; gap: 8px; }
        .header-notification-wrap {
            position: relative;
            display: inline-flex;
        }
        .header-icon-link {
            position: relative;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--text-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            flex-shrink: 0;
        }
        .header-icon-link svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .header-icon-link.has-unread {
            color: var(--primary-color);
            border-color: color-mix(in srgb, var(--primary-color) 45%, white 55%);
            background: color-mix(in srgb, var(--primary-color) 10%, white 90%);
        }
        .header-icon-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #e74c3c;
            color: white;
            font-size: 0.68rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--header-bg);
        }
        .header-popover {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: min(320px, calc(100vw - 32px));
            padding: 14px;
            border-radius: 18px;
            border: 1px solid var(--border-color);
            background: linear-gradient(135deg, #1f7a43 0%, #4ea95f 28%, #f4c400 72%, #ffd95a 100%);
            box-shadow: 0 18px 40px var(--shadow-color);
            opacity: 0;
            visibility: hidden;
            transform: translateY(6px);
            transition: opacity 0.18s ease, transform 0.18s ease, visibility 0.18s ease;
            z-index: 1100;
        }
        .header-notification-wrap:hover .header-popover,
        .header-notification-wrap:focus-within .header-popover {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .header-popover::before {
            content: "";
            position: absolute;
            top: -7px;
            right: 16px;
            width: 14px;
            height: 14px;
            background: linear-gradient(135deg, #1f7a43 0%, #4ea95f 28%, #f4c400 72%, #ffd95a 100%);
            border-top: 1px solid rgba(255, 255, 255, 0.28);
            border-left: 1px solid var(--border-color);
            transform: rotate(45deg);
        }
        .header-popover-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text-color);
            margin-bottom: 10px;
        }
        .header-popover-list {
            display: grid;
            gap: 10px;
        }
        .header-popover-item {
            padding: 10px 12px;
            border-radius: 14px;
            background: color-mix(in srgb, var(--primary-color) 8%, white 92%);
        }
        .header-live-toast {
            position: fixed;
            right: 16px;
            top: 88px;
            max-width: min(320px, calc(100vw - 32px));
            padding: 12px 14px;
            border-radius: 16px;
            background: linear-gradient(135deg, #1f7a43 0%, #4ea95f 28%, #f4c400 72%, #ffd95a 100%);
            border: 1px solid var(--border-color);
            box-shadow: 0 18px 40px var(--shadow-color);
            color: var(--text-color);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: opacity 0.18s ease, transform 0.18s ease, visibility 0.18s ease;
            z-index: 1200;
        }
        .header-live-toast.is-visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .header-live-toast-title {
            font-size: 0.82rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 4px;
        }
        .header-live-toast-copy {
            font-size: 0.9rem;
            line-height: 1.5;
            color: var(--text-color);
        }
        .header-popover-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .header-popover-copy {
            color: var(--text-color);
            line-height: 1.5;
            font-size: 0.9rem;
        }
        .header-popover-more {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 0 14px;
            margin-top: 12px;
            border-radius: 12px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            font-weight: 700;
        }
        .main-content {
            min-height: calc(100vh - 150px);
            padding-bottom: 146px;
        }

        .quick-support-stack {
            position: fixed;
            right: 16px;
            bottom: 82px;
            z-index: 95;
            display: grid;
            gap: 10px;
        }

        .quick-support-btn {
            width: 54px;
            height: 54px;
            border: none;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #fff;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.18);
            cursor: pointer;
        }

        .quick-support-btn svg {
            width: 24px;
            height: 24px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .quick-support-btn span {
            display: none;
        }

        .quick-support-btn.live-chat {
            background: linear-gradient(135deg, #1f7a43 0%, #2f9f5c 100%);
        }

        .quick-support-btn.whatsapp {
            background: linear-gradient(135deg, #149c4a 0%, #25d366 100%);
        }

        .support-chat-panel {
            position: fixed;
            right: 16px;
            bottom: 146px;
            width: min(360px, calc(100vw - 24px));
            max-height: min(70vh, 560px);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            background: #fff;
            box-shadow: 0 20px 48px rgba(0, 0, 0, 0.22);
            overflow: hidden;
            display: none;
            flex-direction: column;
            z-index: 96;
        }

        .support-chat-panel.is-open {
            display: flex;
        }

        .support-chat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            background: linear-gradient(135deg, #1f7a43 0%, #4ea95f 60%, #f4c400 100%);
        }

        .support-chat-header h3 {
            margin: 0;
            font-size: 1rem;
            color: #0d2318;
        }

        .support-chat-header p {
            margin: 3px 0 0;
            color: rgba(13, 35, 24, 0.78);
            font-size: 0.82rem;
        }

        .support-chat-close {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid rgba(13, 35, 24, 0.14);
            background: rgba(255, 255, 255, 0.7);
            color: #143121;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .support-chat-close svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .support-chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #f7faf8;
        }

        .support-chat-row {
            display: flex;
        }

        .support-chat-row.is-sent {
            justify-content: flex-end;
        }

        .support-chat-row.is-received {
            justify-content: flex-start;
        }

        .support-chat-bubble {
            max-width: 82%;
            padding: 11px 13px;
            border-radius: 16px;
            line-height: 1.55;
            word-break: break-word;
        }

        .support-chat-bubble.is-sent {
            background: #1f4a36;
            color: #fff;
        }

        .support-chat-bubble.is-received {
            background: #fff;
            color: #183527;
            border: 1px solid #dbe6e0;
        }

        .support-chat-author {
            display: block;
            margin-bottom: 4px;
            font-size: 0.72rem;
            font-weight: 800;
            opacity: 0.78;
        }

        .support-chat-time {
            display: block;
            margin-top: 6px;
            font-size: 0.72rem;
            opacity: 0.72;
        }

        .support-chat-empty {
            padding: 22px 16px;
            text-align: center;
            color: var(--muted-color);
            line-height: 1.6;
        }

        .support-chat-intro {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            background: #fffef7;
        }

        .support-chat-intro p {
            color: var(--muted-color);
            font-size: 0.86rem;
            line-height: 1.6;
        }

        .support-chat-identity {
            padding: 12px;
            border-top: 1px solid var(--border-color);
            background: #fff;
            display: grid;
            gap: 10px;
        }

        .support-chat-identity input {
            width: 100%;
            min-height: 46px;
            border: 1px solid #d2ddd7;
            border-radius: 12px;
            padding: 0 12px;
            color: #173b2a;
            background: #fff;
        }

        .support-chat-identity button {
            min-height: 46px;
            border: none;
            border-radius: 12px;
            background: #1f4a36;
            color: #fff;
            font-weight: 800;
            cursor: pointer;
        }

        .support-chat-form {
            padding: 12px;
            border-top: 1px solid var(--border-color);
            background: #fff;
        }

        .support-chat-form-inner {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 10px;
            align-items: end;
        }

        .support-chat-textarea {
            min-height: 52px;
            max-height: 130px;
            border: 1px solid #d2ddd7;
            border-radius: 14px;
            padding: 12px 14px;
            resize: vertical;
            color: #173b2a;
            background: #fff;
        }

        .support-chat-send {
            min-width: 56px;
            height: 52px;
            border: none;
            border-radius: 14px;
            background: #1f4a36;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .support-chat-send svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: linear-gradient(135deg, #1f7a43 0%, #4ea95f 28%, #f4c400 72%, #ffd95a 100%);
            border-top: 1px solid rgba(255, 255, 255, 0.28);
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            z-index: 90;
            box-shadow: 0 -2px 16px var(--shadow-color);
        }

        .nav-item {
            text-align: center;
            padding: 8px;
            cursor: pointer;
            flex: 1;
            font-size: 12px;
            color: #000;
            text-decoration: none;
        }

        .nav-item.active {
            color: #1f7a43;
            font-weight: bold;
        }

        .nav-item:hover { color: #1f7a43; }
        .nav-item-icon {
            width: 22px;
            height: 22px;
            margin: 0 auto 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .nav-item-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #e74c3c;
            color: white;
            font-size: 0.68rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bottom-sheet-bg);
            line-height: 1;
        }

        .nav-item-icon svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .nav-item-icon svg.fill-icon {
            fill: currentColor;
            stroke: none;
        }

        .nav-item.active .nav-item-icon svg.fill-soft {
            fill: currentColor;
            stroke: currentColor;
        }

        .nav-item-icon .accent-fill {
            fill: currentColor;
            stroke: none;
        }

        .nav-item-icon .accent-stroke {
            stroke: currentColor;
        }

        .nav-item-icon {
            margin-bottom: 4px;
        }

        .nav-item-label {
            font-size: 12px;
            font-weight: 600;
        }

        @media (min-width: 1024px) {
            .header {
                padding: 14px 24px 14px 0;
            }

            .header-top {
                max-width: none;
                margin-left: 0;
                margin-right: 0;
            }

            .header-top {
                justify-content: flex-start;
                gap: 8px;
                margin-bottom: 0;
            }

            .logo {
                width: 500px;
                height: 124px;
            }

            .desktop-nav,
            .desktop-auth-links {
                display: flex;
            }

            .mobile-login-link {
                display: none;
            }

            .desktop-nav {
                flex: 1;
                margin-left: 0;
                justify-content: flex-start;
                flex-wrap: nowrap;
                row-gap: 0;
            }

            .desktop-auth-links {
                margin-left: auto;
            }

            .search-section {
                max-width: calc(100% - 36px);
                margin-left: 18px;
                margin-right: 18px;
                width: 100%;
                margin-top: 12px;
            }

            .global-search-form {
                flex: 1 1 100%;
                max-width: 100%;
                width: 100%;
            }

            .search-autocomplete {
                flex: 1 1 100%;
                width: 100%;
            }

            .search-bar {
                width: 100%;
                min-height: 52px;
                padding: 12px 16px;
                font-size: 16px;
            }

            .search-submit,
            .header-action {
                width: 52px;
                min-height: 52px;
            }

            .header-tools {
                display: flex;
                position: fixed;
                top: 14px;
                right: 22px;
                z-index: 1301;
                background: transparent;
            }

            .header-tools > .header-icon-link {
                display: none;
            }

            .header-tools > .header-notification-wrap {
                display: inline-flex;
            }



            .main-content {
                max-width: 1240px;
                margin: 0 auto;
                min-height: calc(100vh - 110px);
                padding: 22px 24px 44px;
            }

            .desktop-footer {
                display: block;
                width: 100%;
                max-width: none;
                margin: 0;
                padding: 0 0 26px;
            }

            .desktop-footer-inner {
                border-top: 0;
                border-radius: 0;
                background: linear-gradient(135deg, #1f7a43 0%, #4ea95f 28%, #f4c400 72%, #ffd95a 100%);
                padding: 18px 24px;
            }

            .bottom-nav {
                display: none;
            }

            .quick-support-stack {
                display: grid !important;
                right: 24px;
                bottom: 24px;
            }

            .quick-support-stack .quick-support-btn.whatsapp {
                display: none;
            }

            .support-chat-panel {
                right: 24px;
                bottom: 92px;
            }
        }

        .global-call-modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(10, 16, 14, 0.72);
            z-index: 999999;
        }
        .global-call-modal.is-visible { display: flex !important; }
        .global-call-card {
            width: 100%;
            max-width: 420px;
            padding: 26px;
            border-radius: 22px;
            background: linear-gradient(135deg, #1f7a43 0%, #4ea95f 28%, #f4c400 72%, #ffd95a 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 24px 54px rgba(0, 0, 0, 0.42);
            text-align: center;
            color: #000;
        }
        .global-call-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 14px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.24);
            color: #143121;
        }
        .global-call-icon svg {
            width: 34px;
            height: 34px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .global-call-card h3 { margin: 0 0 8px; color: #0d2318; font-weight: 900; }
        .global-call-card p { margin: 0 0 18px; color: rgba(13, 35, 24, 0.82); line-height: 1.6; font-weight: 600; }
        .global-call-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .global-call-btn {
            min-height: 44px;
            padding: 0 18px;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.12);
            background: rgba(255, 255, 255, 0.6);
            color: #0d2318;
            font-weight: 700;
            cursor: pointer;
        }
        .global-call-btn.primary {
            background: #1f4a36;
            border-color: #1f4a36;
            color: white;
        }
        .global-call-workspace {
            position: fixed;
            inset: 0;
            display: none;
            align-items: stretch;
            justify-content: center;
            background: rgba(10, 16, 14, 0.82);
            z-index: 999998;
            padding: 14px;
        }
        .global-call-workspace.is-visible {
            display: flex !important;
        }
        .global-call-workspace-shell {
            width: min(1180px, 100%);
            height: min(100%, 880px);
            display: grid;
            grid-template-rows: auto minmax(0, 1fr);
            border-radius: 26px;
            overflow: hidden;
            background: linear-gradient(135deg, #1f7a43 0%, #4ea95f 28%, #f4c400 72%, #ffd95a 100%);
            border: 1px solid var(--border-color);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
        }
        .global-call-workspace-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-color);
            background: color-mix(in srgb, var(--primary-color) 6%, var(--bottom-sheet-bg));
        }
        .global-call-workspace-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text-color);
        }
        .global-call-workspace-copy {
            color: var(--muted-color);
            font-size: 0.86rem;
            line-height: 1.5;
        }
        .global-call-workspace-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        .global-call-workspace-btn {
            min-height: 40px;
            padding: 0 14px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background: linear-gradient(135deg, #1f7a43 0%, #4ea95f 28%, #f4c400 72%, #ffd95a 100%);
            color: var(--text-color);
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .global-call-workspace-btn.primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        .global-call-workspace-frame {
            width: 100%;
            height: 100%;
            border: 0;
            background: var(--page-bg);
        }
        body.call-workspace-open {
            overflow: hidden;
        }
        @media (max-width: 760px) {
            .global-call-workspace {
                padding: 0;
            }
            .global-call-workspace-shell {
                width: 100%;
                height: 100%;
                border-radius: 0;
                border: 0;
            }
            .global-call-workspace-bar {
                padding: 12px 14px;
            }
        }

        body.safplace-theme .header-icon-link {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        body.safplace-theme .header-icon-link.has-unread {
            background: rgba(255, 255, 255, 0.28);
            border-color: rgba(255, 255, 255, 0.34);
        }
        body.safplace-theme .header-popover {
            background: rgba(11, 23, 19, 0.96);
            border-color: rgba(255, 255, 255, 0.12);
        }
        body.safplace-theme .header-popover::before {
            background: rgba(11, 23, 19, 0.96);
            border-top-color: rgba(255, 255, 255, 0.12);
            border-left-color: rgba(255, 255, 255, 0.12);
        }
        body.safplace-theme .header-popover-item {
            background: rgba(255, 255, 255, 0.08);
        }
        body.safplace-theme .header-popover-copy,
        body.safplace-theme .header-popover-title {
            color: white;
        }

        input, select, textarea {
            background: white;
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .auth-container,
        .profile-container,
        .checkout-container,
        .cart-container,
        .orders-container,
        .order-container,
        .messages-container,
        .onboarding-container,
        .product-container,
        .vendor-info,
        .actions,
        .orders,
        .product,
        .stat,
        .vendor-card,
        .product-card,
        .conversation,
        .order,
        .section,
        .banner,
        .cart-summary,
        .order-info,
        .street-food-item {
            background: var(--bottom-sheet-bg) !important;
            color: var(--text-color) !important;
            box-shadow: 0 2px 10px var(--shadow-color) !important;
        }

        .category-filter,
        .map-label {
            background: var(--header-bg) !important;
        }

        .auth-container h1,
        .profile-container h1,
        .checkout-container h1,
        .cart-container h1,
        .orders-container h1,
        .order-container h1,
        .messages-container h1,
        .onboarding-container h1,
        .product-container h1,
        .category-container h1,
        .dashboard-container h1,
        .actions h2,
        .orders h2,
        .cart-summary h3,
        .order-items h3,
        .conversation h3,
        .price,
        .product-price,
        .item-price,
        .order-total,
        .order-link,
        .back-link,
        .login-link,
        .vendor-badge,
        .product h3 a:hover {
            color: var(--primary-color) !important;
        }

        .auth-button,
        .checkout-button,
        .profile-button,
        .cart-button,
        .checkout-btn,
        .add-to-cart-btn,
        .message-button,
        .onboarding-button,
        .action-btn,
        .filter-btn,
        .btn-apply {
            background: var(--primary-color) !important;
            color: white !important;
        }

        .auth-tab.active,
        .category-chip.active {
            background: var(--primary-color) !important;
            color: white !important;
            border-color: var(--primary-color) !important;
        }

        .auth-tab {
            background: #d1d5db !important;
            color: #000000 !important;
        }

        .category-chip,
        .btn-reset,
        .message.received,
        .filter-panel,
        .vendor-item {
            background: var(--surface-alt) !important;
            color: white !important;
        }

        body.safplace-theme .category-chip,
        body.safplace-theme .btn-reset,
        body.safplace-theme .message.received,
        body.safplace-theme .filter-panel,
        body.safplace-theme .vendor-item,
        body.safplace-theme .profile-container,
        body.safplace-theme .category-container,
        body.safplace-theme .section,
        body.safplace-theme .banner,
        body.safplace-theme .product-card,
        body.safplace-theme .vendor-card,
        body.safplace-theme .street-food-item,
        body.safplace-theme .category-filter,
        body.safplace-theme .auth-container,
        body.safplace-theme .checkout-container,
        body.safplace-theme .cart-container,
        body.safplace-theme .orders-container,
        body.safplace-theme .order-container,
        body.safplace-theme .messages-container,
        body.safplace-theme .onboarding-container,
        body.safplace-theme .product-container,
        body.safplace-theme .vendor-info,
        body.safplace-theme .actions,
        body.safplace-theme .orders,
        body.safplace-theme .product,
        body.safplace-theme .stat,
        body.safplace-theme .conversation,
        body.safplace-theme .order,
        body.safplace-theme .cart-summary,
        body.safplace-theme .food-tile,
        body.safplace-theme .vendor-tile,
        body.safplace-theme .step,
        body.safplace-theme .review-card,
        body.safplace-theme .location-display,
        body.safplace-theme .address-box,
        body.safplace-theme .order-info,
        body.safplace-theme .cart-summary,
        body.safplace-theme .payment-card,
        body.safplace-theme .address-card {
            background: var(--secondary-background) !important;
            color: white !important;
            border-color: transparent !important;
        }

        body.safplace-theme .category-chip:hover,
        body.safplace-theme .recipe-link:hover {
            background: color-mix(in srgb, var(--secondary-background) 85%, white 15%) !important;
        }

        body.safplace-theme .food-desc,
        body.safplace-theme .vendor-distance,
        body.safplace-theme .product-distance,
        body.safplace-theme .review-text,
        body.safplace-theme .message small,
        body.safplace-theme .order-info p,
        body.safplace-theme .location-note,
        body.safplace-theme .onboarding-description,
        body.safplace-theme .product-vendor,
        body.safplace-theme .product-stock,
        body.safplace-theme .item-info p,
        body.safplace-theme .empty-cart,
        body.safplace-theme .empty-orders,
        body.safplace-theme .empty-messages,
        body.safplace-theme .payment-number,
        body.safplace-theme .address-label,
        body.safplace-theme .option-time,
        body.safplace-theme .payment-desc,
        body.safplace-theme .summary-row,
        body.safplace-theme .section-subtitle,
        body.safplace-theme .profile-input,
        body.safplace-theme .profile-select {
            color: rgba(255, 255, 255, 0.82) !important;
        }

        body.safplace-theme .item-info h3,
        body.safplace-theme .order-info h3,
        body.safplace-theme .order-info strong,
        body.safplace-theme .item-info h4,
        body.safplace-theme .product h3 a,
        body.safplace-theme .vendor-name,
        body.safplace-theme .section-title,
        body.safplace-theme .food-name,
        body.safplace-theme .ingredient-name,
        body.safplace-theme .review-author,
        body.safplace-theme .sponsored-name {
            color: white !important;
        }

        body.safplace-theme .profile-input,
        body.safplace-theme .profile-select,
        body.safplace-theme .auth-input,
        body.safplace-theme .auth-select,
        body.safplace-theme .checkout-input,
        body.safplace-theme .checkout-select,
        body.safplace-theme .checkout-textarea,
        body.safplace-theme .cart-input,
        body.safplace-theme .message-textarea,
        body.safplace-theme .onboarding-input,
        body.safplace-theme .quantity-input {
            background: rgba(255, 255, 255, 0.10) !important;
            color: white !important;
            border-color: rgba(255, 255, 255, 0.22) !important;
        }

        body.safplace-theme .profile-input::placeholder,
        body.safplace-theme .auth-input::placeholder,
        body.safplace-theme .checkout-input::placeholder,
        body.safplace-theme .checkout-textarea::placeholder,
        body.safplace-theme .message-textarea::placeholder,
        body.safplace-theme .onboarding-input::placeholder {
            color: rgba(255, 255, 255, 0.65) !important;
        }

        .message.sent {
            background: color-mix(in srgb, var(--primary-color) 18%, white) !important;
            border-left-color: var(--primary-color) !important;
        }

        .item-info h3,
        .order-info h3,
        .order-info strong,
        .item-info h4,
        .product h3 a,
        .vendor-name,
        .section-title {
            color: var(--text-color) !important;
        }

        .item-info p,
        .product-description,
        .product-vendor,
        .product-stock,
        .empty-cart,
        .empty-orders,
        .empty-messages,
        .onboarding-description,
        .location-note,
        .vendor-distance,
        .product-distance,
        .message small,
        .order-info p {
            color: var(--muted-color) !important;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="header @yield('hide_header')">
        <div class="header-top">
            <a class="logo" href="{{ route('home') }}" aria-label="Go to homepage">
                <img src="{{ asset('images/logo.png') }}" alt="PikFreshFood">
            </a>
            <nav class="desktop-nav" aria-label="Desktop navigation">
                <a href="{{ route('home') }}" class="desktop-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5"></path><path d="M5.5 9.5V20h13V9.5"></path><path d="M10 20v-5h4v5"></path></svg>
                    <span>Home</span>
                </a>
                <a href="{{ route('about') }}" class="desktop-nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M12 10v6"></path><path d="M12 7h.01"></path></svg>
                    <span>About</span>
                </a>
                @guest
                    <a href="{{ route('contact-us') }}" class="desktop-nav-link {{ request()->routeIs('contact-us') ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"></path></svg>
                        <span>Contact-us</span>
                    </a>
                @endguest


                @auth
                    @if(!auth()->user()->isVendor())
                        <a href="{{ route('cart.index') }}" class="desktop-nav-link {{ request()->routeIs('cart.*') ? 'is-active' : '' }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="19" r="1.6"></circle><circle cx="17" cy="19" r="1.6"></circle><path d="M3 5h2l2.1 9.2A1 1 0 0 0 8.1 15H18a1 1 0 0 0 1-.8L20.5 8H6.2"></path></svg>
                            <span>Cart</span>
                            @if(($headerCartCount ?? 0) > 0)
                                <span class="desktop-menu-badge">{{ $headerCartCount > 9 ? '9+' : $headerCartCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('profile.wishlist') }}" class="desktop-nav-link {{ request()->routeIs('profile.wishlist') ? 'is-active' : '' }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20s-7-4.5-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.5-7 10-7 10Z"></path></svg>
                            <span>Watch list</span>
                            @if(($headerWishlistCount ?? 0) > 0)
                                <span class="desktop-menu-badge">{{ $headerWishlistCount > 9 ? '9+' : $headerWishlistCount }}</span>
                            @endif
                        </a>
                    @endif

                    <a href="{{ $headerMessageRoute }}" class="desktop-nav-link {{ request()->routeIs('messages.*') ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"></path></svg>
                        <span>Messages</span>
                        @if(($headerUnreadMessageCount ?? 0) > 0)
                            <span class="desktop-menu-badge">{{ $headerUnreadMessageCount > 9 ? '9+' : $headerUnreadMessageCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('calls.index', ['mode' => 'audio']) }}" class="desktop-nav-link {{ request()->routeIs('calls.index') && request('mode', 'audio') === 'audio' ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.5 3a2 2 0 0 1-.6 1.8l-1.3 1.3a16 16 0 0 0 6.4 6.4l1.3-1.3a2 2 0 0 1 1.8-.6l3 .5A2 2 0 0 1 22 16.9Z"></path></svg>
                        <span>Audio</span>
                        @if(($headerIncomingCallCount ?? 0) > 0)
                            <span class="desktop-menu-badge">{{ $headerIncomingCallCount > 9 ? '9+' : $headerIncomingCallCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('calls.index', ['mode' => 'video']) }}" class="desktop-nav-link {{ request()->routeIs('calls.index') && request('mode') === 'video' ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m22 8-6 4 6 4V8Z"></path><rect x="2" y="6" width="14" height="12" rx="2"></rect></svg>
                        <span>Video</span>
                        @if(($headerIncomingCallCount ?? 0) > 0)
                            <span class="desktop-menu-badge">{{ $headerIncomingCallCount > 9 ? '9+' : $headerIncomingCallCount }}</span>
                        @endif
                    </a>
                @endauth
            </nav>
            <div class="desktop-auth-links">
                @guest
                    <a href="{{ route('login') }}" class="desktop-auth-link {{ request()->routeIs('login') ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"></circle><path d="M5 20a7 7 0 0 1 14 0"></path></svg>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="desktop-auth-link primary {{ request()->routeIs('register') ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
                        <span>Register</span>
                    </a>
                @else
                    @if(auth()->user()->isVendor())
                        <a href="{{ route('vendor.dashboard') }}" class="desktop-auth-link {{ request()->routeIs('vendor.*') ? 'is-active' : '' }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 13h7V4H4z"></path><path d="M13 20h7v-9h-7z"></path><path d="M13 11h7V4h-7z"></path><path d="M4 20h7v-5H4z"></path></svg>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('profile.edit') }}" class="desktop-auth-link {{ request()->routeIs('profile.*') ? 'is-active' : '' }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"></circle><path d="M5 20a7 7 0 0 1 14 0"></path></svg>
                            <span>Profile</span>
                        </a>
                    @endif
                    <a href="{{ route('contact-us') }}" class="desktop-auth-link {{ request()->routeIs('contact-us') ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"></path></svg>
                        <span>Contact-us</span>
                    </a>
                    <a href="{{ route('auth.logout.get') }}" class="desktop-auth-link">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><path d="M10 17l5-5-5-5"></path><path d="M15 12H3"></path></svg>
                        <span>Logout</span>
                    </a>
                @endguest
            </div>
            @guest
                <a href="{{ route('login') }}" class="mobile-login-link {{ request()->routeIs('login') || request()->routeIs('register') ? 'is-active' : '' }}" aria-label="Login">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"></circle><path d="M5 20a7 7 0 0 1 14 0"></path></svg>
                    <span>Login</span>
                </a>
            @endguest
            <div class="header-tools">
                @auth
                    <div class="header-notification-wrap">
                        <a href="{{ route('profile.notifications') }}" id="headerNotificationLink" class="header-icon-link {{ $headerNotificationCount > 0 ? 'has-unread' : '' }}" aria-label="Notifications">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M6 9a6 6 0 1 1 12 0c0 7 3 7 3 7H3s3 0 3-7"></path>
                                <path d="M10 20a2 2 0 0 0 4 0"></path>
                            </svg>
                            <span id="headerNotificationBadge" class="header-icon-badge" @if($headerNotificationCount <= 0) style="display:none;" @endif>{{ $headerNotificationCount > 9 ? '9+' : $headerNotificationCount }}</span>
                        </a>
                        <div class="header-popover" role="dialog" aria-label="Notification summary">
                            <div class="header-popover-title">Notifications</div>
                            <div class="header-popover-list" id="headerNotificationPreviewList">
                                @foreach($headerNotificationPreview as $notificationPreview)
                                    <div class="header-popover-item">
                                        <span class="header-popover-label">{{ $notificationPreview['label'] }}</span>
                                        <div class="header-popover-copy">{{ $notificationPreview['message'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('profile.notifications') }}" class="header-popover-more">View More</a>
                        </div>
                    </div>
                    <a href="{{ $headerMessageRoute }}" id="headerMessageLink" class="header-icon-link {{ $headerUnreadMessageCount > 0 ? 'has-unread' : '' }}" aria-label="Messages">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"></path>
                        </svg>
                        <span id="headerMessageBadge" class="header-icon-badge" @if($headerUnreadMessageCount <= 0) style="display:none;" @endif>{{ $headerUnreadMessageCount > 9 ? '9+' : $headerUnreadMessageCount }}</span>
                    </a>
                    <a href="{{ route('calls.index', ['mode' => 'audio']) }}" class="header-icon-link {{ $headerIncomingCallCount > 0 ? 'has-unread' : '' }}" aria-label="Call history">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.5 3a2 2 0 0 1-.6 1.8l-1.3 1.3a16 16 0 0 0 6.4 6.4l1.3-1.3a2 2 0 0 1 1.8-.6l3 .5A2 2 0 0 1 22 16.9Z"></path>
                        </svg>
                        @if($headerIncomingCallCount > 0)
                            <span class="header-icon-badge">{{ $headerIncomingCallCount > 9 ? '9+' : $headerIncomingCallCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('calls.index', ['mode' => 'video']) }}" class="header-icon-link" aria-label="Video call history">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m22 8-6 4 6 4V8Z"></path>
                            <rect x="2" y="6" width="14" height="12" rx="2"></rect>
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
        <div class="search-section">
            <form class="global-search-form" method="GET" action="{{ route('home') }}">
                <div class="search-autocomplete">
                    <input
                        type="text"
                        class="search-bar"
                        name="search"
                        value="{{ request('search', '') }}"
                        placeholder="Search products or vendors"
                        aria-label="Search for products or vendors"
                        autocomplete="off"
                    >
                    <div class="search-suggest-list" id="searchSuggestList" role="listbox" aria-label="Search suggestions"></div>
                </div>
                @if(request()->has('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                <button type="submit" class="search-submit" aria-label="Search">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.2-3.2"></path></svg>
                </button>
            </form>
            <a href="{{ route('map.index') }}" class="header-action" aria-label="Map">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-6-5.4-6-11a6 6 0 1 1 12 0c0 5.6-6 11-6 11Z"></path><circle cx="12" cy="10" r="2.2"></circle></svg>
            </a>
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>
    @if(!request()->boolean('embedded'))
    <footer class="desktop-footer" aria-label="Desktop footer">
        <div class="desktop-footer-inner">
            <div class="desktop-footer-top">
                <div class="desktop-footer-news">
                    <h4>New to Jumia?</h4>
                    <p>Subscribe to our newsletter to get updates on our latest offers, you can unsubscribe at any time as described in Privacy Policy.</p>
                    <p>To subscribe to our newsletter, you must first read and agree to Jumia's Privacy Policy and Cookie Notice</p>
                    <label class="desktop-footer-checkline">
                        <input type="checkbox" name="footer_terms" value="1">
                        <span>I agree Our Privacy and Cookie Policy.</span>
                    </label>
                    <form class="desktop-footer-subscribe" action="#" method="GET">
                        <input type="email" name="newsletter_email" placeholder="Enter E-mail Address" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
                <div class="desktop-footer-app-panel">
                    <div class="desktop-footer-app">DOWNLOAD JPIKFRESHFOOD FREE APP</div>
                    <div class="desktop-footer-app-copy">Get access to exclusive offers!</div>
                    <a href="#" class="desktop-playstore-badge" aria-label="Get it on Google Play">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 3l11 9-11 9z" fill="#34a853"></path>
                            <path d="M4 3l7.5 6L9 11 4 3z" fill="#4285f4"></path>
                            <path d="M4 21l7.5-6L9 13 4 21z" fill="#fbbc05"></path>
                            <path d="M9 11l2.5-2 3.5 3-3.5 3L9 13z" fill="#ea4335"></path>
                        </svg>
                        <span>Google Play Store</span>
                    </a>
                </div>
            </div>
            <div class="desktop-footer-bottom">
                <div class="desktop-footer-copy">© {{ now()->year }} PikFreshFood. All rights reserved.</div>
                <div class="desktop-footer-links">
                    <a href="{{ route('home') }}" class="desktop-footer-link">Home</a>
                    <a href="{{ route('about') }}" class="desktop-footer-link">About</a>
                    <a href="{{ route('contact-us') }}" class="desktop-footer-link">Contact-us</a>
                    <a href="{{ route('faq') }}" class="desktop-footer-link">FAQ</a>
                    <a href="{{ route('terms-and-condition') }}" class="desktop-footer-link">Termes and Condition</a>
                    <a href="{{ route('privacy-and-policy') }}" class="desktop-footer-link">Privacy and Policy</a>
                </div>
            </div>
            <div class="desktop-footer-social" aria-label="Social media links">
                <span class="desktop-footer-social-title">Follow us</span>
                <div class="desktop-footer-social-links">
                    <a href="#" class="desktop-footer-social-link" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 8h3V4h-3a5 5 0 0 0-5 5v3H7v4h3v4h4v-4h3l1-4h-4V9a1 1 0 0 1 1-1Z"></path></svg>
                    </a>
                    <a href="#" class="desktop-footer-social-link" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17" cy="7" r="1"></circle></svg>
                    </a>
                    <a href="#" class="desktop-footer-social-link" aria-label="X">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 4 6.8 8.9L4.6 20H8l4.5-5.2L16.5 20H20l-7-9.2L19.4 4H16l-4 4.7L8.5 4Z"></path></svg>
                    </a>
                    <a href="#" class="desktop-footer-social-link" aria-label="YouTube">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="6" width="20" height="12" rx="3"></rect><path d="m10 9 5 3-5 3z"></path></svg>
                    </a>
                    <a href="#" class="desktop-footer-social-link" aria-label="TikTok">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 4v9.5a3.5 3.5 0 1 1-2.8-3.4"></path><path d="M14 6.5a5 5 0 0 0 4 2.1"></path></svg>
                    </a>
                    <a href="#" class="desktop-footer-social-link" aria-label="WhatsApp">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12a8 8 0 0 1-11.7 7l-4.3 1.2 1.2-4.2A8 8 0 1 1 20 12Z"></path><path d="M9.4 10.2c.2-.5.4-.5.6-.5h.5c.2 0 .4 0 .5.3l.7 1.5c.1.2 0 .4-.1.5l-.3.4c-.1.1-.2.2-.1.4a6.3 6.3 0 0 0 2.9 2.5c.2.1.3 0 .4-.1l.5-.6c.1-.1.3-.2.5-.1l1.4.7c.2.1.3.2.3.4v.5c0 .2 0 .4-.4.6a3.4 3.4 0 0 1-2 .2c-1.8-.5-4.2-2.7-5.2-4.4-.6-1.1-.8-2-.4-2.8Z"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    @endif

    @php
        $callUiEnabled = request()->routeIs('vendor.show', 'calls.*', 'caller.*', 'messages.*');
    @endphp

    <div class="header-live-toast" id="headerLiveToast" aria-live="polite" aria-atomic="true">
        <div class="header-live-toast-title" id="headerLiveToastTitle">New notification</div>
        <div class="header-live-toast-copy" id="headerLiveToastCopy"></div>
    </div>

    @if($callUiEnabled)
        <div class="global-call-workspace" id="globalCallWorkspace" aria-hidden="true">
            <div class="global-call-workspace-shell">
                <div class="global-call-workspace-bar">
                    <div>
                        <div class="global-call-workspace-title" id="globalCallWorkspaceTitle">Active call</div>
                        <div class="global-call-workspace-copy">The call now runs inline here without refreshing the rest of the page.</div>
                    </div>
                    <div class="global-call-workspace-actions">
                        <a href="#" class="global-call-workspace-btn" id="globalCallWorkspaceOpenNew">Open Full Page</a>
                        <button type="button" class="global-call-workspace-btn primary" id="globalCallWorkspaceClose">Close</button>
                    </div>
                </div>
                <iframe
                    id="globalCallWorkspaceFrame"
                    class="global-call-workspace-frame"
                    title="Call workspace"
                    loading="lazy"
                    allow="camera; microphone; autoplay; display-capture; fullscreen"
                ></iframe>
            </div>
        </div>

        @auth
            @if(auth()->user()->isVendor())
                <div class="global-call-modal" id="globalIncomingCallModal" aria-hidden="true">
                    <div class="global-call-card">
                        <div class="global-call-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.5 3a2 2 0 0 1-.6 1.8l-1.3 1.3a16 16 0 0 0 6.4 6.4l1.3-1.3a2 2 0 0 1 1.8-.6l3 .5A2 2 0 0 1 22 16.9Z"></path>
                            </svg>
                        </div>
                        <h3>Incoming Buyer Call</h3>
                        <p id="globalIncomingCallMessage">A buyer is trying to reach you online.</p>
                        <div class="global-call-actions">
                            <button type="button" class="global-call-btn primary" id="globalAcceptIncomingCallButton">Accept Call</button>
                            <button type="button" class="global-call-btn" id="globalDismissIncomingCallButton">Close</button>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
    @endif

    @if(!auth()->check() || !auth()->user()->isAdmin())
        <div class="quick-support-stack" aria-label="Quick support actions">
            <button type="button" class="quick-support-btn live-chat" id="supportChatToggle" aria-label="Open live chat support">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"></path>
                </svg>
                <span>Live Chat</span>
            </button>
            <a href="https://wa.me/2348000000000" class="quick-support-btn whatsapp" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 12a8 8 0 0 1-11.7 7l-4.3 1.2 1.2-4.2A8 8 0 1 1 20 12Z"></path>
                    <path d="M9.4 10.2c.2-.5.4-.5.6-.5h.5c.2 0 .4 0 .5.3l.7 1.5c.1.2 0 .4-.1.5l-.3.4c-.1.1-.2.2-.1.4a6.3 6.3 0 0 0 2.9 2.5c.2.1.3 0 .4-.1l.5-.6c.1-.1.3-.2.5-.1l1.4.7c.2.1.3.2.3.4v.5c0 .2 0 .4-.4.6a3.4 3.4 0 0 1-2 .2c-1.8-.5-4.2-2.7-5.2-4.4-.6-1.1-.8-2-.4-2.8Z"></path>
                </svg>
                <span>WhatsApp</span>
            </a>
        </div>

        <div class="support-chat-panel" id="supportChatPanel" aria-hidden="true">
            <div class="support-chat-header">
                <div>
                    <h3>Live Chat</h3>
                    <p>Chat with admin support</p>
                </div>
                <button type="button" class="support-chat-close" id="supportChatClose" aria-label="Close live chat">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="support-chat-intro" id="supportChatIntro">
                <p>Start a live support conversation. Guests can chat by entering a name and email first.</p>
            </div>
            <div class="support-chat-messages" id="supportChatMessages">
                <div class="support-chat-empty">Loading support chat...</div>
            </div>
            <form class="support-chat-identity" id="supportChatIdentityForm">
                @csrf
                <input type="text" id="supportGuestName" name="name" placeholder="Your name" value="{{ auth()->check() ? auth()->user()->name : '' }}">
                <input type="email" id="supportGuestEmail" name="email" placeholder="Your email" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                <button type="submit" id="supportChatStart">Start Chat</button>
            </form>
            <form class="support-chat-form" id="supportChatForm" style="display:none;">
                @csrf
                <div class="support-chat-form-inner">
                    <textarea class="support-chat-textarea" id="supportChatInput" name="message" placeholder="Type your message to admin..." required></textarea>
                    <button type="submit" class="support-chat-send" id="supportChatSend" aria-label="Send support message">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M22 2 11 13"></path>
                            <path d="m22 2-7 20-4-9-9-4Z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="bottom-nav">
        <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <div class="nav-item-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                    <path d="M3 10.5 12 3l9 7.5"></path>
                    <path d="M5.5 9.5V20h13V9.5"></path>
                    <path d="M10 20v-5h4v5"></path>
                </svg>
            </div>
            <div class="nav-item-label">Home</div>
        </a>
        <a href="{{ route('live.index') }}" class="nav-item {{ request()->routeIs('live.index') ? 'active' : '' }}">
            <div class="nav-item-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                    <polygon points="9,7 18,12 9,17"></polygon>
                    <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                </svg>
            </div>
            <div class="nav-item-label">Lives</div>
        </a>
        @auth
            @if(auth()->user()->isVendor())
                <a href="{{ route('vendor.dashboard') }}" class="nav-item {{ request()->routeIs('vendor.*') && !request()->routeIs('vendor.profile.*') ? 'active' : '' }}">
                    <div class="nav-item-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 13h7V4H4z"></path>
                            <path d="M13 20h7v-9h-7z"></path>
                            <path d="M13 11h7V4h-7z"></path>
                            <path d="M4 20h7v-5H4z"></path>
                        </svg>
                    </div>
                    <div class="nav-item-label">Dashboard</div>
                </a>
                <a href="{{ route('vendor.profile.edit') }}" class="nav-item {{ request()->routeIs('vendor.profile.*') ? 'active' : '' }}">
                    <div class="nav-item-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="3.5"></circle>
                            <path d="M5 20a7 7 0 0 1 14 0"></path>
                        </svg>
                    </div>
                    <div class="nav-item-label">Profile</div>
                </a>
            @else
                <a href="{{ route('cart.index') }}" class="nav-item {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                    <div class="nav-item-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <circle cx="9" cy="19" r="1.6"></circle>
                            <circle cx="17" cy="19" r="1.6"></circle>
                            <path d="M3 5h2l2.1 9.2A1 1 0 0 0 8.1 15H18a1 1 0 0 0 1-.8L20.5 8H6.2"></path>
                        </svg>
                        @if(($headerCartCount ?? 0) > 0)
                            <span class="nav-item-badge">{{ $headerCartCount > 9 ? '9+' : $headerCartCount }}</span>
                        @endif
                    </div>
                    <div class="nav-item-label">Cart</div>
                </a>
                <a href="{{ route('profile.wishlist') }}" class="nav-item {{ request()->routeIs('profile.wishlist') ? 'active' : '' }}">
                    <div class="nav-item-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 20s-7-4.5-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.5-7 10-7 10Z"></path>
                        </svg>
                        @if(($headerWishlistCount ?? 0) > 0)
                            <span class="nav-item-badge">{{ $headerWishlistCount > 9 ? '9+' : $headerWishlistCount }}</span>
                        @endif
                    </div>
                    <div class="nav-item-label">Watch list</div>
                </a>
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') || request()->routeIs('profile.addresses') || request()->routeIs('profile.payment-methods') || request()->routeIs('profile.notifications') ? 'active' : '' }}">
                    <div class="nav-item-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="3.5"></circle>
                            <path d="M5 20a7 7 0 0 1 14 0"></path>
                        </svg>
                    </div>
                    <div class="nav-item-label">Profile</div>
                </a>
            @endif
            <a href="{{ route('auth.logout.get') }}" class="nav-item" aria-label="Logout">
                <div class="nav-item-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <path d="M16 17l5-5-5-5"></path>
                        <path d="M21 12H9"></path>
                    </svg>
                </div>
                <div class="nav-item-label">Logout</div>
            </a>
        @else
            <a href="{{ route('cart.index') }}" class="nav-item {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                <div class="nav-item-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <circle cx="9" cy="19" r="1.6"></circle>
                        <circle cx="17" cy="19" r="1.6"></circle>
                        <path d="M3 5h2l2.1 9.2A1 1 0 0 0 8.1 15H18a1 1 0 0 0 1-.8L20.5 8H6.2"></path>
                    </svg>
                    @if(($headerCartCount ?? 0) > 0)
                        <span class="nav-item-badge">{{ $headerCartCount > 9 ? '9+' : $headerCartCount }}</span>
                    @endif
                </div>
                <div class="nav-item-label">Cart</div>
            </a>

        @endauth
    </div>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.getRegistrations().then(function (registrations) {
                    registrations.forEach(function (registration) {
                        registration.unregister();
                    });
                });

                if ('caches' in window) {
                    caches.keys().then(function (keys) {
                        keys.forEach(function (key) {
                            caches.delete(key);
                        });
                    });
                }
            });
        }

        @auth
            (function () {
                const notificationLink = document.getElementById('headerNotificationLink');
                const notificationBadge = document.getElementById('headerNotificationBadge');
                const notificationPreviewList = document.getElementById('headerNotificationPreviewList');
                const messageLink = document.getElementById('headerMessageLink');
                const messageBadge = document.getElementById('headerMessageBadge');
                const toast = document.getElementById('headerLiveToast');
                const toastTitle = document.getElementById('headerLiveToastTitle');
                const toastCopy = document.getElementById('headerLiveToastCopy');
                let lastNotificationCount = {{ (int) $headerNotificationCount }};
                let toastTimeout = null;

                if (!notificationLink || !notificationPreviewList || !messageLink) {
                    return;
                }

                function formatBadge(value) {
                    return value > 9 ? '9+' : String(value);
                }

                function toggleUnreadState(element, active) {
                    element.classList.toggle('has-unread', active);
                }

                function showToast(item) {
                    if (!toast || !toastTitle || !toastCopy || !item) {
                        return;
                    }

                    toastTitle.textContent = item.label || 'New notification';
                    toastCopy.textContent = item.message || '';
                    toast.classList.add('is-visible');

                    if (toastTimeout) {
                        window.clearTimeout(toastTimeout);
                    }

                    toastTimeout = window.setTimeout(function () {
                        toast.classList.remove('is-visible');
                    }, 3200);
                }

                async function pollNotificationSummary() {
                    if (document.hidden) {
                        return;
                    }

                    try {
                        const response = await fetch('{{ route('profile.notifications.summary') }}', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Cache-Control': 'no-cache',
                            },
                            cache: 'no-store',
                        });

                        if (!response.ok) {
                            return;
                        }

                        const data = await response.json();
                        const notificationCount = Number(data.notification_count || 0);
                        const unreadMessages = Number(data.unread_messages || 0);
                        const preview = Array.isArray(data.preview) ? data.preview : [];

                        notificationLink.href = '{{ route('profile.notifications') }}';
                        messageLink.href = data.message_route || '{{ route('messages.index') }}';

                        toggleUnreadState(notificationLink, notificationCount > 0);
                        toggleUnreadState(messageLink, unreadMessages > 0);

                        if (notificationBadge) {
                            notificationBadge.textContent = formatBadge(notificationCount);
                            notificationBadge.style.display = notificationCount > 0 ? '' : 'none';
                        }

                        if (messageBadge) {
                            messageBadge.textContent = formatBadge(unreadMessages);
                            messageBadge.style.display = unreadMessages > 0 ? '' : 'none';
                        }

                        notificationPreviewList.innerHTML = preview.map(function (item) {
                            const label = String(item.label || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                            const message = String(item.message || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');

                            return '<div class="header-popover-item"><span class="header-popover-label">' + label + '</span><div class="header-popover-copy">' + message + '</div></div>';
                        }).join('');

                        if (notificationCount > lastNotificationCount && preview.length > 0) {
                            showToast(preview[0]);
                        }

                        lastNotificationCount = notificationCount;
                    } catch (error) {
                        // Ignore temporary polling failures.
                    }
                }

                window.setInterval(pollNotificationSummary, 5000);
            })();
        @endauth

        @if($callUiEnabled)
            (function () {
                const workspace = document.getElementById('globalCallWorkspace');
            const workspaceFrame = document.getElementById('globalCallWorkspaceFrame');
            const workspaceTitle = document.getElementById('globalCallWorkspaceTitle');
            const workspaceClose = document.getElementById('globalCallWorkspaceClose');
            const workspaceOpenNew = document.getElementById('globalCallWorkspaceOpenNew');
            let rawCallUrl = '';
            let activeCallId = null;

            if (!workspace || !workspaceFrame || !workspaceClose || !workspaceOpenNew || !workspaceTitle) {
                return;
            }

            function buildEmbeddedUrl(url) {
                const targetUrl = new URL(url, window.location.origin);
                targetUrl.searchParams.set('embedded', '1');

                return targetUrl.toString();
            }

            async function prepareMedia(options) {
                const details = options || {};
                const type = details.type || 'audio';
                
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error('This browser does not support camera and microphone access. Please ensure you are using HTTPS.');
                }

                // Try to check permission status first if the browser supports it
                try {
                    if (navigator.permissions && navigator.permissions.query) {
                        const micStatus = await navigator.permissions.query({ name: 'microphone' });
                        const camStatus = type === 'video' ? await navigator.permissions.query({ name: 'camera' }) : { state: 'granted' };
                        
                        if (micStatus.state === 'granted' && camStatus.state === 'granted') {
                            return true;
                        }
                    }
                } catch (e) {
                    // Ignore permission query failures and proceed to request
                }

                // Explicitly request media access to force the browser permission prompt
                try {
                    const constraints = {
                        audio: true,
                        video: type === 'video' ? { facingMode: 'user' } : false
                    };
                    const stream = await navigator.mediaDevices.getUserMedia(constraints);
                    // If we got the stream, permissions are granted. Stop the tracks immediately.
                    stream.getTracks().forEach(function(track) { track.stop(); });
                    return true;
                } catch (error) {
                    console.error('Permission denied or media error:', error);
                    throw error;
                }
            }

            function openCall(url, options) {
                const details = options || {};
                rawCallUrl = new URL(url, window.location.origin).toString();
                
                // Extract call ID from URL like /calls/123
                const match = rawCallUrl.match(/\/calls\/(\d+)/);
                activeCallId = match ? match[1] : null;

                workspaceFrame.src = buildEmbeddedUrl(rawCallUrl);
                workspaceOpenNew.href = rawCallUrl;
                workspaceTitle.textContent = details.title || 'Active call';
                workspace.classList.add('is-visible');
                workspace.setAttribute('aria-hidden', 'false');
                document.body.classList.add('call-workspace-open');
            }

            async function endActiveCallOnServer() {
                if (!activeCallId) return;

                try {
                    await fetch(`{{ url('/calls') }}/${activeCallId}/end`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin'
                    });
                } catch (error) {
                    console.error('Failed to end call on server:', error);
                } finally {
                    activeCallId = null;
                }
            }

            function closeCall() {
                // Proactively end the call session on the server
                endActiveCallOnServer();

                workspace.classList.remove('is-visible');
                workspace.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('call-workspace-open');
                rawCallUrl = '';
                window.setTimeout(function () {
                    if (!workspace.classList.contains('is-visible')) {
                        workspaceFrame.src = 'about:blank';
                        workspaceOpenNew.href = '#';
                    }
                }, 120);
            }

            workspaceClose.addEventListener('click', closeCall);
            workspace.addEventListener('click', function (event) {
                if (event.target === workspace) {
                    closeCall();
                }
            });
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && workspace.classList.contains('is-visible')) {
                    closeCall();
                }
            });
            window.addEventListener('message', function (event) {
                if (event.origin !== window.location.origin || !event.data || typeof event.data !== 'object') {
                    return;
                }

                if (event.data.type === 'pikfresh-call-close') {
                    closeCall();
                }

                if (event.data.type === 'pikfresh-call-title' && typeof event.data.title === 'string' && event.data.title.trim() !== '') {
                    workspaceTitle.textContent = event.data.title.trim();
                }
            });

            window.PikFreshCallLauncher = {
                open: openCall,
                close: closeCall,
                prepareMedia: prepareMedia,
            };
            })();

            @auth
                @if(auth()->user()->isVendor())
                (function () {
                    const incomingCallModal = document.getElementById('globalIncomingCallModal');
                    const incomingCallMessage = document.getElementById('globalIncomingCallMessage');
                    const acceptIncomingCallButton = document.getElementById('globalAcceptIncomingCallButton');
                    const dismissIncomingCallButton = document.getElementById('globalDismissIncomingCallButton');
                    let activeInviteId = null;
                    let activeInviteType = 'audio';
                    let isCheckingIncomingCall = false;
                    let ringInterval = null;
                    let audioContext = null;
                    let currentOscillator = null;
                    let currentGain = null;

                    if (!incomingCallModal || !incomingCallMessage || !acceptIncomingCallButton || !dismissIncomingCallButton) {
                        return;
                    }

                    function ensureAudioContext() {
                        if (audioContext) {
                            return audioContext;
                        }

                        const AudioContextClass = window.AudioContext || window.webkitAudioContext;

                        if (!AudioContextClass) {
                            return null;
                        }

                        audioContext = new AudioContextClass();
                        return audioContext;
                    }

                    function stopTone() {
                        if (currentOscillator) {
                            try {
                                currentOscillator.stop();
                            } catch (error) {
                                // Ignore stop errors.
                            }
                            currentOscillator.disconnect();
                            currentOscillator = null;
                        }

                        if (currentGain) {
                            currentGain.disconnect();
                            currentGain = null;
                        }
                    }

                    function playTone(frequency, duration) {
                        const context = ensureAudioContext();

                        if (!context) {
                            return;
                        }

                        if (context.state === 'suspended') {
                            context.resume().catch(function () {});
                        }

                        stopTone();

                        currentOscillator = context.createOscillator();
                        currentGain = context.createGain();
                        currentOscillator.type = 'sine';
                        currentOscillator.frequency.value = frequency;
                        currentGain.gain.value = 0.04;
                        currentOscillator.connect(currentGain);
                        currentGain.connect(context.destination);
                        currentOscillator.start();
                        window.setTimeout(stopTone, duration);
                    }

                    function startRinging() {
                        if (ringInterval) {
                            return;
                        }

                        playTone(880, 240);
                        ringInterval = window.setInterval(function () {
                            playTone(880, 240);
                            window.setTimeout(function () {
                                playTone(660, 240);
                            }, 320);
                        }, 1800);
                    }

                    function stopRinging() {
                        if (ringInterval) {
                            window.clearInterval(ringInterval);
                            ringInterval = null;
                        }

                        stopTone();
                    }

                    function closeIncomingCallModal() {
                        incomingCallModal.classList.remove('is-visible');
                        incomingCallModal.setAttribute('aria-hidden', 'true');
                        stopRinging();
                    }

                    async function checkIncomingCall() {
                        if (isCheckingIncomingCall || incomingCallModal.classList.contains('is-visible')) {
                            return;
                        }

                        isCheckingIncomingCall = true;

                        try {
                            const response = await fetch('{{ route('vendor.call.incoming', [], false) }}', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'ngrok-skip-browser-warning': 'true'
                                },
                            });

                            if (!response.ok) {
                                return;
                            }

                            const payload = await response.json();

                            if (payload.incoming && payload.invite) {
                                activeInviteId = payload.invite.id;
                                activeInviteType = payload.invite.call_type || 'audio';
                                const callTypeLabel = payload.invite.call_type === 'video' ? 'video call' : 'audio call';
                                incomingCallMessage.textContent = `${payload.invite.buyer_name} is starting an online ${callTypeLabel}.`;
                                incomingCallModal.classList.add('is-visible');
                                incomingCallModal.setAttribute('aria-hidden', 'false');
                                startRinging();
                            }
                        } catch (error) {
                            // Ignore transient polling errors.
                        } finally {
                            isCheckingIncomingCall = false;
                        }
                    }

                    acceptIncomingCallButton.addEventListener('click', async function () {
                        if (!activeInviteId) {
                            return;
                        }

                        try {
                            acceptIncomingCallButton.disabled = true;
                            acceptIncomingCallButton.textContent = activeInviteType === 'video' ? 'Opening Video...' : 'Opening Audio...';
                            if (window.PikFreshCallLauncher) {
                                await window.PikFreshCallLauncher.prepareMedia({
                                    type: activeInviteType,
                                });
                            }

                            const response = await fetch(`{{ url('/vendor/incoming-call') }}/${activeInviteId}/accept`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'ngrok-skip-browser-warning': 'true'
                                },
                                credentials: 'same-origin'
                            });

                            if (!response.ok) {
                                return;
                            }

                            const payload = await response.json();

                            if (payload.call_url) {
                                stopRinging();
                                closeIncomingCallModal();
                                if (window.PikFreshCallLauncher) {
                                    window.PikFreshCallLauncher.open(payload.call_url, {
                                        title: activeInviteType === 'video' ? 'Incoming video call' : 'Incoming audio call',
                                    });
                                } else {
                                    window.location.href = payload.call_url;
                                }
                            }
                        } catch (error) {
                            acceptIncomingCallButton.disabled = false;
                            acceptIncomingCallButton.textContent = 'Accept Call';
                            window.alert('Microphone or camera permission is required before the call can open.');
                        }
                    });

                    dismissIncomingCallButton.addEventListener('click', closeIncomingCallModal);
                    incomingCallModal.addEventListener('click', function (event) {
                        if (event.target === incomingCallModal) {
                            closeIncomingCallModal();
                        }
                    });
                    document.addEventListener('keydown', function (event) {
                        if (event.key === 'Escape' && incomingCallModal.classList.contains('is-visible')) {
                            closeIncomingCallModal();
                        }
                    });

                    checkIncomingCall();
                    window.setInterval(checkIncomingCall, 5000);
                })();
                @endif
            @endauth
        @endif
    </script>

    <script>
        (function () {
            const form = document.querySelector('.global-search-form');
            const input = form ? form.querySelector('.search-bar') : null;
            const list = document.getElementById('searchSuggestList');
            if (!form || !input || !list) {
                return;
            }

            let controller = null;
            let timer = null;

            const escapeHtml = function (value) {
                return value
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            const closeList = function () {
                list.classList.remove('is-open');
                list.innerHTML = '';
            };

            const renderItems = function (items) {
                if (!items || items.length === 0) {
                    closeList();
                    return;
                }

                const html = items.map(function (item) {
                    const safeType = escapeHtml((item.type || 'item').toString());
                    const safeTitle = escapeHtml((item.title || '').toString());
                    const safeSubtitle = escapeHtml((item.subtitle || '').toString());
                    const safeUrl = escapeHtml((item.url || '#').toString());

                    return '<a class="search-suggest-link" href="' + safeUrl + '" role="option">'
                        + '<div>'
                        + '<div class="search-suggest-title">' + safeTitle + '</div>'
                        + '<div class="search-suggest-subtitle">' + safeSubtitle + '</div>'
                        + '</div>'
                        + '<span class="search-suggest-type">' + safeType + '</span>'
                        + '</a>';
                }).join('');

                list.innerHTML = html;
                list.classList.add('is-open');
            };

            const fetchSuggestions = function (query) {
                if (controller) {
                    controller.abort();
                }

                controller = new AbortController();
                const url = '{{ route('search.suggestions') }}?q=' + encodeURIComponent(query);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    signal: controller.signal
                })
                    .then(function (response) {
                        return response.ok ? response.json() : { items: [] };
                    })
                    .then(function (data) {
                        renderItems(data.items || []);
                    })
                    .catch(function () {
                        closeList();
                    });
            };

            input.addEventListener('input', function () {
                const query = input.value.trim();
                if (timer) {
                    clearTimeout(timer);
                }

                if (query.length < 2) {
                    closeList();
                    return;
                }

                timer = setTimeout(function () {
                    fetchSuggestions(query);
                }, 180);
            });

            input.addEventListener('focus', function () {
                const query = input.value.trim();
                if (query.length >= 2 && list.children.length > 0) {
                    list.classList.add('is-open');
                }
            });

            document.addEventListener('click', function (event) {
                if (!form.contains(event.target)) {
                    closeList();
                }
            });

            form.addEventListener('submit', function () {
                closeList();
            });
        })();
    </script>

    @if(!auth()->check() || !auth()->user()->isAdmin())
        <script>
            (function () {
                const toggle = document.getElementById('supportChatToggle');
                const panel = document.getElementById('supportChatPanel');
                const closeButton = document.getElementById('supportChatClose');
                const intro = document.getElementById('supportChatIntro');
                const messagesBox = document.getElementById('supportChatMessages');
                const identityForm = document.getElementById('supportChatIdentityForm');
                const guestName = document.getElementById('supportGuestName');
                const guestEmail = document.getElementById('supportGuestEmail');
                const startButton = document.getElementById('supportChatStart');
                const form = document.getElementById('supportChatForm');
                const input = document.getElementById('supportChatInput');
                const sendButton = document.getElementById('supportChatSend');
                const bootstrapUrl = '{{ route('support.chat') }}';
                const startUrl = '{{ route('support.chat.start') }}';
                const sendUrl = '{{ route('support.chat.store') }}';

                if (!toggle || !panel || !closeButton || !messagesBox || !identityForm || !form || !input || !sendButton) {
                    return;
                }

                let currentSignature = '';
                let pollTimer = null;
                let hoverCloseTimer = null;
                let chatReady = false;
                let isStartingChat = false;
                let isHydrating = false;
                let lastStartTimestamp = 0;

                function isDesktopSupportMode() {
                    return window.matchMedia('(min-width: 1024px)').matches;
                }

                function escapeHtml(value) {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;');
                }

                function setChatReady(ready) {
                    // Prevent resetting the state if we are currently starting a chat 
                    // or if we started one very recently (within 5 seconds) to avoid race conditions on mobile
                    const recentlyStarted = (Date.now() - lastStartTimestamp) < 5000;
                    if (!ready && (isStartingChat || recentlyStarted || chatReady)) {
                        // If we think we're ready, don't let a "not ready" signal from hydration 
                        // break the UI unless we're absolutely sure (e.g. manual close or long timeout)
                        return;
                    }

                    chatReady = ready;
                    identityForm.style.display = ready ? 'none' : 'grid';
                    form.style.display = ready ? 'block' : 'none';

                    if (ready) {
                        intro.querySelector('p').textContent = 'You are now connected to live support.';
                    } else {
                        intro.querySelector('p').textContent = 'Start a live support conversation. Guests can chat by entering a name and email first.';
                    }
                }

                function scrollSupportToBottom() {
                    requestAnimationFrame(function () {
                        messagesBox.scrollTop = messagesBox.scrollHeight;
                    });
                }

                function renderMessages(messages) {
                    if (!Array.isArray(messages) || !messages.length) {
                        messagesBox.innerHTML = '<div class="support-chat-empty">No messages yet. Start the conversation below.</div>';
                        return;
                    }

                    messagesBox.innerHTML = messages.map(function (message) {
                        return '<div class="support-chat-row ' + (message.is_sent ? 'is-sent' : 'is-received') + '">' +
                            '<div class="support-chat-bubble ' + (message.is_sent ? 'is-sent' : 'is-received') + '">' +
                            '<span class="support-chat-author">' + escapeHtml(message.author_name) + '</span>' +
                            '<div>' + escapeHtml(message.message) + '</div>' +
                            '<span class="support-chat-time">' + escapeHtml(message.time) + '</span>' +
                            '</div>' +
                            '</div>';
                    }).join('');

                    scrollSupportToBottom();
                }

                async function hydrateChat(forceRender) {
                    if (isHydrating) return;
                    isHydrating = true;

                    try {
                        const response = await fetch(bootstrapUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'ngrok-skip-browser-warning': 'true'
                            },
                            cache: 'no-store',
                            credentials: 'same-origin'
                        });

                        if (!response.ok) {
                            throw new Error('Unable to load support chat.');
                        }

                        const data = await response.json();
                        const nextSignature = data.thread_signature || '';

                        if (guestName && data.visitor_name && !guestName.value) {
                            guestName.value = data.visitor_name;
                        }

                        if (guestEmail && data.visitor_email && !guestEmail.value) {
                            guestEmail.value = data.visitor_email;
                        }

                        // Only update readiness if we aren't in the middle of starting a chat
                        // and the server actually confirms a thread exists. 
                        // If it says NO thread but we think we HAVE one, we trust the frontend state for now
                        // to prevent flickering/resets on mobile.
                        if (data.thread_exists) {
                            setChatReady(true);
                        } else if (!isStartingChat && (Date.now() - lastStartTimestamp) > 10000) {
                            // Only reset to identity form if we haven't tried to start a chat in the last 10s
                            setChatReady(false);
                        }

                        if (forceRender || nextSignature !== currentSignature) {
                            renderMessages(data.messages || []);
                            currentSignature = nextSignature;
                        }
                    } catch (error) {
                        console.error('Support hydration error:', error);
                    } finally {
                        isHydrating = false;
                    }
                }

                async function startChat(event) {
                    if (event) event.preventDefault();
                    if (isStartingChat) return;

                    const name = guestName.value.trim();
                    const email = guestEmail.value.trim();

                    if (!name || !email) {
                        alert('Please enter both your name and email to start the chat.');
                        return;
                    }

                    isStartingChat = true;
                    lastStartTimestamp = Date.now();
                    startButton.disabled = true;
                    const originalText = startButton.textContent;
                    startButton.textContent = 'Starting...';

                    try {
                        const response = await fetch(startUrl, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'ngrok-skip-browser-warning': 'true'
                            },
                            body: JSON.stringify({
                                name: name,
                                email: email,
                            }),
                            credentials: 'same-origin'
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            if (data.errors) {
                                const errorMessages = Object.values(data.errors).flat().join('\n');
                                alert('Validation Error:\n' + errorMessages);
                            } else {
                                throw new Error(data.message || 'Unable to start support chat.');
                            }
                            return;
                        }

                        setChatReady(true);
                        currentSignature = data.thread_signature || '';
                        renderMessages(data.messages || []);
                        
                        // Small delay for focus to work better on mobile
                        setTimeout(() => {
                            if (input) input.focus();
                        }, 150);
                        
                    } catch (error) {
                        console.error('Support chat error:', error);
                        alert(error.message || 'An unexpected error occurred. Please try again.');
                    } finally {
                        isStartingChat = false;
                        startButton.disabled = false;
                        startButton.textContent = originalText;
                    }
                }

                async function sendMessage(event) {
                    if (event) event.preventDefault();

                    const message = input.value.trim();
                    if (!message || !chatReady) {
                        return;
                    }

                    sendButton.disabled = true;

                    try {
                        const response = await fetch(sendUrl, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'ngrok-skip-browser-warning': 'true'
                            },
                            body: JSON.stringify({ message: message }),
                            credentials: 'same-origin'
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Unable to send support message.');
                        }

                        input.value = '';
                        currentSignature = data.thread_signature || '';
                        renderMessages(data.messages || []);
                    } catch (error) {
                        console.error('Support chat error:', error);
                        alert(error.message || 'Failed to send message. Please try again.');
                    } finally {
                        sendButton.disabled = false;
                    }
                }

                function startPolling() {
                    stopPolling();
                    pollTimer = window.setInterval(function () {
                        if (!panel.classList.contains('is-open') || document.hidden || !chatReady) {
                            return;
                        }

                        hydrateChat(false).catch(function () {});
                    }, 2500);
                }

                function stopPolling() {
                    if (pollTimer) {
                        window.clearInterval(pollTimer);
                        pollTimer = null;
                    }
                }

                function openPanel(shouldFocus) {
                    panel.classList.add('is-open');
                    panel.setAttribute('aria-hidden', 'false');
                    hydrateChat(true).then(function () {
                        if (shouldFocus && chatReady) {
                            input.focus();
                        } else if (shouldFocus && guestName) {
                            guestName.focus();
                        }
                    }).catch(function () {});
                    startPolling();
                }

                function closePanel() {
                    panel.classList.remove('is-open');
                    panel.setAttribute('aria-hidden', 'true');
                    stopPolling();
                }

                toggle.addEventListener('click', function () {
                    if (panel.classList.contains('is-open')) {
                        closePanel();
                        return;
                    }

                    openPanel(true);
                });

                closeButton.addEventListener('click', closePanel);
                identityForm.addEventListener('submit', startChat);
                form.addEventListener('submit', sendMessage);
            })();
        </script>
    @endif

    @yield('scripts')
</body>
</html>

