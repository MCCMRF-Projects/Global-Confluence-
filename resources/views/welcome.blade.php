@extends('layouts.app')

@section('content')

    @include('sections.topbar')
    @include('sections.navbar')
    @include('sections.hero')
    @include('sections.partner-strip')
    @include('sections.about-mcc')
    @include('sections.about-dept')
    @include('sections.about')
    @include('sections.stall-brochures')
    @include('sections.deadlines')
    @include('sections.highlights')
    @include('sections.participants')
    {{-- @include('sections.workshop') --}}
    @include('sections.abstract-guidelines')
    @include('sections.awards')
    @include('sections.registration-details')
    {{-- @include('sections.topics') --}}
    @include('sections.schedule')

    @include('sections.abstract-banner')
    {{-- @include('sections.cta') --}}
    @include('sections.venue')
    @include('sections.footer')

@endsection

@section('scripts')
<!-- Countdown script removed as these boxes are now manually edited statistics from the admin panel -->
@endsection


