@extends('layouts.admin_cms')

@section('header_title', 'Home Page Settings')

@section('content')
<div class="card">
    <h3 style="margin-bottom: 20px; color: var(--admin-sidebar);">Manage Home Page Sections</h3>
    <p style="margin-bottom: 20px;">Select a section below to edit its content.</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
        <a href="{{ route('admin.hero') }}" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: var(--admin-white); color: var(--admin-sidebar); border: 1px solid var(--admin-border);">
            <i class="fa-solid fa-image" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> Hero Section
        </a>
        <a href="{{ route('admin.about_organizer') }}" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: var(--admin-white); color: var(--admin-sidebar); border: 1px solid var(--admin-border);">
            <i class="fa-solid fa-building-user" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> About Organizers
        </a>
        <a href="{{ route('admin.about') }}" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: var(--admin-white); color: var(--admin-sidebar); border: 1px solid var(--admin-border);">
            <i class="fa-solid fa-address-card" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> About Us Section
        </a>
        <a href="{{ route('admin.objectives') }}" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: var(--admin-white); color: var(--admin-sidebar); border: 1px solid var(--admin-border);">
            <i class="fa-solid fa-bullseye" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> Objectives
        </a>
        <a href="{{ route('admin.highlights') }}" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: var(--admin-white); color: var(--admin-sidebar); border: 1px solid var(--admin-border);">
            <i class="fa-solid fa-star" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> Highlights
        </a>
        <a href="{{ route('admin.guidelines') }}" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: var(--admin-white); color: var(--admin-sidebar); border: 1px solid var(--admin-border);">
            <i class="fa-solid fa-file-lines" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> Guidelines
        </a>
        <a href="{{ route('admin.deadlines') }}" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: var(--admin-white); color: var(--admin-sidebar); border: 1px solid var(--admin-border);">
            <i class="fa-regular fa-calendar-check" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> Important Deadlines
        </a>
        <a href="{{ route('admin.event_details') }}" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: var(--admin-white); color: var(--admin-sidebar); border: 1px solid var(--admin-border);">
            <i class="fa-solid fa-calendar-alt" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> Event Details
        </a>
        <a href="{{ route('admin.settings.registration') }}" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: var(--admin-white); color: var(--admin-sidebar); border: 1px solid var(--admin-border);">
            <i class="fa-solid fa-id-card" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> Registration Plans
        </a>
        <a href="{{ route('admin.fees') }}" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: var(--admin-white); color: var(--admin-sidebar); border: 1px solid var(--admin-border);">
            <i class="fa-solid fa-indian-rupee-sign" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> Registration Fees
        </a>
        <a href="{{ route('admin.awards') }}" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: var(--admin-white); color: var(--admin-sidebar); border: 1px solid var(--admin-border);">
            <i class="fa-solid fa-trophy" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> Awards
        </a>
        <a href="{{ route('admin.partner_logos') }}" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: var(--admin-white); color: var(--admin-sidebar); border: 1px solid var(--admin-border);">
            <i class="fa-solid fa-handshake" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> Partner & Accreditation Logos
        </a>
        <a href="{{ route('admin.stall_booking') }}#home-brochures-section" class="btn" style="text-align: center; padding: 20px; font-size: 1.1rem; background: #f0fdf4; color: var(--admin-sidebar); border: 1.5px solid #00A896;">
            <i class="fa-solid fa-book-open" style="font-size: 2rem; display: block; margin-bottom: 10px; color: var(--admin-primary);"></i> Conference Brochures
        </a>

    </div>
</div>
@endsection
