@extends('layouts.admin_cms')

@section('header_title', 'Header Navigation Settings')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--admin-border); padding-bottom: 15px;">
        <div>
            <h3 style="color: var(--admin-sidebar); margin-bottom: 5px;">Header Navigation Bar Management</h3>
            <p style="font-size: 0.9rem; color: #64748b;">Customize all navigation item names (labels), destination URLs/paths, and visibility across the main header navigation menu.</p>
        </div>
        <button type="submit" form="navSettingsForm" class="btn" style="background-color: var(--admin-primary); padding: 10px 24px; font-size: 0.95rem;">
            <i class="fa-solid fa-floppy-disk" style="margin-right: 6px;"></i> Save All Changes
        </button>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #10b981; font-weight: 500;">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
        </div>
    @endif

    <form id="navSettingsForm" action="{{ route('admin.navigation.update') }}" method="POST">
        @csrf

        <style>
            .nav-item-card {
                background: #f8fafc;
                border: 1px solid var(--admin-border);
                border-radius: 12px;
                padding: 20px;
                margin-bottom: 20px;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .nav-item-card:hover {
                border-color: #cbd5e1;
                box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            }
            .nav-item-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 1px dashed #e2e8f0;
            }
            .nav-item-title {
                font-weight: 600;
                color: var(--admin-sidebar);
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .form-row {
                display: grid;
                grid-template-columns: 1fr 1fr 120px;
                gap: 15px;
                align-items: center;
            }
            .form-group label {
                display: block;
                font-size: 0.85rem;
                font-weight: 600;
                color: #475569;
                margin-bottom: 6px;
            }
            .form-group input, .form-group select {
                width: 100%;
                padding: 10px 14px;
                border-radius: 8px;
                border: 1px solid var(--admin-border);
                background-color: #ffffff;
                font-size: 0.9rem;
                color: var(--admin-sidebar);
                box-sizing: border-box;
            }
            .form-group input:focus, .form-group select:focus {
                outline: none;
                border-color: var(--admin-primary);
                box-shadow: 0 0 0 3px rgba(0, 168, 150, 0.15);
            }
            .badge-dropdown {
                background-color: #e0f2fe;
                color: #0369a1;
                font-size: 0.75rem;
                padding: 3px 8px;
                border-radius: 4px;
                font-weight: 600;
            }
        </style>

        <!-- 1. Home -->
        <div class="nav-item-card">
            <div class="nav-item-header">
                <div class="nav-item-title"><i class="fa-solid fa-house" style="color: var(--admin-primary);"></i> 1. Home Link</div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Menu Item Display Name (Label)</label>
                    <input type="text" name="nav_home_label" value="{{ $settings['nav_home_label'] ?? 'Home' }}" required>
                </div>
                <div class="form-group">
                    <label>Target URL / Path</label>
                    <input type="text" name="nav_home_url" value="{{ $settings['nav_home_url'] ?? '/' }}" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="nav_home_show">
                        <option value="1" {{ ($settings['nav_home_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                        <option value="0" {{ ($settings['nav_home_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 2. Technical Events Dropdown Menu -->
        <div class="nav-item-card" style="border-left: 4px solid var(--admin-primary);">
            <div class="nav-item-header">
                <div class="nav-item-title">
                    <i class="fa-solid fa-flask" style="color: var(--admin-primary);"></i> 2. Technical Events Dropdown Header & Submenus
                    <span class="badge-dropdown">Dropdown Menu</span>
                </div>
            </div>
            <div class="form-row" style="margin-bottom: 15px;">
                <div class="form-group">
                    <label>Dropdown Parent Name (Label)</label>
                    <input type="text" name="nav_tech_events_label" value="{{ $settings['nav_tech_events_label'] ?? 'Technical Events' }}" required>
                </div>
                <div class="form-group">
                    <label>Parent Target Link (Default: #)</label>
                    <input type="text" name="nav_tech_events_url" value="{{ $settings['nav_tech_events_url'] ?? '#' }}">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="nav_tech_events_show">
                        <option value="1" {{ ($settings['nav_tech_events_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                        <option value="0" {{ ($settings['nav_tech_events_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>

            <!-- Submenu Items inside Technical Events -->
            <div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 10px;">
                <h5 style="margin-bottom: 12px; color: #475569; font-weight: 600; font-size: 0.85rem;">TECHNICAL EVENTS SUBMENU OPTIONS:</h5>
                
                <!-- Sub-option 1: Tracks -->
                <div class="form-row" style="margin-bottom: 12px;">
                    <div class="form-group">
                        <label>Item 1 Label (Tracks)</label>
                        <input type="text" name="nav_tracks_label" value="{{ $settings['nav_tracks_label'] ?? 'Tracks' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Item 1 Target URL</label>
                        <input type="text" name="nav_tracks_url" value="{{ $settings['nav_tracks_url'] ?? '/scientific-themes' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="nav_tracks_show">
                            <option value="1" {{ ($settings['nav_tracks_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                            <option value="0" {{ ($settings['nav_tracks_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                        </select>
                    </div>
                </div>

                <!-- Submenu Flyout: Event List -->
                <div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px dashed #cbd5e1; margin-top: 10px;">
                    <div class="form-row" style="margin-bottom: 12px;">
                        <div class="form-group">
                            <label style="color: #0284c7;">Submenu Flyout Group Label</label>
                            <input type="text" name="nav_event_list_label" value="{{ $settings['nav_event_list_label'] ?? 'Event List' }}" required>
                        </div>
                        <div class="form-group">
                            <label style="color: #0284c7;">Submenu Flyout Link</label>
                            <input type="text" name="nav_event_list_url" value="{{ $settings['nav_event_list_url'] ?? '#' }}">
                        </div>
                        <div class="form-group">
                            <label style="color: #0284c7;">Status</label>
                            <select name="nav_event_list_show">
                                <option value="1" {{ ($settings['nav_event_list_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                                <option value="0" {{ ($settings['nav_event_list_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                            </select>
                        </div>
                    </div>

                    <h6 style="margin-bottom: 10px; color: #64748b; font-size: 0.8rem;">ITEMS INSIDE EVENT LIST:</h6>

                    <!-- 1. Oral Presentation -->
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="form-group">
                            <label>Oral Presentation Label</label>
                            <input type="text" name="nav_oral_label" value="{{ $settings['nav_oral_label'] ?? 'Oral Presentation' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Oral Presentation Target URL</label>
                            <input type="text" name="nav_oral_url" value="{{ $settings['nav_oral_url'] ?? '/page/oral-presentation' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="nav_oral_show">
                                <option value="1" {{ ($settings['nav_oral_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                                <option value="0" {{ ($settings['nav_oral_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                            </select>
                        </div>
                    </div>

                    <!-- 2. Poster Presentation -->
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="form-group">
                            <label>Poster Presentation Label</label>
                            <input type="text" name="nav_poster_label" value="{{ $settings['nav_poster_label'] ?? 'Poster Presentation' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Poster Presentation Target URL</label>
                            <input type="text" name="nav_poster_url" value="{{ $settings['nav_poster_url'] ?? '/page/poster-presentation' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="nav_poster_show">
                                <option value="1" {{ ($settings['nav_poster_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                                <option value="0" {{ ($settings['nav_poster_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                            </select>
                        </div>
                    </div>

                    <!-- 3. Innovation Pitch -->
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="form-group">
                            <label>Innovation Pitch Label</label>
                            <input type="text" name="nav_innovation_label" value="{{ $settings['nav_innovation_label'] ?? 'Innovation Pitch' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Innovation Pitch Target URL</label>
                            <input type="text" name="nav_innovation_url" value="{{ $settings['nav_innovation_url'] ?? '/page/innovation-pitch' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="nav_innovation_show">
                                <option value="1" {{ ($settings['nav_innovation_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                                <option value="0" {{ ($settings['nav_innovation_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                            </select>
                        </div>
                    </div>

                    <!-- 4. Hackathon -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>Hackathon Label</label>
                            <input type="text" name="nav_hackathon_label" value="{{ $settings['nav_hackathon_label'] ?? 'Hackathon' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Hackathon Target URL</label>
                            <input type="text" name="nav_hackathon_url" value="{{ $settings['nav_hackathon_url'] ?? '/page/hackathon' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="nav_hackathon_show">
                                <option value="1" {{ ($settings['nav_hackathon_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                                <option value="0" {{ ($settings['nav_hackathon_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sub-option 3: Publications -->
                <div class="form-row" style="margin-top: 14px;">
                    <div class="form-group">
                        <label>Item 3 Label (Publications)</label>
                        <input type="text" name="nav_publications_label" value="{{ $settings['nav_publications_label'] ?? 'Publications' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Item 3 Target URL</label>
                        <input type="text" name="nav_publications_url" value="{{ $settings['nav_publications_url'] ?? '/publications' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="nav_publications_show">
                            <option value="1" {{ ($settings['nav_publications_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                            <option value="0" {{ ($settings['nav_publications_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Registrations -->
        <div class="nav-item-card">
            <div class="nav-item-header">
                <div class="nav-item-title"><i class="fa-solid fa-id-card" style="color: var(--admin-primary);"></i> 3. Registrations Link</div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Menu Item Display Name (Label)</label>
                    <input type="text" name="nav_registrations_label" value="{{ $settings['nav_registrations_label'] ?? 'Registrations' }}" required>
                </div>
                <div class="form-group">
                    <label>Target URL / Path</label>
                    <input type="text" name="nav_registrations_url" value="{{ $settings['nav_registrations_url'] ?? '/registration' }}" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="nav_registrations_show">
                        <option value="1" {{ ($settings['nav_registrations_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                        <option value="0" {{ ($settings['nav_registrations_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 4. Experts Dropdown Menu -->
        <div class="nav-item-card" style="border-left: 4px solid var(--admin-primary);">
            <div class="nav-item-header">
                <div class="nav-item-title">
                    <i class="fa-solid fa-user-tie" style="color: var(--admin-primary);"></i> 4. Experts Dropdown Header & Submenus
                    <span class="badge-dropdown">Dropdown Menu</span>
                </div>
            </div>
            <div class="form-row" style="margin-bottom: 15px;">
                <div class="form-group">
                    <label>Dropdown Parent Name (Label)</label>
                    <input type="text" name="nav_experts_label" value="{{ $settings['nav_experts_label'] ?? 'Experts' }}" required>
                </div>
                <div class="form-group">
                    <label>Parent Target Link (Default: #)</label>
                    <input type="text" name="nav_experts_url" value="{{ $settings['nav_experts_url'] ?? '#' }}">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="nav_experts_show">
                        <option value="1" {{ ($settings['nav_experts_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                        <option value="0" {{ ($settings['nav_experts_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>

            <!-- Submenu Items -->
            <div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 10px;">
                <h5 style="margin-bottom: 12px; color: #475569; font-weight: 600; font-size: 0.85rem;">SUBMENU ITEMS INSIDE EXPERTS DROPDOWN:</h5>
                
                <!-- Submenu 1: Keynote Speakers -->
                <div class="form-row" style="margin-bottom: 12px;">
                    <div class="form-group">
                        <label>Submenu Item 1 Label</label>
                        <input type="text" name="nav_keynote_label" value="{{ $settings['nav_keynote_label'] ?? 'Keynote Speakers' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Submenu Item 1 Target URL</label>
                        <input type="text" name="nav_keynote_url" value="{{ $settings['nav_keynote_url'] ?? '/keynote-speakers' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="nav_keynote_show">
                            <option value="1" {{ ($settings['nav_keynote_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                            <option value="0" {{ ($settings['nav_keynote_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                        </select>
                    </div>
                </div>

                <!-- Submenu 2: Distinguished Speakers -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Submenu Item 2 Label</label>
                        <input type="text" name="nav_distinguished_label" value="{{ $settings['nav_distinguished_label'] ?? 'Distinguished Speakers' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Submenu Item 2 Target URL</label>
                        <input type="text" name="nav_distinguished_url" value="{{ $settings['nav_distinguished_url'] ?? '/distinguished-speakers' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="nav_distinguished_show">
                            <option value="1" {{ ($settings['nav_distinguished_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                            <option value="0" {{ ($settings['nav_distinguished_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Distinguished Awards -->
        <div class="nav-item-card">
            <div class="nav-item-header">
                <div class="nav-item-title"><i class="fa-solid fa-trophy" style="color: var(--admin-primary);"></i> 5. Distinguished Awards Link</div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Menu Item Display Name (Label)</label>
                    <input type="text" name="nav_dist_awards_label" value="{{ $settings['nav_dist_awards_label'] ?? 'Distinguished Awards' }}" required>
                </div>
                <div class="form-group">
                    <label>Target URL / Path</label>
                    <input type="text" name="nav_dist_awards_url" value="{{ $settings['nav_dist_awards_url'] ?? '/awards' }}" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="nav_dist_awards_show">
                        <option value="1" {{ ($settings['nav_dist_awards_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                        <option value="0" {{ ($settings['nav_dist_awards_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 6. Committee -->
        <div class="nav-item-card">
            <div class="nav-item-header">
                <div class="nav-item-title"><i class="fa-solid fa-users" style="color: var(--admin-primary);"></i> 6. Committee Link</div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Menu Item Display Name (Label)</label>
                    <input type="text" name="nav_committee_label" value="{{ $settings['nav_committee_label'] ?? 'Committee' }}" required>
                </div>
                <div class="form-group">
                    <label>Target URL / Path</label>
                    <input type="text" name="nav_committee_url" value="{{ $settings['nav_committee_url'] ?? '/committee' }}" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="nav_committee_show">
                        <option value="1" {{ ($settings['nav_committee_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                        <option value="0" {{ ($settings['nav_committee_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 7. Pre-Conference -->
        <div class="nav-item-card">
            <div class="nav-item-header">
                <div class="nav-item-title"><i class="fa-solid fa-person-chalkboard" style="color: var(--admin-primary);"></i> 7. Pre-Conference Link</div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Menu Item Display Name (Label)</label>
                    <input type="text" name="nav_preconf_label" value="{{ $settings['nav_preconf_label'] ?? 'Pre-Conference' }}" required>
                </div>
                <div class="form-group">
                    <label>Target URL / Path</label>
                    <input type="text" name="nav_preconf_url" value="{{ $settings['nav_preconf_url'] ?? '/pre-conference' }}" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="nav_preconf_show">
                        <option value="1" {{ ($settings['nav_preconf_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                        <option value="0" {{ ($settings['nav_preconf_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 8. Stall Booking and Merchandise (Dropdown) -->
        <div class="nav-item-card" style="border-left: 4px solid #8b5cf6;">
            <div class="nav-item-header">
                <div class="nav-item-title">
                    <i class="fa-solid fa-store" style="color: #8b5cf6;"></i> 8. Stall Booking &amp; Merchandise Dropdown Header &amp; Submenus
                    <span class="badge-dropdown" style="background-color: #f3e8ff; color: #6b21a8;">Dropdown Menu</span>
                </div>
            </div>
            <div class="form-row" style="margin-bottom: 15px;">
                <div class="form-group">
                    <label>Dropdown Parent Name (Label)</label>
                    <input type="text" name="nav_stall_label" value="{{ $settings['nav_stall_label'] ?? 'Stall Booking and Merchandise' }}" required>
                </div>
                <div class="form-group">
                    <label>Parent Target Link (Default: #)</label>
                    <input type="text" name="nav_stall_url" value="{{ $settings['nav_stall_url'] ?? '#' }}">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="nav_stall_show">
                        <option value="1" {{ ($settings['nav_stall_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                        <option value="0" {{ ($settings['nav_stall_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>

            <!-- Submenu Items inside Stall Booking Dropdown -->
            <div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 10px;">
                <h5 style="margin-bottom: 12px; color: #475569; font-weight: 600; font-size: 0.85rem;">SUBMENU ITEMS INSIDE STALL BOOKING DROPDOWN:</h5>

                <!-- Submenu 1: Original Stall Booking & Merchandise Page -->
                <div class="form-row" style="margin-bottom: 12px;">
                    <div class="form-group">
                        <label>Submenu Item 1 Label</label>
                        <input type="text" name="nav_stall_item1_label" value="{{ $settings['nav_stall_item1_label'] ?? 'Stall Booking and Merchandise' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Submenu Item 1 Target URL</label>
                        <input type="text" name="nav_stall_item1_url" value="{{ $settings['nav_stall_item1_url'] ?? '/page/stall-booking-and-merchandise' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="nav_stall_item1_show">
                            <option value="1" {{ ($settings['nav_stall_item1_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                            <option value="0" {{ ($settings['nav_stall_item1_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                        </select>
                    </div>
                </div>

                <!-- Submenu 2: Stall Booking Details Page -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Submenu Item 2 Label</label>
                        <input type="text" name="nav_stall_item2_label" value="{{ $settings['nav_stall_item2_label'] ?? 'Stall Booking Details' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Submenu Item 2 Target URL</label>
                        <input type="text" name="nav_stall_item2_url" value="{{ $settings['nav_stall_item2_url'] ?? '/page/stall-booking-details' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="nav_stall_item2_show">
                            <option value="1" {{ ($settings['nav_stall_item2_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                            <option value="0" {{ ($settings['nav_stall_item2_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 9. Glimpse of MCC (Dropdown) -->
        <div class="nav-item-card">
            <div class="nav-item-header">
                <div class="nav-item-title">
                    <i class="fa-solid fa-landmark" style="color: var(--admin-primary);"></i> 9. Glimpse of MCC
                    <span class="badge-dropdown">Dropdown with Places to Visit</span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Menu Item Display Name (Label)</label>
                    <input type="text" name="nav_mcc_label" value="{{ $settings['nav_mcc_label'] ?? 'Glimpse of MCC' }}" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="nav_mcc_show">
                        <option value="1" {{ ($settings['nav_mcc_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                        <option value="0" {{ ($settings['nav_mcc_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
            <div class="form-row" style="margin-top: 10px;">
                <div class="form-group">
                    <label style="color: #00A896;">Sub-item 1: MCC Gallery Label</label>
                    <input type="text" name="nav_mcc_gallery_label" value="{{ $settings['nav_mcc_gallery_label'] ?? 'MCC Gallery' }}">
                </div>
                <div class="form-group">
                    <label style="color: #00A896;">Sub-item 2: Places to Visit Label</label>
                    <input type="text" name="nav_visit_places_label" value="{{ $settings['nav_visit_places_label'] ?? 'Places to Visit' }}">
                </div>
            </div>
        </div>

        <!-- 10. Contact Us -->
        <div class="nav-item-card" style="border-left: 4px solid #06b6d4;">
            <div class="nav-item-header">
                <div class="nav-item-title">
                    <i class="fa-solid fa-envelope" style="color: #06b6d4;"></i> 10. Contact Us Link
                    <span class="badge-dropdown" style="background-color: #cffafe; color: #155e75;">Blank Page Ready</span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Menu Item Display Name (Label)</label>
                    <input type="text" name="nav_contact_label" value="{{ $settings['nav_contact_label'] ?? 'Contact Us' }}" required>
                </div>
                <div class="form-group">
                    <label>Target URL / Path</label>
                    <input type="text" name="nav_contact_url" value="{{ $settings['nav_contact_url'] ?? '/page/contact-us' }}" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="nav_contact_show">
                        <option value="1" {{ ($settings['nav_contact_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                        <option value="0" {{ ($settings['nav_contact_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 11. Register CTA Button -->
        <div class="nav-item-card" style="border-left: 4px solid #84cc16;">
            <div class="nav-item-header">
                <div class="nav-item-title">
                    <i class="fa-solid fa-right-to-bracket" style="color: #84cc16;"></i> Header Action Button (CTA)
                    <span class="badge-dropdown" style="background-color: #ecfccb; color: #3f6212;">Header CTA Button</span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Button Text (Label)</label>
                    <input type="text" name="nav_register_label" value="{{ $settings['nav_register_label'] ?? 'REGISTER' }}" required>
                </div>
                <div class="form-group">
                    <label>Target URL / Path</label>
                    <input type="text" name="nav_register_url" value="{{ $settings['nav_register_url'] ?? '/registration' }}" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="nav_register_show">
                        <option value="1" {{ ($settings['nav_register_show'] ?? '1') == '1' ? 'selected' : '' }}>Visible</option>
                        <option value="0" {{ ($settings['nav_register_show'] ?? '1') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px; text-align: right;">
            <button type="submit" class="btn" style="padding: 12px 30px; font-size: 1rem;">
                <i class="fa-solid fa-floppy-disk" style="margin-right: 8px;"></i> Save All Navigation Settings
            </button>
        </div>
    </form>
</div>
@endsection
