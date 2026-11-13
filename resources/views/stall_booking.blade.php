@extends('layouts.app')

@section('title', 'Stall Booking and Merchandise | Global One Health Confluence 2026')

@section('content')

@include('sections.topbar')
@include('sections.navbar')

@php
    $bannerTitle = $settings['stall_banner_title'] ?? 'STALL BOOKING AND MERCHANDISE';
    $bannerSubtitle = $settings['stall_banner_subtitle'] ?? 'Partnership Avenues, Tariff Details & Sponsorship Opportunities | Global One Health Confluence 2026';
    
    // Default brochures if none configured
    $brochureList = !empty($brochures) ? $brochures : [];

    // Default sponsors if none configured
    $sponsorList = !empty($sponsors) ? $sponsors : [
        ['name' => 'Anderson Diagnostics & Labs', 'logo' => 'images/sponsors/anderson_logo.svg', 'link' => 'https://andersondiagnostics.com'],
        ['name' => 'Sponsorship Partner', 'logo' => 'images/sponsors/sponsor_logo_1.jpg', 'link' => '#'],
    ];

    $sponsorsBadge = $settings['stall_sponsors_badge'] ?? 'Proudly Supported By';
    $sponsorsTitle = $settings['stall_sponsors_title'] ?? 'Our Sponsors & Industry Partners';
    $ctaText = $settings['stall_cta_text'] ?? 'Interested in partnering or booking an exhibition stall?';
    $ctaBtnLabel = $settings['stall_cta_btn_label'] ?? 'Contact Sponsorship Committee';
    $ctaBtnUrl = $settings['stall_cta_btn_url'] ?? route('contact');
@endphp

<!-- Page Banner -->
<div class="page-banner" style="background: linear-gradient(135deg, #0a192f 0%, #0f172a 50%, #112240 100%); padding: 65px 20px 60px; text-align: center; color: #fff; position: relative;">
    <div class="page-banner-content" style="max-width: 900px; margin: 0 auto; position: relative; z-index: 5;">
        <h1 style="text-transform: uppercase; font-size: 2.2rem; font-weight: 800; letter-spacing: 1.5px; color: #ffffff !important; margin: 0 0 10px 0; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
            {{ $bannerTitle }}
        </h1>
        @if(!empty($bannerSubtitle))
            <p style="color: #cbd5e1; font-size: 1.05rem; margin: 0; font-weight: 400;">
                {{ $bannerSubtitle }}
            </p>
        @endif
    </div>
</div>

<style>
    .stall-booking-container {
        padding: 50px 20px 70px;
        max-width: 1000px;
        margin: 0 auto;
        font-family: 'Poppins', sans-serif;
    }

    .brochure-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        padding: 20px;
        margin-bottom: 40px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .brochure-card:hover {
        box-shadow: 0 15px 40px rgba(0, 168, 150, 0.12);
    }

    .brochure-img {
        width: 100%;
        height: auto;
        border-radius: 12px;
        display: block;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        image-rendering: -webkit-optimize-contrast;
        image-rendering: high-quality;
        cursor: zoom-in;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .brochure-img:hover {
        transform: scale(1.008);
        box-shadow: 0 8px 25px rgba(0, 168, 150, 0.15);
    }

    .brochure-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin-top: 15px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }

    .btn-view-full {
        background: #f8fafc;
        color: #0f172a;
        border: 1px solid #cbd5e1;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-view-full:hover {
        background: #00A896;
        color: #ffffff;
        border-color: #00A896;
    }

    .sponsor-card-box {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 240px;
        height: 110px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .sponsor-card-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 168, 150, 0.12);
        border-color: #00A896;
    }
</style>

