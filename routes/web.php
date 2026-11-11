<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PaperSubmissionController;
use App\Http\Controllers\RegistrationController;

Route::get('/storage-file/{path}', function ($path) {
    $cleanPath = ltrim(str_replace(['public/', 'storage/'], '', $path), '/');
    $decodedPath = urldecode($cleanPath);
    
    $candidates = [
        storage_path('app/public/' . $cleanPath),
        storage_path('app/public/' . $decodedPath),
        storage_path('app/' . $cleanPath),
        storage_path('app/' . $decodedPath),
        public_path('storage/' . $cleanPath),
        public_path('storage/' . $decodedPath),
        base_path('../public_html/storage/' . $cleanPath),
        base_path('../public_html/storage/' . $decodedPath),
        public_path($cleanPath),
        public_path($decodedPath),
    ];

    $filePath = null;
    foreach ($candidates as $cand) {
        if (!empty($cand) && file_exists($cand) && !is_dir($cand)) {
            $filePath = $cand;
            break;
        }
    }

    if (!$filePath) {
        abort(404, 'File not found');
    }

    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    $mimeType = $mimeTypes[$extension] ?? (\Illuminate\Support\Facades\File::mimeType($filePath) ?: 'application/octet-stream');

    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('storage.file');

Route::get('/images/{path}', function ($path) {
    $cleanPath = ltrim($path, '/');
    $candidates = [
        public_path('images/' . $cleanPath),
        storage_path('app/public/images/' . $cleanPath),
        storage_path('app/public/' . $cleanPath),
        storage_path('app/' . $cleanPath),
    ];
    foreach ($candidates as $cand) {
        if (!empty($cand) && file_exists($cand) && !is_dir($cand)) {
            $extension = strtolower(pathinfo($cand, PATHINFO_EXTENSION));
            $mimeTypes = [
                'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
                'gif' => 'image/gif', 'webp' => 'image/webp', 'svg' => 'image/svg+xml',
                'pdf' => 'application/pdf',
            ];
            $mimeType = $mimeTypes[$extension] ?? (\Illuminate\Support\Facades\File::mimeType($cand) ?: 'application/octet-stream');
            return response()->file($cand, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($cand) . '"',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
    }
    abort(404);
})->where('path', '.*');

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/page/{slug}', [FrontendController::class, 'customPage'])->name('custom.page');

Route::get('/setup-db', function () {
    try {
        // Force Laravel to clear the cache and read the new .env file
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        
        $pdo = new PDO("mysql:host=127.0.0.1;port=3306", "root", "");
        $pdo->exec("CREATE DATABASE IF NOT EXISTS biomed_app");
        
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--seed' => true]);
        
        // Optionally delete the sqlite file so it doesn't cause confusion
        if (file_exists(database_path('database.sqlite'))) {
            @unlink(database_path('database.sqlite'));
        }
        
        return "Cache cleared, MySQL database created, and migrations run successfully!<br><br>Output:<br>" . nl2br(\Illuminate\Support\Facades\Artisan::output());
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/run-migration', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate');
        return "Migration run successfully!<br>Output:<br>" . nl2br(\Illuminate\Support\Facades\Artisan::output());
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/clear-cache', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        return "All caches (view, cache, config, route) cleared successfully!";
    } catch (\Exception $e) {
        return "Error clearing cache: " . $e->getMessage();
    }
});

Route::get('/seed-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return "Database seeded successfully!<br>Output:<br>" . nl2br(\Illuminate\Support\Facades\Artisan::output());
    } catch (\Exception $e) {
        return "Error seeding database: " . $e->getMessage();
    }
});

Route::get('/seed-tracks', function () {
    $tracks = [
        [
            'title' => 'Track I: Emerging infectious diseases through a One health lens',
            'bullet_points' => ['Zoonosis', 'Vector borne diseases', 'Next generation pandemic preparedness', 'Environmental Reservoirs and AMR', 'Molecular Therapeutics and countermeasure innovations', 'Metagenomics in the wild', 'Novel antimicrobials']
        ],
        [
            'title' => 'Track II: Strengthening Health Systems from Theory to Practice: Embedding Social Infrastructure and Public Governance in One Health Capacities',
            'bullet_points' => ['Institutional Governance & Multi-Sectoral Policy', 'Public health policy and One Health governance', 'Social Infrastructure & Community Resilience', 'Workforce Development & Operational Capacity', 'Addressing Social Determinants of Health', 'Crisis and outbreak management', 'Community-led health equity']
        ],
        [
            'title' => 'Track III: Integrating Environment and Climate change in One Health',
            'bullet_points' => ['Climate Change and Pathogen Dynamics', 'Biodiversity conservation and Biosecurity', 'Ecosystem resilience', 'Climate change and Environmental health', 'Waste management and circular bioeconomy', 'Mitigating Pollution', 'Sustainable production systems']
        ],
        [
            'title' => 'Track IV: Translating Sustainable Chemistry and Future Technologies to One Health',
            'bullet_points' => ['Green Chemistry & Eco-Safe Material Design', 'Advanced Technologies for Environmental and Pathogen Remediation', 'Translational Innovation & Regulatory Harmonization', 'One Health and chemical challenges', 'Emerging contaminants and environmental chemistry', 'Sustainable solutions for environmental challenges']
        ],
        [
            'title' => 'Track V: Ensuring health intervention through the Indian Knowledge System',
            'bullet_points' => ['Traditional Healthcare Systems', 'Ethnomedicine and Community Health Practices', 'Medicinal Plants and Natural Product Research', 'Traditional Food Systems, Nutrition and Functional Foods', 'Biodiversity Conservation and Indigenous Ecological Knowledge', 'Validation of Traditional Knowledge through Modern Science', 'Integrative Medicine and Precision Traditional Therapeutics', 'One Health Perspectives in Indian Knowledge Systems', 'Digital Documentation and Preservation of Indigenous Knowledge', 'Policy, Ethics and Intellectual Property Rights in Traditional Knowledge', 'AI and Omics Approaches for Traditional Medicine Research', 'Translational Research and Commercialization of IKS-based Innovations']
        ],
        [
            'title' => 'Track VI: Regenerative Health: Redefining Industrial One Health Paradigms',
            'bullet_points' => ['Responsible Pharmaceutical Manufacturing', 'Next-Generation Veterinary Biologics', 'Green Agrochemicals & Biopesticides', 'Corporate Stewardship & Supply Chain Resilience', 'Venture Capital in Planetary Health', 'Cross-Sectoral Commercial Collaboration', 'Integrating comprehensive One Health metrics into Environmental, Social, and Governance (ESG) corporate reporting standards']
        ]
    ];
    
    \App\Models\Track::truncate();
    foreach($tracks as $index => $trackData) {
        \App\Models\Track::create([
            'title' => $trackData['title'],
            'bullet_points' => $trackData['bullet_points'],
            'sort_order' => $index + 1
        ]);
    }
    return "Default tracks have been seeded! You can now visit the /scientific-themes page or the admin CMS to view them.";
});

//     return view('submit-paper');
// })->name('submit-paper');

Route::get('/registration', function () {
    $registrationFees = \App\Models\RegistrationFee::where('is_active', true)->orderBy('sort_order')->get();
    $addons = \App\Models\Addon::where('is_active', true)->get();
    $policies = \App\Models\Policy::where('is_active', true)->orderBy('sort_order')->get();
    return view('registration', compact('registrationFees', 'addons', 'policies'));
})->name('registration');

Route::get('/speakers', function () {
    $keynote = \App\Models\Speaker::where('type', 'keynote')->orderBy('sort_order')->get();
    $distinguished = \App\Models\Speaker::where('type', 'distinguished')->orderBy('sort_order')->get();
    return view('speakers', compact('keynote', 'distinguished'));
})->name('speakers');

Route::get('/keynote-speakers', function () {
    $speakers = \App\Models\Speaker::where('type', 'keynote')->orderBy('sort_order')->get();
    return view('keynote-speakers', compact('speakers'));
})->name('keynote-speakers');

Route::get('/distinguished-speakers', function () {
    $speakers = \App\Models\Speaker::where('type', 'distinguished')->orderBy('sort_order')->get();
    $preConferenceSpeakers = \App\Models\Speaker::where('type', 'pre_conference')->orderBy('sort_order')->get();
    return view('distinguished-speakers', compact('speakers', 'preConferenceSpeakers'));
})->name('distinguished-speakers');

Route::get('/committee', function () {
    $leadership = \App\Models\CommitteeMember::where('category', 'leadership')->orderBy('sort_order')->get()->groupBy('subcategory');
    $organizing = \App\Models\CommitteeMember::where('category', 'organizing_committee')->orderBy('sort_order')->get();
    $advisory = \App\Models\CommitteeMember::where('category', 'advisory_committee')->orderBy('sort_order')->get();
    $settings = \App\Models\SiteSetting::where('group', 'committee_page')->pluck('value', 'key')->toArray();
    return view('committee', compact('leadership', 'organizing', 'advisory', 'settings'));
})->name('committee');

Route::get('/venue', function () {
    return view('venue');
})->name('venue');

Route::get('/about-organizer', function () {
    return view('about-organizer');
})->name('about-organizer');

Route::get('/topics', function () {
    return view('topics-page');
})->name('topics');

Route::get('/scientific-themes', function () {
    $tracks = \App\Models\Track::orderBy('sort_order')->get();
    $settings = \App\Models\SiteSetting::where('group', 'tracks_page')->pluck('value', 'key')->toArray();
    return view('scientific-themes', compact('tracks', 'settings'));
})->name('scientific-themes');

Route::get('/guidelines', function () {
    $settings = \App\Models\SiteSetting::pluck('value', 'key')->toArray();
    return view('guidelines', compact('settings'));
})->name('guidelines');

Route::get('/publications', function () {
    $settings = \App\Models\SiteSetting::pluck('value', 'key')->toArray();
    return view('guidelines', compact('settings'));
})->name('publications');

Route::get('/sponsors', function () {
    return view('sponsors');
})->name('sponsors');

Route::get('/awards', function () {
    $settings = \App\Models\SiteSetting::whereIn('group', ['awards_page', 'awards', 'page_banners'])->pluck('value', 'key')->all();
    return view('awards', compact('settings'));
})->name('awards');

Route::get('/key-dates', function () {
    $deadlines = \App\Models\Deadline::where('is_active', true)->orderBy('sort_order')->get();
    return view('key-dates', compact('deadlines'));
})->name('key-dates');

Route::get('/venue', function () {
    $settings = \App\Models\SiteSetting::where('group', 'venue')->pluck('value', 'key');
    return view('venue', compact('settings'));
})->name('venue');

Route::get('/schedule', function () {
    return view('schedule');
})->name('schedule');

Route::get('/pre-conference', function () {
    $settings = \App\Models\SiteSetting::where('group', 'pre_conference')->pluck('value', 'key')->toArray();
    $bannerSettings = \App\Models\SiteSetting::where('group', 'banner')->pluck('value', 'key')->toArray();
    return view('pre_conference', compact('settings', 'bannerSettings'));
})->name('pre-conference');

// Technical Event Pages
Route::get('/events/oral-presentation', function () {
    return view('events.oral_presentation');
})->name('events.oral_presentation');
Route::get('/oral-presentation', function () {
    return view('events.oral_presentation');
})->name('oral-presentation');

Route::get('/events/poster-presentation', function () {
    return view('events.poster_presentation');
})->name('events.poster_presentation');
Route::get('/poster-presentation', function () {
    return view('events.poster_presentation');
})->name('poster-presentation');

Route::get('/events/innovation-pitch', function () {
    return view('events.innovation_pitch');
})->name('events.innovation_pitch');
Route::get('/innovation-pitch', function () {
    return view('events.innovation_pitch');
})->name('innovation-pitch');

Route::get('/events/hackathon', function () {
    return view('events.hackathon');
})->name('events.hackathon');
Route::get('/hackathon', function () {
    return view('events.hackathon');
})->name('hackathon');

Route::get('/stall-booking-details', function () {
    return view('stall_booking_details');
})->name('stall_booking_details');

Route::get('/stall-booking-and-merchandise', function () {
    $settings = \App\Models\SiteSetting::where('group', 'stall_booking')->pluck('value', 'key')->toArray();
    $brochures = isset($settings['stall_brochures_json']) ? json_decode($settings['stall_brochures_json'], true) : [];
    $sponsors = isset($settings['stall_sponsors_json']) ? json_decode($settings['stall_sponsors_json'], true) : [];
    return view('stall_booking', compact('settings', 'brochures', 'sponsors'));
})->name('stall_booking_and_merchandise');

Route::get('/stall-booking', function () {
    $settings = \App\Models\SiteSetting::where('group', 'stall_booking')->pluck('value', 'key')->toArray();
    $brochures = isset($settings['stall_brochures_json']) ? json_decode($settings['stall_brochures_json'], true) : [];
    $sponsors = isset($settings['stall_sponsors_json']) ? json_decode($settings['stall_sponsors_json'], true) : [];
    return view('stall_booking', compact('settings', 'brochures', 'sponsors'));
})->name('stall_booking');

Route::post('/api/submit-paper', [PaperSubmissionController::class, 'store'])->name('api.submit_paper');
Route::post('/api/register', [RegistrationController::class, 'store']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/registrations', [AdminController::class, 'registrations'])->name('admin.registrations');
    Route::delete('/registrations/{id}', [AdminController::class, 'deleteRegistration'])->name('admin.registrations.destroy');
    Route::get('/submissions', [AdminController::class, 'submissions'])->name('admin.submissions');
    Route::delete('/submissions/{id}', [AdminController::class, 'deleteSubmission'])->name('admin.submissions.destroy');
    Route::get('/award-applications', [AdminController::class, 'awardApplications'])->name('admin.award_applications');
    Route::delete('/award-applications/{id}', [AdminController::class, 'deleteAwardApplication'])->name('admin.award_applications.destroy');
    
    // CMS: Registration Fees
    Route::get('/fees', [AdminController::class, 'fees'])->name('admin.fees');
    Route::post('/fees', [AdminController::class, 'storeFee'])->name('admin.fees.store');
    Route::put('/fees/{id}', [AdminController::class, 'updateFee'])->name('admin.fees.update');
    Route::delete('/fees/{id}', [AdminController::class, 'deleteFee'])->name('admin.fees.delete');

    Route::post('/addons', [AdminController::class, 'storeAddon'])->name('admin.addons.store');
    Route::put('/addons/{id}', [AdminController::class, 'updateAddon'])->name('admin.addons.update');
    Route::delete('/addons/{id}', [AdminController::class, 'deleteAddon'])->name('admin.addons.delete');

    // CMS: Policies
    Route::get('/policies', [AdminController::class, 'policies'])->name('admin.policies');
    Route::post('/policies', [AdminController::class, 'storePolicy'])->name('admin.policies.store');
    Route::put('/policies/{id}', [AdminController::class, 'updatePolicy'])->name('admin.policies.update');
    Route::delete('/policies/{id}', [AdminController::class, 'deletePolicy'])->name('admin.policies.delete');


    // Partner & Accreditation Logos CMS
    Route::get('/partner-logos', [AdminController::class, 'partnerLogosSettings'])->name('admin.partner_logos');
    Route::post('/partner-logos', [AdminController::class, 'storePartnerLogo'])->name('admin.partner_logos.store');
    Route::put('/partner-logos/{id}', [AdminController::class, 'updatePartnerLogo'])->name('admin.partner_logos.update');
    Route::delete('/partner-logos/{id}', [AdminController::class, 'destroyPartnerLogo'])->name('admin.partner_logos.destroy');
    Route::post('/partner-logos/reorder', [AdminController::class, 'reorderPartnerLogos'])->name('admin.partner_logos.reorder');

    // CMS: Global Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

    // NEW STRUCTURE:
    
    // 1. Home
    Route::get('/home', [AdminController::class, 'homeSettings'])->name('admin.home');
    Route::post('/home', [AdminController::class, 'updateHomeSettings'])->name('admin.home.update');
    
    // Sub-sections under home that were previously top-level
    Route::get('/home/hero', [AdminController::class, 'heroSettings'])->name('admin.hero');
    Route::post('/home/hero', [AdminController::class, 'updateSettings'])->name('admin.hero.update');
    Route::post('/home/hero/organizers', [AdminController::class, 'storeHeroOrganizer'])->name('admin.hero.organizers.store');
    Route::put('/home/hero/organizers/{id}', [AdminController::class, 'updateHeroOrganizer'])->name('admin.hero.organizers.update');
    Route::delete('/home/hero/organizers/{id}', [AdminController::class, 'destroyHeroOrganizer'])->name('admin.hero.organizers.destroy');
    Route::post('/home/hero/organizers/reorder', [AdminController::class, 'reorderHeroOrganizers'])->name('admin.hero.organizers.reorder');
    Route::get('/home/about', [AdminController::class, 'aboutSettings'])->name('admin.about');
    Route::post('/home/about', [AdminController::class, 'updateSettings'])->name('admin.about.update');
    Route::get('/home/objectives', [AdminController::class, 'objectivesSettings'])->name('admin.objectives');
    Route::post('/home/objectives', [AdminController::class, 'updateObjectivesSettings'])->name('admin.objectives.update');
    Route::get('/home/highlights', [AdminController::class, 'highlights'])->name('admin.highlights');
    Route::post('/home/highlights/settings', [AdminController::class, 'updateHighlightsSettings'])->name('admin.highlights.update_settings');
    Route::post('/home/highlights', [AdminController::class, 'storeHighlight'])->name('admin.highlights.store');
    Route::put('/home/highlights/{id}', [AdminController::class, 'updateHighlight'])->name('admin.highlights.update');
    Route::delete('/home/highlights/{id}', [AdminController::class, 'destroyHighlight'])->name('admin.highlights.destroy');
    Route::get('/home/guidelines', [AdminController::class, 'guidelinesSettings'])->name('admin.guidelines');
    Route::post('/home/guidelines', [AdminController::class, 'updateGuidelinesSettings'])->name('admin.guidelines.update');
    Route::get('/home/event-details', [AdminController::class, 'eventDetails'])->name('admin.event_details');
    Route::post('/home/event-details', [AdminController::class, 'updateEventDetails'])->name('admin.event_details.update');
    Route::post('/home/event-details/deadlines', [AdminController::class, 'storeDeadline'])->name('admin.deadlines.store.legacy');
    Route::put('/home/event-details/deadlines/{id}', [AdminController::class, 'updateDeadline'])->name('admin.deadlines.update.legacy');
    Route::delete('/home/event-details/deadlines/{id}', [AdminController::class, 'deleteDeadline'])->name('admin.deadlines.delete.legacy');

    // Dedicated Important Deadlines Management
    Route::get('/deadlines', [AdminController::class, 'deadlines'])->name('admin.deadlines');
    Route::post('/deadlines/header', [AdminController::class, 'updateDeadlinesHeaderSettings'])->name('admin.deadlines.header.update');
    Route::post('/deadlines', [AdminController::class, 'storeDeadline'])->name('admin.deadlines.store');
    Route::put('/deadlines/{id}', [AdminController::class, 'updateDeadline'])->name('admin.deadlines.update');
    Route::delete('/deadlines/{id}', [AdminController::class, 'deleteDeadline'])->name('admin.deadlines.delete');
    Route::post('/deadlines/reorder', [AdminController::class, 'reorderDeadlines'])->name('admin.deadlines.reorder');

    // 2. Committee (Already exists, just keep it clean)
    Route::get('/committee', [AdminController::class, 'committee'])->name('admin.committee');
    Route::post('/committee/settings', [AdminController::class, 'updateCommitteeSettings'])->name('admin.committee.settings.update');
    Route::post('/committee', [AdminController::class, 'storeCommitteeMember'])->name('admin.committee.store');
    Route::put('/committee/{id}', [AdminController::class, 'updateCommitteeMember'])->name('admin.committee.update');
    Route::delete('/committee/{id}', [AdminController::class, 'destroyCommitteeMember'])->name('admin.committee.destroy');

    // 3. Experts (Renamed from Speakers)
    Route::get('/experts', [AdminController::class, 'speakers'])->name('admin.experts');
    Route::post('/experts', [AdminController::class, 'storeSpeaker'])->name('admin.speakers.store');
    Route::put('/experts/{id}', [AdminController::class, 'updateSpeaker'])->name('admin.speakers.update');
    Route::delete('/experts/{id}', [AdminController::class, 'destroySpeaker'])->name('admin.speakers.destroy');

    // 4. Tracks (Renamed from Scientific Themes / Programs)
    Route::get('/tracks', [AdminController::class, 'programsSettings'])->name('admin.tracks');
    Route::post('/tracks/settings', [AdminController::class, 'updateProgramsSettings'])->name('admin.programs.update');
    Route::post('/tracks', [AdminController::class, 'storeTrack'])->name('admin.tracks.store');
    Route::put('/tracks/{id}', [AdminController::class, 'updateTrack'])->name('admin.tracks.update');
    Route::delete('/tracks/{id}', [AdminController::class, 'destroyTrack'])->name('admin.tracks.destroy');

    // 5. Schedule
    Route::get('/schedule', [AdminController::class, 'scheduleSettings'])->name('admin.schedule');
    Route::post('/schedule', [AdminController::class, 'updateScheduleSettings'])->name('admin.schedule.update');

    // 6. Awards
    Route::get('/awards', [AdminController::class, 'awards'])->name('admin.awards');
    Route::post('/awards/settings', [AdminController::class, 'updateAwardsSettings'])->name('admin.awards.settings.update');
    Route::post('/awards', [AdminController::class, 'storeAward'])->name('admin.awards.store');
    Route::put('/awards/{id}', [AdminController::class, 'updateAward'])->name('admin.awards.update');
    Route::delete('/awards/{id}', [AdminController::class, 'destroyAward'])->name('admin.awards.destroy');

    // 7. Pre-Conference
    Route::get('/pre-conference', [AdminController::class, 'preConferenceSettings'])->name('admin.pre_conference');
    Route::post('/pre-conference', [AdminController::class, 'updatePreConferenceSettings'])->name('admin.pre_conference.update');

    // 8. MCC Memorial
    Route::get('/mcc-memorial', [AdminController::class, 'mccMemorialSettings'])->name('admin.mcc_memorial');
    Route::post('/mcc-memorial', [AdminController::class, 'updateMccMemorialSettings'])->name('admin.mcc_memorial.update');
    Route::post('/mcc-memorial/upload-images', [AdminController::class, 'uploadMccMemorialImages'])->name('admin.mcc_memorial.upload_images');
    Route::post('/mcc-memorial/delete-image', [AdminController::class, 'deleteMccMemorialImage'])->name('admin.mcc_memorial.delete_image');

    // Poster Presentation Settings
    Route::get('/poster-presentation', [AdminController::class, 'posterPresentationSettings'])->name('admin.poster_presentation');
    Route::post('/poster-presentation', [AdminController::class, 'updatePosterPresentationSettings'])->name('admin.poster_presentation.update');

    // Oral Presentation Settings
    Route::get('/oral-presentation', [AdminController::class, 'oralPresentationSettings'])->name('admin.oral_presentation');
    Route::post('/oral-presentation', [AdminController::class, 'updateOralPresentationSettings'])->name('admin.oral_presentation.update');

    // Innovation Pitch Settings
    Route::get('/innovation-pitch', [AdminController::class, 'innovationPitchSettings'])->name('admin.innovation_pitch');
    Route::post('/innovation-pitch', [AdminController::class, 'updateInnovationPitchSettings'])->name('admin.innovation_pitch.update');

    // Hackathon Settings
    Route::get('/hackathon', [AdminController::class, 'hackathonSettings'])->name('admin.hackathon');
    Route::post('/hackathon', [AdminController::class, 'updateHackathonSettings'])->name('admin.hackathon.update');



    // 9. Visit (Renamed from Venue)
    Route::get('/visit', [AdminController::class, 'visitSettings'])->name('admin.visit');
    Route::post('/visit', [AdminController::class, 'updateVisitSettings'])->name('admin.venue.update');

    // 10. Theme Settings
    Route::get('/theme-settings', [AdminController::class, 'themeSettings'])->name('admin.theme_settings');
    Route::post('/theme-settings', [AdminController::class, 'updateThemeSettings'])->name('admin.theme_settings.update');

    // 11. Header & Navigation Settings
    Route::get('/navigation', [AdminController::class, 'navigationSettings'])->name('admin.navigation');
    Route::post('/navigation', [AdminController::class, 'updateNavigationSettings'])->name('admin.navigation.update');

    // 11b. Topbar & Announcements Settings
    Route::get('/topbar', [AdminController::class, 'topbarSettings'])->name('admin.topbar');
    Route::post('/topbar', [AdminController::class, 'updateTopbarSettings'])->name('admin.topbar.update');

    // 12. Contact Us Settings
    Route::get('/contact', [AdminController::class, 'contactSettings'])->name('admin.contact');
    Route::post('/contact', [AdminController::class, 'updateContactSettings'])->name('admin.contact.update');

    // 8. Stall Booking & Merchandise (Dropdown matching UI)
    Route::get('/conference-brochures', [AdminController::class, 'conferenceBrochuresSettings'])->name('admin.conference_brochures');
    Route::post('/conference-brochures', [AdminController::class, 'updateConferenceBrochuresSettings'])->name('admin.conference_brochures.update');

    Route::get('/stall-booking', [AdminController::class, 'stallBookingSettings'])->name('admin.stall_booking');
    Route::post('/stall-booking', [AdminController::class, 'updateStallBookingSettings'])->name('admin.stall_booking.update');

    Route::get('/stall-booking-details-cms', [AdminController::class, 'stallBookingDetailsSettings'])->name('admin.stall_booking_details');
    Route::post('/stall-booking-details-cms', [AdminController::class, 'updateStallBookingDetailsSettings'])->name('admin.stall_booking_details.update');


    // LEGACY BUT NEEDED FOR NOW:
    Route::get('/settings/registration', [AdminController::class, 'registrationSettings'])->name('admin.settings.registration');
    Route::post('/settings/registration', [AdminController::class, 'updateRegistrationSettings'])->name('admin.settings.registration.update');
    Route::post('/settings/registration/interest-options', [AdminController::class, 'storeInterestOption'])->name('admin.interest_options.store');
    Route::delete('/settings/registration/interest-options/{id}', [AdminController::class, 'deleteInterestOption'])->name('admin.interest_options.delete');
    Route::get('/about-organizer', [AdminController::class, 'aboutOrganizerSettings'])->name('admin.about_organizer');
    Route::post('/about-organizer', [AdminController::class, 'updateSettings'])->name('admin.about_organizer.update');
});

