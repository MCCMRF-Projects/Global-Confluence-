@extends('layouts.admin_cms')

@section('header_title', 'Topbar & Announcement Header CMS')

@section('content')
<style>
    .topbar-admin-wrap {
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

    .form-group {
        margin-bottom: 20px;
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
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-input:focus {
        border-color: #009688;
        box-shadow: 0 0 0 3px rgba(0, 150, 136, 0.15);
        outline: none;
    }

    .speed-range-track {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        height: 9px;
        border-radius: 999px;
        background: linear-gradient(to right, #0284c7 0%, #0284c7 var(--progress, 35%), #64748b var(--progress, 35%), #64748b 100%);
        outline: none;
        cursor: pointer;
        transition: background 0.05s ease;
    }
    .speed-range-track::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #0284c7;
        border: 2.5px solid #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        cursor: pointer;
        transition: transform 0.15s ease;
    }
    .speed-range-track::-webkit-slider-thumb:hover {
        transform: scale(1.15);
    }
    .speed-range-track::-moz-range-thumb {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #0284c7;
        border: 2.5px solid #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        cursor: pointer;
    }

    /* Live Preview Component */
    .prev-topbar-desktop {
        padding: 10px 22px;
        display: flex;
        align-items: center;
        background-color: {{ $settings['topbar_bg_color'] ?? '#0f233a' }};
        color: {{ $settings['topbar_text_color'] ?? '#ffffff' }};
        font-size: 0.88rem;
        border-radius: 12px;
        overflow: hidden;
        transition: background-color 0.2s, color 0.2s;
    }

    .prev-topbar-left {
        flex-shrink: 0;
        padding-right: 20px;
        border-right: 1px solid rgba(255,255,255,0.25);
        display: flex;
        gap: 15px;
        align-items: center;
        white-space: nowrap;
        font-weight: 600;
        font-size: 0.84rem;
        z-index: 2;
    }

    .prev-topbar-left span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .prev-marquee-container {
        flex-grow: 1;
        overflow: hidden;
        display: flex;
        align-items: center;
        padding-left: 20px;
        white-space: nowrap;
        position: relative;
        min-width: 0;
    }

    .prev-marquee-content {
        display: flex;
        min-width: max-content;
        animation: prev-marquee-scroll {{ $settings['topbar_speed'] ?? '55' }}s linear infinite;
        will-change: transform;
    }

    .prev-marquee-container:hover .prev-marquee-content {
        animation-play-state: paused;
    }

    @keyframes prev-marquee-scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .item-row-card {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
    }

    .item-row-card:hover {
        border-color: #009688;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }

    .item-badge-num {
        background: #e2e8f0;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .item-btn-tool {
        background: #ffffff;
        color: #64748b;
        border: 1px solid #cbd5e1;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        transition: all 0.15s ease;
    }

    .item-btn-tool:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #94a3b8;
    }

    .item-btn-tool.btn-del:hover {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fca5a5;
    }

    .preset-pill {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s;
    }

    .preset-pill:hover {
        background: #009688;
        color: #ffffff;
        border-color: #009688;
        transform: translateY(-1px);
    }

    .color-picker-box {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .color-swatch-input {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 44px;
        height: 44px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        cursor: pointer;
        padding: 3px;
        background: #ffffff;
    }

    .color-swatch-input::-webkit-color-swatch {
        border-radius: 5px;
        border: none;
    }
</style>

<div class="topbar-admin-wrap">

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="font-size: 1.7rem; font-weight: 800; color: #0a192f; margin: 0 0 4px 0;">
                <i class="fa-solid fa-bullhorn" style="color: #009688; margin-right: 8px;"></i> Topbar &amp; Announcement Header CMS
            </h2>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Dynamically manage announcement marquee ticker messages, contact phone numbers, colors, and scroll speed with real-time preview.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="/" target="_blank" style="background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; padding: 11px 20px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.04);">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> View Live Site
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.3rem;"></i> {{ session('success') }}
        </div>
    @endif

    <!-- 1. DYNAMIC LIVE VISUAL PREVIEW (Real-Time Synchronized) -->
    <div style="background: #ffffff; border-radius: 20px; border: 2px solid #e2e8f0; padding: 25px; box-shadow: 0 10px 35px rgba(15, 23, 42, 0.06); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h4 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0;">
                    <i class="fa-solid fa-desktop" style="color: #64748b; margin-right: 6px;"></i> Live Visual Preview
                </h4>
                <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #0f766e; background: #ccfbf1; padding: 3px 10px; border-radius: 12px; border: 1px solid #99f6e4;">
                    Announcement Ticker
                </span>
            </div>
            <span style="font-size: 0.8rem; color: #94a3b8; font-weight: 500;">
                <i class="fa-solid fa-arrows-rotate"></i> Updates in real-time as you type or change settings
            </span>
        </div>

        <!-- Preview Bar -->
        <div id="prev-topbar-desktop" class="prev-topbar-desktop">
            <div id="prev-left-phones" class="prev-topbar-left">
                <!-- Dynamically populated by JS -->
            </div>
            <div class="prev-marquee-container">
                <div id="prev-marquee-content" class="prev-marquee-content">
                    <!-- Dynamically populated by JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- 2. EDIT FORM -->
    <form id="topbar-cms-form" method="POST" action="{{ route('admin.topbar.update') }}">
        @csrf

        <div style="display: flex; flex-direction: column; gap: 30px;">

            <!-- SECTION 1: ANNOUNCEMENT TICKER / MARQUEE -->
            <div class="admin-card-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h3 style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-scroll" style="color: #f59e0b;"></i> 1. Scrolling Announcement Marquee Ticker
                        </h3>
                        <p style="margin: 0; color: #64748b; font-size: 0.88rem;">Add, edit, and reorder custom marquee headlines, announcements, and alerts.</p>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" onclick="addTickerRow('')" style="background: #fffbeb; color: #b45309; border: 1.5px solid #fde68a; padding: 9px 18px; border-radius: 8px; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(245,158,11,0.1);">
                            <i class="fa-solid fa-plus"></i> Add Custom Message
                        </button>
                    </div>
                </div>

                <!-- Quick Presets Toolbar -->
                <div id="presets-wrap" style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px;">
                    <div style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #64748b; margin-bottom: 8px;">
                        <i class="fa-solid fa-wand-magic-sparkles" style="color: #009688;"></i> Quick Add Templates (Click to insert):
                    </div>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <button type="button" class="preset-pill" onclick="addTickerRow('Quality presentation will be peer reviewed and considered for further publication in selected Scopus/ WoS indexed journals. All the Presentation will be published as a conference proceedings in ISBN indexed book')">
                            <i class="fa-solid fa-plus"></i> Publication Note
                        </button>
                        <button type="button" class="preset-pill" onclick="addTickerRow('📌 Important Announcement: Register before the deadline as a participant, and submit your abstract later!')">
                            <i class="fa-solid fa-plus"></i> Important Announcement
                        </button>
                        <button type="button" class="preset-pill" onclick="addTickerRow('Registration fee waived!')">
                            <i class="fa-solid fa-plus"></i> Fee Notice
                        </button>
                        <button type="button" class="preset-pill" onclick="addTickerRow('Submission of Abstract: 2026-10-30')">
                            <i class="fa-solid fa-plus"></i> Abstract Deadline
                        </button>
                        <button type="button" class="preset-pill" onclick="addTickerRow('Early Bird Registration: 2026-10-15')">
                            <i class="fa-solid fa-plus"></i> Early Bird
                        </button>
                    </div>
                </div>

                <!-- Custom Ticker Items List Container -->
                <div id="custom-ticker-wrap">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <label class="form-label" style="margin: 0;">
                            Announcement Items (<span id="ticker-items-count">0</span>)
                        </label>
                        <span style="font-size: 0.8rem; color: #94a3b8;">Use the arrows to reorder items dynamically</span>
                    </div>

                    <div id="ticker-items-container">
                        @if(isset($customTickerItems) && count($customTickerItems) > 0)
                            @foreach($customTickerItems as $idx => $tItem)
                                <div class="item-row-card">
                                    <span class="item-badge-num">{{ $loop->iteration }}</span>
                                    <i class="fa-solid fa-bullhorn" style="color: #f59e0b; font-size: 0.95rem;"></i>
                                    <input type="text" name="custom_ticker_items[]" value="{{ $tItem }}" class="form-input" style="flex: 1;" placeholder="Enter marquee text..." oninput="updateLivePreview()">
                                    <button type="button" class="item-btn-tool" onclick="moveItemUp(this)" title="Move Up">
                                        <i class="fa-solid fa-arrow-up"></i>
                                    </button>
                                    <button type="button" class="item-btn-tool" onclick="moveItemDown(this)" title="Move Down">
                                        <i class="fa-solid fa-arrow-down"></i>
                                    </button>
                                    <button type="button" class="item-btn-tool btn-del" onclick="removeTickerRow(this)" title="Delete Item">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- SECTION 2: APPEARANCE & STYLING -->
            <div class="admin-card-section">
                <h3 style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-palette" style="color: #009688;"></i> 2. Topbar Appearance &amp; Speed
                </h3>
                <p style="margin: 0 0 20px 0; color: #64748b; font-size: 0.88rem;">Customize colors and marquee animation speed in real time.</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
                    <!-- Background Color -->
                    <div>
                        <label class="form-label">Background Color</label>
                        <div class="color-picker-box">
                            <input type="color" id="bg-color-swatch" value="{{ $settings['topbar_bg_color'] ?? '#0f233a' }}" class="color-swatch-input" oninput="syncColor('bg', this.value)">
                            <input type="text" id="topbar_bg_color" name="topbar_bg_color" value="{{ $settings['topbar_bg_color'] ?? '#0f233a' }}" class="form-input" placeholder="#0f233a" oninput="syncColor('bg', this.value)">
                        </div>
                    </div>

                    <!-- Text Color -->
                    <div>
                        <label class="form-label">Text &amp; Icon Color</label>
                        <div class="color-picker-box">
                            <input type="color" id="text-color-swatch" value="{{ $settings['topbar_text_color'] ?? '#ffffff' }}" class="color-swatch-input" oninput="syncColor('text', this.value)">
                            <input type="text" id="topbar_text_color" name="topbar_text_color" value="{{ $settings['topbar_text_color'] ?? '#ffffff' }}" class="form-input" placeholder="#ffffff" oninput="syncColor('text', this.value)">
                        </div>
                    </div>

                    <!-- Scroll Speed (Seconds) -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <label class="form-label" style="margin: 0; font-weight: 800; color: #0a192f; font-size: 0.95rem;">Scroll Speed (Seconds)</label>
                        </div>
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="flex: 1; display: flex; flex-direction: column; gap: 6px;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.78rem; font-weight: 700; color: #64748b;">
                                    <span>Faster (15s)</span>
                                    <span>Slower (150s)</span>
                                </div>
                                <input type="range" 
                                       id="topbar_speed_range" 
                                       min="15" 
                                       max="150" 
                                       value="{{ $settings['topbar_speed'] ?? '55' }}" 
                                       class="speed-range-track" 
                                       oninput="syncSpeed(this.value)">
                            </div>
                            <div style="display: flex; align-items: center; gap: 4px; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 6px 12px; flex-shrink: 0;">
                                <input type="number" 
                                       id="topbar_speed" 
                                       name="topbar_speed" 
                                       value="{{ $settings['topbar_speed'] ?? '55' }}" 
                                       style="width: 50px; border: none; background: transparent; font-weight: 800; text-align: center; font-size: 1.05rem; color: #0284c7; outline: none;" 
                                       min="10" 
                                       max="200" 
                                       oninput="syncSpeed(this.value)">
                                <span style="font-weight: 700; color: #64748b; font-size: 0.85rem;">sec</span>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Event Format Badge -->
                    <div>
                        <label class="form-label">Event Format Badge</label>
                        <input type="text" name="topbar_format" value="{{ $settings['topbar_format'] ?? 'Online | In-person' }}" class="form-input" placeholder="Online | In-person">
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PHONE NUMBERS -->
            <div class="admin-card-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h3 style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-phone" style="color: #009688;"></i> 3. Contact &amp; Helpdesk Phone Numbers
                        </h3>
                        <p style="margin: 0; color: #64748b; font-size: 0.88rem;">These phone numbers appear on the left side of the topbar.</p>
                    </div>
                    <button type="button" onclick="addPhoneRow()" style="background: #e0f2fe; color: #0284c7; border: 1.5px solid #bae6fd; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-plus"></i> Add Another Phone
                    </button>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 15px;">
                    <div>
                        <label class="form-label">Phone 1 (Primary)</label>
                        <input type="text" id="contact_phone_1" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+91 9789582404' }}" class="form-input" placeholder="+91 9789582404" oninput="updateLivePreview()" required>
                    </div>
                    <div>
                        <label class="form-label">Phone 2</label>
                        <input type="text" id="contact_phone_2" name="contact_phone_2" value="{{ $settings['contact_phone_2'] ?? '+91 9025596984' }}" class="form-input" placeholder="+91 9025596984" oninput="updateLivePreview()">
                    </div>
                    <div>
                        <label class="form-label">Phone 3</label>
                        <input type="text" id="contact_phone_3" name="contact_phone_3" value="{{ $settings['contact_phone_3'] ?? '+91 81480 18894' }}" class="form-input" placeholder="+91 81480 18894" oninput="updateLivePreview()">
                    </div>
                </div>

                <div id="additional-phones-wrapper">
                    @for($p = 4; $p <= 20; $p++)
                        @if(isset($settings['contact_phone_' . $p]))
                            <div class="item-row-card">
                                <i class="fa-solid fa-phone" style="color: #009688;"></i>
                                <input type="text" name="additional_phones[]" value="{{ $settings['contact_phone_' . $p] }}" class="form-input" style="flex: 1;" placeholder="Enter phone number..." oninput="updateLivePreview()">
                                <button type="button" class="item-btn-tool btn-del" onclick="this.closest('.item-row-card').remove(); updateLivePreview();" title="Remove">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div style="text-align: right; margin-bottom: 30px;">
                <button type="submit" class="btn" style="background: linear-gradient(135deg, #009688, #00796b); color: #ffffff; padding: 14px 38px; border-radius: 10px; font-weight: 800; font-size: 1.05rem; border: none; cursor: pointer; box-shadow: 0 6px 20px rgba(0, 150, 136, 0.35); display: inline-flex; align-items: center; gap: 10px; transition: transform 0.15s, box-shadow 0.15s;">
                    <i class="fa-solid fa-floppy-disk"></i> Save Topbar &amp; Announcements
                </button>
            </div>

        </div>
    </form>

</div>

<script>
    function syncColor(type, val) {
        if (!val) return;
        if (type === 'bg') {
            document.getElementById('bg-color-swatch').value = val;
            document.getElementById('topbar_bg_color').value = val;
            document.getElementById('prev-topbar-desktop').style.backgroundColor = val;
        } else {
            document.getElementById('text-color-swatch').value = val;
            document.getElementById('topbar_text_color').value = val;
            document.getElementById('prev-topbar-desktop').style.color = val;
        }
    }

    function updateSpeedSliderTrack(val) {
        const slider = document.getElementById('topbar_speed_range');
        if (!slider) return;
        const min = parseFloat(slider.min) || 15;
        const max = parseFloat(slider.max) || 150;
        const pct = Math.max(0, Math.min(100, ((val - min) / (max - min)) * 100));
        slider.style.setProperty('--progress', pct + '%');
    }

    function syncSpeed(val) {
        const speed = Math.max(10, parseInt(val) || 55);
        const slider = document.getElementById('topbar_speed_range');
        const input = document.getElementById('topbar_speed');
        if (slider) slider.value = speed;
        if (input) input.value = speed;
        updateSpeedSliderTrack(speed);

        const marquee = document.getElementById('prev-marquee-content');
        if (marquee) {
            marquee.style.animationDuration = speed + 's';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const initSpeed = document.getElementById('topbar_speed')?.value || 55;
        updateSpeedSliderTrack(initSpeed);
    });

    function renumberTickerItems() {
        const container = document.getElementById('ticker-items-container');
        const rows = container.querySelectorAll('.item-row-card');
        rows.forEach((row, idx) => {
            const badge = row.querySelector('.item-badge-num');
            if (badge) badge.textContent = (idx + 1);
        });
        const countSpan = document.getElementById('ticker-items-count');
        if (countSpan) countSpan.textContent = rows.length;
    }

    function addTickerRow(text = '') {
        const container = document.getElementById('ticker-items-container');
        const div = document.createElement('div');
        div.className = 'item-row-card';
        div.innerHTML = `
            <span class="item-badge-num">1</span>
            <i class="fa-solid fa-bullhorn" style="color: #f59e0b; font-size: 0.95rem;"></i>
            <input type="text" name="custom_ticker_items[]" value="" class="form-input" style="flex: 1;" placeholder="Enter marquee text..." oninput="updateLivePreview()">
            <button type="button" class="item-btn-tool" onclick="moveItemUp(this)" title="Move Up">
                <i class="fa-solid fa-arrow-up"></i>
            </button>
            <button type="button" class="item-btn-tool" onclick="moveItemDown(this)" title="Move Down">
                <i class="fa-solid fa-arrow-down"></i>
            </button>
            <button type="button" class="item-btn-tool btn-del" onclick="removeTickerRow(this)" title="Delete Item">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        div.querySelector('input').value = text;
        container.appendChild(div);
        renumberTickerItems();
        updateLivePreview();
        if (!text) {
            div.querySelector('input').focus();
        }
    }

    function removeTickerRow(btn) {
        const row = btn.closest('.item-row-card');
        if (row) {
            row.remove();
            renumberTickerItems();
            updateLivePreview();
        }
    }

    function moveItemUp(btn) {
        const row = btn.closest('.item-row-card');
        const prev = row.previousElementSibling;
        if (prev && prev.classList.contains('item-row-card')) {
            row.parentNode.insertBefore(row, prev);
            renumberTickerItems();
            updateLivePreview();
        }
    }

    function moveItemDown(btn) {
        const row = btn.closest('.item-row-card');
        const next = row.nextElementSibling;
        if (next && next.classList.contains('item-row-card')) {
            row.parentNode.insertBefore(next, row);
            renumberTickerItems();
            updateLivePreview();
        }
    }

    function addPhoneRow() {
        const wrapper = document.getElementById('additional-phones-wrapper');
        const div = document.createElement('div');
        div.className = 'item-row-card';
        div.innerHTML = `
            <i class="fa-solid fa-phone" style="color: #009688;"></i>
            <input type="text" name="additional_phones[]" value="" class="form-input" style="flex: 1;" placeholder="Enter phone number..." oninput="updateLivePreview()">
            <button type="button" class="item-btn-tool btn-del" onclick="this.closest('.item-row-card').remove(); updateLivePreview();" title="Remove">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        wrapper.appendChild(div);
        div.querySelector('input').focus();
        updateLivePreview();
    }

    function updateLivePreview() {
        // 1. Update Left Phones in Preview
        const phonesContainer = document.getElementById('prev-left-phones');
        if (phonesContainer) {
            const phones = [];
            const p1 = document.getElementById('contact_phone_1')?.value.trim();
            const p2 = document.getElementById('contact_phone_2')?.value.trim();
            const p3 = document.getElementById('contact_phone_3')?.value.trim();
            if (p1) phones.push(p1);
            if (p2) phones.push(p2);
            if (p3) phones.push(p3);
            document.querySelectorAll('input[name="additional_phones[]"]').forEach(inp => {
                if (inp.value.trim()) phones.push(inp.value.trim());
            });

            if (phones.length > 0) {
                phonesContainer.style.display = 'flex';
                phonesContainer.innerHTML = phones.map(p => `<span><i class="fa-solid fa-phone" style="font-size: 0.75rem;"></i> ${p}</span>`).join('');
            } else {
                phonesContainer.style.display = 'none';
            }
        }

        // 2. Update Marquee Content
        const marquee = document.getElementById('prev-marquee-content');
        if (!marquee) return;

        let items = [];
        document.querySelectorAll('#ticker-items-container input[name="custom_ticker_items[]"]').forEach(inp => {
            const val = inp.value.trim();
            if (val) items.push(val);
        });
        if (items.length === 0) {
            items = [
                'All the Presentation will be published as a conference proceedings in ISBN indexed book',
                'Quality presentation will be peer reviewed and considered for further publication in selected Scopus/ WoS indexed journals'
            ];
        }

        // Render repeated for seamless loop
        let html = '';
        for (let r = 0; r < 4; r++) {
            items.forEach(item => {
                html += `
                    <span style="margin-right: 45px; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-circle" style="color: #00A896; font-size: 0.45rem;"></i>
                        <span>${item}</span>
                        <span style="color: rgba(255,255,255,0.25); margin-left: 20px;">|</span>
                    </span>
                `;
            });
        }
        marquee.innerHTML = html;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        renumberTickerItems();
        syncSpeed(document.getElementById('topbar_speed')?.value || 55);
        syncColor('bg', document.getElementById('topbar_bg_color')?.value || '#0f233a');
        syncColor('text', document.getElementById('topbar_text_color')?.value || '#ffffff');
        updateLivePreview();
    });
</script>
@endsection
