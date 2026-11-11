<!-- Conference Brochures & Partnership Section (Two Professional Executive Cards) -->
<section id="conference-brochures" style="padding: 45px 0 60px; background: #ffffff; position: relative; font-family: 'Poppins', sans-serif;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

        @php
            $stallSettings = [];
            try {
                $stallSettings = \App\Models\SiteSetting::where('group', 'stall_booking')->pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                $stallSettings = [];
            }

            $stallPageUrl = url('/page/stall-booking-and-merchandise');

            $card1_img1 = asset(file_exists(public_path('images/stall_booking/stall_brochure_1.jpg')) ? 'images/stall_booking/stall_brochure_1.jpg' : 'images/stall_booking/stall_brochure_1.png');
            $card1_img2 = asset('images/stall_booking/stall_brochure_3.png');

            $card2_img1 = asset('images/stall_booking/stall_brochure_4.png');
            $card2_img2 = asset('images/stall_booking/stall_brochure_2.png');
        @endphp

        <!-- Section Header -->
        <div style="text-align: center; margin-bottom: 40px;">
            <span style="display: inline-flex; align-items: center; gap: 8px; background: #e6f7f5; color: #009688; border: 1px solid #b2dfdb; padding: 6px 20px; border-radius: 50px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 12px;">
                <i class="fa-solid fa-book-open"></i> {{ $stallSettings['stall_home_badge'] ?? 'Conference Brochures' }}
            </span>
            <h2 style="font-size: clamp(2rem, 3.8vw, 2.7rem); font-weight: 800; color: #0f172a; margin: 0 0 10px 0; letter-spacing: -0.5px;">
                {!! $stallSettings['stall_home_title'] ?? 'Partnership Avenues &amp; <span style="color: #009688;">Sponsorship Tariff</span>' !!}
            </h2>
            <div style="width: 60px; height: 3.5px; background: linear-gradient(90deg, #009688, #84cc16); margin: 0 auto 14px auto; border-radius: 4px;"></div>
            <p style="max-width: 720px; margin: 0 auto; color: #64748b; font-size: 1.05rem; line-height: 1.6; font-weight: 500;">
                {{ $stallSettings['stall_home_subtitle'] ?? 'Explore our complete partnership avenues, tariff matrix, exhibition stall details, and sponsorship packages.' }}
            </p>
        </div>

        <style>
            .brochure-duo-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
                margin-bottom: 35px;
            }

            @media (max-width: 900px) {
                .brochure-duo-grid {
                    grid-template-columns: 1fr;
                }
            }

            .brochure-duo-card {
                background: #ffffff;
                border-radius: 18px;
                border: 1.5px solid #e2e8f0;
                box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
                padding: 24px;
                display: flex;
                flex-direction: column;
                text-decoration: none !important;
                color: inherit;
                transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
                cursor: pointer;
                position: relative;
                overflow: hidden;
            }

            .brochure-duo-card:hover {
                transform: translateY(-5px);
                border-color: #00A896;
                box-shadow: 0 14px 30px -4px rgba(15, 23, 42, 0.1);
            }

            .brochure-duo-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 16px;
                flex-wrap: wrap;
                gap: 10px;
            }

            .brochure-main-tag {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: #e6f7f5;
                color: #00897b;
                font-size: 0.78rem;
                font-weight: 800;
                padding: 5px 12px;
                border-radius: 8px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                border: 1px solid #b2dfdb;
            }

            .brochure-switch-tabs {
                display: inline-flex;
                background: #f1f5f9;
                padding: 3px;
                border-radius: 8px;
                gap: 4px;
            }

            .brochure-tab-btn {
                background: transparent;
                border: none;
                font-size: 0.75rem;
                font-weight: 700;
                color: #64748b;
                padding: 4px 10px;
                border-radius: 6px;
                cursor: pointer;
                transition: all 0.2s;
            }

            .brochure-tab-btn.active {
                background: #ffffff;
                color: #0f172a;
                box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            }

            .brochure-duo-window {
                width: 100%;
                height: 330px;
                border-radius: 12px;
                overflow: hidden;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                position: relative;
                display: flex;
                align-items: flex-start;
                justify-content: center;
                margin-bottom: 20px;
            }

            .brochure-duo-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: top center;
                image-rendering: -webkit-optimize-contrast;
                image-rendering: high-quality;
                transition: transform 0.35s ease, opacity 0.25s ease;
                display: block;
            }

            .brochure-duo-card:hover .brochure-duo-img {
                transform: scale(1.025);
            }

            .brochure-duo-title {
                font-size: 1.2rem;
                font-weight: 800;
                color: #0f172a;
                margin: 0 0 8px 0;
                line-height: 1.4;
                transition: color 0.2s;
            }

            .brochure-duo-card:hover .brochure-duo-title {
                color: #00A896;
            }

            .brochure-duo-desc {
                font-size: 0.9rem;
                color: #64748b;
                line-height: 1.6;
                margin: 0 0 16px 0;
            }

            .brochure-feature-list {
                list-style: none;
                padding: 0;
                margin: 0 0 20px 0;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .brochure-feature-item {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 0.85rem;
                color: #334155;
                font-weight: 500;
            }

            .brochure-feature-item i {
                color: #00A896;
                font-size: 0.9rem;
            }

            .brochure-duo-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: auto;
                padding-top: 16px;
                border-top: 1px solid #f1f5f9;
            }

            .brochure-cta-btn {
                background: linear-gradient(135deg, #00A896, #028090);
                color: #ffffff !important;
                font-size: 0.88rem;
                font-weight: 700;
                padding: 9px 20px;
                border-radius: 8px;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.2s ease;
                box-shadow: 0 2px 8px rgba(0, 168, 150, 0.2);
            }

            .brochure-duo-card:hover .brochure-cta-btn {
                transform: translateX(3px);
                box-shadow: 0 4px 14px rgba(0, 168, 150, 0.35);
            }

            .brochure-badge-meta {
                font-size: 0.8rem;
                color: #64748b;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
        </style>

        <div class="brochure-duo-grid">
            
            <!-- Card 1: Partnership Avenues (Analytical & Hospitals) -->
            <a href="{{ $stallPageUrl }}" class="brochure-duo-card" title="Click to view Partnership Avenues">
                <div class="brochure-duo-header">
                    <span class="brochure-main-tag">
                        <i class="fa-solid fa-handshake"></i> Partnership Avenues
                    </span>
                    <div class="brochure-switch-tabs" onclick="event.preventDefault(); event.stopPropagation();">
                        <button type="button" class="brochure-tab-btn active" id="tab_card1_btn1" onclick="switchBrochureDuo(1, 1, '{{ $card1_img1 }}')">
                            Analytical
                        </button>
                        <button type="button" class="brochure-tab-btn" id="tab_card1_btn2" onclick="switchBrochureDuo(1, 2, '{{ $card1_img2 }}')">
                            Hospitals
                        </button>
                    </div>
                </div>

                <div class="brochure-duo-window">
                    <img id="card1_img" src="{{ $card1_img1 }}" alt="Partnership Avenues Brochure" class="brochure-duo-img" loading="lazy">
                </div>

                <div style="flex: 1; display: flex; flex-direction: column;">
                    <h3 class="brochure-duo-title">Partnership Avenues for Industry &amp; Healthcare</h3>
                    <p class="brochure-duo-desc">
                        Explore dedicated exhibition stalls, clinical &amp; analytical technology showcases, and corporate sponsorship engagement.
                    </p>

                    <ul class="brochure-feature-list">
                        <li class="brochure-feature-item">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Analytical Instrument Showcases &amp; Academic B2B Connect</span>
                        </li>
                        <li class="brochure-feature-item">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Hospitals, Diagnostic Labs &amp; Healthcare Sector Visibility</span>
                        </li>
                        <li class="brochure-feature-item">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Digital Promotions Across MCC Campus &amp; Conference Kits</span>
                        </li>
                    </ul>

                    <div class="brochure-duo-footer">
                        <span class="brochure-badge-meta">
                            <i class="fa-solid fa-file-pdf" style="color: #00A896;"></i> 2 Partnership Decks
                        </span>
                        <div class="brochure-cta-btn">
                            <span>Explore Brochure &amp; Booking</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Card 2: Tariff Details & Sponsorship Opportunities -->
            <a href="{{ $stallPageUrl }}" class="brochure-duo-card" title="Click to view Tariff Details & Sponsorship">
                <div class="brochure-duo-header">
                    <span class="brochure-main-tag">
                        <i class="fa-solid fa-receipt"></i> Tariff &amp; Sponsorship
                    </span>
                    <div class="brochure-switch-tabs" onclick="event.preventDefault(); event.stopPropagation();">
                        <button type="button" class="brochure-tab-btn active" id="tab_card2_btn1" onclick="switchBrochureDuo(2, 1, '{{ $card2_img1 }}')">
                            Tariff &amp; QR
                        </button>
                        <button type="button" class="brochure-tab-btn" id="tab_card2_btn2" onclick="switchBrochureDuo(2, 2, '{{ $card2_img2 }}')">
                            Matrix
                        </button>
                    </div>
                </div>

                <div class="brochure-duo-window">
                    <img id="card2_img" src="{{ $card2_img1 }}" alt="Tariff Details & Sponsorship Opportunities" class="brochure-duo-img" loading="lazy">
                </div>

                <div style="flex: 1; display: flex; flex-direction: column;">
                    <h3 class="brochure-duo-title">Tariff Details &amp; Sponsorship Opportunities</h3>
                    <p class="brochure-duo-desc">
                        Complete breakdown of sponsor mileage packages, exhibition booth tariffs, and instant online payment options.
                    </p>

                    <ul class="brochure-feature-list">
                        <li class="brochure-feature-item">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Sponsor Mileage Tiers: Platinum, Gold, Silver, Bronze &amp; Associate</span>
                        </li>
                        <li class="brochure-feature-item">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Exhibition Stalls (10x10), Banners &amp; Session Sponsorship</span>
                        </li>
                        <li class="brochure-feature-item">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Instant UPI QR Code Payment &amp; Online PayU Link</span>
                        </li>
                    </ul>

                    <div class="brochure-duo-footer">
                        <span class="brochure-badge-meta">
                            <i class="fa-solid fa-qrcode" style="color: #00A896;"></i> Tariff &amp; Payment QR
                        </span>
                        <div class="brochure-cta-btn">
                            <span>Explore Tariff &amp; Booking</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </a>

        </div>

        <!-- Section Bottom Button -->
        <div style="text-align: center; margin-top: 10px;">
            <a href="{{ $stallPageUrl }}" style="background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; padding: 12px 32px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 0.92rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: all 0.2s;" onmouseover="this.style.borderColor='#00A896'; this.style.color='#00A896';" onmouseout="this.style.borderColor='#cbd5e1'; this.style.color='#0f172a';">
                <i class="fa-solid fa-store" style="color: #00A896;"></i> Explore Full Stall Booking &amp; Merchandise Page &rarr;
            </a>
        </div>

    </div>
</section>

<script>
    function switchBrochureDuo(cardNum, tabNum, imgSrc) {
        const img = document.getElementById('card' + cardNum + '_img');
        if (img) {
            img.style.opacity = '0.4';
            setTimeout(function() {
                img.src = imgSrc;
                img.style.opacity = '1';
            }, 120);
        }
        const btn1 = document.getElementById('tab_card' + cardNum + '_btn1');
        const btn2 = document.getElementById('tab_card' + cardNum + '_btn2');
        if (btn1 && btn2) {
            if (tabNum === 1) {
                btn1.classList.add('active');
                btn2.classList.remove('active');
            } else {
                btn2.classList.add('active');
                btn1.classList.remove('active');
            }
        }
    }
</script>
