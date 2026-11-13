@extends('layouts.admin_cms')

@section('header_title', 'Stall Booking Details & Conference Brochures CMS')

@section('content')
<style>
    .details-admin-wrap {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .admin-card-section {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .form-label {
        display: block;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
        font-size: 0.92rem;
    }

    .form-input {
        width: 100%;
        padding: 11px 15px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.95rem;
        color: #0f172a;
        font-family: inherit;
        transition: border-color 0.2s;
    }

    .form-input:focus {
        border-color: #00A896;
        outline: none;
    }

    .item-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    }

    .item-card:hover {
        border-color: #cbd5e1;
        background: #ffffff;
    }

    .section-title {
        margin: 0 0 18px 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 12px;
    }

    .btn-add-item {
        background: #f1f5f9;
        color: #0f172a;
        border: 1.5px dashed #94a3b8;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-add-item:hover {
        background: #e6f7f5;
        color: #00A896;
        border-color: #00A896;
    }
</style>

@php
    $sectionHeading = $settings['stall_home_title'] ?? 'Conference <span style="color: #009688;">Brochures</span>';
    $isSectionVisible = ($settings['stall_home_show'] ?? '1') == '1';
    $btnLabel = $settings['stall_home_btn_label'] ?? 'Explore Conference Brochures &rarr;';
    if (empty($settings['stall_home_btn_label']) || str_contains($settings['stall_home_btn_label'], 'Stall Booking')) {
        $btnLabel = 'Explore Conference Brochures &rarr;';
    }
    $btnUrl = $settings['stall_home_btn_url'] ?? '/page/stall-booking-details';
    $showBtn = ($settings['stall_home_btn_show'] ?? '1') == '1';
@endphp

<div class="details-admin-wrap">

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="font-size: 1.7rem; font-weight: 800; color: #0a192f; margin: 0 0 4px 0;">
                <i class="fa-solid fa-file-lines" style="color: #00A896; margin-right: 8px;"></i> Stall Booking Details CMS
            </h2>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Manage the 3 conference brochures, banners, payment QR, and display settings.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="/page/stall-booking-details" target="_blank" style="background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; padding: 11px 20px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> View Live Details Page
            </a>
            <a href="/#conference-brochures" target="_blank" style="background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; padding: 11px 20px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-house"></i> View on Home Page
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.3rem;"></i> {{ session('success') }}
        </div>
    @endif

    <!-- 1. LIVE VISUAL PREVIEW OF BROCHURES -->
    <div style="background: #ffffff; border-radius: 20px; border: 2px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 35px rgba(15, 23, 42, 0.05); position: relative; padding: 35px 25px;">
        <div style="position: absolute; top: 15px; right: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #00A896; background: #e6f7f5; padding: 5px 14px; border-radius: 20px; border: 1px solid #b2dfdb; z-index: 10;">
            <i class="fa-solid fa-eye"></i> Live Brochures Showcase Preview
        </div>

        <div style="text-align: center; margin-bottom: 25px;">
            <h2 style="font-size: 2.1rem; font-weight: 800; color: #0f172a; margin: 0 0 10px 0;">
                {!! $sectionHeading !!}
            </h2>
            <div style="width: 65px; height: 3.5px; background: linear-gradient(90deg, #009688, #84cc16); margin: 0 auto; border-radius: 4px;"></div>
        </div>

        <div style="display: grid; grid-template-columns: repeat({{ count($homeBrochures) > 0 ? min(count($homeBrochures), 3) : 3 }}, 1fr); gap: 20px; max-width: 950px; margin: 0 auto 20px auto;">
            @foreach($homeBrochures as $idx => $hb)
                @php
                    $hbRaw = $hb['image'] ?? 'images/stall_booking/stall_brochure_1.png';
                    $cleanHb = ltrim($hbRaw, '/');
                    if (str_starts_with($cleanHb, 'storage/')) {
                        $hbImg = asset($cleanHb);
                    } elseif (preg_match('/^\d+_/', basename($cleanHb)) || file_exists(storage_path('app/public/' . $cleanHb))) {
                        $hbImg = asset('storage/' . $cleanHb);
                    } else {
                        $hbImg = asset($cleanHb);
                    }
                @endphp
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 8px; text-align: center;">
                    <a href="{{ $hbImg }}" target="_blank" title="Click to view full HD">
                        <img src="{{ $hbImg }}" alt="{{ $hb['title'] }}" onerror="if (!this.dataset.triedStorage && this.src.indexOf('/storage/') === -1) { this.dataset.triedStorage = '1'; this.src = this.src.replace('/images/', '/storage/images/'); } else { this.onerror=null; this.src='{{ asset('images/stall_booking/stall_brochure_1.png') }}'; }" style="width: 100%; height: auto; max-height: 260px; object-fit: contain; border-radius: 8px; border: 1px solid #f1f5f9; display: block; image-rendering: -webkit-optimize-contrast;">
                    </a>
                    <div style="margin-top: 8px; font-size: 0.78rem; font-weight: 700; color: #334155; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $hb['title'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 2. EDIT FORM -->
    <form method="POST" action="{{ route('admin.stall_booking_details.update') }}" enctype="multipart/form-data">
        @csrf

        <!-- A. Page Banner & Header Settings -->
        <div class="admin-card-section">
            <div class="section-title">
                <i class="fa-solid fa-heading" style="color: #00A896;"></i> 1. Details Page Banner &amp; Header
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label class="form-label">Top Badge Label</label>
                    <input type="text" name="stall_details_badge" value="{{ $settings['stall_details_badge'] ?? 'Conference Opportunities & Brochures' }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Banner Title</label>
                    <input type="text" name="stall_details_title" value="{{ $settings['stall_details_title'] ?? 'Stall Booking & Merchandise' }}" class="form-input" required>
                </div>
            </div>

            <div>
                <label class="form-label">Banner Subtitle</label>
                <input type="text" name="stall_details_subtitle" value="{{ $settings['stall_details_subtitle'] ?? 'Partnership Avenues, Tariff Details & Sponsorship Opportunities | Global One Health Confluence 2026' }}" class="form-input">
            </div>
        </div>

        <!-- B. The 3 Official Conference Brochures -->
        <div class="admin-card-section">
            <div class="section-title">
                <i class="fa-solid fa-images" style="color: #00A896;"></i> 2. Official Conference Brochures (Cards)
            </div>
            <p style="color: #64748b; font-size: 0.9rem; margin-top: -10px; margin-bottom: 20px;">
                Upload master high-resolution images. These appear both on the Home page section and the Full Details page with HD Zoom.
            </p>

            <div id="home-brochures-container">
                @foreach($homeBrochures as $idx => $hb)
                    <div class="item-card home-brochure-row">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                                <i class="fa-solid fa-file-image" style="color: #00A896;"></i> Brochure Card #<span class="home-brochure-number">{{ $idx + 1 }}</span>
                            </span>
                            <button type="button" onclick="this.closest('.home-brochure-row').remove(); updateHomeBrochureNumbers();" style="background: #fee2e2; color: #dc2626; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                                <i class="fa-solid fa-trash"></i> Remove Card
                            </button>
                        </div>

                        <div style="display: grid; grid-template-columns: 1.5fr 1.5fr 1fr 2fr; gap: 15px; align-items: center;">
                            <div>
                                <label class="form-label">Card Title / Alt Text</label>
                                <input type="text" name="home_brochure_titles[]" value="{{ $hb['title'] }}" class="form-input" required>
                            </div>

                            <div>
                                <label class="form-label">Target Link URL</label>
                                <input type="text" name="home_brochure_urls[]" value="{{ $hb['url'] ?? '/page/stall-booking-details' }}" class="form-input">
                            </div>

                            <div>
                                <label class="form-label">Current Image (HD)</label>
                                @php
                                    $rowRaw = $hb['image'] ?? 'images/stall_booking/stall_brochure_1.png';
                                    $cleanRow = ltrim($rowRaw, '/');
                                    if (str_starts_with($cleanRow, 'storage/')) {
                                        $rowImg = asset($cleanRow);
                                    } elseif (preg_match('/^\d+_/', basename($cleanRow)) || file_exists(storage_path('app/public/' . $cleanRow))) {
                                        $rowImg = asset('storage/' . $cleanRow);
                                    } else {
                                        $rowImg = asset($cleanRow);
                                    }
                                @endphp
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <a href="{{ $rowImg }}" target="_blank" title="View Full HD" style="display: inline-block;">
                                        <img src="{{ $rowImg }}" alt="{{ $hb['title'] }}" id="home_brochure_preview_img_{{ $idx }}" onerror="if (!this.dataset.triedStorage && this.src.indexOf('/storage/') === -1) { this.dataset.triedStorage = '1'; this.src = this.src.replace('/images/', '/storage/images/'); } else { this.onerror=null; this.src='{{ asset('images/stall_booking/stall_brochure_1.png') }}'; }" style="height: 70px; width: auto; max-width: 100px; object-fit: contain; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #fff; padding: 2px; image-rendering: -webkit-optimize-contrast;">
                                    </a>
                                    <input type="hidden" name="home_brochure_existing_images[]" value="{{ $hb['image'] }}">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Upload New / Replace Image</label>
                                <input type="file" name="home_brochure_files[]" class="form-input" accept="image/*" onchange="previewBrochureLocal(this, 'home_brochure_preview_img_{{ $idx }}')">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn-add-item" onclick="addHomeBrochureRow()" style="margin-top: 10px;">
                <i class="fa-solid fa-plus"></i> Add Another Brochure Card
            </button>
        </div>

        <!-- C. Home Section Display Settings -->
        <div class="admin-card-section">
            <div class="section-title">
                <i class="fa-solid fa-sliders" style="color: #00A896;"></i> 3. Home Page Section Controls
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label class="form-label">Home Section Heading (HTML Supported)</label>
                    <input type="text" name="stall_home_title" value="{{ $sectionHeading }}" class="form-input" required>
                    <small style="color: #64748b; font-size: 0.78rem;">Default: <code>Conference &lt;span style="color: #009688;"&gt;Brochures&lt;/span&gt;</code></small>
                </div>
                <div>
                    <label class="form-label">Home Section Visibility</label>
                    <select name="stall_home_show" class="form-input">
                        <option value="1" {{ $isSectionVisible ? 'selected' : '' }}>Visible (Show on Home Page)</option>
                        <option value="0" {{ !$isSectionVisible ? 'selected' : '' }}>Hidden (Hide from Home Page)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1.5fr 1fr; gap: 20px;">
                <div>
                    <label class="form-label">Explore Button Text</label>
                    <input type="text" name="stall_home_btn_label" value="{{ $btnLabel }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Explore Button URL</label>
                    <input type="text" name="stall_home_btn_url" value="{{ $btnUrl }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Show Button</label>
                    <select name="stall_home_btn_show" class="form-input">
                        <option value="1" {{ $showBtn ? 'selected' : '' }}>Show Button</option>
                        <option value="0" {{ !$showBtn ? 'selected' : '' }}>Hide Button</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Sticky Save Button Bar -->
        <div style="position: sticky; bottom: 20px; background: #ffffff; padding: 18px 25px; border-radius: 14px; border: 1.5px solid #cbd5e1; box-shadow: 0 10px 30px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; z-index: 50;">
            <span style="color: #64748b; font-size: 0.95rem; font-weight: 600;">
                <i class="fa-solid fa-circle-info" style="color: #00A896;"></i> Changes apply instantly to both Home and Details pages.
            </span>
            <button type="submit" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 13px 36px; border-radius: 10px; font-weight: 800; font-size: 1.05rem; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 168, 150, 0.35); display: inline-flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-floppy-disk"></i> Save Stall Booking Details
            </button>
        </div>

    </form>

