<!-- Partner & Accreditation Logos Infinite Ticker Strip (Below Hero) -->
@php
    if (defined('__PARTNER_STRIP_SHOWN')) {
        return;
    }
    define('__PARTNER_STRIP_SHOWN', true);

    $defaultLogos = [
        ['name' => 'Microbiologists Society, India', 'logo_path' => 'images/microbiologists_society.png', 'link_url' => 'https://microbiosoc.org'],
        ['name' => 'MCC - MRF Innovation Park', 'logo_path' => 'images/1791374861_ChatGPT_Image_Oct_5__2026__05_34_56_PM.png', 'link_url' => 'https://mcc.edu.in'],
        ['name' => 'Mazumdar Shaw Medical Foundation (MSMF)', 'logo_path' => 'images/msmf_logo2.png', 'link_url' => 'https://msmf.org'],
        ['name' => 'Madras Christian College (MCC)', 'logo_path' => 'images/MMC-LOGO-2.jpg', 'link_url' => 'https://mcc.edu.in'],
        ['name' => 'National Institute of Siddha (NIS)', 'logo_path' => 'images/nis-logo-transparent.png', 'link_url' => 'https://nischennai.org'],
    ];

    $partnerLogos = collect();
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('partner_logos')) {
            $dbLogos = \App\Models\PartnerLogo::where('is_active', true)
                ->whereNotIn('name', ['---', '77', 'test', 'Test'])
                ->orderBy('sort_order')
                ->get();
            if ($dbLogos->isNotEmpty()) {
                $partnerLogos = $dbLogos;
            }
        }
    } catch (\Throwable $e) {
        $partnerLogos = collect();
    }

    if ($partnerLogos->isEmpty()) {
        $partnerLogos = collect($defaultLogos)->map(fn($item) => (object)$item);
    }

    // Multiply logos per group to guarantee seamless continuous marquee across all display sizes (including 4K/ultrawide)
    $repeatCount = $partnerLogos->isNotEmpty() ? max(3, (int)ceil(16 / max(1, $partnerLogos->count()))) : 0;
    $expandedLogos = collect();
    for ($i = 0; $i < $repeatCount; $i++) {
        $expandedLogos = $expandedLogos->concat($partnerLogos);
    }

    $stripLogoHeight = (int)(($settings['partner_strip_logo_height'] ?? null) ?: (\App\Models\SiteSetting::where('key', 'partner_strip_logo_height')->value('value') ?: 95));
    $cardHeight = $stripLogoHeight + 35;
    $cardWidth = max(260, (int)($stripLogoHeight * 3.1));
    $imgMaxWidth = (int)($cardWidth * 0.82);
@endphp