// Award Application Routes
Route::get('/downloads/proforma', function () {
    $headers = [
        "Content-type"        => "application/msword",
        "Content-Disposition" => "attachment;Filename=Proforma_For_Nomination.doc",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];
    return response()->view('downloads.proforma')->withHeaders($headers);
})->name('download.proforma');

Route::get('/downloads/proforma-scholar', function () {
    $headers = [
        "Content-type"        => "application/msword",
        "Content-Disposition" => "attachment;Filename=Proforma_For_Scholar_Award.doc",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];
    return response()->view('downloads.proforma_scholar')->withHeaders($headers);
})->name('download.proforma_scholar');

Route::get('/downloads/proforma-innovator', function () {
    $headers = [
        "Content-type"        => "application/msword",
        "Content-Disposition" => "attachment;Filename=Proforma_For_Innovator_Award.doc",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];
    return response()->view('downloads.proforma_innovator')->withHeaders($headers);
})->name('download.proforma_innovator');

Route::post('/awards/apply', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'application_file' => 'required|file|mimes:doc,docx,pdf|max:10240', // 10MB max
    ]);

    $awardTitle = $request->input('award_name', 'Award Application');
    $awardNameClean = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($awardTitle, PATHINFO_FILENAME));
    $file = $request->file('application_file');
    $originalName = $file->getClientOriginalName();
    $extension = $file->getClientOriginalExtension();
    $filename = time() . '_' . $awardNameClean . '.' . $extension;
    
    // Ensure upload directory exists and is writable
    $awardDir = storage_path('app/public/award_applications');
    if (!file_exists($awardDir)) {
        @mkdir($awardDir, 0777, true);
    }
    @chmod($awardDir, 0777);

    // Store in storage/app/public/award_applications
    $path = $file->storeAs('award_applications', $filename, 'public');
    
    \App\Models\AwardApplication::create([
        'award_name' => $awardTitle,
        'file_path' => $path,
        'original_filename' => $originalName,
    ]);

    return redirect()->back()->with('success', 'Your award application has been submitted successfully!');
})->name('awards.apply');

