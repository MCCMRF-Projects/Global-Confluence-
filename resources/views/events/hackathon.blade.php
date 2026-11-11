@extends('layouts.app')

@section('content')

@include('sections.topbar')
@include('sections.navbar')

@php
    $hackathonSettings = \App\Models\SiteSetting::where('group', 'hackathon')->pluck('value', 'key')->toArray();
    $bannerTitle = $hackathonSettings['hackathon_banner_title'] ?? 'HACKATHON';
    $sectionTitle = $hackathonSettings['hackathon_section_title'] ?? 'One Health Grand Hackathon Challenge';
    $mainContent = $hackathonSettings['hackathon_main_content'] ?? 'Join multidisciplinary teams of engineers, healthcare professionals, data scientists, and developers to build rapid digital and hardware solutions for One Health surveillance, pandemic preparedness, antimicrobial resistance tracking, and environmental monitoring.';
    $specTeam = $hackathonSettings['hackathon_spec_team'] ?? '2 to 5 Developers / Researchers per Team';
    $specDuration = $hackathonSettings['hackathon_spec_duration'] ?? '24-Hour Intensive Prototyping Sprint';
    $specPrizes = $hackathonSettings['hackathon_spec_prizes'] ?? 'Cash Awards + Fast-track Incubation Support';
    $guidelines = $hackathonSettings['hackathon_guidelines_content'] ?? null;
    $flyerImage = $hackathonSettings['hackathon_flyer_image'] ?? (file_exists(public_path('images/event/hackathon_flyer.png')) ? 'images/event/hackathon_flyer.png' : null);
@endphp

<!-- Page Banner -->
<div class="page-banner" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 65px 20px; text-align: center; color: #fff; position: relative;">
    <div class="page-banner-content">
        <h1 style="text-transform: uppercase; font-size: 2.4rem; font-weight: 800; letter-spacing: 1.5px; color: #ffffff; margin: 0;">
            {{ $bannerTitle }}
        </h1>
    </div>
</div>

