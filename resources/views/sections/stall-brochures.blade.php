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
    $stallBtnLabel = $stallSettings['stall_home_btn_label'] ?? 'Explore Conference Brochures &rarr;';
    if (empty($stallSettings['stall_home_btn_label']) || str_contains($stallSettings['stall_home_btn_label'], 'Stall Booking')) {
        $stallBtnLabel = 'Explore Conference Brochures &rarr;';
    }
    $showBtn = ($stallSettings['stall_home_btn_show'] ?? '1') == '1';

    $homeBrochures = [];
    if (!empty($stallSettings['stall_home_brochures_json'])) {
        $homeBrochures = json_decode($stallSettings['stall_home_brochures_json'], true) ?: [];
    }

    // Keep brochure list empty if none uploaded
    if (empty($homeBrochures)) {
        $homeBrochures = [];
    }
@endphp

@if($isSectionVisible)
<!-- Conference Brochures Section (3-Card Swipe Slider - Dynamic Admin Powered) -->
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
            .brochure-carousel-wrapper {
                position: relative;
                max-width: 1180px;
                margin: 0 auto 30px auto;
            }

            .brochures-track {
                display: flex;
                gap: 24px;
                overflow-x: auto;
                scroll-behavior: smooth;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                -ms-overflow-style: none;
                padding: 10px 4px 18px 4px;
            }

            .brochures-track::-webkit-scrollbar {
                display: none;
            }

            .brochure-slide-item {
                flex: 0 0 calc((100% - 48px) / 3);
                min-width: 0;
                scroll-snap-align: start;
                box-sizing: border-box;
            }

            @media (max-width: 991px) {
                .brochure-slide-item {
                    flex: 0 0 calc((100% - 20px) / 2);
                }
                .brochures-track {
                    gap: 20px;
                }
            }

            @media (max-width: 640px) {
                .brochure-slide-item {
                    flex: 0 0 85%;
                }
                .brochures-track {
                    gap: 16px;
                }
            }

            /* Exact pure image card design - zero change to styling */
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
                transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
                overflow: hidden;
                cursor: pointer;
                height: 100%;
                box-sizing: border-box;
            }

            .brochure-img-card:hover {
                transform: translateY(-4px);
                border-color: #00A896;
                box-shadow: 0 8px 24px rgba(0, 168, 150, 0.12) !important;
            }

            .brochure-img-frame {
                width: 100%;
                border-radius: 10px;
                overflow: hidden;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: none !important;
                aspect-ratio: 1 / 1.414;
                min-height: 380px;
            }

            .brochure-main-img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                image-rendering: -webkit-optimize-contrast;
                image-rendering: high-quality;
                transition: transform 0.35s ease;
                display: block;
            }

            .brochure-img-card:hover .brochure-main-img {
                transform: scale(1.02);
            }

            /* Carousel Navigation Arrow Buttons matching Sample Image */
            .brochure-nav-btn {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 46px;
                height: 46px;
                border-radius: 50%;
                background: #ffffff;
                border: 1.5px solid #e2e8f0;
                color: #028090;
                box-shadow: 0 4px 16px rgba(15, 23, 42, 0.12);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.05rem;
                cursor: pointer;
                z-index: 10;
                transition: all 0.25s ease;
                outline: none;
            }

            .brochure-nav-btn:hover {
                background: #00A896;
                color: #ffffff;
                border-color: #00A896;
                box-shadow: 0 6px 20px rgba(0, 168, 150, 0.35);
                transform: translateY(-50%) scale(1.08);
            }

            .brochure-nav-btn:active {
                transform: translateY(-50%) scale(0.96);
            }

            .brochure-prev-btn {
                left: -22px;
            }

            .brochure-next-btn {
                right: -22px;
            }

            @media (max-width: 1250px) {
                .brochure-prev-btn {
                    left: -12px;
                }
                .brochure-next-btn {
                    right: -12px;
                }
            }

            @media (max-width: 640px) {
                .brochure-img-frame {
                    min-height: 280px;
                }
                .brochure-prev-btn {
                    left: -6px;
                    width: 38px;
                    height: 38px;
                    font-size: 0.9rem;
                }
                .brochure-next-btn {
                    right: -6px;
                    width: 38px;
                    height: 38px;
                    font-size: 0.9rem;
                }
            }
        </style>

        <!-- Brochure Slider / Card Container -->
        <div class="brochure-carousel-wrapper">

            @if(count($homeBrochures) > 1)
            <!-- Left Navigation Arrow Button -->
            <button type="button" class="brochure-nav-btn brochure-prev-btn" aria-label="Previous Brochures" onclick="slideBrochures(-1)">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            @endif

            <!-- Scrollable / Swipeable Track -->
            <div id="brochuresTrack" class="brochures-track" style="{{ count($homeBrochures) <= 1 ? 'justify-content: center;' : '' }}">
                @forelse($homeBrochures as $index => $b)
                    @php
                        $rawPath = $b['image'] ?? '';
                        if (empty($rawPath)) continue;
                        if (str_starts_with($rawPath, 'http://')) {
                            $imgSrc = 'https://' . substr($rawPath, 7);
                        } elseif (str_starts_with($rawPath, 'https://')) {
                            $imgSrc = $rawPath;
                        } else {
                            $cleanPath = ltrim($rawPath, '/');
                            if (str_starts_with($cleanPath, 'storage/')) {
                                $imgSrc = asset($cleanPath);
                            } elseif (preg_match('/^\d+_/', basename($cleanPath)) || file_exists(storage_path('app/public/' . $cleanPath))) {
                                $imgSrc = asset('storage/' . $cleanPath);
                            } else {
                                $imgSrc = asset($cleanPath);
                            }
                            if (!app()->environment('local') && str_starts_with($imgSrc, 'http://')) {
                                $imgSrc = 'https://' . substr($imgSrc, 7);
                            }
                        }
                        $bTitle = $b['title'] ?? 'Conference Brochure';
                        $targetUrl = !empty($b['url']) ? (str_starts_with($b['url'], 'http') ? $b['url'] : url($b['url'])) : $stallPageUrl;
                    @endphp
                    <div class="brochure-slide-item" style="flex: 0 0 min(100%, 460px); max-width: 460px; margin: 0 auto;">
                        <a href="{{ $targetUrl }}" class="brochure-img-card" title="{{ $bTitle }}">
                            <div class="brochure-img-frame">
                                <img src="{{ $imgSrc }}" 
                                     alt="{{ $bTitle }}" 
                                     class="brochure-main-img" 
                                     loading="lazy"
                                     onerror="if (!this.dataset.triedStorage && this.src.indexOf('/storage/') === -1) { this.dataset.triedStorage = '1'; this.src = this.src.replace('/images/', '/storage/images/'); }">
                            </div>
                        </a>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px 20px; color: #64748b; font-size: 0.95rem; width: 100%;">
                        <i class="fa-regular fa-image" style="font-size: 2.2rem; color: #94a3b8; display: block; margin-bottom: 10px;"></i>
                        Brochure will be updated soon.
                    </div>
                @endforelse
            </div>

            @if(count($homeBrochures) > 1)
            <!-- Right Navigation Arrow Button -->
            <button type="button" class="brochure-nav-btn brochure-next-btn" aria-label="Next Brochures" onclick="slideBrochures(1)">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
            @endif

        </div>

        @if($showBtn)
        <!-- Section Bottom Button -->
        <div style="text-align: center; margin-top: 15px;">
            <a href="{{ $stallPageUrl }}" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 12px 32px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 0.92rem; display: inline-flex; align-items: center; gap: 8px; border: none; box-shadow: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                {!! $stallBtnLabel !!}
            </a>
        </div>
        @endif

    </div>