Route::get('/pre-conference', function () {
    $bannerSettings = \App\Models\SiteSetting::where('group', 'page_banners')->pluck('value', 'key')->toArray();
    $settings = \App\Models\SiteSetting::where('group', 'pre_conference')->pluck('value', 'key')->toArray();
    return view('pre_conference', compact('bannerSettings', 'settings'));
})->name('pre-conference');

Route::get('/mcc-memorial', function () {
    $settings = \App\Models\SiteSetting::where('group', 'mcc_memorial')->pluck('value', 'key')->toArray();
    $images = [];
    $seen = [];
    $files = array_merge(
        glob(public_path('images/mcc_memorial_*.*')) ?: [],
        glob(storage_path('app/public/images/mcc_memorial_*.*')) ?: []
    );
    foreach ($files as $file) {
        $base = basename($file);
        if (!isset($seen[$base])) {
            $seen[$base] = true;
            $images[] = asset('images/' . $base);
        }
    }
    usort($images, function($a, $b) {
        preg_match('/mcc_memorial_(\d+)/', $a, $matchA);
        preg_match('/mcc_memorial_(\d+)/', $b, $matchB);
        $numA = (int)($matchA[1] ?? 0);
        $numB = (int)($matchB[1] ?? 0);
        return $numA <=> $numB;
    });
    return view('mcc_memorial', compact('settings', 'images'));
})->name('mcc-memorial');

