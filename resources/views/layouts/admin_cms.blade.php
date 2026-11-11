<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BioMed Summit 2027</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --admin-bg: #f4f7fb;
            --admin-sidebar: #0f172a;
            --admin-sidebar-hover: rgba(255, 255, 255, 0.06);
            --admin-primary: #00A896;
            --admin-text: #475569;
            --admin-white: #ffffff;
            --admin-border: #e2e8f0;
            --admin-green: #10b981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: high-quality;
        }

        body {
            background-color: var(--admin-bg);
            color: var(--admin-text);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 270px;
            background: linear-gradient(180deg, var(--admin-sidebar) 0%, #1e293b 100%);
            color: #94a3b8;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 10;
            transition: transform 0.3s ease;
            box-shadow: 4px 0 15px rgba(0,0,0,0.05);
        }

        .sidebar-header {
            min-height: 85px;
            padding: 15px 20px;
            background-color: #ffffff;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .sidebar-header i {
            color: var(--admin-primary);
            font-size: 1.5rem;
        }

        .nav-links {
            list-style: none;
            padding: 15px 0 30px;
            display: flex;
            flex-direction: column;
            gap: 3px;
            overflow-y: auto;
            flex: 1;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background-color: rgba(148, 163, 184, 0.3);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background-color: rgba(148, 163, 184, 0.5);
        }
        
        .content-area::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
        }

        .nav-links li a.nav-item-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 22px;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            font-size: 0.92rem;
            border-left: 4px solid transparent;
        }

        .nav-links li a.nav-item-link:hover, .nav-links li a.nav-item-link.active {
            background-color: var(--admin-sidebar-hover);
            color: #fff;
            border-left: 4px solid var(--admin-primary);
        }
        .nav-links li a.nav-item-link:hover {
            padding-left: 26px;
        }

        .nav-links li a.nav-item-link i {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
        }

        /* Dropdown Category in Sidebar */
        .nav-item-dropdown {
            display: flex;
            flex-direction: column;
        }

        .nav-dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 22px;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.92rem;
            cursor: pointer;
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
            user-select: none;
        }

        .nav-dropdown-toggle:hover, .nav-item-dropdown.open .nav-dropdown-toggle {
            background-color: var(--admin-sidebar-hover);
            color: #fff;
        }

        .nav-item-dropdown.open .nav-dropdown-toggle {
            border-left-color: var(--admin-primary);
        }

        .nav-dropdown-toggle .toggle-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-dropdown-toggle .toggle-left i {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
        }

        .nav-dropdown-toggle .chevron {
            font-size: 0.72rem;
            transition: transform 0.25s ease;
            color: #94a3b8;
        }

        .nav-item-dropdown.open .nav-dropdown-toggle .chevron {
            transform: rotate(180deg);
            color: var(--admin-primary);
        }

        .sub-nav-links {
            list-style: none;
            padding: 4px 0 6px 0;
            background: rgba(15, 23, 42, 0.4);
            display: none;
        }

        .nav-item-dropdown.open .sub-nav-links {
            display: block;
        }

        .sub-nav-links li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 20px 8px 45px;
            font-size: 0.86rem;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sub-nav-links li a:hover, .sub-nav-links li a.active {
            color: #38bdf8;
            background-color: rgba(255, 255, 255, 0.04);
            border-left-color: #38bdf8;
            padding-left: 50px;
        }

        .sub-nav-links li a i {
            font-size: 0.8rem;
            width: 14px;
            text-align: center;
        }

        .sidebar-section-header {
            padding: 16px 22px 6px;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 800;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            min-width: 0;
            margin-left: 270px;
            display: flex;
            flex-direction: column;
            background-color: var(--admin-bg);
        }

        .topbar {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 5;
            height: 85px;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--admin-border);
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }

        .topbar .title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--admin-sidebar);
        }

        .content-area {
            padding: 30px;
            flex: 1;
            min-width: 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Card Styles */
        .card {
            background-color: var(--admin-white);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
            padding: 25px;
            margin-bottom: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: var(--admin-primary);
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: background 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #008a7a;
        }

        .hamburger-menu {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--admin-sidebar);
            cursor: pointer;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(17, 35, 64, 0.5);
            z-index: 9;
            backdrop-filter: blur(2px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .topbar {
                padding: 0 15px;
            }
            .content-area {
                padding: 15px;
            }
            .hamburger-menu {
                display: block;
            }

            /* Smart Grid Overrides for inline styles */
            .main-content div[style*="grid-template-columns: 1fr 350px"],
            .main-content div[style*="grid-template-columns: 1fr 350px;"],
            .main-content div[style*="grid-template-columns: 2fr 1fr"],
            .main-content div[style*="grid-template-columns: 2fr 1fr;"],
            .main-content div[style*="grid-template-columns: 1fr 1fr"],
            .main-content div[style*="grid-template-columns: 1fr 1fr;"],
            .main-content div[style*="grid-template-columns: 3fr 1fr"],
            .main-content div[style*="grid-template-columns: 3fr 1fr;"],
            .main-content div[style*="grid-template-columns: 1fr 1fr 1fr"],
            .main-content div[style*="grid-template-columns: 1fr 1fr 1fr;"],
            .main-content div[style*="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr))"],
            .main-content div[style*="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));"],
            .main-content div[style*="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr))"],
            .main-content div[style*="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));"],
            .crm-detail-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/MMC-LOGO-2.jpg') }}" alt="MMC Logo" style="height: 70px; width: auto; object-fit: contain; mix-blend-mode: multiply;">
        </div>
        
        <ul class="nav-links">
            <!-- 0. Dashboard & Data Records -->
            <li>
                <a href="{{ route('admin.dashboard') }}" class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.registrations') }}" class="nav-item-link {{ request()->routeIs('admin.registrations') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Registered Attendees
                </a>
            </li>
            <li>
                <a href="{{ route('admin.submissions') }}" class="nav-item-link {{ request()->routeIs('admin.submissions') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-arrow-up"></i> Paper Submissions
                </a>
            </li>
            <li>
                <a href="{{ route('admin.award_applications') }}" class="nav-item-link {{ request()->routeIs('admin.award_applications') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-lines"></i> Award Applications
                </a>
            </li>

            <!-- SECTION: MAIN NAVIGATION PAGES (Arranged exactly as Frontend UI) -->
            <li class="sidebar-section-header">
                <i class="fa-solid fa-layer-group" style="font-size: 0.75rem;"></i> Navigation Pages
            </li>

            <!-- 1. Home -->
            <li>
                <a href="{{ route('admin.home') }}" class="nav-item-link {{ request()->routeIs('admin.home') || request()->is('admin/home*') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> 1. Home
                </a>
            </li>

            <!-- 2. Technical Events (Dropdown matching UI exactly) -->
            @php
                $isTechEventsActive = request()->routeIs('admin.tracks') 
                    || request()->routeIs('admin.oral_presentation') 
                    || request()->routeIs('admin.poster_presentation') 
                    || request()->routeIs('admin.innovation_pitch') 
                    || request()->routeIs('admin.hackathon') 
                    || request()->routeIs('admin.guidelines');
            @endphp
            <li class="nav-item-dropdown {{ $isTechEventsActive ? 'open' : '' }}">
                <div class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <div class="toggle-left">
                        <i class="fa-solid fa-calendar-check"></i> 2. Technical Events
                    </div>
                    <i class="fa-solid fa-chevron-down chevron"></i>
                </div>
                <ul class="sub-nav-links">
                    <li>
                        <a href="{{ route('admin.tracks') }}" class="{{ request()->routeIs('admin.tracks') ? 'active' : '' }}">
                            <i class="fa-solid fa-flask"></i> Tracks
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.oral_presentation') }}" class="{{ request()->routeIs('admin.oral_presentation') ? 'active' : '' }}">
                            <i class="fa-solid fa-microphone-lines"></i> Oral Presentation
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.poster_presentation') }}" class="{{ request()->routeIs('admin.poster_presentation') ? 'active' : '' }}">
                            <i class="fa-solid fa-image"></i> Poster Presentation
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.innovation_pitch') }}" class="{{ request()->routeIs('admin.innovation_pitch') ? 'active' : '' }}">
                            <i class="fa-solid fa-lightbulb"></i> Innovation Pitch
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.hackathon') }}" class="{{ request()->routeIs('admin.hackathon') ? 'active' : '' }}">
                            <i class="fa-solid fa-code"></i> Hackathon
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.guidelines') }}" class="{{ request()->routeIs('admin.guidelines') ? 'active' : '' }}">
                            <i class="fa-solid fa-book-open"></i> Publications
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 3. Registrations -->
            <li>
                <a href="{{ route('admin.fees') }}" class="nav-item-link {{ request()->routeIs('admin.fees') ? 'active' : '' }}">
                    <i class="fa-solid fa-id-card"></i> 3. Registrations
                </a>
            </li>

            <!-- 4. Speakers -->
            <li>
                <a href="{{ route('admin.experts') }}" class="nav-item-link {{ request()->routeIs('admin.experts') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-tie"></i> 4. Speakers
                </a>
            </li>

            <!-- 5. Distinguished Awards -->
            <li>
                <a href="{{ route('admin.awards') }}" class="nav-item-link {{ request()->routeIs('admin.awards') ? 'active' : '' }}">
                    <i class="fa-solid fa-trophy"></i> 5. Distinguished Awards
                </a>
            </li>

            <!-- 6. Committee -->
            <li>
                <a href="{{ route('admin.committee') }}" class="nav-item-link {{ request()->routeIs('admin.committee') ? 'active' : '' }}">
                    <i class="fa-solid fa-users-gear"></i> 6. Committee
                </a>
            </li>

            <!-- 7. Pre-Conference -->
            <li>
                <a href="{{ route('admin.pre_conference') }}" class="nav-item-link {{ request()->routeIs('admin.pre_conference') ? 'active' : '' }}">
                    <i class="fa-solid fa-person-chalkboard"></i> 7. Pre-Conference
                </a>
            </li>

            <!-- 8. Stall Booking and Merchandise (Dropdown matching UI) -->
            @php
                $isStallActive = request()->routeIs('admin.stall_booking') 
                    || request()->routeIs('admin.conference_brochures') 
                    || request()->routeIs('admin.stall_booking_details');
            @endphp
            <li class="nav-item-dropdown {{ $isStallActive ? 'open' : '' }}">
                <div class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <div class="toggle-left">
                        <i class="fa-solid fa-store"></i> 8. Stall Booking &amp; Merchandise
                    </div>
                    <i class="fa-solid fa-chevron-down chevron"></i>
                </div>
                <ul class="sub-nav-links">
                    <li>
                        <a href="{{ route('admin.stall_booking') }}" class="{{ request()->routeIs('admin.stall_booking') ? 'active' : '' }}">
                            <i class="fa-solid fa-store"></i> Stall Booking &amp; Merchandise
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.stall_booking_details') }}" class="{{ request()->routeIs('admin.stall_booking_details') || request()->routeIs('admin.conference_brochures') ? 'active' : '' }}">
                            <i class="fa-solid fa-file-lines"></i> Stall Booking Details
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 9. Glimpse of MCC (Dropdown matching UI) -->
            @php
                $isMccActive = request()->routeIs('admin.mcc_memorial') || request()->routeIs('admin.visit');
            @endphp
            <li class="nav-item-dropdown {{ $isMccActive ? 'open' : '' }}">
                <div class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <div class="toggle-left">
                        <i class="fa-solid fa-landmark"></i> 9. Glimpse of MCC
                    </div>
                    <i class="fa-solid fa-chevron-down chevron"></i>
                </div>
                <ul class="sub-nav-links">
                    <li>
                        <a href="{{ route('admin.mcc_memorial') }}" class="{{ request()->routeIs('admin.mcc_memorial') ? 'active' : '' }}">
                            <i class="fa-solid fa-images"></i> MCC Gallery
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.visit') }}" class="{{ request()->routeIs('admin.visit') ? 'active' : '' }}">
                            <i class="fa-solid fa-map-location-dot"></i> Places to Visit
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 10. Contact Us -->
            <li>
                <a href="{{ route('admin.contact') }}" class="nav-item-link {{ request()->routeIs('admin.contact') ? 'active' : '' }}">
                    <i class="fa-solid fa-phone"></i> 10. Contact Us
                </a>
            </li>

            <!-- SECTION: SCHEDULE & DEADLINES -->
            <li class="sidebar-section-header" style="margin-top: 10px;">
                <i class="fa-solid fa-calendar-days" style="font-size: 0.75rem;"></i> Schedule &amp; Deadlines
            </li>

            <li>
                <a href="{{ route('admin.schedule') }}" class="nav-item-link {{ request()->routeIs('admin.schedule') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days"></i> Conference Schedule
                </a>
            </li>
            <li>
                <a href="{{ route('admin.deadlines') }}" class="nav-item-link {{ request()->routeIs('admin.deadlines') ? 'active' : '' }}">
                    <i class="fa-regular fa-clock"></i> Important Deadlines
                </a>
            </li>

            <!-- SECTION: SITE SETTINGS & CONFIGURATION -->
            <li class="sidebar-section-header" style="margin-top: 10px;">
                <i class="fa-solid fa-gears" style="font-size: 0.75rem;"></i> Site Configuration
            </li>

            <li>
                <a href="{{ route('admin.topbar') }}" class="nav-item-link {{ request()->routeIs('admin.topbar') ? 'active' : '' }}">
                    <i class="fa-solid fa-bullhorn"></i> Topbar &amp; Ticker
                </a>
            </li>
            <li>
                <a href="{{ route('admin.navigation') }}" class="nav-item-link {{ request()->routeIs('admin.navigation') ? 'active' : '' }}">
                    <i class="fa-solid fa-bars-staggered"></i> Header Navigation Menu
                </a>
            </li>
            <li>
                <a href="{{ route('admin.partner_logos') }}" class="nav-item-link {{ request()->routeIs('admin.partner_logos') ? 'active' : '' }}">
                    <i class="fa-solid fa-handshake"></i> Partner Logos
                </a>
            </li>
            <li>
                <a href="{{ route('admin.theme_settings') }}" class="nav-item-link {{ request()->routeIs('admin.theme_settings') ? 'active' : '' }}">
                    <i class="fa-solid fa-palette"></i> Theme Settings
                </a>
            </li>

            <li>
                <a href="/" target="_blank" class="nav-item-link" style="color: #38bdf8; font-weight: 600;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> View Live Site
                </a>
            </li>
        </ul>
    </aside>

    <style>
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        @media (max-width: 768px) {
            .topbar .title {
                font-size: 1.1rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .topbar-right {
                gap: 12px;
            }
            .topbar-user-name {
                display: none;
            }
            .topbar-logout-text {
                display: none;
            }
            div[style*="display: flex;"][style*="justify-content: space-between"] {
                flex-wrap: wrap !important;
                gap: 15px !important;
            }
        }
    </style>

    <!-- Main Content -->
    <main class="main-content">
        <header class="topbar">
            <div style="display: flex; align-items: center; gap: 15px; min-width: 0;">
                <button class="hamburger-menu" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">@yield('header_title', 'Dashboard')</div>
            </div>
            <div class="topbar-right">
                <span style="font-weight: 600; color: var(--admin-text); display: flex; align-items: center;">
                    <i class="fa-solid fa-circle-user" style="font-size: 1.5rem; color: var(--admin-sidebar); margin-right: 8px;"></i> 
                    <span class="topbar-user-name" style="white-space: nowrap;">{{ auth()->user()->name ?? 'Administrator' }}</span>
                </span>
                
                <form method="POST" action="{{ route('logout') }}" style="display: flex; margin: 0;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #ef4444; font-weight: 600; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 5px; padding: 0;">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> <span class="topbar-logout-text">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <div class="content-area">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('open');
            
            if (sidebar.classList.contains('open')) {
                overlay.classList.add('show');
            } else {
                overlay.classList.remove('show');
            }
        }

        function toggleNavDropdown(toggleEl) {
            const dropdown = toggleEl.closest('.nav-item-dropdown');
            dropdown.classList.toggle('open');
        }
    </script>
</body>
</html>
