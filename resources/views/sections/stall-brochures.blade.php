<!-- Conference Brochures & Partnership Section (Compact Cards) -->
<section id="conference-brochures" style="padding: 40px 0 55px; background: #ffffff; position: relative; font-family: 'Poppins', sans-serif;">
    <div class="container" style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">

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
            .brochure-small-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 340px));
                gap: 24px;
                justify-content: center;
                margin-bottom: 25px;
            }

            .brochure-small-card {
                background: #ffffff;
                border-radius: 16px;
                border: 1.5px solid #e2e8f0;
                box-shadow: 0 6px 20px rgba(15, 23, 42, 0.05);
                padding: 16px;
                display: flex;
                flex-direction: column;
                text-decoration: none !important;
                color: inherit;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                position: relative;
                overflow: hidden;
            }

            .brochure-small-card:hover {
                transform: translateY(-6px);
                border-color: #00A896;
                box-shadow: 0 16px 35px rgba(0, 168, 150, 0.18);
            }

            .brochure-thumb-wrap {
                width: 100%;
                height: 290px;
                border-radius: 12px;
                overflow: hidden;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                position: relative;
                display: flex;
                align-items: flex-start;
                justify-content: center;
            }

            .brochure-thumb-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: top center;
                image-rendering: -webkit-optimize-contrast;
                image-rendering: high-quality;
                transition: transform 0.4s ease;
                display: block;
            }

            .brochure-small-card:hover .brochure-thumb-img {
                transform: scale(1.04);
            }

            .brochure-hover-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(15, 23, 42, 0.05) 0%, rgba(15, 23, 42, 0.75) 100%);
                opacity: 0;
                transition: opacity 0.3s ease;
                display: flex;
                align-items: flex-end;
                justify-content: center;
                padding-bottom: 16px;
            }

            .brochure-small-card:hover .brochure-hover-overlay {
                opacity: 1;
            }

            .brochure-hover-badge {
                background: #00A896;
                color: #ffffff;
                font-size: 0.82rem;
                font-weight: 700;
                padding: 7px 16px;
                border-radius: 20px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            }

            .brochure-info-box {
                padding-top: 14px;
                display: flex;
                flex-direction: column;
                flex: 1;
            }

            .brochure-card-title {
                font-size: 1.02rem;
                font-weight: 700;
                color: #0f172a;
                margin: 0 0 10px 0;
                line-height: 1.45;
                transition: color 0.2s;
            }

            .brochure-small-card:hover .brochure-card-title {
                color: #00A896;
            }

            .brochure-card-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: auto;
                padding-top: 12px;
                border-top: 1px solid #f1f5f9;
            }

            .brochure-action-link {
                color: #00A896;
                font-weight: 700;
                font-size: 0.88rem;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .brochure-tag-badge {
                background: #e6f7f5;
                color: #009688;
                font-size: 0.74rem;
                font-weight: 700;
                padding: 4px 10px;
                border-radius: 20px;
            }
        </style>

        <!-- Small Size Cards Grid (Each clicks directly to /page/stall-booking-and-merchandise) -->
        <div class="brochure-small-grid">
            @foreach($homeBrochures as $index => $brochure)
                @php
                    $imgSrc = asset($brochure['image'] ?? 'images/stall_booking/stall_brochure_1.jpg');
                    $bTitle = $brochure['title'] ?? 'Brochure ' . ($index + 1);
                @endphp
                <a href="{{ $stallPageUrl }}" class="brochure-small-card" title="Click to view complete details on Stall Booking & Merchandise page">
                    <div class="brochure-thumb-wrap">
                        <img src="{{ $imgSrc }}" alt="{{ $bTitle }}" class="brochure-thumb-img" loading="lazy">
                        <div class="brochure-hover-overlay">
                            <span class="brochure-hover-badge">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Open Full Brochure
                            </span>
                        </div>
                    </div>
                    <div class="brochure-info-box">
                        <h4 class="brochure-card-title">{{ $bTitle }}</h4>
                        <div class="brochure-card-footer">
                            <span class="brochure-action-link">
                                View Details &amp; Booking <i class="fa-solid fa-arrow-right"></i>
                            </span>
                            <span class="brochure-tag-badge">
                                Brochure #{{ $index + 1 }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Section Bottom Button -->
        <div style="text-align: center; margin-top: 15px;">
            <a href="{{ $stallPageUrl }}" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 12px 30px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 0.92rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(0, 168, 150, 0.28); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <i class="fa-solid fa-store"></i> Explore All Stall Booking &amp; Merchandise Details &rarr;
            </a>
        </div>

    </div>
</section>
