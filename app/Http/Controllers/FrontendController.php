<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationFee;
use App\Models\Deadline;

class FrontendController extends Controller
{
    public function index()
    {
        $registrationFees = RegistrationFee::where('is_active', true)->orderBy('sort_order')->get();
        $addons = \App\Models\Addon::where('is_active', true)->get();
        // $deadlines is now shared globally via AppServiceProvider

        return view('welcome', compact('registrationFees', 'addons'));
    }

    public function customPage($slug)
    {
        if ($slug === 'contact-us' || $slug === 'contact') {
            $settings = \App\Models\SiteSetting::where('group', 'contact')->pluck('value', 'key')->toArray();
            return view('contact', compact('settings'));
        }

        if ($slug === 'oral-presentation') {
            return view('events.oral_presentation');
        }

        if ($slug === 'poster-presentation') {
            return view('events.poster_presentation');
        }

        if ($slug === 'innovation-pitch' || $slug === 'innovator-pitch' || $slug === 'innovators-pitch') {
            return view('events.innovation_pitch');
        }

        if ($slug === 'hackathon' || $slug === 'hackathon-challenge') {
            return view('events.hackathon');
        }

        if ($slug === 'stall-booking-and-merchandise' || $slug === 'stall-booking' || $slug === 'stall-booking-merchandise') {
            $settings = \App\Models\SiteSetting::where('group', 'stall_booking')->pluck('value', 'key')->toArray();
            $brochures = isset($settings['stall_brochures_json']) ? json_decode($settings['stall_brochures_json'], true) : [];
            $homeBrochures = isset($settings['stall_home_brochures_json']) ? json_decode($settings['stall_home_brochures_json'], true) : [];
            $sponsors = isset($settings['stall_sponsors_json']) ? json_decode($settings['stall_sponsors_json'], true) : [];
            return view('stall_booking', compact('settings', 'brochures', 'sponsors', 'homeBrochures'));
        }

        if ($slug === 'stall-booking-details' || $slug === 'stall-details' || $slug === 'brochure-details' || $slug === 'conference-brochures-details') {
            return view('stall_booking_details');
        }

        $formattedTitle = ucwords(str_replace('-', ' ', $slug));
        $content = \App\Models\SiteSetting::where('group', 'custom_pages')->where('key', 'page_' . $slug)->value('value');
        $bannerTitle = \App\Models\SiteSetting::where('group', 'custom_pages')->where('key', 'page_stall_banner_title')->value('value');
        $cardTitle = \App\Models\SiteSetting::where('group', 'custom_pages')->where('key', 'page_stall_card_title')->value('value');

        return view('custom_page', [
            'title' => ($slug === 'stall-booking-and-merchandise' && $bannerTitle) ? $bannerTitle : $formattedTitle,
            'cardTitle' => ($slug === 'stall-booking-and-merchandise' && $cardTitle) ? $cardTitle : $formattedTitle,
            'slug' => $slug,
            'content' => $content ?: 'Content for ' . $formattedTitle . ' will be updated soon. Please check back later!'
        ]);
    }
}