Route::get('/contact', function () {
    $settings = \App\Models\SiteSetting::where('group', 'contact')->pluck('value', 'key')->toArray();
    return view('contact', compact('settings'));
})->name('contact');

Route::get('/contact-us', function () {
    return redirect()->route('contact');
});

Route::get('/page/contact-us', function () {
    return redirect()->route('contact');
});

Route::get('/page/contact', function () {
    return redirect()->route('contact');
});

Route::get('/venue', function () {
    $settings = \App\Models\SiteSetting::where('group', 'visit')->pluck('value', 'key')->toArray();
    $placesJson = $settings['visit_places_json'] ?? null;
    $places = $placesJson ? json_decode($placesJson, true) : null;
    return view('venue', compact('settings', 'places'));
})->name('venue');

Route::get('/visit', function () {
    return redirect()->route('venue');
})->name('visit');

// Helper route to sync and update default site settings and track incharges safely on live server
Route::get('/sync-db-settings', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        
        \App\Models\RegistrationField::where('name', 'interested_in')->delete();
        \App\Models\SiteSetting::updateOrCreate(['key' => 'contact_address'], ['value' => 'Madras Christian College, Tambaram, Chennai', 'group' => 'contact']);
        \App\Models\SiteSetting::updateOrCreate(['key' => 'hero_btn1_text'], ['value' => 'REGISTER NOW', 'group' => 'hero', 'type' => 'text', 'label' => 'Primary Button Text']);
        \App\Models\SiteSetting::updateOrCreate(['key' => 'hero_btn1_link'], ['value' => '/registration', 'group' => 'hero', 'type' => 'text', 'label' => 'Primary Button Link']);
        \App\Models\SiteSetting::updateOrCreate(['key' => 'hero_btn2_text'], ['value' => 'APPLY FOR AWARDS', 'group' => 'hero', 'type' => 'text', 'label' => 'Secondary Button Text']);
        \App\Models\SiteSetting::updateOrCreate(['key' => 'hero_btn2_link'], ['value' => '/awards', 'group' => 'hero', 'type' => 'text', 'label' => 'Secondary Button Link']);

        $seeder = new \Database\Seeders\SiteSettingSeeder();
        $seeder->run();

        $logoSeeder = new \Database\Seeders\PartnerLogoSeeder();
        $logoSeeder->run();

        return "<div style='font-family: sans-serif; padding: 40px; background: #f0fdf4; color: #166534; border-radius: 12px; margin: 30px auto; max-width: 600px; border: 1px solid #bbf7d0;'>
            <h2><i class='fa-solid fa-circle-check'></i> Live Database Settings Synchronized!</h2>
            <p>All Site Settings (including Track 1-6 Adjudicators, Staff Incharges, Committee, and Contact Us) have been synchronized and all caches cleared.</p>
            <a href='/' style='background: #16a34a; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin-top: 15px;'>Back to Homepage</a>
        </div>";
    } catch (\Exception $e) {
        return "<div style='font-family: sans-serif; padding: 40px; background: #fef2f2; color: #991b1b; border-radius: 12px; margin: 30px auto; max-width: 600px; border: 1px solid #fecaca;'>
            <h2>Sync Error</h2>
            <p>" . $e->getMessage() . "</p>
        </div>";
    }
});