@if($expandedLogos->isNotEmpty())
<section class="partner-logos-bar-section" aria-label="Our Partners">
    <!-- Top glowing accent bar -->
    <div class="partner-strip-top-bar"></div>

    <div class="partner-strip-header-container">
        <span class="partner-badge-pill">
            <i class="fa-solid fa-handshake"></i> OUR PARTNERS
        </span>
    </div>

    <div class="partner-ticker-wrapper">
        <div class="partner-ticker-track">
            <!-- First Set of Logos -->
            <div class="partner-ticker-group">
                @foreach($expandedLogos as $logo)
                    @php
                        $cleanLogoPath = !empty($logo->logo_path) ? ltrim(str_replace('public/', '', $logo->logo_path), '/') : 'images/MMC-LOGO-2.jpg';
                    @endphp
                    <a href="{{ $logo->link_url ?? '#' }}" 
                       target="{{ (!empty($logo->link_url) && $logo->link_url !== '#' && $logo->link_url !== '/') ? '_blank' : '_self' }}"
                       class="partner-logo-card"
                       title="{{ $logo->name }}">
                        <img src="{{ asset($cleanLogoPath) }}" 
                             alt="{{ $logo->name }}" 
                             class="partner-strip-img"
                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="partner-logo-fallback" style="display: none;">
                            <i class="fa-solid fa-building-columns" style="color: #0d9488;"></i>
                            <span>{{ $logo->name }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <!-- Duplicate Set for Seamless Infinite Loop -->
            <div class="partner-ticker-group" aria-hidden="true">
                @foreach($expandedLogos as $logo)
                    @php
                        $cleanLogoPath = !empty($logo->logo_path) ? ltrim(str_replace('public/', '', $logo->logo_path), '/') : 'images/MMC-LOGO-2.jpg';
                    @endphp
                    <a href="{{ $logo->link_url ?? '#' }}" 
                       target="{{ (!empty($logo->link_url) && $logo->link_url !== '#' && $logo->link_url !== '/') ? '_blank' : '_self' }}"
                       class="partner-logo-card"
                       title="{{ $logo->name }}">
                        <img src="{{ asset($cleanLogoPath) }}" 
                             alt="{{ $logo->name }}" 
                             class="partner-strip-img"
                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="partner-logo-fallback" style="display: none;">
                            <i class="fa-solid fa-building-columns" style="color: #0d9488;"></i>
                            <span>{{ $logo->name }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<style>
    .partner-logos-bar-section {
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 50%, #f8fafc 100%);
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        position: relative;
        z-index: 10;
        width: 100%;
        overflow: hidden;
        padding: 16px 0 20px 0;
        display: block !important;
    }

    .partner-strip-top-bar {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #0d9488 0%, #0284c7 35%, #0d9488 70%, #0f766e 100%);
    }

    .partner-strip-header-container {
        text-align: center;
        margin-bottom: 14px;
        position: relative;
        z-index: 5;
    }

    .partner-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(13, 148, 136, 0.1);
        color: #0d9488;
        font-weight: 800;
        font-size: 0.82rem;
        padding: 5px 18px;
        border-radius: 30px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        border: 1px solid rgba(13, 148, 136, 0.2);
        box-shadow: 0 2px 6px rgba(13, 148, 136, 0.08);
    }

    .partner-ticker-wrapper {
        width: 100%;
        overflow: hidden;
        display: flex;
        position: relative;
        padding: 4px 0;
    }

    .partner-ticker-wrapper::before,
    .partner-ticker-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 120px;
        z-index: 2;
        pointer-events: none;
    }
    .partner-ticker-wrapper::before {
        left: 0;
        background: linear-gradient(to right, #f8fafc 15%, rgba(248, 250, 252, 0) 100%);
    }
    .partner-ticker-wrapper::after {
        right: 0;
        background: linear-gradient(to left, #f8fafc 15%, rgba(248, 250, 252, 0) 100%);
    }

    .partner-ticker-track {
        display: flex;
        width: max-content;
        will-change: transform;
        animation: ticker-slide 40s linear infinite;
        align-items: center;
    }

    .partner-ticker-wrapper:hover .partner-ticker-track {
        animation-play-state: paused;
    }

    .partner-ticker-group {
        display: flex;
        align-items: center;
        flex-shrink: 0;
        gap: 22px;
        padding: 0 11px;
    }

    .partner-logo-card {
        flex: 0 0 {{ $cardWidth }}px !important;
        width: {{ $cardWidth }}px !important;
        height: {{ $cardHeight }}px !important;
        padding: 10px 22px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.05);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
    }

    .partner-logo-card:hover {
        background: #ffffff;
        border-color: #0d9488;
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(13, 148, 136, 0.16);
    }

    .partner-strip-img {
        height: {{ $stripLogoHeight }}px !important;
        max-height: {{ $stripLogoHeight }}px !important;
        max-width: {{ $imgMaxWidth }}px !important;
        width: auto !important;
        object-fit: contain !important;
        transition: transform 0.3s ease;
        filter: contrast(1.02);
    }

    .partner-logo-card:hover .partner-strip-img {
        transform: scale(1.06);
    }

    .partner-logo-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 0.88rem;
        font-weight: 700;
        color: #1e293b;
        text-align: center;
        line-height: 1.3;
    }

    @keyframes ticker-slide {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    @media (max-width: 768px) {
        .partner-logos-bar-section {
            padding: 12px 0 16px 0;
        }
        .partner-badge-pill {
            font-size: 0.75rem;
            padding: 4px 14px;
        }
        .partner-ticker-group {
            gap: 12px;
        }
        .partner-logo-card {
            flex: 0 0 {{ max(190, (int)($cardWidth * 0.72)) }}px !important;
            width: {{ max(190, (int)($cardWidth * 0.72)) }}px !important;
            height: {{ max(80, (int)($cardHeight * 0.75)) }}px !important;
            padding: 8px 14px !important;
            border-radius: 10px;
        }
        .partner-strip-img {
            height: {{ max(50, (int)($stripLogoHeight * 0.72)) }}px !important;
            max-height: {{ max(50, (int)($stripLogoHeight * 0.72)) }}px !important;
            max-width: {{ max(150, (int)($imgMaxWidth * 0.72)) }}px !important;
            width: auto !important;
            object-fit: contain !important;
        }
        .partner-logo-fallback {
            font-size: 0.75rem;
        }
    }
</style>
