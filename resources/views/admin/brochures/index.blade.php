@extends('layouts.admin_cms')

@section('header_title', 'Conference Brochures CMS')

@section('content')
<style>
    .brochures-admin-wrap {
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
    $btnLabel = $settings['stall_home_btn_label'] ?? 'Explore Full Stall Booking & Merchandise Details &rarr;';
    $btnUrl = $settings['stall_home_btn_url'] ?? '/page/stall-booking-details';
    $showBtn = ($settings['stall_home_btn_show'] ?? '1') == '1';
@endphp

<div class="brochures-admin-wrap">

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="font-size: 1.7rem; font-weight: 800; color: #0a192f; margin: 0 0 4px 0;">
                <i class="fa-solid fa-book-open" style="color: #00A896; margin-right: 8px;"></i> Conference Brochures (Home Showcase) CMS
            </h2>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Customize the 3 brochure image cards, title, and explore button shown on the Home screen.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="/#conference-brochures" target="_blank" style="background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; padding: 11px 20px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> View on Home Page
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.3rem;"></i> {{ session('success') }}
        </div>
    @endif

    <!-- 1. LIVE VISUAL PREVIEW OF HOME SECTION -->
    <div style="background: #ffffff; border-radius: 20px; border: 2px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 35px rgba(15, 23, 42, 0.05); position: relative; padding: 40px 25px;">
        <div style="position: absolute; top: 15px; right: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #00A896; background: #e6f7f5; padding: 5px 14px; border-radius: 20px; border: 1px solid #b2dfdb; z-index: 10;">
            <i class="fa-solid fa-eye"></i> Live Home Section Preview
        </div>

        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin: 0 0 10px 0;">
                {!! $sectionHeading !!}
            </h2>
            <div style="width: 65px; height: 3.5px; background: linear-gradient(90deg, #009688, #84cc16); margin: 0 auto; border-radius: 4px;"></div>
        </div>

        <div style="display: grid; grid-template-columns: repeat({{ count($homeBrochures) > 0 ? min(count($homeBrochures), 3) : 3 }}, 1fr); gap: 20px; max-width: 950px; margin: 0 auto 25px auto;">
            @foreach($homeBrochures as $idx => $hb)
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 8px; text-align: center;">
                    <a href="{{ asset($hb['image']) }}" target="_blank" title="Click to view full HD">
                        <img src="{{ asset($hb['image']) }}" alt="{{ $hb['title'] }}" style="width: 100%; height: auto; max-height: 280px; object-fit: contain; border-radius: 8px; border: 1px solid #f1f5f9; display: block; image-rendering: -webkit-optimize-contrast;">
                    </a>
                    <div style="margin-top: 8px; font-size: 0.78rem; font-weight: 700; color: #334155; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $hb['title'] }}</div>
                </div>
            @endforeach
        </div>

        @if($showBtn)
        <div style="text-align: center;">
            <span style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 11px 30px; border-radius: 30px; font-weight: 700; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-store"></i> {!! $btnLabel !!}
            </span>
        </div>
        @endif
    </div>

    <!-- 2. EDIT FORM -->
    <form method="POST" action="{{ route('admin.conference_brochures.update') }}" enctype="multipart/form-data">
        @csrf

        <!-- General Section Controls -->
        <div class="admin-card-section">
            <div class="section-title">
                <i class="fa-solid fa-sliders" style="color: #00A896;"></i> 1. Section Controls
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label class="form-label">Section Heading (HTML Supported for Color Accents)</label>
                    <input type="text" name="stall_home_title" value="{{ $sectionHeading }}" class="form-input" required>
                    <small style="color: #64748b; font-size: 0.78rem;">Default: <code>Conference &lt;span style="color: #009688;"&gt;Brochures&lt;/span&gt;</code></small>
                </div>
                <div>
                    <label class="form-label">Section Visibility on Home</label>
                    <select name="stall_home_show" class="form-input">
                        <option value="1" {{ $isSectionVisible ? 'selected' : '' }}>Visible (Show on Home Page)</option>
                        <option value="0" {{ !$isSectionVisible ? 'selected' : '' }}>Hidden (Hide from Home Page)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1.5fr 1fr; gap: 20px;">
                <div>
                    <label class="form-label">Bottom Button Text</label>
                    <input type="text" name="stall_home_btn_label" value="{{ $btnLabel }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Bottom Button Target URL</label>
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

        <!-- Brochure Image Cards -->
        <div class="admin-card-section">
            <div class="section-title">
                <i class="fa-solid fa-images" style="color: #00A896;"></i> 2. Brochure Image Cards
            </div>
            <p style="color: #64748b; font-size: 0.9rem; margin-top: -10px; margin-bottom: 20px;">
                Manage each brochure image card shown on the Home screen. Upload new high-resolution images to replace existing ones.
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
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <a href="{{ asset($hb['image']) }}" target="_blank" title="View Full HD" style="display: inline-block;">
                                        <img src="{{ asset($hb['image']) }}" alt="{{ $hb['title'] }}" id="home_brochure_preview_img_{{ $idx }}" style="height: 70px; width: auto; max-width: 100px; object-fit: contain; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #fff; padding: 2px; image-rendering: -webkit-optimize-contrast;">
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

        <!-- Sticky Save Button Bar -->
        <div style="position: sticky; bottom: 20px; background: #ffffff; padding: 18px 25px; border-radius: 14px; border: 1.5px solid #cbd5e1; box-shadow: 0 10px 30px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; z-index: 50;">
            <span style="color: #64748b; font-size: 0.95rem; font-weight: 600;">
                <i class="fa-solid fa-circle-info" style="color: #00A896;"></i> Changes take effect immediately on the Home page after saving.
            </span>
            <button type="submit" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 13px 36px; border-radius: 10px; font-weight: 800; font-size: 1.05rem; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 168, 150, 0.35); display: inline-flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-floppy-disk"></i> Save Conference Brochures
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
