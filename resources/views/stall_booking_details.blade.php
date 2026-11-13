@extends('layouts.app')

@section('title', 'Stall Booking & Merchandise Details | Global One Health Confluence 2026')

@section('content')

@include('sections.topbar')
@include('sections.navbar')

@php
    $stallSettings = [];
    try {
        $stallSettings = \App\Models\SiteSetting::where('group', 'stall_booking')->pluck('value', 'key')->toArray();
    } catch (\Throwable $e) {
        $stallSettings = [];
    }

    $detailsSettings = [];
    try {
        $detailsSettings = \App\Models\SiteSetting::where('group', 'stall_booking_details')->pluck('value', 'key')->toArray();
    } catch (\Throwable $e) {
        $detailsSettings = [];
    }

    $badge = $detailsSettings['stall_details_badge'] ?? 'Conference Opportunities & Brochures';
    $bannerTitle = $detailsSettings['stall_details_title'] ?? 'Stall Booking & Merchandise';
    $subtitle = $detailsSettings['stall_details_subtitle'] ?? 'Partnership Avenues, Tariff Details & Sponsorship Opportunities | Global One Health Confluence 2026';

    // All official conference brochures (restored)
    $homeBrochures = [];
    if (!empty($stallSettings['stall_home_brochures_json'])) {
        $homeBrochures = json_decode($stallSettings['stall_home_brochures_json'], true) ?: [];
    }

    if (empty($homeBrochures)) {
        $homeBrochures = [
            [
                'title' => 'Global One Health Confluence 2026 Brochure',
                'image' => file_exists(public_path('images/stall_booking/stall_brochure_1.png')) ? 'images/stall_booking/stall_brochure_1.png' : 'images/stall_booking/stall_brochure_1.jpg',
            ],
        ];
    }
@endphp

<style>
    .stall-details-wrapper {
        background: #f8fafc;
        padding: 40px 20px 80px;
        font-family: 'Poppins', sans-serif;
    }

    .stall-details-container {
        max-width: 1040px;
        margin: 0 auto;
    }

    .details-brochure-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        padding: 24px;
        margin-bottom: 45px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .details-brochure-card:hover {
        box-shadow: 0 18px 45px rgba(0, 168, 150, 0.12);
    }


    .details-brochure-img {
        width: 100%;
        height: auto;
        border-radius: 12px;
        display: block;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        image-rendering: -webkit-optimize-contrast;
        image-rendering: high-quality;
        cursor: zoom-in;
        transition: transform 0.25s ease;
        border: 1px solid #e2e8f0;
    }

    .details-brochure-img:hover {
        transform: scale(1.008);
    }

    .details-brochure-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 11px 24px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-zoom {
        background: #f8fafc;
        color: #0f172a;
        border: 1.5px solid #cbd5e1;
    }

    .btn-zoom:hover {
        background: #00A896;
        color: #ffffff;
        border-color: #00A896;
    }

    .btn-download {
        background: linear-gradient(135deg, #00A896, #028090);
        color: #ffffff;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 168, 150, 0.25);
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 168, 150, 0.35);
        color: #ffffff;
    }
</style>

<!-- Page Banner -->
<div class="page-banner" style="background: linear-gradient(135deg, #0a192f 0%, #0f172a 50%, #112240 100%); padding: 60px 20px 55px; text-align: center; color: #ffffff; position: relative;">
    <div class="page-banner-content" style="max-width: 900px; margin: 0 auto; position: relative; z-index: 5;">
        <span style="display: inline-block; padding: 5px 16px; background: rgba(0, 168, 150, 0.2); color: #2dd4bf; border: 1px solid rgba(45, 212, 191, 0.35); border-radius: 30px; font-weight: 700; font-size: 0.8rem; letter-spacing: 1.2px; text-transform: uppercase; margin-bottom: 14px;">
            {{ $badge }}
        </span>
        <h1 style="text-transform: uppercase; font-size: clamp(2rem, 4vw, 2.7rem); font-weight: 800; letter-spacing: 1.5px; color: #ffffff !important; margin: 0 0 10px 0; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
            {{ $bannerTitle }}
        </h1>
        <p style="color: #cbd5e1; font-size: 1.05rem; margin: 0; font-weight: 400; line-height: 1.6;">
            {{ $subtitle }}
        </p>
    </div>
</div>