Route::get('/clear-all-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    return "<div style='font-family: sans-serif; padding: 40px; background: #f0fdf4; color: #166534; border-radius: 12px; margin: 30px auto; max-width: 600px; border: 1px solid #bbf7d0;'>
        <h2>All Caches Cleared!</h2>
        <p>Views, routes, config, and cache have been reset.</p>
        <a href='/' style='background: #16a34a; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin-top: 15px;'>Go to Website</a>
    </div>";
});

Route::get('/git-pull', function () {
    try {
        $output = [];
        $returnVar = 0;
        @exec('git pull origin main 2>&1', $output, $returnVar);
        $gitLog = [];
        @exec('git log -n 1 --oneline 2>&1', $gitLog);
        
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        
        $seeder = new \Database\Seeders\PartnerLogoSeeder();
        $seeder->run();

        // Ensure all media upload directories exist and are writable
        $uploadDirs = [
            public_path('images'),
            public_path('images/stall_booking'),
            public_path('images/sponsors'),
            public_path('images/settings'),
            public_path('images/speakers'),
            public_path('images/venue'),
            public_path('images/event'),
            storage_path('app/public'),
            storage_path('app/public/images'),
            storage_path('app/public/images/stall_booking'),
            storage_path('app/public/images/sponsors'),
            storage_path('app/public/images/settings'),
            storage_path('app/public/images/speakers'),
            storage_path('app/public/images/venue'),
            storage_path('app/public/images/event'),
            storage_path('app/public/abstracts'),
            storage_path('app/public/id_cards'),
            storage_path('app/public/receipts'),
            storage_path('app/public/award_applications'),
            storage_path('framework/views'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
        ];
        foreach ($uploadDirs as $dir) {
            if (!file_exists($dir)) {
                @mkdir($dir, 0777, true);
            }
            @chmod($dir, 0777);
        }

        return "<div style='font-family: sans-serif; padding: 30px; max-width: 650px; margin: 30px auto; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px;'>
            <h2 style='color: #166534;'>Git Pull & Live Update</h2>
            <p><strong>Current Live Commit:</strong> " . (!empty($gitLog) ? implode('<br>', $gitLog) : 'N/A (exec disabled)') . "</p>
            <p><strong>Git Pull Output:</strong><br><pre style='background: #1e293b; color: #f8fafc; padding: 12px; border-radius: 8px;'>" . (!empty($output) ? implode("\n", $output) : 'Executed') . "</pre></p>
            <p style='color: #166534;'>Caches cleared and PartnerLogos synchronized!</p>
            <a href='/' style='display: inline-block; background: #16a34a; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold;'>Visit Homepage</a>
        </div>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});