<div class="stall-booking-container">
    
    <!-- Dynamic Brochures List -->
    @forelse($brochureList as $index => $brochure)
        @php
            $rawImg = $brochure['image'] ?? '';
            if (empty($rawImg)) continue;
            $cleanImg = ltrim($rawImg, '/');
            if (str_starts_with($cleanImg, 'storage/')) {
                $imgSrc = asset($cleanImg);
            } elseif (preg_match('/^\d+_/', basename($cleanImg)) || file_exists(storage_path('app/public/' . $cleanImg))) {
                $imgSrc = asset('storage/' . $cleanImg);
            } else {
                $imgSrc = asset($cleanImg);
            }
            if (!app()->environment('local') && str_starts_with($imgSrc, 'http://')) {
                $imgSrc = 'https://' . substr($imgSrc, 7);
            }
            $bTitle = $brochure['title'] ?? 'Brochure ' . ($index + 1);
        @endphp
        <div class="brochure-card">
            <img src="{{ $imgSrc }}" 
                 alt="{{ $bTitle }}" 
                 class="brochure-img" 
                 loading="lazy" 
                 onerror="if (!this.dataset.triedStorage && this.src.indexOf('/storage/') === -1) { this.dataset.triedStorage = '1'; this.src = this.src.replace('/images/', '/storage/images/'); }"
                 onclick="openLightboxModal('{{ $imgSrc }}', '{{ addslashes($bTitle) }}')" 
                 title="Click to open Full Resolution Zoom">
            <div class="brochure-actions">
                <button type="button" onclick="openLightboxModal('{{ $imgSrc }}', '{{ addslashes($bTitle) }}')" class="btn-view-full" style="cursor: pointer;">
                    <i class="fa-solid fa-up-right-and-down-left-from-center"></i> View Full Resolution (HD Zoom)
                </button>
                <a href="{{ $imgSrc }}" download class="btn-view-full" style="background: #00A896; color: #ffffff; border-color: #00A896;">
                    <i class="fa-solid fa-download"></i> Download
                </a>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 16px; border: 1.5px dashed #cbd5e1; color: #64748b; margin-bottom: 25px;">
            <i class="fa-regular fa-file-image" style="font-size: 2.8rem; color: #94a3b8; margin-bottom: 12px; display: block;"></i>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Brochures Will Be Available Soon</h3>
            <p style="margin: 0; font-size: 0.92rem;">Please check back later or contact us for stall booking details.</p>
        </div>
    @endforelse

    <!-- Quick Payment QR Section for Stall Booking & Tariff -->
    <div style="background: linear-gradient(135deg, #0a192f 0%, #0f172a 100%); border-radius: 16px; border: 1.5px solid #1e293b; box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2); padding: 35px 30px; margin: 30px 0; color: #ffffff;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 30px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 280px;">
                <span style="background: rgba(0, 168, 150, 0.2); color: #00e676; font-size: 0.8rem; font-weight: 800; padding: 4px 14px; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; display: inline-block; margin-bottom: 10px;">
                    Fast & Direct Payment
                </span>
                <h3 style="font-size: 1.6rem; font-weight: 800; color: #ffffff; margin: 0 0 10px 0;">
                    Scan QR Code for Payment
                </h3>
                <p style="color: #94a3b8; font-size: 0.95rem; line-height: 1.6; margin: 0 0 18px 0;">
                    Scan the official MCC payment QR code using any UPI App (GPay, PhonePe, Paytm) to complete your stall booking or sponsorship contribution.
                </p>
                <div style="font-size: 0.9rem; color: #cbd5e1; margin-bottom: 8px;">
                    <strong>Online PayU Link:</strong> 
                    <a href="https://u.payu.in/PAYUMN/IJZCzKXf5LTs" target="_blank" style="color: #00e676; font-weight: 700; text-decoration: underline; margin-left: 5px;">https://u.payu.in/PAYUMN/IJZCzKXf5LTs</a>
                </div>
            </div>
            
            <div style="flex-shrink: 0; text-align: center; background: #ffffff; padding: 15px; border-radius: 14px; box-shadow: 0 8px 25px rgba(0,0,0,0.3); border: 2px solid #00A896;">
                <img src="{{ asset('images/payment_qr_final.png') }}" alt="Scan QR Code for Payment" style="max-width: 200px; width: 100%; display: block; border-radius: 8px;">
            </div>
        </div>
    </div>

    <!-- Sponsors & Industry Partners Showcase -->
    <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06); padding: 35px 30px; margin-top: 20px;">
        <div style="text-align: center; margin-bottom: 28px;">
            @if(!empty($sponsorsBadge))
                <span style="background: #e6f7f5; color: #00A896; font-size: 0.8rem; font-weight: 800; padding: 4px 14px; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; display: inline-block; margin-bottom: 8px;">
                    {{ $sponsorsBadge }}
                </span>
            @endif
            @if(!empty($sponsorsTitle))
                <h3 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin: 0;">
                    {{ $sponsorsTitle }}
                </h3>
            @endif
        </div>

        @if(!empty($sponsorList))
            <div style="display: flex; justify-content: center; align-items: center; gap: 30px; flex-wrap: wrap; margin-bottom: 25px;">
                @foreach($sponsorList as $sponsor)
                    @php
                        $sponsorLink = !empty($sponsor['link']) && $sponsor['link'] !== '#' ? $sponsor['link'] : null;
                        $sponsorLogo = asset($sponsor['logo'] ?? 'images/sponsors/anderson_logo.svg');
                        $sponsorName = $sponsor['name'] ?? 'Sponsor Partner';
                    @endphp
                    @if($sponsorLink)
                        <a href="{{ $sponsorLink }}" target="_blank" rel="noopener noreferrer" class="sponsor-card-box">
                            <img src="{{ $sponsorLogo }}" alt="{{ $sponsorName }}" style="max-height: 65px; max-width: 200px; object-fit: contain; border-radius: 6px;">
                        </a>
                    @else
                        <div class="sponsor-card-box">
                            <img src="{{ $sponsorLogo }}" alt="{{ $sponsorName }}" style="max-height: 65px; max-width: 200px; object-fit: contain; border-radius: 6px;">
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <div style="text-align: center; padding-top: 15px; border-top: 1px solid #f1f5f9;">
            @if(!empty($ctaText))
                <p style="color: #64748b; font-size: 0.95rem; margin: 0 0 15px 0;">
                    {{ $ctaText }}
                </p>
            @endif
            @if(!empty($ctaBtnLabel))
                <a href="{{ $ctaBtnUrl }}" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 12px 30px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(0, 168, 150, 0.3);">
                    <i class="fa-solid fa-handshake"></i> {{ $ctaBtnLabel }}
                </a>
            @endif
        </div>
    </div>

