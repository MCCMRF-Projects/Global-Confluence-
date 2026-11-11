<!-- Navbar -->
<nav class="navbar">
    <a href="/" class="logo" style="display: flex; align-items: center; gap: 15px;">
        <div style="display: flex; justify-content: center; align-items: center; padding: 5px 0;">
            <img src="{{ asset('images/MMC-LOGO-2.jpg') }}" alt="MMC Logo" style="height: 120px; width: auto; mix-blend-mode: multiply;">
        </div>
    </a>
    
    <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle Navigation">
        <i class="fa-solid fa-bars"></i>
    </button>
    
    <div class="nav-links" id="navLinks">
        <!-- 1. Home -->
        @if(($settings['nav_home_show'] ?? '1') == '1')
            <a href="{{ $settings['nav_home_url'] ?? '/' }}">{{ $settings['nav_home_label'] ?? 'Home' }}</a>
        @endif

        <!-- 2. Technical Events Dropdown -->
        @if(($settings['nav_tech_events_show'] ?? '1') == '1')
            <div class="nav-dropdown">
                <a href="{{ $settings['nav_tech_events_url'] ?? '#' }}" style="display: flex; align-items: center;">
                    {{ $settings['nav_tech_events_label'] ?? 'Technical Events' }}
                    <i class="fa-solid fa-chevron-down nav-arrow" style="font-size: 0.75rem; margin-left: 4px; transition: transform 0.2s;"></i>
                </a>
                <div class="nav-dropdown-content" style="min-width: 230px;">
                    @if(($settings['nav_tracks_show'] ?? '1') == '1')
                        <a href="{{ $settings['nav_tracks_url'] ?? route('scientific-themes') }}">{{ $settings['nav_tracks_label'] ?? 'Tracks' }}</a>
                    @endif

                    @if(($settings['nav_event_list_show'] ?? '1') == '1')
                        <div style="background-color: #f8fafc; border-top: 1px solid var(--border-light); padding: 5px 0;">
                            <div style="padding: 8px 20px 4px; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; color: #00A896; letter-spacing: 0.5px; text-align: left;">
                                {{ $settings['nav_event_list_label'] ?? 'Events' }}
                            </div>
                            @if(($settings['nav_oral_show'] ?? '1') == '1')
                                <a href="{{ $settings['nav_oral_url'] ?? route('events.oral_presentation') }}" style="padding: 9px 20px 9px 28px; font-size: 0.98rem; color: #334155; text-align: left; font-weight: 600;">{{ $settings['nav_oral_label'] ?? 'Oral Presentation' }}</a>
                            @endif
                            @if(($settings['nav_poster_show'] ?? '1') == '1')
                                <a href="{{ $settings['nav_poster_url'] ?? route('events.poster_presentation') }}" style="padding: 9px 20px 9px 28px; font-size: 0.98rem; color: #334155; text-align: left; font-weight: 600;">{{ $settings['nav_poster_label'] ?? 'Poster Presentation' }}</a>
                            @endif
                            @if(($settings['nav_innovation_show'] ?? '1') == '1')
                                <a href="{{ $settings['nav_innovation_url'] ?? route('events.innovation_pitch') }}" style="padding: 9px 20px 9px 28px; font-size: 0.98rem; color: #334155; text-align: left; font-weight: 600;">{{ $settings['nav_innovation_label'] ?? 'Innovation Pitch' }}</a>
                            @endif
                            @if(($settings['nav_hackathon_show'] ?? '1') == '1')
                                <a href="{{ $settings['nav_hackathon_url'] ?? route('events.hackathon') }}" style="padding: 9px 20px 9px 28px; font-size: 0.98rem; color: #334155; text-align: left; font-weight: 600;">{{ $settings['nav_hackathon_label'] ?? 'Hackathon' }}</a>
                            @endif
                        </div>
                    @endif

                    @if(($settings['nav_publications_show'] ?? '1') == '1')
                        <div style="border-top: 1px solid var(--border-light);">
                            <a href="{{ $settings['nav_publications_url'] ?? url('/publications') }}">{{ $settings['nav_publications_label'] ?? 'Publications' }}</a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- 3. Registrations -->
        @if(($settings['nav_registrations_show'] ?? '1') == '1')
            <a href="{{ $settings['nav_registrations_url'] ?? route('registration') }}">{{ $settings['nav_registrations_label'] ?? 'Registrations' }}</a>
        @endif

        <!-- 4. Experts (Dropdown) -->
        @if(($settings['nav_experts_show'] ?? '1') == '1')
            <div class="nav-dropdown">
                <a href="{{ $settings['nav_experts_url'] ?? '#' }}" style="display: flex; align-items: center;">
                    {{ $settings['nav_experts_label'] ?? 'Experts' }}
                    <i class="fa-solid fa-chevron-down nav-arrow" style="font-size: 0.75rem; margin-left: 4px; transition: transform 0.2s;"></i>
                </a>
                <div class="nav-dropdown-content">
                    @if(($settings['nav_keynote_show'] ?? '1') == '1')
                        <a href="{{ $settings['nav_keynote_url'] ?? route('keynote-speakers') }}">{{ $settings['nav_keynote_label'] ?? 'Keynote Speakers' }}</a>
                    @endif
                    @if(($settings['nav_distinguished_show'] ?? '1') == '1')
                        <a href="{{ $settings['nav_distinguished_url'] ?? route('distinguished-speakers') }}">{{ $settings['nav_distinguished_label'] ?? 'Distinguished Speakers' }}</a>
                    @endif
                </div>
            </div>
        @endif

        <!-- 5. Distinguished Awards -->
        @if(($settings['nav_dist_awards_show'] ?? '1') == '1')
            <a href="{{ $settings['nav_dist_awards_url'] ?? route('awards') }}">{{ $settings['nav_dist_awards_label'] ?? 'Distinguished Awards' }}</a>
        @endif

        <!-- 6. Committee -->
        @if(($settings['nav_committee_show'] ?? '1') == '1')
            <a href="{{ $settings['nav_committee_url'] ?? route('committee') }}">{{ $settings['nav_committee_label'] ?? 'Committee' }}</a>
        @endif

        <!-- 7. Pre-Conference -->
        @if(($settings['nav_preconf_show'] ?? '1') == '1')
            <a href="{{ $settings['nav_preconf_url'] ?? route('pre-conference') }}">{{ $settings['nav_preconf_label'] ?? 'Pre-Conference' }}</a>
        @endif

        <!-- 8. Stall Booking and Merchandise -->
        @if(($settings['nav_stall_show'] ?? '1') == '1')
            <a href="{{ $settings['nav_stall_url'] ?? '/page/stall-booking-and-merchandise' }}">{{ $settings['nav_stall_label'] ?? 'Stall Booking and Merchandise' }}</a>
        @endif

        <!-- 9. Glimpse of MCC Dropdown -->
        @if(($settings['nav_mcc_show'] ?? '1') == '1')
            <div class="nav-dropdown">
                <a href="{{ $settings['nav_mcc_url'] ?? '#' }}" style="display: flex; align-items: center;">
                    {{ $settings['nav_mcc_label'] ?? 'Glimpse of MCC' }}
                    <i class="fa-solid fa-chevron-down nav-arrow" style="font-size: 0.75rem; margin-left: 4px; transition: transform 0.2s;"></i>
                </a>
                <div class="nav-dropdown-content" style="min-width: 220px;">
                    <a href="{{ route('mcc-memorial') }}" style="padding: 12px 20px; font-size: 1rem; color: #1e293b; font-weight: 600; text-align: left; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-landmark" style="color: #00A896; font-size: 0.95rem;"></i> {{ $settings['nav_mcc_gallery_label'] ?? 'MCC Gallery' }}
                    </a>
                    <a href="{{ route('venue') }}" style="padding: 12px 20px; font-size: 1rem; color: #1e293b; font-weight: 600; text-align: left; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-map-location-dot" style="color: #00A896; font-size: 0.95rem;"></i> {{ $settings['nav_visit_places_label'] ?? 'Places to Visit' }}
                    </a>
                </div>
            </div>
        @endif

        <!-- 10. Contact Us -->
        @if(($settings['nav_contact_show'] ?? '1') == '1')
            <a href="{{ $settings['nav_contact_url'] ?? route('contact') }}">{{ $settings['nav_contact_label'] ?? 'Contact Us' }}</a>
        @endif
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile nav toggle
        var mobileToggle = document.getElementById('mobileToggle');
        var navLinks = document.getElementById('navLinks');
        if (mobileToggle && navLinks) {
            mobileToggle.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });
        }
    });
</script>
