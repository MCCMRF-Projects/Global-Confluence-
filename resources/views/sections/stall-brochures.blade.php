<!-- Conference Brochures & Partnership Section -->
<section id="conference-brochures" style="padding: 45px 0 60px; background: #ffffff; position: relative; font-family: 'Poppins', sans-serif;">
    <div class="container" style="max-width: 1050px; margin: 0 auto; padding: 0 20px;">

        @php
            $stallSettings = [];
            try {
                $stallSettings = \App\Models\SiteSetting::where('group', 'stall_booking')->pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                $stallSettings = [];
            }

            $homeBrochures = [];
            if (!empty($stallSettings['stall_brochures_json'])) {
                $homeBrochures = json_decode($stallSettings['stall_brochures_json'], true) ?: [];
            }

            if (empty($homeBrochures)) {
                $homeBrochures = [
                    [
                        'title' => 'Partnership Avenues for Analytical Service Providers',
                        'image' => 'images/stall_booking/stall_brochure_1.jpg'
                    ],
                    [
                        'title' => 'Tariff Details & Sponsorship Opportunities',
                        'image' => 'images/stall_booking/stall_brochure_2.png'
                    ],
                ];
            }
        @endphp

        <!-- Section Header -->
        <div style="text-align: center; margin-bottom: 40px;">
            <span style="display: inline-flex; align-items: center; gap: 8px; background: #e6f7f5; color: #009688; border: 1px solid #b2dfdb; padding: 7px 22px; border-radius: 50px; font-weight: 800; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 14px;">
                <i class="fa-solid fa-book-open"></i> {{ $stallSettings['stall_home_badge'] ?? 'Conference Brochures' }}
            </span>
            <h2 style="font-size: clamp(2rem, 4vw, 2.7rem); font-weight: 800; color: #0f172a; margin: 0 0 12px 0; letter-spacing: -0.5px;">
                {!! $stallSettings['stall_home_title'] ?? 'Partnership Avenues &amp; <span style="color: #009688;">Sponsorship Tariff</span>' !!}
            </h2>
            <div style="width: 70px; height: 4px; background: linear-gradient(90deg, #009688, #84cc16); margin: 0 auto 16px auto; border-radius: 4px;"></div>
            <p style="max-width: 720px; margin: 0 auto; color: #64748b; font-size: 1.05rem; line-height: 1.6; font-weight: 500;">
                {{ $stallSettings['stall_home_subtitle'] ?? 'Official partnership avenues, tariff details, and sponsorship opportunities for Global One Health Confluence 2026.' }}
            </p>
        </div>

        <style>
            .home-brochure-card {
                background: #ffffff;
                border-radius: 16px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
                padding: 22px;
                margin-bottom: 35px;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .home-brochure-card:hover {
                box-shadow: 0 15px 40px rgba(0, 168, 150, 0.12);
            }

            .home-brochure-img {
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

            .home-brochure-img:hover {
                transform: scale(1.006);
                box-shadow: 0 8px 25px rgba(0, 168, 150, 0.15);
            }

            .home-brochure-actions {
                display: flex;
                justify-content: flex-end;
                align-items: center;
                gap: 12px;
                margin-top: 15px;
                padding-top: 14px;
                border-top: 1px solid #f1f5f9;
                flex-wrap: wrap;
            }

            .home-btn-view-full {
                background: #f8fafc;
                color: #0f172a;
                border: 1px solid #cbd5e1;
                padding: 9px 20px;
                border-radius: 8px;
                font-size: 0.88rem;
                font-weight: 600;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.2s;
                cursor: pointer;
            }

            .home-btn-view-full:hover {
                background: #00A896;
                color: #ffffff;
                border-color: #00A896;
            }
        </style>

        <!-- Dynamic Brochures List -->
        @foreach($homeBrochures as $index => $brochure)
            @php
                $imgSrc = asset($brochure['image'] ?? 'images/stall_booking/stall_brochure_1.jpg');
                $bTitle = $brochure['title'] ?? 'Brochure ' . ($index + 1);
            @endphp
            <div class="home-brochure-card">
                <img src="{{ $imgSrc }}" alt="{{ $bTitle }}" class="home-brochure-img" loading="lazy" onclick="openHomeBrochureModal('{{ $imgSrc }}', '{{ addslashes($bTitle) }}')" title="Click to open Full Resolution HD Zoom">
                <div class="home-brochure-actions">
                    <button type="button" onclick="openHomeBrochureModal('{{ $imgSrc }}', '{{ addslashes($bTitle) }}')" class="home-btn-view-full">
                        <i class="fa-solid fa-up-right-and-down-left-from-center"></i> View Full Resolution (HD Zoom)
                    </button>
                    <a href="{{ $imgSrc }}" download class="home-btn-view-full" style="background: #00A896; color: #ffffff; border-color: #00A896;">
                        <i class="fa-solid fa-download"></i> Download
                    </a>
                </div>
            </div>
        @endforeach

        <!-- Navigation CTA to Stall Booking & Merchandise -->
        <div style="text-align: center; margin-top: 15px; padding-top: 10px;">
            <a href="{{ route('custom.page', 'stall-booking-and-merchandise') }}" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 13px 32px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(0, 168, 150, 0.3);">
                <i class="fa-solid fa-store"></i> Explore All Stall Booking &amp; Merchandise Details &rarr;
            </a>
        </div>

    </div>
</section>

<!-- Full Resolution HD Lightbox Modal for Home -->
<div id="homeBrochureModal" style="display: none; position: fixed; inset: 0; background: rgba(10, 25, 47, 0.95); z-index: 99999; backdrop-filter: blur(8px); flex-direction: column; justify-content: space-between; align-items: center; padding: 20px;">
    <!-- Top Bar -->
    <div style="width: 100%; max-width: 1200px; display: flex; justify-content: space-between; align-items: center; color: #ffffff; padding: 10px 0;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fa-solid fa-file-image" style="color: #00A896; font-size: 1.3rem;"></i>
            <h4 id="homeBrochureModalTitle" style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #ffffff;">Brochure Full HD Resolution</h4>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <button type="button" onclick="zoomHomeBrochure(0.2)" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); width: 38px; height: 38px; border-radius: 8px; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Zoom In (+)">
                <i class="fa-solid fa-magnifying-glass-plus"></i>
            </button>
            <button type="button" onclick="zoomHomeBrochure(-0.2)" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); width: 38px; height: 38px; border-radius: 8px; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Zoom Out (-)">
                <i class="fa-solid fa-magnifying-glass-minus"></i>
            </button>
            <button type="button" onclick="resetHomeBrochureZoom()" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); padding: 0 14px; height: 38px; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer;" title="Reset Zoom">
                100% Reset
            </button>
            <a id="homeBrochureDownload" href="#" download style="background: #00A896; color: #fff; text-decoration: none; padding: 0 16px; height: 38px; border-radius: 8px; font-size: 0.88rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-download"></i> Download
            </a>
            <button type="button" onclick="closeHomeBrochureModal()" style="background: #ef4444; color: #fff; border: none; width: 38px; height: 38px; border-radius: 8px; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Close (Esc)">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    <!-- Image Container (Scrollable & Zoomable) -->
    <div id="homeBrochureViewport" style="flex: 1; width: 100%; overflow: auto; display: flex; align-items: center; justify-content: center; cursor: grab;">
        <img id="homeBrochureImage" src="" alt="Full resolution preview" style="max-height: 85vh; max-width: 95vw; width: auto; height: auto; object-fit: contain; transition: transform 0.2s ease; image-rendering: -webkit-optimize-contrast; image-rendering: high-quality; box-shadow: 0 20px 60px rgba(0,0,0,0.5); border-radius: 8px;">
    </div>

    <!-- Bottom Hint -->
    <div style="color: #94a3b8; font-size: 0.8rem; padding-top: 8px;">
        Tip: Use (+ / -) to zoom or scroll inside to inspect small text and details with crystal clarity. Press ESC to close.
    </div>
</div>

<script>
    let currentHomeBrochureZoom = 1;
    function openHomeBrochureModal(src, title) {
        const modal = document.getElementById('homeBrochureModal');
        const img = document.getElementById('homeBrochureImage');
        const titleEl = document.getElementById('homeBrochureModalTitle');
        const dl = document.getElementById('homeBrochureDownload');
        
        currentHomeBrochureZoom = 1;
        img.style.transform = 'scale(1)';
        img.src = src;
        titleEl.textContent = title || 'High Resolution View';
        dl.href = src;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeHomeBrochureModal() {
        document.getElementById('homeBrochureModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function zoomHomeBrochure(delta) {
        currentHomeBrochureZoom = Math.max(0.5, Math.min(3.5, currentHomeBrochureZoom + delta));
        document.getElementById('homeBrochureImage').style.transform = `scale(${currentHomeBrochureZoom})`;
    }

    function resetHomeBrochureZoom() {
        currentHomeBrochureZoom = 1;
        document.getElementById('homeBrochureImage').style.transform = 'scale(1)';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeHomeBrochureModal();
    });
</script>
