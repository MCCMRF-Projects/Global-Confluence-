@extends('layouts.admin_cms')

@section('header_title', 'Stall Booking Details Page CMS')

@section('content')
<style>
    .admin-card-section {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin-bottom: 25px;
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
</style>

<div style="display: flex; flex-direction: column; gap: 25px;">

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="font-size: 1.7rem; font-weight: 800; color: #0a192f; margin: 0 0 4px 0;">
                <i class="fa-solid fa-file-lines" style="color: #00A896; margin-right: 8px;"></i> Stall Booking Details Page CMS
            </h2>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Customize the contents of the separate details page.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="/page/stall-booking-details" target="_blank" style="background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; padding: 11px 20px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> View Live Page
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.3rem;"></i> {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.stall_booking_details.update') }}">
        @csrf

        <div class="admin-card-section">
            <div class="section-title">
                <i class="fa-solid fa-heading" style="color: #00A896;"></i> 1. Banner Settings
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label class="form-label">Top Badge Label</label>
                    <input type="text" name="stall_details_badge" value="{{ $settings['stall_details_badge'] ?? 'Conference Opportunities' }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Banner Title</label>
                    <input type="text" name="stall_details_title" value="{{ $settings['stall_details_title'] ?? 'Stall Booking & Merchandise' }}" class="form-input" required>
                </div>
            </div>

            <div>
                <label class="form-label">Banner Subtitle</label>
                <input type="text" name="stall_details_subtitle" value="{{ $settings['stall_details_subtitle'] ?? 'Global One Health Confluence 2026 • Madras Christian College' }}" class="form-input">
            </div>
        </div>

        <div class="admin-card-section">
            <div class="section-title">
                <i class="fa-solid fa-align-left" style="color: #00A896;"></i> 2. Page Content
            </div>

            <div style="margin-bottom: 20px;">
                <label class="form-label">Content Box Heading</label>
                <input type="text" name="stall_details_heading" value="{{ $settings['stall_details_heading'] ?? 'Stall Booking & Merchandise Details' }}" class="form-input">
            </div>

            <div>
                <label class="form-label">Detailed Content (HTML Supported)</label>
                <textarea name="stall_details_content" rows="6" class="form-input" style="font-family: monospace;">{{ $settings['stall_details_content'] ?? 'This page is prepared and ready. Detailed information, tariffs, and forms will be displayed here soon.' }}</textarea>
            </div>
        </div>

        <!-- Sticky Save Button -->
        <div style="position: sticky; bottom: 20px; background: #ffffff; padding: 18px 25px; border-radius: 14px; border: 1.5px solid #cbd5e1; box-shadow: 0 10px 30px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; z-index: 50;">
            <span style="color: #64748b; font-size: 0.95rem; font-weight: 600;">
                <i class="fa-solid fa-circle-info" style="color: #00A896;"></i> Save changes when ready.
            </span>
            <button type="submit" style="background: linear-gradient(135deg, #00A896, #028090); color: #ffffff; padding: 13px 36px; border-radius: 10px; font-weight: 800; font-size: 1.05rem; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 168, 150, 0.35); display: inline-flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-floppy-disk"></i> Save Details Page
            </button>
        </div>

    </form>

</div>
@endsection

