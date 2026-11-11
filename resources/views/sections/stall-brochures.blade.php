<!-- Conference Brochures Section (3 Pure Image Cards - Flat Clean Design) -->
<section id="conference-brochures" style="padding: 40px 0 55px; background: #ffffff; position: relative; font-family: 'Poppins', sans-serif;">
    <div class="container" style="max-width: 1220px; margin: 0 auto; padding: 0 20px;">

        @php
            $stallSettings = [];
            try {
                $stallSettings = \App\Models\SiteSetting::where('group', 'stall_booking')->pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                $stallSettings = [];
            }

            $stallPageUrl = url('/page/stall-booking-and-merchandise');

            // The 3 official conference brochures
            $threeBrochures = [
                [
                    'title' => 'Partnership Avenues for Analytical Service Providers',
                    'image' => file_exists(public_path('images/stall_booking/stall_brochure_1.jpg')) ? 'images/stall_booking/stall_brochure_1.jpg' : 'images/stall_booking/stall_brochure_1.png',
                ],
                [
                    'title' => 'Partnership Avenues for Hospitals & Health Sectors',
                    'image' => 'images/stall_booking/stall_brochure_3.png',
                ],
                [
                    'title' => 'Tariff Details & Sponsorship Opportunities',
                    'image' => 'images/stall_booking/stall_brochure_4.png',
                ],
            ];
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
            .brochure-trio-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 24px;
                margin-bottom: 30px;
            }

            @media (max-width: 880px) {
                .brochure-trio-grid {
                    grid-template-columns: 1fr;
                }
            }

            .brochure-img-card {
                background: #ffffff;
                border-radius: 14px;
                border: 1.5px solid #e2e8f0;
                box-shadow: none !important;
                padding: 12px;
                display: flex;
                flex-direction: column;
                text-decoration: none !important;
                color: inherit;
                transition: transform 0.25s ease, border-color 0.25s ease;
                overflow: hidden;
                cursor: pointer;
            }

            .brochure-img-card:hover {
                transform: translateY(-4px);
                border-color: #00A896;
                box-shadow: none !important;
            }

            .brochure-img-frame {
                width: 100%;
                height: 380px;
                border-radius: 10px;
                overflow: hidden;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: none !important;
            }

            .brochure-main-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: top center;
                image-rendering: -webkit-optimize-contrast;
                image-rendering: high-quality;
                transition: transform 0.35s ease;
                display: block;
            }

            .brochure-img-card:hover .brochure-main-img {
                transform: scale(1.02);
            }

            .brochure-title-bar {
                padding: 12px 6px 4px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                font-size: 0.95rem;
                font-weight: 700;
                color: #0f172a;
                transition: color 0.2s;
            }

            .brochure-title-bar span {
                line-height: 1.4;
            }

            .brochure-title-bar i {
                color: #00A896;
                transition: transform 0.2s;
                flex-shrink: 0;
            }

            .brochure-img-card:hover .brochure-title-bar {
                color: #00A896;
            }

            .brochure-img-card:hover .brochure-title-bar i {
                transform: translateX(4px);
            }
        </style>

        <!-- 3 Pure Image Brochure Cards (Clean Flat Design, Zero Shadow, Zero Dark Overlay) -->
        <div class="brochure-trio-grid">
            @foreach($threeBrochures as $index => $b)
                @php
                    $imgSrc = asset($b['image']);
                    $bTitle = $b['title'];
                @endphp
                <a href="{{ $stallPageUrl }}" class="brochure-img-card" title="Click to view {{ $bTitle }}">
                    <div class="brochure-img-frame">
                        <img src="{{ $imgSrc }}" alt="{{ $bTitle }}" class="brochure-main-img" loading="lazy">
                    </div>
                    <div class="brochure-title-bar">
                        <span>{{ $bTitle }}</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Section Bottom Button -->
        <div style="text-align: center; margin-top: 10px;">
            <a href="{{ $stallPageUrl }}" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 12px 32px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 0.92rem; display: inline-flex; align-items: center; gap: 8px; border: none; box-shadow: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <i class="fa-solid fa-store"></i> Explore Full Stall Booking &amp; Merchandise Details &rarr;
            </a>
        </div>

    </div>
</section>