<!-- Main Details Section -->
<div class="stall-details-wrapper">
    <div class="stall-details-container">

        <!-- All Official Conference Brochures (Restored with Full Master HD Quality) -->
        @foreach($homeBrochures as $index => $brochure)
            @php
                $rawImg = $brochure['image'] ?? 'images/stall_booking/stall_brochure_1.png';
                if ($rawImg === 'images/stall_booking/stall_brochure_1.jpg' && file_exists(public_path('images/stall_booking/stall_brochure_1.png'))) {
                    $rawImg = 'images/stall_booking/stall_brochure_1.png';
                }
                if (str_starts_with($rawImg, 'http://')) {
                    $imgSrc = 'https://' . substr($rawImg, 7);
                } elseif (str_starts_with($rawImg, 'https://')) {
                    $imgSrc = $rawImg;
                } else {
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
                }
                $bTitle = $brochure['title'] ?? 'Brochure ' . ($index + 1);
                $fallbackImg = asset('images/stall_booking/stall_brochure_1.png');
                if (!app()->environment('local') && str_starts_with($fallbackImg, 'http://')) {
                    $fallbackImg = 'https://' . substr($fallbackImg, 7);
                }
            @endphp
            <div class="details-brochure-card">
                <!-- High Quality Brochure Image -->
                <img src="{{ $imgSrc }}" 
                     alt="{{ $bTitle }}" 
                     class="details-brochure-img" 
                     loading="lazy" 
                     onerror="if (!this.dataset.triedStorage && this.src.indexOf('/storage/') === -1) { this.dataset.triedStorage = '1'; this.src = this.src.replace('/images/', '/storage/images/'); } else if (!this.dataset.triedFallback) { this.dataset.triedFallback = '1'; this.src = '{{ $fallbackImg }}'; }"
                     onclick="openLightboxModal('{{ $imgSrc }}', '{{ addslashes($bTitle) }}')" 
                     title="Click to open Full Resolution Zoom">

                <!-- Action Bar -->
                <div class="details-brochure-actions">
                    <button type="button" onclick="openLightboxModal('{{ $imgSrc }}', '{{ addslashes($bTitle) }}')" class="btn-action btn-zoom">
                        View Full Resolution (HD Zoom)
                    </button>
                    <a href="{{ $imgSrc }}" download class="btn-action btn-download">
                        Download Brochure
                    </a>
                </div>
            </div>
        @endforeach

    </div>
</div>

<!-- Full Resolution HD Lightbox Modal -->
<div id="hdLightboxModal" style="display: none; position: fixed; inset: 0; background: rgba(10, 25, 47, 0.95); z-index: 99999; backdrop-filter: blur(8px); flex-direction: column; justify-content: space-between; align-items: center; padding: 20px;">
    <!-- Top Bar -->
    <div style="width: 100%; max-width: 1200px; display: flex; justify-content: space-between; align-items: center; color: #ffffff; padding: 10px 0;">
        <div>
            <h4 id="hdLightboxTitle" style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #ffffff;">Brochure Full HD Resolution</h4>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <button type="button" onclick="zoomLightbox(0.2)" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); width: 38px; height: 38px; border-radius: 8px; font-size: 1.2rem; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Zoom In (+)">
                +
            </button>
            <button type="button" onclick="zoomLightbox(-0.2)" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); width: 38px; height: 38px; border-radius: 8px; font-size: 1.2rem; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Zoom Out (-)">
                &minus;
            </button>
            <button type="button" onclick="resetLightboxZoom()" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); padding: 0 14px; height: 38px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer;" title="Reset Zoom">
                100% Reset
            </button>
            <a id="hdLightboxDownload" href="#" download style="background: #00A896; color: #fff; text-decoration: none; padding: 0 16px; height: 38px; border-radius: 8px; font-size: 0.88rem; font-weight: 700; display: inline-flex; align-items: center;">
                Download
            </a>
            <button type="button" onclick="closeLightboxModal()" style="background: #ef4444; color: #fff; border: none; width: 38px; height: 38px; border-radius: 8px; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Close (Esc)">
                &times;
            </button>
        </div>
    </div>

    <!-- Image Container (Scrollable & Zoomable) -->
    <div id="hdLightboxViewport" style="flex: 1; width: 100%; overflow: auto; display: flex; align-items: center; justify-content: center; cursor: grab;">
        <img id="hdLightboxImage" src="" alt="Full resolution preview" style="max-height: 85vh; max-width: 95vw; width: auto; height: auto; object-fit: contain; transition: transform 0.2s ease; image-rendering: -webkit-optimize-contrast; image-rendering: high-quality; box-shadow: 0 20px 60px rgba(0,0,0,0.5); border-radius: 8px;">
    </div>

    <!-- Bottom Hint -->
    <div style="color: #94a3b8; font-size: 0.8rem; padding-top: 8px;">
        Tip: Click (+) to zoom in and examine small text and details with crystal clarity. Press ESC to close.
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