</div>

<!-- Full Resolution HD Lightbox Modal -->
<div id="hdLightboxModal" style="display: none; position: fixed; inset: 0; background: rgba(10, 25, 47, 0.95); z-index: 99999; backdrop-filter: blur(8px); flex-direction: column; justify-content: space-between; align-items: center; padding: 20px;">
    <!-- Top Bar -->
    <div style="width: 100%; max-width: 1200px; display: flex; justify-content: space-between; align-items: center; color: #ffffff; padding: 10px 0;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fa-solid fa-file-image" style="color: #00A896; font-size: 1.3rem;"></i>
            <h4 id="hdLightboxTitle" style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #ffffff;">Brochure Full HD Resolution</h4>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <button type="button" onclick="zoomLightbox(0.2)" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); width: 38px; height: 38px; border-radius: 8px; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Zoom In (+)">
                <i class="fa-solid fa-magnifying-glass-plus"></i>
            </button>
            <button type="button" onclick="zoomLightbox(-0.2)" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); width: 38px; height: 38px; border-radius: 8px; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Zoom Out (-)">
                <i class="fa-solid fa-magnifying-glass-minus"></i>
            </button>
            <button type="button" onclick="resetLightboxZoom()" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); padding: 0 14px; height: 38px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer;" title="Reset Zoom">
                100% Reset
            </button>
            <a id="hdLightboxDownload" href="#" download style="background: #00A896; color: #fff; text-decoration: none; padding: 0 16px; height: 38px; border-radius: 8px; font-size: 0.88rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-download"></i> Download
            </a>
            <button type="button" onclick="closeLightboxModal()" style="background: #ef4444; color: #fff; border: none; width: 38px; height: 38px; border-radius: 8px; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Close (Esc)">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    <!-- Image Container (Scrollable & Zoomable) -->
    <div id="hdLightboxViewport" style="flex: 1; width: 100%; overflow: auto; display: flex; align-items: center; justify-content: center; cursor: grab;">
        <img id="hdLightboxImage" src="" alt="Full resolution preview" style="max-height: 85vh; max-width: 95vw; width: auto; height: auto; object-fit: contain; transition: transform 0.2s ease; image-rendering: -webkit-optimize-contrast; image-rendering: high-quality; box-shadow: 0 20px 60px rgba(0,0,0,0.5); border-radius: 8px;">
    </div>

    <!-- Bottom Hint -->
    <div style="color: #94a3b8; font-size: 0.8rem; padding-top: 8px;">
        Tip: Use (+ / -) to zoom or scroll inside to inspect small text and details with crystal clarity. Press ESC to close.
    </div>
</div>

<script>
    let currentLightboxZoom = 1;
    function openLightboxModal(src, title) {
        const modal = document.getElementById('hdLightboxModal');
        const img = document.getElementById('hdLightboxImage');
        const titleEl = document.getElementById('hdLightboxTitle');
        const dl = document.getElementById('hdLightboxDownload');
        
        currentLightboxZoom = 1;
        img.style.transform = 'scale(1)';
        img.src = src;
        titleEl.textContent = title || 'High Resolution View';
        dl.href = src;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeLightboxModal() {
        document.getElementById('hdLightboxModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function zoomLightbox(delta) {
        currentLightboxZoom = Math.max(0.5, Math.min(3.5, currentLightboxZoom + delta));
        document.getElementById('hdLightboxImage').style.transform = `scale(${currentLightboxZoom})`;
    }

    function resetLightboxZoom() {
        currentLightboxZoom = 1;
        document.getElementById('hdLightboxImage').style.transform = 'scale(1)';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightboxModal();
    });
</script>

@include('sections.footer')

@endsection
