@php
    $stallSettings = [];
    try {
        $stallSettings = \App\Models\SiteSetting::where('group', 'stall_booking')->pluck('value', 'key')->toArray();
    } catch (\Throwable $e) {
        $stallSettings = [];
    }

    $isSectionVisible = ($stallSettings['stall_home_show'] ?? '1') == '1';
    $sectionHeading = $stallSettings['stall_home_title'] ?? 'Conference <span style="color: #009688;">Brochures</span>';
    $stallPageUrl = !empty($stallSettings['stall_home_btn_url']) ? url($stallSettings['stall_home_btn_url']) : url('/page/stall-booking-details');
    $stallBtnLabel = $stallSettings['stall_home_btn_label'] ?? 'Explore Full Stall Booking & Merchandise Details &rarr;';
    $showBtn = ($stallSettings['stall_home_btn_show'] ?? '1') == '1';

    $homeBrochures = [];
    if (!empty($stallSettings['stall_home_brochures_json'])) {
        $homeBrochures = json_decode($stallSettings['stall_home_brochures_json'], true) ?: [];
    }

    if (empty($homeBrochures)) {
        $homeBrochures = [
            [
                'title' => 'Partnership Avenues for Analytical Service Providers',
                'image' => file_exists(public_path('images/stall_booking/stall_brochure_1.png')) ? 'images/stall_booking/stall_brochure_1.png' : 'images/stall_booking/stall_brochure_1.jpg',
                'url' => $stallPageUrl,
            ],
            [
                'title' => 'Partnership Avenues for Hospitals & Health Sectors',
                'image' => 'images/stall_booking/stall_brochure_3.png',
                'url' => $stallPageUrl,
            ],
            [
                'title' => 'Tariff Details & Sponsorship Opportunities',
                'image' => 'images/stall_booking/stall_brochure_4.png',
                'url' => $stallPageUrl,
            ],
        ];
    }
@endphp

@if($isSectionVisible)
<!-- Conference Brochures Section (100% Pure Image Cards - Dynamic Admin Powered) -->
<section id="conference-brochures" style="padding: 35px 0 55px; background: #ffffff; position: relative; font-family: 'Poppins', sans-serif;">
    <div class="container" style="max-width: 1220px; margin: 0 auto; padding: 0 20px;">

        <!-- Section Header (Only Heading) -->
        <div style="text-align: center; margin-bottom: 35px;">
            <h2 style="font-size: clamp(2rem, 3.8vw, 2.7rem); font-weight: 800; color: #0f172a; margin: 0 0 12px 0; letter-spacing: -0.5px;">
                {!! $sectionHeading !!}
            </h2>
            <div style="width: 65px; height: 3.5px; background: linear-gradient(90deg, #009688, #84cc16); margin: 0 auto; border-radius: 4px;"></div>
        </div>

        <style>
            .brochure-trio-grid {
                display: grid;
                grid-template-columns: repeat({{ count($homeBrochures) > 0 ? min(count($homeBrochures), 3) : 3 }}, 1fr);
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
                padding: 8px;
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
                border-radius: 10px;
                overflow: hidden;
                background: #ffffff;
                border: 1px solid #e2e8f0;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: none !important;
            }

            .brochure-main-img {
                width: 100%;
                height: auto;
                object-fit: contain;
                image-rendering: -webkit-optimize-contrast;
                image-rendering: high-quality;
                transition: transform 0.35s ease;
                display: block;
            }

            .brochure-img-card:hover .brochure-main-img {
                transform: scale(1.02);
            }
        </style>

        <!-- Dynamic Pure Image Brochure Cards (100% Image Only, Zero Text, Zero Shadow) -->
        <div class="brochure-trio-grid">
            @foreach($homeBrochures as $index => $b)
                @php
                    $imgSrc = asset($b['image'] ?? 'images/stall_booking/stall_brochure_1.png');
                    $bTitle = $b['title'] ?? 'Conference Brochure';
                    $targetUrl = !empty($b['url']) ? (str_starts_with($b['url'], 'http') ? $b['url'] : url($b['url'])) : $stallPageUrl;
                @endphp
                <a href="{{ $targetUrl }}" class="brochure-img-card" title="{{ $bTitle }}">
                    <div class="brochure-img-frame">
                        <img src="{{ $imgSrc }}" alt="{{ $bTitle }}" class="brochure-main-img" loading="lazy">
                    </div>
                </a>
            @endforeach
        </div>

        @if($showBtn)
        <!-- Section Bottom Button -->
        <div style="text-align: center; margin-top: 10px;">
            <a href="{{ $stallPageUrl }}" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 12px 32px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 0.92rem; display: inline-flex; align-items: center; gap: 8px; border: none; box-shadow: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <i class="fa-solid fa-store"></i> {!! $stallBtnLabel !!}
            </a>
        </div>
        @endif

    </div>
</section>
@endif
