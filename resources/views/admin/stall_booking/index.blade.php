@extends('layouts.admin_cms')

@section('header_title', 'Stall Booking & Merchandise CMS')

@section('content')
<style>
    .stall-admin-wrap {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .admin-card-section {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .form-label {
        display: block;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
        font-size: 0.92rem;
    }

    .form-input {
        width: 100%;
        padding: 11px 15px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.95rem;
        color: #0f172a;
        font-family: inherit;
        transition: border-color 0.2s;
    }

    .form-input:focus {
        border-color: #00A896;
        outline: none;
    }

    .item-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    }

    .item-card:hover {
        border-color: #cbd5e1;
        background: #ffffff;
    }

    .section-title {
        margin: 0 0 18px 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 12px;
    }

    .btn-add-item {
        background: #f1f5f9;
        color: #0f172a;
        border: 1.5px dashed #94a3b8;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-add-item:hover {
        background: #e6f7f5;
        color: #00A896;
        border-color: #00A896;
    }
</style>

@php
    $bannerTitle = $settings['stall_banner_title'] ?? 'STALL BOOKING AND MERCHANDISE';
    $bannerSubtitle = $settings['stall_banner_subtitle'] ?? 'Partnership Avenues, Tariff Details & Sponsorship Opportunities | Global One Health Confluence 2026';
    
    $brochureList = !empty($brochures) ? $brochures : [
        ['title' => 'Global One Health Confluence 2026 Brochure', 'image' => 'storage/images/stall_booking/1794506923_Illuminated_INNOVEITY_Corporate_Lobby_Sign.png'],
    ];

    $sponsorList = !empty($sponsors) ? $sponsors : [
        ['name' => 'Anderson Diagnostics & Labs', 'logo' => 'images/sponsors/anderson_logo.svg', 'link' => 'https://andersondiagnostics.com'],
        ['name' => 'Sponsorship Partner', 'logo' => 'images/sponsors/sponsor_logo_1.jpg', 'link' => '#'],
    ];

    $sponsorsBadge = $settings['stall_sponsors_badge'] ?? 'Proudly Supported By';
    $sponsorsTitle = $settings['stall_sponsors_title'] ?? 'Our Sponsors & Industry Partners';
    $ctaText = $settings['stall_cta_text'] ?? 'Interested in partnering or booking an exhibition stall?';
    $ctaBtnLabel = $settings['stall_cta_btn_label'] ?? 'Contact Sponsorship Committee';
    $ctaBtnUrl = $settings['stall_cta_btn_url'] ?? '/contact';
@endphp

<div class="stall-admin-wrap">

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="font-size: 1.7rem; font-weight: 800; color: #0a192f; margin: 0 0 4px 0;">
                <i class="fa-solid fa-store" style="color: #00A896; margin-right: 8px;"></i> Stall Booking & Merchandise CMS
            </h2>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Customize the page banner, brochures, sponsor logos, and partnership call-to-action in real time.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="/stall-booking-and-merchandise" target="_blank" style="background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; padding: 11px 20px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> View Live Page
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.3rem;"></i> {{ session('success') }}
        </div>
    @endif

    <!-- 1. LIVE VISUAL PREVIEW -->
    <div style="background: #f8fafc; border-radius: 20px; border: 2px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 35px rgba(15, 23, 42, 0.06); position: relative;">
        <div style="position: absolute; top: 15px; right: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #00A896; background: rgba(255, 255, 255, 0.9); padding: 5px 14px; border-radius: 20px; border: 1px solid #b2dfdb; z-index: 10;">
            <i class="fa-solid fa-eye"></i> Live Visual Preview
        </div>

        <!-- Banner Preview -->
        <div style="background: linear-gradient(135deg, #0a192f 0%, #0f172a 50%, #112240 100%); padding: 50px 20px 45px; text-align: center; color: #ffffff;">
            <h1 style="text-transform: uppercase; font-size: 1.8rem; font-weight: 800; letter-spacing: 1px; color: #ffffff; margin: 0 0 8px 0;">
                {{ $bannerTitle }}
            </h1>
            <p style="color: #cbd5e1; font-size: 0.95rem; margin: 0; font-weight: 400;">
                {{ $bannerSubtitle }}
            </p>
        </div>

        <!-- Content Preview Container -->
        <div style="padding: 35px 25px; max-width: 800px; margin: 0 auto;">
            
            <!-- Brochures Mini Preview -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 30px;">
                @foreach($brochureList as $idx => $b)
                    @php
                        $bRaw = $b['image'] ?? 'images/stall_booking/stall_brochure_1.png';
                        $cleanB = ltrim($bRaw, '/');
                        if (str_starts_with($cleanB, 'storage/')) {
                            $bImg = asset($cleanB);
                        } elseif (preg_match('/^\d+_/', basename($cleanB)) || file_exists(storage_path('app/public/' . $cleanB))) {
                            $bImg = asset('storage/' . $cleanB);
                        } else {
                            $bImg = asset($cleanB);
                        }
                    @endphp
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); text-align: center;">
                        <a href="{{ $bImg }}" target="_blank" title="Click to view full resolution HD image" style="display: block; text-decoration: none;">
                            <img src="{{ $bImg }}" alt="{{ $b['title'] }}" onerror="if (!this.dataset.triedStorage && this.src.indexOf('/storage/') === -1) { this.dataset.triedStorage = '1'; this.src = this.src.replace('/images/', '/storage/images/'); }" style="width: 100%; max-height: 200px; object-fit: contain; background: #f8fafc; border-radius: 6px; margin-bottom: 8px; image-rendering: -webkit-optimize-contrast; border: 1px solid #e2e8f0; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        </a>
                        <div style="font-size: 0.8rem; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $b['title'] }}</div>
                        <div style="font-size: 0.72rem; color: #00A896; margin-top: 4px; font-weight: 600;">Brochure #{{ $idx + 1 }} &bull; <a href="{{ $bImg }}" target="_blank" style="color: #0284c7; text-decoration: underline;">Full HD ↗</a></div>
                    </div>
                @endforeach
            </div>

            <!-- Sponsors Mini Preview -->
            <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 25px 20px; text-align: center;">
                <span style="background: #e6f7f5; color: #00A896; font-size: 0.75rem; font-weight: 800; padding: 3px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; display: inline-block; margin-bottom: 6px;">
                    {{ $sponsorsBadge }}
                </span>
                <h4 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 18px 0;">
                    {{ $sponsorsTitle }}
                </h4>

                <div style="display: flex; justify-content: center; align-items: center; gap: 20px; flex-wrap: wrap; margin-bottom: 18px;">
                    @foreach($sponsorList as $s)
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 20px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset($s['logo']) }}" alt="{{ $s['name'] }}" style="max-height: 40px; max-width: 120px; object-fit: contain;">
                        </div>
                    @endforeach
                </div>

                <div style="border-top: 1px solid #f1f5f9; padding-top: 12px;">
                    <p style="color: #64748b; font-size: 0.85rem; margin: 0 0 10px 0;">{{ $ctaText }}</p>
                    <span style="background: linear-gradient(135deg, #00A896, #028090); color: #fff; padding: 7px 18px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-handshake"></i> {{ $ctaBtnLabel }}
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- 2. EDIT FORM -->
    <form method="POST" action="{{ route('admin.stall_booking.update') }}" enctype="multipart/form-data">
        @csrf

        <!-- Section 0: Home Screen Conference Brochures Showcase (Dynamic) -->
        <div class="admin-card-section" id="home-brochures-section" style="border: 2px solid #00A896; background: #ffffff;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
                <div class="section-title" style="margin: 0; padding: 0; border: none; font-size: 1.35rem;">
                    <i class="fa-solid fa-book-open" style="color: #00A896;"></i> Home Screen: Conference Brochures Showcase
                </div>
                <span style="background: #e6f7f5; color: #009688; border: 1px solid #b2dfdb; padding: 5px 16px; border-radius: 20px; font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.8px;">
                    <i class="fa-solid fa-house"></i> Displayed on Home Screen
                </span>
            </div>
            
            <p style="color: #64748b; font-size: 0.92rem; margin-top: -6px; margin-bottom: 22px;">
                Control the brochure image cards, heading, and bottom explore button shown directly on the Home page (between About and Deadlines).
            </p>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label class="form-label">Section Heading (HTML Supported)</label>
                    <input type="text" name="stall_home_title" value="{{ $settings['stall_home_title'] ?? 'Conference <span style=&quot;color: #009688;&quot;>Brochures</span>' }}" class="form-input" required>
                    <small style="color: #64748b; font-size: 0.78rem;">Default: <code>Conference &lt;span style="color: #009688;"&gt;Brochures&lt;/span&gt;</code></small>
                </div>
                <div>
                    <label class="form-label">Section Visibility on Home</label>
                    <select name="stall_home_show" class="form-input">
                        <option value="1" {{ ($settings['stall_home_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible (Show on Home Page)</option>
                        <option value="0" {{ ($settings['stall_home_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden (Hide from Home Page)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1.5fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div>
                    <label class="form-label">Bottom Button Text</label>
                    <input type="text" name="stall_home_btn_label" value="{{ $settings['stall_home_btn_label'] ?? 'Explore Full Stall Booking & Merchandise Details &rarr;' }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Bottom Button Target URL</label>
                    <input type="text" name="stall_home_btn_url" value="{{ $settings['stall_home_btn_url'] ?? '/page/stall-booking-details' }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Show Button</label>
                    <select name="stall_home_btn_show" class="form-input">
                        <option value="1" {{ ($settings['stall_home_btn_show'] ?? '1') == '1' ? 'selected' : '' }}>Show Button</option>
                        <option value="0" {{ ($settings['stall_home_btn_show'] ?? '1') == '0' ? 'selected' : '' }}>Hide Button</option>
                    </select>
                </div>
            </div>

            <div style="border-top: 1.5px dashed #e2e8f0; padding-top: 20px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <h4 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">
                        <i class="fa-solid fa-images" style="color: #00A896;"></i> Home Screen Brochure Image Cards
                    </h4>
                    <span style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Each card links directly to its target page</span>
                </div>
                <p style="color: #64748b; font-size: 0.88rem; margin: 0 0 18px 0;">
                    Upload high-resolution images for each brochure card on the Home page. You can add more cards or remove any as needed.
                </p>
            </div>

            <div id="home-brochures-container">
                @foreach($homeBrochures as $idx => $hb)
                    <div class="item-card home-brochure-row">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                                <i class="fa-solid fa-file-image" style="color: #00A896;"></i> Home Brochure Card #<span class="home-brochure-number">{{ $idx + 1 }}</span>
                            </span>
                            <button type="button" onclick="this.closest('.home-brochure-row').remove(); updateHomeBrochureNumbers();" style="background: #fee2e2; color: #dc2626; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                                <i class="fa-solid fa-trash"></i> Remove Card
                            </button>
                        </div>

                        <div style="display: grid; grid-template-columns: 1.5fr 1.5fr 1fr 2fr; gap: 15px; align-items: center;">
                            <div>
                                <label class="form-label">Card Title / Alt Text</label>
                                <input type="text" name="home_brochure_titles[]" value="{{ $hb['title'] }}" class="form-input" required>
                            </div>

                            <div>
                                <label class="form-label">Target Link URL</label>
                                <input type="text" name="home_brochure_urls[]" value="{{ $hb['url'] ?? '/page/stall-booking-details' }}" class="form-input">
                            </div>

                            <div>
                                <label class="form-label">Current Preview (HD)</label>
                                @php
                                    $hbRaw = $hb['image'] ?? 'images/stall_booking/stall_brochure_1.png';
                                    $cleanHb = ltrim($hbRaw, '/');
                                    if (str_starts_with($cleanHb, 'storage/')) {
                                        $hbImg = asset($cleanHb);
                                    } elseif (preg_match('/^\d+_/', basename($cleanHb)) || file_exists(storage_path('app/public/' . $cleanHb))) {
                                        $hbImg = asset('storage/' . $cleanHb);
                                    } else {
                                        $hbImg = asset($cleanHb);
                                    }
                                @endphp
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <a href="{{ $hbImg }}" target="_blank" title="Click to view full resolution HD image" style="display: inline-block;">
                                        <img src="{{ $hbImg }}" alt="{{ $hb['title'] }}" id="home_brochure_preview_img_{{ $idx }}" onerror="if (!this.dataset.triedStorage && this.src.indexOf('/storage/') === -1) { this.dataset.triedStorage = '1'; this.src = this.src.replace('/images/', '/storage/images/'); }" style="height: 70px; width: auto; max-width: 100px; object-fit: contain; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #fff; padding: 2px; image-rendering: -webkit-optimize-contrast;">
                                    </a>
                                    <input type="hidden" name="home_brochure_existing_images[]" value="{{ $hb['image'] }}">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Upload New / Replace Image</label>
                                <input type="file" name="home_brochure_files[]" class="form-input" accept="image/*" onchange="previewBrochureLocal(this, 'home_brochure_preview_img_{{ $idx }}')">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn-add-item" onclick="addHomeBrochureRow()" style="margin-top: 10px;">
                <i class="fa-solid fa-plus"></i> Add Another Home Brochure Card
            </button>
        </div>

        <!-- Section 1: Hero Banner Settings -->
        <div class="admin-card-section">
            <div class="section-title">
                <i class="fa-solid fa-heading" style="color: #00A896;"></i> 1. Hero Banner Settings
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label class="form-label">Banner Title</label>
                    <input type="text" name="stall_banner_title" value="{{ $bannerTitle }}" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Banner Subtitle</label>
                    <input type="text" name="stall_banner_subtitle" value="{{ $bannerSubtitle }}" class="form-input" required>
                </div>
            </div>
        </div>

        <!-- Section 2: Brochures Management -->
        <div class="admin-card-section">
            <div class="section-title">
                <i class="fa-solid fa-file-pdf" style="color: #00A896;"></i> 2. Dynamic Brochures List
            </div>
            <p style="color: #64748b; font-size: 0.9rem; margin-top: -10px; margin-bottom: 20px;">
                Manage each brochure image displayed on the page. Users can view them at full resolution or download them.
            </p>

            <div id="brochures-container">
                @foreach($brochureList as $index => $brochure)
                    <div class="item-card brochure-row">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                                <i class="fa-solid fa-image" style="color: #00A896;"></i> Brochure #<span class="brochure-number">{{ $index + 1 }}</span>
                            </span>
                            <button type="button" onclick="this.closest('.brochure-row').remove(); updateBrochureNumbers();" style="background: #fee2e2; color: #dc2626; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                                <i class="fa-solid fa-trash"></i> Remove
                            </button>
                        </div>

                        <div style="display: grid; grid-template-columns: 2fr 1fr 2fr; gap: 15px; align-items: center;">
                            <div>
                                <label class="form-label">Brochure Title / Description</label>
                                <input type="text" name="brochure_titles[]" value="{{ $brochure['title'] }}" class="form-input" required>
                            </div>

                            <div>
                                <label class="form-label">Current Preview (HD)</label>
                                @php
                                    $bRaw2 = $brochure['image'] ?? 'images/stall_booking/stall_brochure_1.png';
                                    $cleanB2 = ltrim($bRaw2, '/');
                                    if (str_starts_with($cleanB2, 'storage/')) {
                                        $bImg2 = asset($cleanB2);
                                    } elseif (preg_match('/^\d+_/', basename($cleanB2)) || file_exists(storage_path('app/public/' . $cleanB2))) {
                                        $bImg2 = asset('storage/' . $cleanB2);
                                    } else {
                                        $bImg2 = asset($cleanB2);
                                    }
                                @endphp
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <a href="{{ $bImg2 }}" target="_blank" title="Click to view full resolution HD image" style="display: inline-block;">
                                        <img src="{{ $bImg2 }}" alt="{{ $brochure['title'] }}" id="brochure_preview_img_{{ $index }}" onerror="if (!this.dataset.triedStorage && this.src.indexOf('/storage/') === -1) { this.dataset.triedStorage = '1'; this.src = this.src.replace('/images/', '/storage/images/'); }" style="height: 75px; width: auto; max-width: 120px; object-fit: contain; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #f8fafc; padding: 2px; image-rendering: -webkit-optimize-contrast;">
                                    </a>
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        <a href="{{ $bImg2 }}" target="_blank" style="font-size: 0.8rem; font-weight: 700; color: #0284c7; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fa-solid fa-up-right-from-square"></i> View Full HD
                                        </a>
                                        <span style="font-size: 0.72rem; color: #64748b;">(Click to view high-res)</span>
                                    </div>
                                    <input type="hidden" name="brochure_existing_images[]" value="{{ $brochure['image'] }}">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Upload New / Replace Image</label>
                                <input type="file" name="brochure_files[]" class="form-input" accept="image/*" onchange="previewBrochureLocal(this, 'brochure_preview_img_{{ $index }}')">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn-add-item" onclick="addBrochureRow()">
                <i class="fa-solid fa-plus"></i> Add Another Brochure
            </button>
        </div>

        <!-- Section 3: Sponsors & Industry Partners -->
        <div class="admin-card-section">
            <div class="section-title">
                <i class="fa-solid fa-handshake" style="color: #00A896;"></i> 3. Sponsors & Industry Partners
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 22px;">
                <div>
                    <label class="form-label">Sponsors Top Badge</label>
                    <input type="text" name="stall_sponsors_badge" value="{{ $sponsorsBadge }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Sponsors Section Title</label>
                    <input type="text" name="stall_sponsors_title" value="{{ $sponsorsTitle }}" class="form-input">
                </div>
            </div>

            <div id="sponsors-container">
                @foreach($sponsorList as $index => $sponsor)
                    <div class="item-card sponsor-row">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                                <i class="fa-solid fa-building" style="color: #00A896;"></i> Sponsor #<span class="sponsor-number">{{ $index + 1 }}</span>
                            </span>
                            <button type="button" onclick="this.closest('.sponsor-row').remove(); updateSponsorNumbers();" style="background: #fee2e2; color: #dc2626; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                                <i class="fa-solid fa-trash"></i> Remove
                            </button>
                        </div>

                        <div style="display: grid; grid-template-columns: 1.5fr 1.5fr 1fr 2fr; gap: 15px; align-items: center;">
                            <div>
                                <label class="form-label">Sponsor / Company Name</label>
                                <input type="text" name="sponsor_names[]" value="{{ $sponsor['name'] }}" class="form-input" required>
                            </div>

                            <div>
                                <label class="form-label">Website / Target URL</label>
                                <input type="text" name="sponsor_links[]" value="{{ $sponsor['link'] ?? '#' }}" class="form-input">
                            </div>

                            <div>
                                <label class="form-label">Current Logo</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <img src="{{ asset($sponsor['logo']) }}" alt="{{ $sponsor['name'] }}" style="height: 45px; max-width: 80px; object-fit: contain; border-radius: 6px; border: 1px solid #cbd5e1; padding: 3px; background: #fff;">
                                    <input type="hidden" name="sponsor_existing_logos[]" value="{{ $sponsor['logo'] }}">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Upload New / Replace Logo</label>
                                <input type="file" name="sponsor_files[]" class="form-input" accept="image/*">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn-add-item" onclick="addSponsorRow()">
                <i class="fa-solid fa-plus"></i> Add Another Sponsor
            </button>
        </div>

        <!-- Section 4: CTA Settings -->
        <div class="admin-card-section">
            <div class="section-title">
                <i class="fa-solid fa-bullhorn" style="color: #00A896;"></i> 4. Bottom Call-To-Action (CTA) Settings
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
                <div>
                    <label class="form-label">CTA Prompt Text</label>
                    <input type="text" name="stall_cta_text" value="{{ $ctaText }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Button Label</label>
                    <input type="text" name="stall_cta_btn_label" value="{{ $ctaBtnLabel }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Button Destination URL</label>
                    <input type="text" name="stall_cta_btn_url" value="{{ $ctaBtnUrl }}" class="form-input">
                </div>
            </div>
        </div>

        <!-- Save Button Bar -->
        <div style="position: sticky; bottom: 20px; background: #ffffff; padding: 18px 25px; border-radius: 14px; border: 1.5px solid #cbd5e1; box-shadow: 0 10px 30px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; z-index: 50;">
            <span style="color: #64748b; font-size: 0.95rem; font-weight: 600;">
                <i class="fa-solid fa-circle-info" style="color: #00A896;"></i> Remember to save your changes after uploading or updating rows.
            </span>
            <button type="submit" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 13px 36px; border-radius: 10px; font-weight: 800; font-size: 1.05rem; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 168, 150, 0.35); display: inline-flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-floppy-disk"></i> Save Stall Booking Settings
            </button>
        </div>

    </form>

</div>

<script>
    function updateBrochureNumbers() {
        document.querySelectorAll('.brochure-row').forEach((row, idx) => {
            const numEl = row.querySelector('.brochure-number');
            if (numEl) numEl.textContent = idx + 1;
        });
    }

    function previewBrochureLocal(input, targetId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(targetId);
                if (img) {
                    img.src = e.target.result;
                    if (img.parentElement && img.parentElement.tagName === 'A') {
                        img.parentElement.href = e.target.result;
                    }
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addBrochureRow() {
        const container = document.getElementById('brochures-container');
        const count = container.querySelectorAll('.brochure-row').length + 1;
        const newImgId = 'new_brochure_img_' + Date.now();
        const row = document.createElement('div');
        row.className = 'item-card brochure-row';
        row.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                    <i class="fa-solid fa-image" style="color: #00A896;"></i> Brochure #<span class="brochure-number">${count}</span> (New)
                </span>
                <button type="button" onclick="this.closest('.brochure-row').remove(); updateBrochureNumbers();" style="background: #fee2e2; color: #dc2626; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                    <i class="fa-solid fa-trash"></i> Remove
                </button>
            </div>
            <div style="display: grid; grid-template-columns: 2fr 1fr 2fr; gap: 15px; align-items: center;">
                <div>
                    <label class="form-label">Brochure Title / Description</label>
                    <input type="text" name="brochure_titles[]" placeholder="e.g. Partnership Avenues Brochure" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Instant HD Preview</label>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img id="${newImgId}" src="{{ asset('images/stall_booking/stall_brochure_1.png') }}" alt="Preview" style="height: 75px; width: auto; max-width: 120px; object-fit: contain; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #f8fafc; padding: 2px; image-rendering: -webkit-optimize-contrast;">
                        <input type="hidden" name="brochure_existing_images[]" value="images/stall_booking/stall_brochure_1.png">
                    </div>
                </div>
                <div>
                    <label class="form-label">Upload Brochure Image</label>
                    <input type="file" name="brochure_files[]" class="form-input" accept="image/*" required onchange="previewBrochureLocal(this, '${newImgId}')">
                </div>
            </div>
        `;
        container.appendChild(row);
        updateBrochureNumbers();
    }

    function updateSponsorNumbers() {
        document.querySelectorAll('.sponsor-row').forEach((row, idx) => {
            const numEl = row.querySelector('.sponsor-number');
            if (numEl) numEl.textContent = idx + 1;
        });
    }

    function addSponsorRow() {
        const container = document.getElementById('sponsors-container');
        const count = container.querySelectorAll('.sponsor-row').length + 1;
        const row = document.createElement('div');
        row.className = 'item-card sponsor-row';
        row.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                    <i class="fa-solid fa-building" style="color: #00A896;"></i> Sponsor #<span class="sponsor-number">${count}</span> (New)
                </span>
                <button type="button" onclick="this.closest('.sponsor-row').remove(); updateSponsorNumbers();" style="background: #fee2e2; color: #dc2626; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                    <i class="fa-solid fa-trash"></i> Remove
                </button>
            </div>
            <div style="display: grid; grid-template-columns: 1.5fr 1.5fr 1fr 2fr; gap: 15px; align-items: center;">
                <div>
                    <label class="form-label">Sponsor / Company Name</label>
                    <input type="text" name="sponsor_names[]" placeholder="e.g. Anderson Diagnostics" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Website / Target URL</label>
                    <input type="text" name="sponsor_links[]" placeholder="https://..." value="#" class="form-input">
                </div>
                <div>
                    <label class="form-label">Default Logo</label>
                    <span style="color: #64748b; font-size: 0.85rem;">New Upload</span>
                    <input type="hidden" name="sponsor_existing_logos[]" value="images/sponsors/anderson_logo.svg">
                </div>
                <div>
                    <label class="form-label">Upload Sponsor Logo</label>
                    <input type="file" name="sponsor_files[]" class="form-input" accept="image/*" required>
                </div>
            </div>
        `;
        container.appendChild(row);
        updateSponsorNumbers();
    }

    function updateHomeBrochureNumbers() {
        document.querySelectorAll('.home-brochure-row').forEach((row, idx) => {
            const numEl = row.querySelector('.home-brochure-number');
            if (numEl) numEl.textContent = idx + 1;
        });
    }

    function addHomeBrochureRow() {
        const container = document.getElementById('home-brochures-container');
        const count = container.querySelectorAll('.home-brochure-row').length + 1;
        const newImgId = 'new_home_brochure_img_' + Date.now();
        const row = document.createElement('div');
        row.className = 'item-card home-brochure-row';
        row.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                    <i class="fa-solid fa-file-image" style="color: #00A896;"></i> Home Brochure Card #<span class="home-brochure-number">${count}</span> (New)
                </span>
                <button type="button" onclick="this.closest('.home-brochure-row').remove(); updateHomeBrochureNumbers();" style="background: #fee2e2; color: #dc2626; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                    <i class="fa-solid fa-trash"></i> Remove Card
                </button>
            </div>
            <div style="display: grid; grid-template-columns: 1.5fr 1.5fr 1fr 2fr; gap: 15px; align-items: center;">
                <div>
                    <label class="form-label">Card Title / Alt Text</label>
                    <input type="text" name="home_brochure_titles[]" placeholder="e.g. Conference Brochure" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Target Link URL</label>
                    <input type="text" name="home_brochure_urls[]" value="/page/stall-booking-details" class="form-input">
                </div>
                <div>
                    <label class="form-label">Instant HD Preview</label>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img id="${newImgId}" src="{{ asset('images/stall_booking/stall_brochure_1.png') }}" alt="Preview" style="height: 70px; width: auto; max-width: 100px; object-fit: contain; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #fff; padding: 2px;">
                        <input type="hidden" name="home_brochure_existing_images[]" value="images/stall_booking/stall_brochure_1.png">
                    </div>
                </div>
                <div>
                    <label class="form-label">Upload New Image</label>
                    <input type="file" name="home_brochure_files[]" class="form-input" accept="image/*" required onchange="previewBrochureLocal(this, '${newImgId}')">
                </div>
            </div>
        `;
        container.appendChild(row);
        updateHomeBrochureNumbers();
    }
</script>
@endsection
