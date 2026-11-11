<!-- Conference Brochures Section (100% Pure Image Cards - No Text) -->
<section id="conference-brochures" style="padding: 15px 0 45px; background: #ffffff; position: relative; font-family: 'Poppins', sans-serif;">
    <div class="container" style="max-width: 1220px; margin: 0 auto; padding: 0 20px;">

        @php
            $stallPageUrl = url('/page/stall-booking-and-merchandise');

            // The 3 official conference brochures (Master High-Resolution)
            $threeBrochures = [
                [
                    'title' => 'Partnership Avenues for Analytical Service Providers',
                    'image' => file_exists(public_path('images/stall_booking/stall_brochure_1.png')) ? 'images/stall_booking/stall_brochure_1.png' : 'images/stall_booking/stall_brochure_1.jpg',
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

        <!-- 3 Pure Image Brochure Cards (100% Image Only, Zero Text, Zero Shadow) -->
        <div class="brochure-trio-grid">
            @foreach($threeBrochures as $index => $b)
                @php
                    $imgSrc = asset($b['image']);
                    $bTitle = $b['title'];
                @endphp
                <a href="{{ $stallPageUrl }}" class="brochure-img-card" title="{{ $bTitle }}">
                    <div class="brochure-img-frame">
                        <img src="{{ $imgSrc }}" alt="{{ $bTitle }}" class="brochure-main-img" loading="lazy">
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
