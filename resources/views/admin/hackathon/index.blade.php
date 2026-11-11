@extends('layouts.admin_cms')

@section('header_title', 'Hackathon CMS')

@section('content')
<style>
    .event-admin-wrap {
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

    .dynamic-item-row {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
    }

    .dynamic-item-row:hover {
        border-color: #00A896;
        background: #ffffff;
    }
</style>

<div class="event-admin-wrap">

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="font-size: 1.7rem; font-weight: 800; color: #0a192f; margin: 0 0 4px 0;">
                <i class="fa-solid fa-laptop-code" style="color: #00A896; margin-right: 8px;"></i> Hackathon CMS
            </h2>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Customize hackathon challenge themes, team requirements, duration, cash prizes, and guidelines with full Add/Edit/Delete.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="/events/hackathon" target="_blank" style="background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; padding: 11px 20px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> View Live Page
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.3rem;"></i> {{ session('success') }}
        </div>
    @endif

    @php
        $bannerTitle = $settings['hackathon_banner_title'] ?? 'HACKATHON';
        $sectionTitle = $settings['hackathon_section_title'] ?? 'One Health Grand Hackathon Challenge';
        $mainContent = $settings['hackathon_main_content'] ?? 'Join multidisciplinary teams of engineers, healthcare professionals, data scientists, and developers to build rapid digital and hardware solutions for One Health surveillance, pandemic preparedness, antimicrobial resistance tracking, and environmental monitoring.';
        $flyerImage = $settings['hackathon_flyer_image'] ?? (file_exists(public_path('images/event/hackathon_flyer.png')) ? 'images/event/hackathon_flyer.png' : null);

        $defaultGuidelines = [
            'Track A - Genomic & AMR Surveillance: AI-powered tools for early pathogen detection, AMR mutation tracking, and outbreak modeling.',
            'Track B - Environmental Biosensors: Low-cost IoT sensors for real-time monitoring of effluent water, soil toxicants, and airborne pathogens.',
            'Track C - Community Health & Tele-Diagnostics: Portable point-of-care diagnostics and accessible mobile apps for rural community outreach.',
            'Track D - Sustainable Bioprocesses: Circular economy algorithms and biotechnological tools for safe clinical waste remediation.',
            'Deliverables: Functional prototype / code repository, 5-minute live demonstration, and project presentation.'
        ];

        $guidelineList = !empty($guidelines) ? $guidelines : $defaultGuidelines;
    @endphp

    <!-- 1. LIVE VISUAL PREVIEW -->
    <div style="background: #f8fafc; border-radius: 20px; border: 2px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 35px rgba(15, 23, 42, 0.06); position: relative;">
        <div style="position: absolute; top: 15px; right: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #00A896; background: rgba(255, 255, 255, 0.95); padding: 5px 14px; border-radius: 20px; border: 1px solid #b2dfdb; z-index: 10;">
            <i class="fa-solid fa-eye"></i> Live Visual Preview
        </div>

        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 55px 20px 45px; text-align: center; color: #fff;">
            <h1 style="text-transform: uppercase; font-size: 2rem; font-weight: 800; letter-spacing: 1.5px; color: #ffffff; margin: 0;">
                {{ $bannerTitle }}
            </h1>
        </div>

        <div style="padding: 40px 30px; max-width: 950px; margin: 0 auto; font-family: 'Poppins', sans-serif;">
            <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 35px 30px; box-shadow: 0 6px 20px rgba(0,0,0,0.03);">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 18px 0; border-bottom: 3px solid #00A896; padding-bottom: 10px; display: inline-block;">
                    {{ $sectionTitle }}
                </h2>

                <div style="background: #f0fdf4; border-left: 4px solid #10b981; border-radius: 10px; padding: 18px 22px; margin-bottom: 25px; color: #334155; line-height: 1.7; font-size: 0.95rem;">
                    {{ $mainContent }}
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 25px;">
                    <div style="background: #f8fafc; border-left: 4px solid #00A896; border-radius: 8px; padding: 15px;">
                        <h4 style="color: #0f172a; font-size: 0.95rem; font-weight: 700; margin: 0 0 4px 0;"><i class="fa-solid fa-users-gear" style="color: #00A896;"></i> Team Composition</h4>
                        <p style="color: #475569; font-size: 0.85rem; margin: 0;">{{ $settings['hackathon_spec_team'] ?? '2 to 5 Developers / Researchers per Team' }}</p>
                    </div>
                    <div style="background: #f8fafc; border-left: 4px solid #00A896; border-radius: 8px; padding: 15px;">
                        <h4 style="color: #0f172a; font-size: 0.95rem; font-weight: 700; margin: 0 0 4px 0;"><i class="fa-solid fa-hourglass-half" style="color: #00A896;"></i> Hackathon Duration</h4>
                        <p style="color: #475569; font-size: 0.85rem; margin: 0;">{{ $settings['hackathon_spec_duration'] ?? '24-Hour Intensive Prototyping Sprint' }}</p>
                    </div>
                    <div style="background: #f8fafc; border-left: 4px solid #00A896; border-radius: 8px; padding: 15px;">
                        <h4 style="color: #0f172a; font-size: 0.95rem; font-weight: 700; margin: 0 0 4px 0;"><i class="fa-solid fa-award" style="color: #00A896;"></i> Cash Prizes & Incubation</h4>
                        <p style="color: #475569; font-size: 0.85rem; margin: 0;">{{ $settings['hackathon_spec_prizes'] ?? 'Cash Awards + Fast-track Incubation Support' }}</p>
                    </div>
                </div>

                <!-- Problem Statements Preview -->
                <h4 style="color: #0f172a; font-size: 1.1rem; font-weight: 700; margin-bottom: 12px;"><i class="fa-solid fa-layer-group" style="color: #00A896;"></i> Problem Statements & Challenge Rules</h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px;">
                    @foreach($guidelineList as $g)
                        <li style="display: flex; align-items: flex-start; gap: 10px; color: #334155; font-size: 0.92rem; line-height: 1.6;">
                            <i class="fa-solid fa-check" style="color: #00A896; margin-top: 3px;"></i>
                            <span>{{ $g }}</span>
                        </li>
                    @endforeach
                </ul>

                @if(!empty($flyerImage))
                    <div style="margin-top: 30px; padding-top: 25px; border-top: 1px solid #e2e8f0; text-align: center;">
                        <h4 style="color: #0f172a; font-size: 1.1rem; font-weight: 700; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="fa-solid fa-file-image" style="color: #00A896;"></i> Official Event Flyer & Challenge Guide
                        </h4>
                        <div style="display: inline-block; max-width: 420px; width: 100%; border: 1.5px solid #cbd5e1; border-radius: 14px; overflow: hidden; box-shadow: 0 6px 20px rgba(0,0,0,0.06); background: #ffffff;">
                            <img src="{{ asset($flyerImage) }}" alt="Hackathon Flyer" style="width: 100%; height: auto; display: block; image-rendering: -webkit-optimize-contrast; image-rendering: high-quality;">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 2. EDIT FORM -->
    <form action="{{ route('admin.hackathon.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display: flex; flex-direction: column; gap: 30px;">
            
            <div class="admin-card-section">
                <h3 style="margin: 0 0 18px 0; font-size: 1.25rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-pen-to-square" style="color: #00A896;"></i> 1. Page Header & Hackathon Overview
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label class="form-label">Hero Banner Title</label>
                        <input type="text" name="hackathon_banner_title" value="{{ $bannerTitle }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Main Section Heading</label>
                        <input type="text" name="hackathon_section_title" value="{{ $sectionTitle }}" class="form-input" required>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="form-label">Hackathon Overview Paragraph</label>
                    <textarea name="hackathon_main_content" rows="4" class="form-input" required>{{ $mainContent }}</textarea>
                </div>
            </div>

            <div class="admin-card-section">
                <h3 style="margin: 0 0 18px 0; font-size: 1.25rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-list-check" style="color: #00A896;"></i> 2. Hackathon Specifications & Rules
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label class="form-label">Team Composition</label>
                        <input type="text" name="hackathon_spec_team" value="{{ $settings['hackathon_spec_team'] ?? '2 to 5 Developers / Researchers per Team' }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Sprint Duration</label>
                        <input type="text" name="hackathon_spec_duration" value="{{ $settings['hackathon_spec_duration'] ?? '24-Hour Intensive Prototyping Sprint' }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Prizes & Recognition</label>
                        <input type="text" name="hackathon_spec_prizes" value="{{ $settings['hackathon_spec_prizes'] ?? 'Cash Awards + Fast-track Incubation Support' }}" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Dynamic Guidelines Items (Add / Edit / Delete) -->
            <div class="admin-card-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h3 style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-list-ol" style="color: #00A896;"></i> 3. Dynamic Problem Statements & Challenge Rules
                        </h3>
                        <p style="color: #64748b; font-size: 0.9rem; margin: 0;">Add new problem tracks, edit in place, or delete obsolete rules.</p>
                    </div>
                    <button type="button" onclick="addHackathonTrackRow()" style="background: #e6fffa; color: #0d9488; border: 1.5px solid #99f6e4; padding: 9px 18px; border-radius: 8px; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-plus"></i> Add Problem Track
                    </button>
                </div>

                <div id="hackathon-guidelines-container" style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($guidelineList as $idx => $item)
                        <div class="dynamic-item-row">
                            <i class="fa-solid fa-circle-check" style="color: #00A896; font-size: 1.15rem;"></i>
                            <input type="text" name="hackathon_guideline_items[]" value="{{ $item }}" class="form-input" style="flex: 1; padding: 10px 14px;" required placeholder="Enter problem statement / track / rule...">
                            <button type="button" onclick="this.closest('.dynamic-item-row').remove()" style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; width: 38px; height: 38px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0;" title="Delete item">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 4. Official Event Flyer / Poster Upload -->
            <div class="admin-card-section">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h3 style="margin: 0 0 6px 0; font-size: 1.25rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-file-image" style="color: #00A896;"></i> 4. Official Event Flyer / Poster (High-Resolution)
                        </h3>
                        <p style="color: #64748b; font-size: 0.9rem; margin: 0;">Upload high-resolution event poster/flyer (PNG, JPG, WEBP). Preserves full original clarity without blur, with interactive HD zoom for viewers.</p>
                    </div>
                </div>

                <div style="display: flex; gap: 30px; align-items: flex-start; flex-wrap: wrap;">
                    <!-- Current Flyer Display -->
                    <div style="flex: 1; min-width: 280px;">
                        <label class="form-label">Current Event Flyer</label>
                        <div id="flyer_current_box" style="background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 18px; text-align: center;">
                            @if(!empty($flyerImage))
                                <img id="flyer_preview_img" src="{{ asset($flyerImage) }}" alt="Event Flyer" style="max-height: 260px; max-width: 100%; width: auto; height: auto; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); image-rendering: -webkit-optimize-contrast; image-rendering: high-quality; display: inline-block;">
                                <div id="flyer_preview_action" style="margin-top: 14px; display: flex; justify-content: center; gap: 10px;">
                                    <a href="{{ asset($flyerImage) }}" target="_blank" style="background: #ffffff; color: #00A896; border: 1.5px solid #00A896; padding: 8px 18px; border-radius: 8px; font-weight: 700; font-size: 0.88rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> View Full Resolution HD
                                    </a>
                                </div>
                            @else
                                <div id="flyer_preview_placeholder" style="padding: 40px 20px; color: #94a3b8;">
                                    <i class="fa-solid fa-image" style="font-size: 3rem; margin-bottom: 10px; color: #cbd5e1;"></i>
                                    <p style="margin: 0; font-size: 0.9rem;">No flyer uploaded yet. Choose an image file below.</p>
                                </div>
                                <img id="flyer_preview_img" src="" alt="Event Flyer" style="max-height: 260px; max-width: 100%; width: auto; height: auto; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); image-rendering: -webkit-optimize-contrast; display: none;">
                                <div id="flyer_preview_action" style="margin-top: 14px; display: none; justify-content: center;">
                                    <span style="color: #00A896; font-weight: 700; font-size: 0.85rem;"><i class="fa-solid fa-eye"></i> Ready for upload preview</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Controls -->
                    <div style="flex: 1; min-width: 280px;">
                        <label class="form-label">Upload New Flyer / Poster</label>
                        <input type="file" name="hackathon_flyer_image" id="hackathon_flyer_input" accept="image/*" class="form-input" onchange="previewFlyerLocal(this)" style="padding: 11px; background: #ffffff;">
                        <p style="color: #64748b; font-size: 0.85rem; margin: 10px 0 0 0; line-height: 1.5;">
                            <i class="fa-solid fa-circle-info" style="color: #00A896;"></i> <strong>Zero-Blur High Fidelity:</strong> Images are stored without downsampling or lossy compression. High contrast CSS optimization ensures razor-sharp text on all displays.
                        </p>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 13px 36px; border-radius: 10px; font-weight: 800; font-size: 1rem; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 168, 150, 0.35); display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Hackathon Content & Poster
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>

<script>
    function addHackathonTrackRow() {
        const container = document.getElementById('hackathon-guidelines-container');
        const div = document.createElement('div');
        div.className = 'dynamic-item-row';
        div.innerHTML = `
            <i class="fa-solid fa-circle-check" style="color: #00A896; font-size: 1.15rem;"></i>
            <input type="text" name="hackathon_guideline_items[]" value="" class="form-input" style="flex: 1; padding: 10px 14px;" required placeholder="Enter new problem statement / track / rule...">
            <button type="button" onclick="this.closest('.dynamic-item-row').remove()" style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; width: 38px; height: 38px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0;" title="Delete item">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        `;
        container.appendChild(div);
        div.querySelector('input').focus();
    }

    function previewFlyerLocal(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('flyer_preview_img');
                const placeholder = document.getElementById('flyer_preview_placeholder');
                const action = document.getElementById('flyer_preview_action');
                if (placeholder) placeholder.style.display = 'none';
                img.src = e.target.result;
                img.style.display = 'inline-block';
                if (action) action.style.display = 'flex';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