</section>

<script>
    function slideBrochures(direction) {
        const track = document.getElementById('brochuresTrack');
        if (!track) return;
        
        const firstCard = track.querySelector('.brochure-slide-item');
        if (!firstCard) return;

        const gap = parseInt(window.getComputedStyle(track).gap) || 24;
        const scrollAmount = (firstCard.offsetWidth + gap) * direction;
        
        track.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('brochuresTrack');
        const prevBtn = document.querySelector('.brochure-prev-btn');
        const nextBtn = document.querySelector('.brochure-next-btn');

        if (!track) return;

        function updateArrowState() {
            if (!prevBtn || !nextBtn) return;
            const maxScroll = track.scrollWidth - track.clientWidth - 5;
            
            if (track.scrollWidth <= track.clientWidth + 5) {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            } else {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';
                prevBtn.style.opacity = track.scrollLeft <= 5 ? '0.45' : '1';
                prevBtn.style.pointerEvents = track.scrollLeft <= 5 ? 'none' : 'auto';
                nextBtn.style.opacity = track.scrollLeft >= maxScroll ? '0.45' : '1';
                nextBtn.style.pointerEvents = track.scrollLeft >= maxScroll ? 'none' : 'auto';
            }
        }

        track.addEventListener('scroll', updateArrowState, { passive: true });
        window.addEventListener('resize', updateArrowState);
        updateArrowState();

        // Mouse drag swipe support
        let isDown = false;
        let startX;
        let scrollLeft;

        track.addEventListener('mousedown', (e) => {
            isDown = true;
            track.style.cursor = 'grabbing';
            track.style.scrollBehavior = 'auto';
            startX = e.pageX - track.offsetLeft;
            scrollLeft = track.scrollLeft;
        });

        track.addEventListener('mouseleave', () => {
            isDown = false;
            track.style.cursor = '';
            track.style.scrollBehavior = 'smooth';
        });

        track.addEventListener('mouseup', () => {
            isDown = false;
            track.style.cursor = '';
            track.style.scrollBehavior = 'smooth';
        });

        track.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - track.offsetLeft;
            const walk = (x - startX) * 1.5;
            track.scrollLeft = scrollLeft - walk;
        });
    });
</script>
@endif
