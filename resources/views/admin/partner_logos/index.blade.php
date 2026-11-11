@extends('layouts.admin_cms')

@section('header_title', 'Partner / Organizational Logos Management')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h2 style="font-size: 1.6rem; font-weight: 800; color: #0a192f; margin: 0 0 5px 0;">Partner & Organizer Logos</h2>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Add, edit, remove, or drag-and-drop to reorder all dynamic logos displayed across the site footer and header.</p>
        </div>
        <button class="btn" onclick="openAddLogoModal()" style="background: linear-gradient(135deg, #00a896, #028090); padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(0, 168, 150, 0.3);">
            <i class="fa-solid fa-plus"></i> Add New Logo
        </button>
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 10px; padding: 15px 20px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i> {{ session('success') }}
        </div>
    @endif

    @php
        $currentLogoHeight = (int)(\App\Models\SiteSetting::where('key', 'partner_strip_logo_height')->value('value') ?? 95);
        $currentLogoSpeed = (int)(\App\Models\SiteSetting::where('key', 'partner_strip_speed')->value('value') ?? 40);
    @endphp

    <style>
        .logo-size-range-track {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 9px;
            border-radius: 999px;
            background: linear-gradient(to right, #0284c7 0%, #0284c7 var(--progress, 40%), #64748b var(--progress, 40%), #64748b 100%);
            outline: none;
            cursor: pointer;
            transition: background 0.05s ease;
        }
        .logo-size-range-track::-webkit-slider-thumb {
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
        .logo-size-range-track::-webkit-slider-thumb:hover {
            transform: scale(1.15);
        }
        .logo-size-range-track::-moz-range-thumb {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #0284c7;
            border: 2.5px solid #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            cursor: pointer;
        }
    </style>

    <!-- Logo Size & Scroll Speed Controller Card -->
    <div class="card" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 24px 28px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
        <form action="{{ route('admin.settings.update') }}" method="POST" style="margin: 0;">
            @csrf
            
            <!-- Row 1: Logo Size Slider -->
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
                <div style="min-width: 220px;">
                    <h3 style="margin: 0 0 4px 0; font-size: 1.15rem; font-weight: 800; color: #0a192f; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-sliders" style="color: #0284c7;"></i> Adjust Logo Size
                    </h3>
                    <p style="margin: 0; color: #64748b; font-size: 0.88rem;">Slide to increase or decrease the logo size across the website.</p>
                </div>

                <div style="display: flex; align-items: center; gap: 16px; flex: 1; max-width: 620px; justify-content: flex-end; flex-wrap: wrap;">
                    <!-- Range Slider as in Image 1 -->
                    <div style="flex: 1; min-width: 220px; max-width: 360px; display: flex; flex-direction: column; gap: 6px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.78rem; font-weight: 700; color: #64748b;">
                            <span>Smaller (50px)</span>
                            <span>Larger (160px)</span>
                        </div>
                        <input type="range" 
                               id="logoSizeSlider" 
                               min="50" 
                               max="160" 
                               step="2" 
                               value="{{ $currentLogoHeight }}" 
                               class="logo-size-range-track"
                               oninput="syncLogoSlider(this.value)">
                    </div>

                    <!-- Number Badge -->
                    <div style="display: flex; align-items: center; gap: 4px; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 6px 12px;">
                        <input type="number" 
                               name="partner_strip_logo_height" 
                               id="logoHeightInput" 
                               min="50" 
                               max="180" 
                               value="{{ $currentLogoHeight }}" 
                               style="width: 55px; border: none; background: transparent; font-weight: 800; text-align: center; font-size: 1.05rem; color: #0284c7; outline: none;"
                               oninput="syncLogoInput(this.value)">
                        <span style="font-weight: 700; color: #64748b; font-size: 0.85rem;">px</span>
                    </div>

                    <span id="saveStatusBadge" style="font-size: 0.82rem; font-weight: 700; color: #16a34a; min-width: 75px;"></span>
                    <button type="submit" style="background: linear-gradient(135deg, #0284c7, #0369a1); color: #ffffff; border: none; padding: 11px 22px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25); transition: all 0.2s ease;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Size
                    </button>
                </div>
            </div>

            <!-- Divider -->
            <div style="width: 100%; height: 1px; background: #e2e8f0; margin: 20px 0;"></div>

            <!-- Row 2: Scroll Speed (Seconds) Slider (Image 2) -->
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
                <div style="min-width: 220px;">
                    <h3 style="margin: 0 0 4px 0; font-size: 1.15rem; font-weight: 800; color: #0a192f; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-gauge-high" style="color: #0284c7;"></i> Scroll Speed (Seconds)
                    </h3>
                    <p style="margin: 0; color: #64748b; font-size: 0.88rem;">Slide to control how fast the partner logos marquee ticker scrolls.</p>
                </div>

                <div style="display: flex; align-items: center; gap: 16px; flex: 1; max-width: 620px; justify-content: flex-end; flex-wrap: wrap;">
                    <!-- Range Slider as in Image 1 -->
                    <div style="flex: 1; min-width: 220px; max-width: 360px; display: flex; flex-direction: column; gap: 6px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.78rem; font-weight: 700; color: #64748b;">
                            <span>Faster (15s)</span>
                            <span>Slower (90s)</span>
                        </div>
                        <input type="range" 
                               id="logoSpeedSlider" 
                               min="15" 
                               max="90" 
                               step="1" 
                               value="{{ $currentLogoSpeed }}" 
                               class="logo-size-range-track"
                               oninput="syncLogoSpeedSlider(this.value)">
                    </div>

                    <!-- Number Badge -->
                    <div style="display: flex; align-items: center; gap: 4px; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 6px 12px;">
                        <input type="number" 
                               name="partner_strip_speed" 
                               id="logoSpeedInput" 
                               min="10" 
                               max="120" 
                               value="{{ $currentLogoSpeed }}" 
                               style="width: 55px; border: none; background: transparent; font-weight: 800; text-align: center; font-size: 1.05rem; color: #0284c7; outline: none;"
                               oninput="syncLogoSpeedInput(this.value)">
                        <span style="font-weight: 700; color: #64748b; font-size: 0.85rem;">sec</span>
                    </div>

                    <span id="speedSaveStatusBadge" style="font-size: 0.82rem; font-weight: 700; color: #16a34a; min-width: 75px;"></span>
                    <button type="submit" style="background: linear-gradient(135deg, #0284c7, #0369a1); color: #ffffff; border: none; padding: 11px 22px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25); transition: all 0.2s ease;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Speed
                    </button>
                </div>
            </div>

        </form>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px 25px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-weight: 700; color: #1e293b; font-size: 1.05rem;"><i class="fa-solid fa-grip-vertical" style="color: #00a896; margin-right: 8px;"></i> Active Partner Logos ({{ $logos->count() }})</span>
            <span style="font-size: 0.85rem; color: #64748b;"><i class="fa-solid fa-info-circle"></i> Drag rows to reorder layout position</span>
        </div>

        <div style="padding: 20px;">
            <div id="sortable-logos-list" style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($logos as $logo)
                    <div class="logo-item-row" data-id="{{ $logo->id }}" style="display: flex; align-items: center; justify-content: space-between; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px 20px; transition: all 0.2s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div class="drag-handle-grip" style="cursor: grab; color: #94a3b8; font-size: 1.2rem; padding: 5px;">
                                <i class="fa-solid fa-grip-vertical"></i>
                            </div>
                            <div style="width: 90px; height: 65px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 6px; flex-shrink: 0;">
                                <img src="{{ asset($logo->logo_path) }}" alt="{{ $logo->name }}" style="max-height: 100%; max-width: 100%; object-fit: contain;" onerror="this.onerror=null; this.src='https://placehold.co/120x80?text=Logo';">
                            </div>
                            <div>
                                <h4 style="margin: 0 0 4px 0; font-size: 1.05rem; font-weight: 700; color: #0a192f;">{{ $logo->name }}</h4>
                                <div style="font-size: 0.85rem; color: #64748b; display: flex; align-items: center; gap: 12px;">
                                    <span><i class="fa-solid fa-link" style="color: #00a896;"></i> {{ $logo->link_url ?? '/' }}</span>
                                    <span style="background: {{ $logo->is_active ? '#d1fae5' : '#fee2e2' }}; color: {{ $logo->is_active ? '#065f46' : '#991b1b' }}; font-weight: 700; font-size: 0.72rem; padding: 2px 8px; border-radius: 12px; text-transform: uppercase;">
                                        {{ $logo->is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px;">
                            <button type="button" onclick="openEditLogoModal({{ $logo->id }}, '{{ addslashes($logo->name) }}', '{{ addslashes($logo->link_url) }}', {{ $logo->is_active ? 1 : 0 }}, '{{ asset($logo->logo_path) }}')" style="background: #e0f2fe; color: #0369a1; border: none; padding: 8px 14px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>

                            <form action="{{ route('admin.partner_logos.destroy', $logo->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this logo?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #fee2e2; color: #b91c1c; border: none; padding: 8px 14px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-trash-can"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Overlay -->
    <div id="logoModalOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
        <div style="background: #ffffff; border-radius: 20px; width: 100%; max-width: 520px; padding: 30px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35); position: relative; margin: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px;">
                <h3 id="modalTitle" style="margin: 0; font-size: 1.3rem; font-weight: 800; color: #0a192f;">Add New Logo</h3>
                <button type="button" onclick="closeLogoModal()" style="background: none; border: none; font-size: 1.4rem; color: #64748b; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form id="logoForm" method="POST" action="{{ route('admin.partner_logos.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.9rem;">Organization / Partner Name *</label>
                    <input type="text" name="name" id="logoName" required placeholder="e.g. Madras Christian College" style="width: 100%; padding: 12px 15px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.95rem;">
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.9rem;">Target Link URL</label>
                    <input type="text" name="link_url" id="logoLink" placeholder="e.g. https://mcc.edu.in or /" style="width: 100%; padding: 12px 15px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.95rem;">
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 0.9rem;">Upload Logo Image <span id="logoRequiredMark" style="color:#ef4444;">*</span></label>
                    <input type="file" name="logo" id="logoFile" accept="image/*" style="width: 100%; padding: 10px; border: 1px dashed #00a896; border-radius: 10px; background: #f0fdf4;">
                    <div id="currentLogoPreview" style="margin-top: 10px; display: none;">
                        <span style="font-size: 0.8rem; color: #64748b;">Current Preview:</span><br>
                        <img id="previewImg" src="" style="max-height: 90px; max-width: 170px; margin-top: 5px; border-radius: 8px; border: 1px solid #cbd5e1; padding: 6px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); object-fit: contain;">
                    </div>
                </div>

                <div id="activeCheckboxGroup" style="margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_active" id="logoIsActive" value="1" checked style="accent-color: #00a896; width: 18px; height: 18px;">
                    <label for="logoIsActive" style="font-weight: 600; color: #1e293b; font-size: 0.95rem; cursor: pointer;">Show logo on live website footer</label>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" onclick="closeLogoModal()" style="background: #f1f5f9; color: #475569; border: none; padding: 12px 22px; border-radius: 10px; font-weight: 700; cursor: pointer;">Cancel</button>
                    <button type="submit" style="background: linear-gradient(135deg, #00a896, #028090); color: #ffffff; border: none; padding: 12px 28px; border-radius: 10px; font-weight: 700; cursor: pointer;">Save Logo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- HTML5 Drag & Drop Reordering Script -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const initialVal = document.getElementById('logoHeightInput')?.value || 95;
            updateSliderTrack(initialVal);
            const initialSpeed = document.getElementById('logoSpeedInput')?.value || 40;
            updateSpeedTrack(initialSpeed);

            const el = document.getElementById('sortable-logos-list');
            if (el) {
                new Sortable(el, {
                    handle: '.drag-handle-grip',
                    animation: 150,
                    onEnd: function () {
                        const order = Array.from(el.children).map(item => item.getAttribute('data-id'));
                        fetch("{{ route('admin.partner_logos.reorder') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ order: order })
                        }).then(res => res.json()).then(data => {
                            console.log('Reordered partner logos successfully.');
                        });
                    }
                });
            }
        });

        function openAddLogoModal() {
            document.getElementById('modalTitle').innerText = 'Add New Logo';
            document.getElementById('logoForm').action = "{{ route('admin.partner_logos.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('logoName').value = '';
            document.getElementById('logoLink').value = '';
            document.getElementById('logoFile').required = true;
            document.getElementById('logoRequiredMark').style.display = 'inline';
            document.getElementById('currentLogoPreview').style.display = 'none';
            document.getElementById('logoIsActive').checked = true;
            document.getElementById('logoModalOverlay').style.display = 'flex';
        }

        function openEditLogoModal(id, name, link, isActive, imgUrl) {
            document.getElementById('modalTitle').innerText = 'Edit Partner Logo';
            document.getElementById('logoForm').action = "/admin/partner-logos/" + id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('logoName').value = name;
            document.getElementById('logoLink').value = link;
            document.getElementById('logoFile').required = false;
            document.getElementById('logoRequiredMark').style.display = 'none';
            document.getElementById('previewImg').src = imgUrl;
            document.getElementById('currentLogoPreview').style.display = 'block';
            document.getElementById('logoIsActive').checked = isActive == 1;
            document.getElementById('logoModalOverlay').style.display = 'flex';
        }

        function closeLogoModal() {
            document.getElementById('logoModalOverlay').style.display = 'none';
        }

        let saveTimer = null;
        function updateSliderTrack(val) {
            const slider = document.getElementById('logoSizeSlider');
            if (!slider) return;
            const min = parseFloat(slider.min) || 50;
            const max = parseFloat(slider.max) || 160;
            const pct = Math.max(0, Math.min(100, ((val - min) / (max - min)) * 100));
            slider.style.setProperty('--progress', pct + '%');
        }

        function syncLogoSlider(val) {
            const input = document.getElementById('logoHeightInput');
            if (input) input.value = val;
            updateSliderTrack(val);

            // Auto-save via AJAX to directly reflect on the website partner ticker (Image 2)
            const badge = document.getElementById('saveStatusBadge');
            if (badge) {
                badge.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
                badge.style.color = '#0284c7';
            }

            clearTimeout(saveTimer);
            saveTimer = setTimeout(function () {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('partner_strip_logo_height', val);

                fetch("{{ route('admin.settings.update') }}", {
                    method: "POST",
                    body: formData
                }).then(() => {
                    if (badge) {
                        badge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Saved!';
                        badge.style.color = '#16a34a';
                        setTimeout(() => { badge.innerHTML = ''; }, 2500);
                    }
                }).catch(() => {
                    if (badge) { badge.innerHTML = ''; }
                });
            }, 300);
        }

        function syncLogoInput(val) {
            const slider = document.getElementById('logoSizeSlider');
            if (slider) slider.value = val;
            updateSliderTrack(val);
            syncLogoSlider(val);
        }

        let speedSaveTimer = null;
        function updateSpeedTrack(val) {
            const slider = document.getElementById('logoSpeedSlider');
            if (!slider) return;
            const min = parseFloat(slider.min) || 15;
            const max = parseFloat(slider.max) || 90;
            const pct = Math.max(0, Math.min(100, ((val - min) / (max - min)) * 100));
            slider.style.setProperty('--progress', pct + '%');
        }

        function syncLogoSpeedSlider(val) {
            const input = document.getElementById('logoSpeedInput');
            if (input) input.value = val;
            updateSpeedTrack(val);

            const badge = document.getElementById('speedSaveStatusBadge');
            if (badge) {
                badge.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
                badge.style.color = '#0284c7';
            }

            clearTimeout(speedSaveTimer);
            speedSaveTimer = setTimeout(function () {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('partner_strip_speed', val);

                fetch("{{ route('admin.settings.update') }}", {
                    method: "POST",
                    body: formData
                }).then(() => {
                    if (badge) {
                        badge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Saved!';
                        badge.style.color = '#16a34a';
                        setTimeout(() => { badge.innerHTML = ''; }, 2500);
                    }
                }).catch(() => {
                    if (badge) { badge.innerHTML = ''; }
                });
            }, 300);
        }

        function syncLogoSpeedInput(val) {
            const slider = document.getElementById('logoSpeedSlider');
            if (slider) slider.value = val;
            updateSpeedTrack(val);
            syncLogoSpeedSlider(val);
        }
    </script>
@endsection