</div>

<script>
    function previewBrochureLocal(input, targetId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(targetId);
                if (img) {
                    img.src = e.target.result;
                    if (img.parentElement && img.parentElement.tagName === 'A') {
                        img.parentElement.href = e.target.result;
                    }
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateHomeBrochureNumbers() {
        document.querySelectorAll('.home-brochure-row').forEach((row, idx) => {
            const numEl = row.querySelector('.home-brochure-number');
            if (numEl) numEl.textContent = idx + 1;
        });
    }

    function addHomeBrochureRow() {
        const container = document.getElementById('home-brochures-container');
        const count = container.querySelectorAll('.home-brochure-row').length + 1;
        const newImgId = 'new_home_brochure_img_' + Date.now();
        const row = document.createElement('div');
        row.className = 'item-card home-brochure-row';
        row.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                    <i class="fa-solid fa-file-image" style="color: #00A896;"></i> Brochure Card #<span class="home-brochure-number">${count}</span> (New)
                </span>
                <button type="button" onclick="this.closest('.home-brochure-row').remove(); updateHomeBrochureNumbers();" style="background: #fee2e2; color: #dc2626; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                    <i class="fa-solid fa-trash"></i> Remove Card
                </button>
            </div>
            <div style="display: grid; grid-template-columns: 1.5fr 1.5fr 1fr 2fr; gap: 15px; align-items: center;">
                <div>
                    <label class="form-label">Card Title / Alt Text</label>
                    <input type="text" name="home_brochure_titles[]" placeholder="e.g. Conference Brochure" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Target Link URL</label>
                    <input type="text" name="home_brochure_urls[]" value="/page/stall-booking-details" class="form-input">
                </div>
                <div>
                    <label class="form-label">Instant HD Preview</label>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img id="${newImgId}" src="{{ asset('images/stall_booking/stall_brochure_1.png') }}" alt="Preview" style="height: 70px; width: auto; max-width: 100px; object-fit: contain; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #fff; padding: 2px;">
                        <input type="hidden" name="home_brochure_existing_images[]" value="images/stall_booking/stall_brochure_1.png">
                    </div>
                </div>
                <div>
                    <label class="form-label">Upload New Image</label>
                    <input type="file" name="home_brochure_files[]" class="form-input" accept="image/*" required onchange="previewBrochureLocal(this, '${newImgId}')">
                </div>
            </div>
        `;
        container.appendChild(row);
        updateHomeBrochureNumbers();
    }
</script>
@endsection
