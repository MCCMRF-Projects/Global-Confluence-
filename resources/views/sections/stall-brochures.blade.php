<!-- Conference Brochures & Partnership Section (Executive Minimalist Cards) -->
<section id="conference-brochures" style="padding: 40px 0 55px; background: #ffffff; position: relative; font-family: 'Poppins', sans-serif;">
    <div class="container" style="max-width: 1240px; margin: 0 auto; padding: 0 20px;">

        @php
            $stallSettings = [];
            try {
                $stallSettings = \App\Models\SiteSetting::where('group', 'stall_booking')->pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                $stallSettings = [];
            }

            $allOfficialBrochures = [
                [
                    'title' => 'Partnership Avenues for Analytical Service Providers',
                    'desc' => 'Dedicated exhibition spaces, advanced instruments showcase & academia-industry B2B networking.',
                    'image' => file_exists(public_path('images/stall_booking/stall_brochure_1.jpg')) ? 'images/stall_booking/stall_brochure_1.jpg' : 'images/stall_booking/stall_brochure_1.png',
                    'tag' => 'Analytical Industry',
                    'icon' => 'fa-solid fa-flask-vial'
                ],
                [
                    'title' => 'Partnership Avenues for Hospitals & Health Sectors',
                    'desc' => 'Healthcare innovation showcase, clinical network visibility, CME sessions & CSR recognition.',
                    'image' => 'images/stall_booking/stall_brochure_3.png',
                    'tag' => 'Healthcare Sectors',
                    'icon' => 'fa-solid fa-hospital-user'
                ],
                [
                    'title' => 'Tariff Details & Sponsorship Opportunities',
                    'desc' => 'Sponsorship mileage tiers (Platinum to Associate), booth deliverables, tariff matrix & UPI QR.',
                    'image' => 'images/stall_booking/stall_brochure_4.png',
                    'tag' => 'Tariff & Mileage',
                    'icon' => 'fa-solid fa-file-invoice-dollar'
                ],
                [
                    'title' => 'Sponsorship Matrix & Stall Packages',
                    'desc' => 'Comprehensive comparison of sponsor entitlements, stage branding & delegate registration quotas.',
                    'image' => 'images/stall_booking/stall_brochure_2.png',
                    'tag' => 'Package Matrix',
                    'icon' => 'fa-solid fa-table-cells'
                ],
            ];

            $homeBrochures = [];
            if (!empty($stallSettings['stall_brochures_json'])) {
                $decoded = json_decode($stallSettings['stall_brochures_json'], true) ?: [];
                $homeBrochures = array_values(array_filter($decoded, function($b) {
                    return !empty($b['image']) && !empty($b['title']) && trim($b['title']) !== 'aaaaaaaa';
                }));
            }

            // Ensure complete official brochure deck is displayed
            if (count($homeBrochures) < 3) {
                $homeBrochures = $allOfficialBrochures;
            }

            $stallPageUrl = url('/page/stall-booking-and-merchandise');
        @endphp

        <!-- Section Header -->
        <div style="text-align: center; margin-bottom: 35px;">
            <span style="display: inline-flex; align-items: center; gap: 8px; background: #e6f7f5; color: #009688; border: 1px solid #b2dfdb; padding: 6px 20px; border-radius: 50px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 12px;">
                <i class="fa-solid fa-book-open"></i> {{ $stallSettings['stall_home_badge'] ?? 'Conference Brochures' }}
            </span>
            <h2 style="font-size: clamp(1.9rem, 3.5vw, 2.5rem); font-weight: 800; color: #0f172a; margin: 0 0 10px 0; letter-spacing: -0.5px;">
                {!! $stallSettings['stall_home_title'] ?? 'Partnership Avenues &amp; <span style="color: #009688;">Sponsorship Tariff</span>' !!}
            </h2>
            <div style="width: 60px; height: 3.5px; background: linear-gradient(90deg, #009688, #84cc16); margin: 0 auto 14px auto; border-radius: 4px;"></div>
            <p style="max-width: 680px; margin: 0 auto; color: #64748b; font-size: 1rem; line-height: 1.6; font-weight: 500;">
                {{ $stallSettings['stall_home_subtitle'] ?? 'Click any brochure to explore complete partnership avenues, tariff matrix, exhibition stall details, and sponsorship packages.' }}
            </p>
        </div>

        <style>
            .brochure-deck-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: 24px;
                margin-bottom: 30px;
            }

            .brochure-card-premium {
                background: #ffffff;
                border-radius: 14px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
                padding: 16px;
                display: flex;
                flex-direction: column;
                text-decoration: none !important;
                color: inherit;
                transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
                position: relative;
                overflow: hidden;
            }

            .brochure-card-premium:hover {
                transform: translateY(-4px);
                border-color: #00A896;
                box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.08), 0 4px 6px -2px rgba(15, 23, 42, 0.03);
            }

            .brochure-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 12px;
            }

            .brochure-category-pill {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: #f1f5f9;
                color: #0f766e;
                font-size: 0.72rem;
                font-weight: 700;
                padding: 4px 10px;
                border-radius: 6px;
                letter-spacing: 0.3px;
                text-transform: uppercase;
                border: 1px solid #e2e8f0;
                transition: all 0.2s ease;
            }

            .brochure-card-premium:hover .brochure-category-pill {
                background: #e6f7f5;
                border-color: #b2dfdb;
                color: #00897b;
            }

            .brochure-open-icon {
                width: 28px;
                height: 28px;
                border-radius: 50%;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                color: #64748b;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.72rem;
                transition: all 0.2s ease;
            }

            .brochure-card-premium:hover .brochure-open-icon {
                background: #00A896;
                border-color: #00A896;
                color: #ffffff;
                transform: rotate(45deg);
            }

            .brochure-frame {
                width: 100%;
                height: 230px;
                border-radius: 8px;
                overflow: hidden;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                position: relative;
                display: flex;
                align-items: flex-start;
                justify-content: center;
            }

            .brochure-frame-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: top center;
                image-rendering: -webkit-optimize-contrast;
                image-rendering: high-quality;
                transition: transform 0.35s ease;
                display: block;
            }

            .brochure-card-premium:hover .brochure-frame-img {
                transform: scale(1.03);
            }

            .brochure-card-body {
                padding-top: 14px;
                display: flex;
                flex-direction: column;
                flex: 1;
            }

            .brochure-heading {
                font-size: 0.96rem;
                font-weight: 700;
                color: #0f172a;
                margin: 0 0 6px 0;
                line-height: 1.4;
                transition: color 0.2s;
                min-height: 42px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .brochure-card-premium:hover .brochure-heading {
                color: #00A896;
            }

            .brochure-subtext {
                font-size: 0.8rem;
                color: #64748b;
                line-height: 1.5;
                margin: 0 0 14px 0;
                min-height: 38px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .brochure-card-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: auto;
                padding-top: 10px;
                border-top: 1px solid #f1f5f9;
                font-size: 0.84rem;
                font-weight: 700;
                color: #00A896;
            }

            .brochure-card-footer span {
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .brochure-arrow-right {
                transition: transform 0.2s ease;
            }

            .brochure-card-premium:hover .brochure-arrow-right {
                transform: translateX(4px);
            }
        </style>

        <!-- Professional Brochure Deck Grid (All linking directly to /page/stall-booking-and-merchandise) -->
        <div class="brochure-deck-grid">
            @foreach($homeBrochures as $index => $brochure)
                @php
                    $imgSrc = asset($brochure['image'] ?? 'images/stall_booking/stall_brochure_1.jpg');
                    $bTitle = $brochure['title'] ?? 'Brochure ' . ($index + 1);
                    $tag = $brochure['tag'] ?? 'Brochure #' . ($index + 1);
                    $icon = $brochure['icon'] ?? 'fa-solid fa-file-lines';
                    $desc = $brochure['desc'] ?? 'Click to explore complete brochure specifications, tariff breakdown, and exhibition stall reservations.';
                @endphp
                <a href="{{ $stallPageUrl }}" class="brochure-card-premium" title="Click to view complete details on Stall Booking & Merchandise page">
                    <!-- Top Category & Micro Action -->
                    <div class="brochure-card-header">
                        <span class="brochure-category-pill">
                            <i class="{{ $icon }}"></i> {{ $tag }}
                        </span>
                        <div class="brochure-open-icon" title="Open Page">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </div>
                    </div>

                    <!-- Clean Poster Frame -->
                    <div class="brochure-frame">
                        <img src="{{ $imgSrc }}" alt="{{ $bTitle }}" class="brochure-frame-img" loading="lazy">
                    </div>

                    <!-- Content & Summary -->
                    <div class="brochure-card-body">
                        <h4 class="brochure-heading">{{ $bTitle }}</h4>
                        <p class="brochure-subtext">{{ $desc }}</p>

                        <!-- Clean Footer Action -->
                        <div class="brochure-card-footer">
                            <span>Explore Brochure &amp; Booking</span>
                            <i class="fa-solid fa-arrow-right brochure-arrow-right"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Section Bottom Button -->
        <div style="text-align: center; margin-top: 10px;">
            <a href="{{ $stallPageUrl }}" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 12px 32px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 0.92rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(0, 168, 150, 0.28); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <i class="fa-solid fa-store"></i> Explore All Stall Booking &amp; Merchandise Details &rarr;
            </a>
        </div>

    </div>
</section>
