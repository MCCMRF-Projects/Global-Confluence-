@extends('layouts.app')

@section('title', ($title ?? 'Custom Page') . ' | Global One Health Confluence 2026')

@section('content')

@include('sections.topbar')
@include('sections.navbar')

<!-- Page Banner -->
<div class="page-banner" style="background-color: #0f172a; padding: 60px 20px; text-align: center; color: #fff;">
    <div class="page-banner-content">
        <h1 style="text-transform: uppercase; font-size: 2.2rem; font-weight: 800; letter-spacing: 1px; color: #ffffff;">{{ $title ?? 'PAGE' }}</h1>
    </div>
</div>

<style>
    .custom-page-container {
        padding: 70px 20px;
        max-width: 1100px;
        margin: 0 auto;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #334155;
    }
    .custom-page-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        padding: 40px;
        min-height: 350px;
    }
</style>

<div class="custom-page-container">
    <div class="custom-page-card">
        <h2 style="font-size: 1.8rem; font-weight: 700; color: #0f172a; margin-bottom: 20px; border-bottom: 2px solid #00A896; padding-bottom: 10px; display: inline-block;">
            {{ $cardTitle ?? $title ?? 'Custom Page' }}
        </h2>
        <div style="font-size: 1.05rem; line-height: 1.8; color: #475569; margin-top: 15px;">
            {!! $content ?? 'Content for this section will be updated soon. Please check back later!' !!}
        </div>
    </div>
</div>

@include('sections.footer')

@endsection