<style>
    .event-page-container {
        padding: 60px 20px;
        max-width: 1100px;
        margin: 0 auto;
        font-family: 'Poppins', 'Inter', system-ui, sans-serif;
        color: #334155;
    }
    .event-page-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.05);
        padding: 45px;
        margin-bottom: 30px;
    }
    .announcement-box {
        background: #f0fdf4;
        border-left: 4px solid #10b981;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 35px;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #334155;
    }
    .spec-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }
    .spec-card {
        background: #f8fafc;
        border-left: 4px solid #00A896;
        border-radius: 12px;
        padding: 22px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .spec-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 168, 150, 0.1);
    }
    .spec-card h4 {
        color: #0f172a;
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .spec-card p {
        font-size: 0.95rem;
        color: #475569;
        margin: 0;
        line-height: 1.6;
    }
    .section-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 35px;
        margin-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .guideline-list {
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }
    .guideline-list li {
        position: relative;
        padding-left: 32px;
        margin-bottom: 14px;
        font-size: 1rem;
        line-height: 1.7;
        color: #334155;
    }
    .guideline-list li i {
        position: absolute;
        left: 0;
        top: 4px;
        color: #00A896;
        font-size: 1.1rem;
    }
</style>

<div class="event-page-container">
    <div class="event-page-card">
        <h2 style="font-size: 1.9rem; font-weight: 800; color: #0f172a; margin-bottom: 20px; border-bottom: 3px solid #00A896; padding-bottom: 12px; display: inline-block;">
            {{ $sectionTitle }}
        </h2>

        <!-- Overview Box -->
        <div class="announcement-box">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-laptop-code" style="color: #10b981;"></i> Challenge Brief
            </h3>
            <p style="margin: 0;">
                {{ $mainContent }}
            </p>
        </div>

        <!-- Quick Specs Cards -->
        <div class="spec-grid">
            <div class="spec-card">
                <h4><i class="fa-solid fa-users-gear" style="color: #00A896;"></i> Team Composition</h4>
                <p>{{ $specTeam }}</p>
            </div>
            <div class="spec-card">
                <h4><i class="fa-solid fa-hourglass-half" style="color: #00A896;"></i> Sprint Duration</h4>
                <p>{{ $specDuration }}</p>
            </div>
            <div class="spec-card">
                <h4><i class="fa-solid fa-trophy" style="color: #00A896;"></i> Cash Awards & Recognition</h4>
                <p>{{ $specPrizes }}</p>
            </div>
        </div>

        <!-- Problem Statements & Challenge Tracks -->
        <div class="section-title">
            <i class="fa-solid fa-layer-group" style="color: #00A896;"></i> Problem Statements & Challenge Rules
        </div>

        @php
            $hackathonItems = !empty($hackathonSettings['hackathon_guidelines_json']) ? json_decode($hackathonSettings['hackathon_guidelines_json'], true) : null;
        @endphp

        @if(!empty($hackathonItems) && is_array($hackathonItems))
            <ul class="guideline-list">
                @foreach($hackathonItems as $item)
                    <li><i class="fa-solid fa-circle-check"></i> {!! $item !!}</li>
                @endforeach
            </ul>
        @elseif($guidelines)
            <div style="background: #f8fafc; border-radius: 12px; padding: 25px; border: 1px solid #e2e8f0; line-height: 1.8; color: #334155; font-size: 1rem; white-space: pre-line;">
                {!! nl2br(e($guidelines)) !!}
            </div>
        @else
            <ul class="guideline-list">
                <li><i class="fa-solid fa-circle-check"></i> <strong>Track A - Genomic & AMR Surveillance:</strong> AI-powered tools for early pathogen detection, AMR mutation tracking, and outbreak modeling.</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Track B - Environmental Biosensors:</strong> Low-cost IoT sensors for real-time monitoring of effluent water, soil toxicants, and airborne pathogens.</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Track C - Community Health & Tele-Diagnostics:</strong> Portable point-of-care diagnostics and accessible mobile apps for rural community outreach.</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Track D - Sustainable Bioprocesses:</strong> Circular economy algorithms and biotechnological tools for safe clinical waste remediation.</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Deliverables:</strong> Functional prototype / code repository, 5-minute live demonstration, and project presentation.</li>
            </ul>
        @endif

        @if(!empty($flyerImage))
            <!-- Official Event Flyer Showcase -->
            <div style="margin-top: 45px; padding-top: 35px; border-top: 2px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px;">
                    <div>
                        <h3 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin: 0 0 6px 0; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-file-image" style="color: #00A896;"></i> Official Event Flyer & Challenge Guide
                        </h3>
                        <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Click poster or use the zoom button to inspect full fine print in crystal-clear HD.</p>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                        <button type="button" onclick="openLightboxModal('{{ asset($flyerImage) }}', '{{ addslashes($sectionTitle) }} - Official Flyer')" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; border: none; padding: 11px 22px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(0, 168, 150, 0.3);">
                            <i class="fa-solid fa-magnifying-glass-plus"></i> View Full Resolution (HD Zoom)
                        </button>
                        <a href="{{ asset($flyerImage) }}" download="Hackathon_Event_Flyer" style="background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-download"></i> Download Flyer
                        </a>
                    </div>
                </div>

                <div style="text-align: center;">
                    <div style="position: relative; display: inline-block; max-width: 780px; width: 100%; cursor: zoom-in; border-radius: 16px; overflow: hidden; border: 2px solid #e2e8f0; box-shadow: 0 12px 35px rgba(15, 23, 42, 0.08); background: #ffffff;" onclick="openLightboxModal('{{ asset($flyerImage) }}', '{{ addslashes($sectionTitle) }} - Official Flyer')">
                        <img src="{{ asset($flyerImage) }}" alt="{{ $sectionTitle }} Flyer" style="width: 100%; height: auto; display: block; image-rendering: -webkit-optimize-contrast; image-rendering: high-quality; transition: transform 0.3s ease;">
                        <div style="position: absolute; bottom: 18px; right: 18px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); color: #ffffff; padding: 9px 18px; border-radius: 30px; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                            <i class="fa-solid fa-expand"></i> Click to Zoom Full HD
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

<!-- Full Resolution HD Lightbox Modal -->
<div id="hdLightboxModal" style="display: none; position: fixed; inset: 0; background: rgba(10, 25, 47, 0.95); z-index: 99999; backdrop-filter: blur(8px); flex-direction: column; justify-content: space-between; align-items: center; padding: 20px;">
    <!-- Top Bar -->
    <div style="width: 100%; max-width: 1200px; display: flex; justify-content: space-between; align-items: center; color: #ffffff; padding: 10px 0;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fa-solid fa-file-image" style="color: #00A896; font-size: 1.3rem;"></i>
            <h4 id="hdLightboxTitle" style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #ffffff;">Event Flyer Full HD Resolution</h4>
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
