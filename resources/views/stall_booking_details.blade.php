@extends('layouts.app')

@section('title', 'Stall Booking & Merchandise Details | Global One Health Confluence 2026')

@section('content')

@include('sections.topbar')
@include('sections.navbar')

@php
    $detailsSettings = [];
    try {
        $detailsSettings = \App\Models\SiteSetting::where('group', 'stall_booking_details')->pluck('value', 'key')->toArray();
    } catch (\Throwable $e) {
        $detailsSettings = [];
    }

    $badge = $detailsSettings['stall_details_badge'] ?? 'Conference Opportunities';
    $title = $detailsSettings['stall_details_title'] ?? 'Stall Booking & Merchandise';
    $subtitle = $detailsSettings['stall_details_subtitle'] ?? 'Global One Health Confluence 2026 • Madras Christian College';
    $heading = $detailsSettings['stall_details_heading'] ?? 'Stall Booking & Merchandise Details';
    $content = $detailsSettings['stall_details_content'] ?? 'This page is prepared and ready. Detailed information, tariffs, and forms will be displayed here soon.';
@endphp

<!-- Page Banner -->
<div class="page-banner" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 65px 20px; text-align: center; color: #ffffff; position: relative;">
    <div class="page-banner-content" style="max-width: 900px; margin: 0 auto;">
        <span style="display: inline-block; padding: 5px 16px; background: rgba(0, 168, 150, 0.18); color: #2dd4bf; border: 1px solid rgba(45, 212, 191, 0.35); border-radius: 30px; font-weight: 700; font-size: 0.8rem; letter-spacing: 1.2px; text-transform: uppercase; margin-bottom: 14px;">
            {{ $badge }}
        </span>
        <h1 style="text-transform: uppercase; font-size: clamp(1.8rem, 3.5vw, 2.5rem); font-weight: 800; letter-spacing: 1.2px; color: #ffffff; margin: 0 0 10px 0;">
            {{ $title }}
        </h1>
        <p style="color: #94a3b8; font-size: 1rem; margin: 0; font-weight: 500;">
            {{ $subtitle }}
        </p>
    </div>
</div>

<!-- Main Container -->
<div style="background: #f8fafc; min-height: 55vh; padding: 70px 20px;">
    <div style="max-width: 1000px; margin: 0 auto;">
        <div style="background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04); padding: 60px 30px; text-align: center;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: #e6f7f5; color: #00A896; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 22px;">
                <i class="fa-solid fa-store"></i>
            </div>
            <h2 style="font-size: 1.75rem; font-weight: 700; color: #0f172a; margin: 0 0 12px 0;">
                {{ $heading }}
            </h2>
            <div style="width: 50px; height: 3px; background: #00A896; margin: 0 auto 18px auto; border-radius: 3px;"></div>
            <div style="color: #64748b; font-size: 1.05rem; line-height: 1.7; max-width: 650px; margin: 0 auto 30px auto;">
                {!! nl2br($content) !!}
            </div>
            <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ url('/') }}" style="display: inline-flex; align-items: center; gap: 8px; background: #00A896; color: #ffffff; padding: 12px 28px; border-radius: 30px; font-weight: 600; text-decoration: none; font-size: 0.92rem; transition: background 0.2s;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

@include('sections.footer')

@endsection

