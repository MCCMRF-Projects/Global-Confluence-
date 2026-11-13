<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\PaperSubmission;
use App\Models\RegistrationFee;
use App\Models\SiteSetting;
use App\Models\Deadline;
use App\Models\Addon;
use App\Models\Policy;
use App\Models\HeroOrganizer;

class AdminController extends Controller
{
    public function index()
    {
        $registrationCount = Registration::count();
        $submissionCount = PaperSubmission::count();
        
        // Calculate Revenue
        $totalRevenue = Registration::sum('total_amount');

        // Chart Data: Registrations by category
        $regByCategory = Registration::select('category_name', \DB::raw('count(*) as total'))
                            ->groupBy('category_name')
                            ->pluck('total', 'category_name')
                            ->toArray();

        // Recent Activity
        $recentRegistrations = Registration::orderBy('created_at', 'desc')->take(5)->get();
        $recentSubmissions = PaperSubmission::orderBy('created_at', 'desc')->take(5)->get();
        
        return view('admin.dashboard', compact(
            'registrationCount', 'submissionCount', 'totalRevenue', 
            'regByCategory', 'recentRegistrations', 'recentSubmissions'
        ));
    }

    public function registrations()
    {
        $registrations = Registration::orderBy('created_at', 'desc')->get();
        return view('admin.registrations', compact('registrations'));
    }

    public function deleteRegistration($id)
    {
        Registration::findOrFail($id)->delete();
        return back()->with('success', 'Registration deleted successfully.');
    }

    public function submissions()
    {
        $submissions = PaperSubmission::orderBy('created_at', 'desc')->get();
        return view('admin.submissions', compact('submissions'));
    }

    public function deleteSubmission($id)
    {
        PaperSubmission::findOrFail($id)->delete();
        return back()->with('success', 'Submission deleted successfully.');
    }

    public function awardApplications()
    {
        $applications = \App\Models\AwardApplication::orderBy('created_at', 'desc')->get();
        return view('admin.award_applications', compact('applications'));
    }

    public function deleteAwardApplication($id)
    {
        $application = \App\Models\AwardApplication::findOrFail($id);
        if ($application->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($application->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($application->file_path);
        }
        $application->delete();
        return back()->with('success', 'Award application deleted successfully.');
    }

    // CMS: Registration Fees
    public function fees()
    {
        $fees = RegistrationFee::orderBy('sort_order')->get();
        $addons = \App\Models\Addon::all();
        return view('admin.fees.index', compact('fees', 'addons'));
    }

    public function storeFee(Request $request)
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:255',
            'price_inr' => 'required|string|max:50',
            'price_usd' => 'nullable|string|max:50',
            'features' => 'required|string',
            'is_active' => 'boolean',
            'is_highlighted' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $data['features'] = json_encode(array_map('trim', explode("\n", $data['features'])));
        $data['is_active'] = $request->has('is_active');
        $data['is_highlighted'] = $request->has('is_highlighted');

        RegistrationFee::create($data);
        return back()->with('success', 'Fee plan created successfully.');
    }

    public function updateFee(Request $request, $id)
    {
        $fee = RegistrationFee::findOrFail($id);
        
        $data = $request->validate([
            'category_name' => 'required|string|max:255',
            'price_inr' => 'required|string|max:50',
            'price_usd' => 'nullable|string|max:50',
            'features' => 'required|string',
            'sort_order' => 'integer'
        ]);

        $data['features'] = json_encode(array_map('trim', explode("\n", $data['features'])));
        $data['is_active'] = $request->has('is_active');
        $data['is_highlighted'] = $request->has('is_highlighted');

        $fee->update($data);
        return back()->with('success', 'Fee plan updated successfully.');
    }

    public function deleteFee($id)
    {
        RegistrationFee::findOrFail($id)->delete();
        return back()->with('success', 'Fee plan deleted.');
    }

    // CMS: Global Settings
    public function settings()
    {
        $settings = SiteSetting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    // CMS: Hero Section Settings
    public function heroSettings()
    {
        $settings = SiteSetting::whereIn('group', ['hero', 'contact'])->get()->groupBy('group');
        $deadlines = Deadline::orderBy('sort_order')->get();
        $organizers = HeroOrganizer::orderBy('sort_order')->get();
        return view('admin.hero.index', compact('settings', 'deadlines', 'organizers'));
    }

    public function storeHeroOrganizer(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $maxOrder = HeroOrganizer::max('sort_order') ?? 0;
        HeroOrganizer::create([
            'name' => $request->name,
            'sort_order' => $maxOrder + 1,
            'is_active' => true
        ]);
        return redirect()->route('admin.hero')->with('success', 'Organizer added successfully.');
    }

    public function updateHeroOrganizer(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $organizer = HeroOrganizer::findOrFail($id);
        $organizer->update(['name' => $request->name]);
        return redirect()->route('admin.hero')->with('success', 'Organizer updated successfully.');
    }

    public function destroyHeroOrganizer($id)
    {
        $organizer = HeroOrganizer::findOrFail($id);
        $organizer->delete();
        return redirect()->route('admin.hero')->with('success', 'Organizer deleted successfully.');
    }

    public function reorderHeroOrganizers(Request $request)
    {
        $request->validate(['order' => 'required|array']);
        foreach ($request->order as $index => $id) {
            HeroOrganizer::where('id', $id)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }

    // CMS: About Section Settings
    public function aboutSettings()
    {
        $settings = SiteSetting::where('group', 'about')->pluck('value', 'key')->all();
        return view('admin.about.index', compact('settings'));
    }

    // CMS: Conference Objectives & Framework Settings
    public function objectivesSettings()
    {
        $settings = SiteSetting::whereIn('group', ['objectives', 'participants', 'outcomes', 'journey'])->pluck('value', 'key')->all();
        return view('admin.objectives.index', compact('settings'));
    }

    public function updateObjectivesSettings(Request $request)
    {
        // 1. Objectives Section Title
        if ($request->has('objectives_section_title')) {
            SiteSetting::updateOrCreate(['key' => 'objectives_section_title'], ['value' => $request->objectives_section_title, 'group' => 'objectives']);
        }
        
        // Save Objective Cards
        if ($request->has('obj_titles')) {
            SiteSetting::where('group', 'objectives')->where(function($q) {
                $q->where('key', 'like', 'obj\_%\_title')->orWhere('key', 'like', 'obj\_%\_desc')->orWhere('key', 'like', 'obj\_%\_icon');
            })->delete();
            
            $titles = $request->input('obj_titles', []);
            $icons = $request->input('obj_icons', []);
            $descs = $request->input('obj_descs', []);
            
            $count = 0;
            foreach ($titles as $idx => $title) {
                if (!empty($title)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "obj_{$count}_title"], ['value' => $title, 'group' => 'objectives']);
                    SiteSetting::updateOrCreate(['key' => "obj_{$count}_icon"], ['value' => $icons[$idx] ?? 'fa-solid fa-check', 'group' => 'objectives']);
                    SiteSetting::updateOrCreate(['key' => "obj_{$count}_desc"], ['value' => $descs[$idx] ?? '', 'group' => 'objectives']);
                }
            }
            SiteSetting::updateOrCreate(['key' => 'objectives_count'], ['value' => $count, 'group' => 'objectives']);
        }

        // 2. Who Can Attend (Participants)
        if ($request->has('part_tag')) SiteSetting::updateOrCreate(['key' => 'part_tag'], ['value' => $request->part_tag, 'group' => 'participants']);
        if ($request->has('part_title')) SiteSetting::updateOrCreate(['key' => 'part_title'], ['value' => $request->part_title, 'group' => 'participants']);
        if ($request->has('part_sub')) SiteSetting::updateOrCreate(['key' => 'part_sub'], ['value' => $request->part_sub, 'group' => 'participants']);

        if ($request->has('part_labels')) {
            SiteSetting::where('group', 'participants')->where(function($q) {
                $q->where('key', 'like', 'part\_%\_label')->orWhere('key', 'like', 'part\_%\_icon');
            })->delete();
            $labels = $request->input('part_labels', []);
            $icons = $request->input('part_icons', []);
            $count = 0;
            foreach ($labels as $idx => $label) {
                if (!empty($label)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "part_{$count}_label"], ['value' => $label, 'group' => 'participants']);
                    SiteSetting::updateOrCreate(['key' => "part_{$count}_icon"], ['value' => $icons[$idx] ?? 'fa-solid fa-user', 'group' => 'participants']);
                }
            }
            SiteSetting::updateOrCreate(['key' => 'participants_count'], ['value' => $count, 'group' => 'participants']);
        }

        // 3. Key Expected Outcomes
        if ($request->has('outcomes_title')) SiteSetting::updateOrCreate(['key' => 'outcomes_title'], ['value' => $request->outcomes_title, 'group' => 'outcomes']);
        if ($request->has('outcomes_sub')) SiteSetting::updateOrCreate(['key' => 'outcomes_sub'], ['value' => $request->outcomes_sub, 'group' => 'outcomes']);

        if ($request->has('out_titles')) {
            SiteSetting::where('group', 'outcomes')->where(function($q) {
                $q->where('key', 'like', 'out\_%\_title')->orWhere('key', 'like', 'out\_%\_tag')->orWhere('key', 'like', 'out\_%\_icon');
            })->delete();
            $tags = $request->input('out_tags', []);
            $icons = $request->input('out_icons', []);
            $titles = $request->input('out_titles', []);
            $count = 0;
            foreach ($titles as $idx => $title) {
                if (!empty($title)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "out_{$count}_tag"], ['value' => $tags[$idx] ?? 'Outcome', 'group' => 'outcomes']);
                    SiteSetting::updateOrCreate(['key' => "out_{$count}_icon"], ['value' => $icons[$idx] ?? 'fa-solid fa-check', 'group' => 'outcomes']);
                    SiteSetting::updateOrCreate(['key' => "out_{$count}_title"], ['value' => $title, 'group' => 'outcomes']);
                }
            }
            SiteSetting::updateOrCreate(['key' => 'outcomes_count'], ['value' => $count, 'group' => 'outcomes']);
        }

        // 4. Our Journey to Impact
        if ($request->has('journey_title')) SiteSetting::updateOrCreate(['key' => 'journey_title'], ['value' => $request->journey_title, 'group' => 'journey']);
        if ($request->has('journey_sub')) SiteSetting::updateOrCreate(['key' => 'journey_sub'], ['value' => $request->journey_sub, 'group' => 'journey']);

        if ($request->has('journey_titles')) {
            SiteSetting::where('group', 'journey')->where(function($q) {
                $q->where('key', 'like', 'journey\_%\_title')->orWhere('key', 'like', 'journey\_%\_desc');
            })->delete();
            $titles = $request->input('journey_titles', []);
            $descs = $request->input('journey_descs', []);
            $count = 0;
            foreach ($titles as $idx => $title) {
                if (!empty($title)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "journey_{$count}_title"], ['value' => $title, 'group' => 'journey']);
                    SiteSetting::updateOrCreate(['key' => "journey_{$count}_desc"], ['value' => $descs[$idx] ?? '', 'group' => 'journey']);
                }
            }
            SiteSetting::updateOrCreate(['key' => 'journey_count'], ['value' => $count, 'group' => 'journey']);
        }

        return back()->with('success', 'Conference Objectives and Strategic Framework updated successfully.');
    }

    // CMS: Who Can Attend (Participants) Settings
    public function participantsSettings()
    {
        $settings = SiteSetting::where('group', 'participants')->pluck('value', 'key')->all();
        return view('admin.participants.index', compact('settings'));
    }

    // CMS: Key Expected Outcomes Settings
    public function outcomesSettings()
    {
        $settings = SiteSetting::where('group', 'outcomes')->pluck('value', 'key')->all();
        return view('admin.outcomes.index', compact('settings'));
    }

    // CMS: About Organizer Settings
    public function aboutOrganizerSettings()
    {
        $settings = SiteSetting::where('group', 'about_organizer')->pluck('value', 'key')->all();
        return view('admin.about_organizer.index', compact('settings'));
    }

    // CMS: Guidelines Settings
    public function guidelinesSettings()
    {
        $settings = SiteSetting::where('group', 'guidelines')->pluck('value', 'key')->all();
        return view('admin.guidelines.index', compact('settings'));
    }

    public function updateGuidelinesSettings(Request $request)
    {
        // Section titles & static fields
        if ($request->has('banner_publications_title')) SiteSetting::updateOrCreate(['key' => 'banner_publications_title'], ['value' => $request->banner_publications_title, 'group' => 'guidelines']);
        if ($request->has('abstract_tag')) SiteSetting::updateOrCreate(['key' => 'abstract_tag'], ['value' => $request->abstract_tag, 'group' => 'guidelines']);
        if ($request->has('abstract_title')) SiteSetting::updateOrCreate(['key' => 'abstract_title'], ['value' => $request->abstract_title, 'group' => 'guidelines']);
        if ($request->has('oral_title')) SiteSetting::updateOrCreate(['key' => 'oral_title'], ['value' => $request->oral_title, 'group' => 'guidelines']);
        if ($request->has('poster_title')) SiteSetting::updateOrCreate(['key' => 'poster_title'], ['value' => $request->poster_title, 'group' => 'guidelines']);
        if ($request->has('poster_dim_label')) SiteSetting::updateOrCreate(['key' => 'poster_dim_label'], ['value' => $request->poster_dim_label, 'group' => 'guidelines']);
        if ($request->has('poster_dim_val')) SiteSetting::updateOrCreate(['key' => 'poster_dim_val'], ['value' => $request->poster_dim_val, 'group' => 'guidelines']);
        if ($request->has('pub_title')) SiteSetting::updateOrCreate(['key' => 'pub_title'], ['value' => $request->pub_title, 'group' => 'guidelines']);
        if ($request->has('pub_tag')) SiteSetting::updateOrCreate(['key' => 'pub_tag'], ['value' => $request->pub_tag, 'group' => 'guidelines']);
        if ($request->has('pub_announce_title')) SiteSetting::updateOrCreate(['key' => 'pub_announce_title'], ['value' => $request->pub_announce_title, 'group' => 'guidelines']);
        if ($request->has('pub_note')) SiteSetting::updateOrCreate(['key' => 'pub_note'], ['value' => $request->pub_note, 'group' => 'guidelines']);
        if ($request->has('pub_desc')) SiteSetting::updateOrCreate(['key' => 'pub_desc'], ['value' => $request->pub_desc, 'group' => 'guidelines']);

        // 1. Abstract Bullet Items
        if ($request->has('abstract_items')) {
            SiteSetting::where('group', 'guidelines')->where('key', 'like', 'abstract\_item\_%')->delete();
            $items = $request->input('abstract_items', []);
            $count = 0;
            foreach ($items as $item) {
                if (!empty($item)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "abstract_item_{$count}"], ['value' => $item, 'group' => 'guidelines']);
                }
            }
            SiteSetting::updateOrCreate(['key' => 'abstract_count'], ['value' => $count, 'group' => 'guidelines']);
        }

        // 2. Oral Bullet Items
        if ($request->has('oral_items')) {
            SiteSetting::where('group', 'guidelines')->where('key', 'like', 'oral\_item\_%')->delete();
            $items = $request->input('oral_items', []);
            $count = 0;
            foreach ($items as $item) {
                if (!empty($item)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "oral_item_{$count}"], ['value' => $item, 'group' => 'guidelines']);
                }
            }
            SiteSetting::updateOrCreate(['key' => 'oral_count'], ['value' => $count, 'group' => 'guidelines']);
        }

        // 3. Poster Bullet Items
        if ($request->has('poster_items')) {
            SiteSetting::where('group', 'guidelines')->where('key', 'like', 'poster\_item\_%')->delete();
            $items = $request->input('poster_items', []);
            $count = 0;
            foreach ($items as $item) {
                if (!empty($item)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "poster_item_{$count}"], ['value' => $item, 'group' => 'guidelines']);
                }
            }
            SiteSetting::updateOrCreate(['key' => 'poster_count'], ['value' => $count, 'group' => 'guidelines']);
        }

        // 4. Scientific Publication Bullet Items & Settings
        if ($request->has('pub_tag')) SiteSetting::updateOrCreate(['key' => 'pub_tag'], ['value' => $request->pub_tag, 'group' => 'guidelines']);
        if ($request->has('pub_note')) SiteSetting::updateOrCreate(['key' => 'pub_note'], ['value' => $request->pub_note, 'group' => 'guidelines']);

        if ($request->has('pub_items')) {
            SiteSetting::where('group', 'guidelines')->where('key', 'like', 'pub\_item\_%')->delete();
            $items = $request->input('pub_items', []);
            $count = 0;
            foreach ($items as $item) {
                if (!empty($item)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "pub_item_{$count}"], ['value' => $item, 'group' => 'guidelines']);
                }
            }
            SiteSetting::updateOrCreate(['key' => 'pub_count'], ['value' => $count, 'group' => 'guidelines']);
        }

        if ($request->has('pub_titles')) {
            SiteSetting::where('group', 'guidelines')->where(function($q) {
                $q->where('key', 'like', 'pub\_%\_title')->orWhere('key', 'like', 'pub\_%\_desc');
            })->delete();
            $titles = $request->input('pub_titles', []);
            $descs = $request->input('pub_descs', []);
            $count = 0;
            foreach ($titles as $idx => $title) {
                if (!empty($title)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "pub_{$count}_title"], ['value' => $title, 'group' => 'guidelines']);
                    SiteSetting::updateOrCreate(['key' => "pub_{$count}_desc"], ['value' => $descs[$idx] ?? '', 'group' => 'guidelines']);
                }
            }
            SiteSetting::updateOrCreate(['key' => 'pub_count'], ['value' => $count, 'group' => 'guidelines']);
        }

        return back()->with('success', 'Guidelines updated successfully.');
    }

    // CMS: Programme Schedule Settings
    public function scheduleSettings()
    {
        $settings = SiteSetting::where('group', 'schedule')->pluck('value', 'key')->all();
        return view('admin.schedule.index', compact('settings'));
    }

    public function updateScheduleSettings(Request $request)
    {
        if ($request->has('sched_title')) SiteSetting::updateOrCreate(['key' => 'sched_title'], ['value' => $request->sched_title, 'group' => 'schedule']);
        if ($request->has('sched_sub')) SiteSetting::updateOrCreate(['key' => 'sched_sub'], ['value' => $request->sched_sub, 'group' => 'schedule']);
        if ($request->has('sched_day1_title')) SiteSetting::updateOrCreate(['key' => 'sched_day1_title'], ['value' => $request->sched_day1_title, 'group' => 'schedule']);
        if ($request->has('sched_day1_time')) SiteSetting::updateOrCreate(['key' => 'sched_day1_time'], ['value' => $request->sched_day1_time, 'group' => 'schedule']);
        if ($request->has('sched_day2_title')) SiteSetting::updateOrCreate(['key' => 'sched_day2_title'], ['value' => $request->sched_day2_title, 'group' => 'schedule']);
        if ($request->has('sched_day2_time')) SiteSetting::updateOrCreate(['key' => 'sched_day2_time'], ['value' => $request->sched_day2_time, 'group' => 'schedule']);
        if ($request->has('sched_tracks_title')) SiteSetting::updateOrCreate(['key' => 'sched_tracks_title'], ['value' => $request->sched_tracks_title, 'group' => 'schedule']);
        if ($request->has('sched_tracks_sub')) SiteSetting::updateOrCreate(['key' => 'sched_tracks_sub'], ['value' => $request->sched_tracks_sub, 'group' => 'schedule']);

        // Day 1 Rows
        if ($request->has('day1_titles')) {
            SiteSetting::where('group', 'schedule')->where('key', 'like', 'day1\_%\_%')->delete();
            $times = $request->input('day1_times', []);
            $titles = $request->input('day1_titles', []);
            $badges = $request->input('day1_badges', []);
            $icons = $request->input('day1_icons', []);
            $count = 0;
            foreach ($titles as $idx => $title) {
                if (!empty($title)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "day1_{$count}_time"], ['value' => $times[$idx] ?? '', 'group' => 'schedule']);
                    SiteSetting::updateOrCreate(['key' => "day1_{$count}_title"], ['value' => $title, 'group' => 'schedule']);
                    SiteSetting::updateOrCreate(['key' => "day1_{$count}_badge"], ['value' => $badges[$idx] ?? '', 'group' => 'schedule']);
                    SiteSetting::updateOrCreate(['key' => "day1_{$count}_icon"], ['value' => $icons[$idx] ?? 'fa-clock', 'group' => 'schedule']);
                }
            }
            SiteSetting::updateOrCreate(['key' => 'day1_count'], ['value' => $count, 'group' => 'schedule']);
        }

        // Day 2 Rows
        if ($request->has('day2_titles')) {
            SiteSetting::where('group', 'schedule')->where('key', 'like', 'day2\_%\_%')->delete();
            $times = $request->input('day2_times', []);
            $titles = $request->input('day2_titles', []);
            $badges = $request->input('day2_badges', []);
            $icons = $request->input('day2_icons', []);
            $count = 0;
            foreach ($titles as $idx => $title) {
                if (!empty($title)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "day2_{$count}_time"], ['value' => $times[$idx] ?? '', 'group' => 'schedule']);
                    SiteSetting::updateOrCreate(['key' => "day2_{$count}_title"], ['value' => $title, 'group' => 'schedule']);
                    SiteSetting::updateOrCreate(['key' => "day2_{$count}_badge"], ['value' => $badges[$idx] ?? '', 'group' => 'schedule']);
                    SiteSetting::updateOrCreate(['key' => "day2_{$count}_icon"], ['value' => $icons[$idx] ?? 'fa-clock', 'group' => 'schedule']);
                }
            }
            SiteSetting::updateOrCreate(['key' => 'day2_count'], ['value' => $count, 'group' => 'schedule']);
        }

        // Tracks Rows
        if ($request->has('track_names')) {
            SiteSetting::where('group', 'schedule')->where('key', 'like', 'track\_%\_%')->delete();
            $names = $request->input('track_names', []);
            $topics = $request->input('track_topics', []);
            $colors = $request->input('track_colors', []);
            $adjudicators = $request->input('track_adjudicators', []);
            $staffs = $request->input('track_staffs', []);
            $count = 0;
            foreach ($names as $idx => $name) {
                if (!empty($name)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "track_{$count}_name"], ['value' => $name, 'group' => 'schedule']);
                    SiteSetting::updateOrCreate(['key' => "track_{$count}_topic"], ['value' => $topics[$idx] ?? '', 'group' => 'schedule']);
                    SiteSetting::updateOrCreate(['key' => "track_{$count}_color"], ['value' => $colors[$idx] ?? '#009688', 'group' => 'schedule']);
                    if (isset($adjudicators[$idx])) {
                        SiteSetting::updateOrCreate(['key' => "track_{$count}_adjudicator"], ['value' => $adjudicators[$idx], 'group' => 'schedule']);
                    }
                    if (isset($staffs[$idx])) {
                        SiteSetting::updateOrCreate(['key' => "track_{$count}_staff"], ['value' => $staffs[$idx], 'group' => 'schedule']);
                    }
                }
            }
            SiteSetting::updateOrCreate(['key' => 'track_count'], ['value' => $count, 'group' => 'schedule']);
        }

        return back()->with('success', 'Programme Schedule settings updated successfully.');
    }

    // CMS: Conference Section Settings
    public function conferenceSettings()
    {
        $settings = SiteSetting::whereIn('group', ['conference', 'objectives', 'participants'])->get()->groupBy('group');
        return view('admin.conference.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                $value = $this->safeUploadImage($request->file($key), 'images/settings');
            } elseif ($value === null && SiteSetting::where('key', $key)->value('type') === 'image') {
                continue;
            }
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        return back()->with('success', 'Settings updated successfully.');
    }

    // CMS: Pillars Settings
    public function pillarsSettings()
    {
        $settings = SiteSetting::whereIn('group', ['pillars'])->get()->groupBy('group');
        return view('admin.pillars.index', compact('settings'));
    }

    // CMS: Registration Page Settings
    public function registrationSettings()
    {
        $settings = SiteSetting::pluck('value', 'key')->all();
        $interestOptions = \App\Models\InterestOption::orderBy('sort_order')->get();
        $policies = \App\Models\Policy::orderBy('sort_order')->get();
        return view('admin.settings.registration', compact('settings', 'interestOptions', 'policies'));
    }

    public function updateRegistrationSettings(Request $request)
    {
        if ($request->has('reg_section_title')) SiteSetting::updateOrCreate(['key' => 'reg_section_title'], ['value' => $request->reg_section_title, 'group' => 'registration']);
        if ($request->has('reg_section_sub')) SiteSetting::updateOrCreate(['key' => 'reg_section_sub'], ['value' => $request->reg_section_sub, 'group' => 'registration']);
        if ($request->has('reg_proc_title')) SiteSetting::updateOrCreate(['key' => 'reg_proc_title'], ['value' => $request->reg_proc_title, 'group' => 'registration']);
        if ($request->has('reg_proc_sub')) SiteSetting::updateOrCreate(['key' => 'reg_proc_sub'], ['value' => $request->reg_proc_sub, 'group' => 'registration']);
        if ($request->has('reg_proc_heading')) SiteSetting::updateOrCreate(['key' => 'reg_proc_heading'], ['value' => $request->reg_proc_heading, 'group' => 'registration']);

        if ($request->has('reg_steps')) {
            SiteSetting::where('group', 'registration')->where('key', 'like', 'reg\_step\_%')->delete();
            $steps = $request->input('reg_steps', []);
            $count = 0;
            foreach ($steps as $step) {
                if (!empty($step)) {
                    $count++;
                    SiteSetting::updateOrCreate(['key' => "reg_step_{$count}"], ['value' => $step, 'group' => 'registration']);
                }
            }
            SiteSetting::updateOrCreate(['key' => 'reg_step_count'], ['value' => $count, 'group' => 'registration']);
        }

        return back()->with('success', 'Registration settings updated successfully.');
    }

    // CMS: Interest Options
    public function storeInterestOption(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'integer'
        ]);
        \App\Models\InterestOption::create($data);
        return back()->with('success', 'Option created successfully.');
    }

    public function deleteInterestOption($id)
    {
        \App\Models\InterestOption::findOrFail($id)->delete();
        return back()->with('success', 'Option deleted successfully.');
    }

    // CMS: Programs & Themes (Scientific Tracks & Thrust Areas)
    public function programsSettings()
    {
        $settings = SiteSetting::whereIn('group', ['tracks_page', 'workshop', 'thrust_areas'])->pluck('value', 'key')->toArray();
        $tracks = \App\Models\Track::orderBy('sort_order')->get();
        $references = isset($settings['tracks_references_json']) ? json_decode($settings['tracks_references_json'], true) : [];
        return view('admin.programs.index', compact('settings', 'tracks', 'references'));
    }

    public function updateProgramsSettings(Request $request)
    {
        $keys = [
            'tracks_banner_title',
            'tracks_banner_subtitle',
            'tracks_section_badge',
            'tracks_section_title',
            'tracks_section_desc',
            'tracks_references_title'
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::updateOrCreate(
                    ['key' => $key, 'group' => 'tracks_page'],
                    ['value' => $request->input($key)]
                );
            }
        }

        // Handle dynamic references
        if ($request->has('references_list')) {
            $rawRefs = $request->input('references_list', []);
            $cleanRefs = array_values(array_filter(array_map('trim', (array)$rawRefs), fn($r) => !empty($r)));
            SiteSetting::updateOrCreate(
                ['key' => 'tracks_references_json', 'group' => 'tracks_page'],
                ['value' => json_encode($cleanRefs)]
            );
        }

        return back()->with('success', 'Scientific Tracks & Themes content updated successfully.');
    }

    public function storeTrack(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'badge' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'integer',
            'bullet_points' => 'nullable|array'
        ]);

        // Filter out empty bullet points
        if (isset($data['bullet_points'])) {
            $data['bullet_points'] = array_filter($data['bullet_points'], fn($val) => !empty(trim($val)));
            $data['bullet_points'] = array_values($data['bullet_points']);
        } else {
            $data['bullet_points'] = [];
        }

        \App\Models\Track::create($data);
        return back()->with('success', 'Track created successfully.');
    }

    public function updateTrack(Request $request, $id)
    {
        $track = \App\Models\Track::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'badge' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'integer',
            'bullet_points' => 'nullable|array'
        ]);

        if (isset($data['bullet_points'])) {
            $data['bullet_points'] = array_filter($data['bullet_points'], fn($val) => !empty(trim($val)));
            $data['bullet_points'] = array_values($data['bullet_points']);
        } else {
            $data['bullet_points'] = [];
        }

        $track->update($data);
        return back()->with('success', 'Track updated successfully.');
    }

    public function destroyTrack($id)
    {
        \App\Models\Track::findOrFail($id)->delete();
        return back()->with('success', 'Track deleted successfully.');
    }

    // CMS: Abstracts & Awards
    public function abstractsAwards()
    {
        $settings = SiteSetting::whereIn('group', ['abstract', 'awards'])->get()->groupBy('group');
        return view('admin.settings.abstracts_awards', compact('settings'));
    }

    public function updateAbstractsAwards(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            // Handle image uploads if present
            if (str_ends_with($key, '_file')) {
                if ($request->hasFile($key)) {
                    $originalKey = str_replace('_file', '', $key);
                    $path = $this->safeUploadImage($request->file($key), 'images/settings');
                    SiteSetting::where('key', $originalKey)->update(['value' => $path]);
                }
                continue;
            }
            SiteSetting::where('key', $key)->update(['value' => $value]);
        }
        return back()->with('success', 'Abstracts & Awards settings updated successfully.');
    }

    // CMS: Venue Highlights
    public function venueHighlights()
    {
        $settings = SiteSetting::where('group', 'venue_highlights')->get()->groupBy('group');
        return view('admin.settings.venue_highlights', compact('settings'));
    }

    // CMS: Venue
    public function venueSettings()
    {
        $settings = SiteSetting::where('group', 'venue')->get()->groupBy('group');
        return view('admin.settings.venue', compact('settings'));
    }

    public function updateVenueSettings(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            if (str_ends_with($key, '_file')) {
                if ($request->hasFile($key)) {
                    $originalKey = str_replace('_file', '', $key);
                    $path = $this->safeUploadImage($request->file($key), 'images/venue');
                    SiteSetting::updateOrCreate(
                        ['key' => $originalKey, 'group' => 'venue'],
                        ['value' => $path]
                    );
                }
                continue;
            }
            SiteSetting::updateOrCreate(
                ['key' => $key, 'group' => 'venue'],
                ['value' => $value]
            );
        }
        return back()->with('success', 'Venue settings updated successfully.');
    }

    // CMS: Important Deadlines Management
    public function deadlines()
    {
        $settings = SiteSetting::where('group', 'deadlines')->pluck('value', 'key')->toArray();
        $deadlines = Deadline::orderBy('sort_order')->get();
        return view('admin.deadlines.index', compact('settings', 'deadlines'));
    }

    public function updateDeadlinesHeaderSettings(Request $request)
    {
        $keys = ['deadlines_badge', 'deadlines_title', 'deadlines_subtitle'];
        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->input($key), 'group' => 'deadlines']
                );
            }
        }
        return back()->with('success', 'Deadlines header settings updated successfully.');
    }

    public function storeDeadline(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $maxOrder = Deadline::max('sort_order') ?? 0;
        
        Deadline::create([
            'phase' => $request->input('phase', 'PHASE ' . sprintf('%02d', $maxOrder + 1)),
            'title' => $request->input('title'),
            'date_text' => $request->input('date_text', 'TBA'),
            'deadline_date' => $request->input('deadline_date') ? $request->input('deadline_date') : null,
            'description' => $request->input('description'),
            'icon' => $request->input('icon', 'fa-solid fa-file-arrow-up'),
            'tag_label' => $request->input('tag_label'),
            'tag_icon' => $request->input('tag_icon', 'fa-solid fa-circle-dot'),
            'color_theme' => $request->input('color_theme', 'teal'),
            'sort_order' => $request->input('sort_order', $maxOrder + 1),
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return back()->with('success', 'Deadline card created successfully.');
    }

    public function updateDeadline(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $deadline = Deadline::findOrFail($id);

        $deadline->update([
            'phase' => $request->input('phase', $deadline->phase),
            'title' => $request->input('title'),
            'date_text' => $request->input('date_text', $deadline->date_text),
            'deadline_date' => $request->input('deadline_date') ? $request->input('deadline_date') : $deadline->deadline_date,
            'description' => $request->input('description'),
            'icon' => $request->input('icon', $deadline->icon),
            'tag_label' => $request->input('tag_label'),
            'tag_icon' => $request->input('tag_icon', $deadline->tag_icon),
            'color_theme' => $request->input('color_theme', $deadline->color_theme),
            'sort_order' => $request->input('sort_order', $deadline->sort_order),
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

        return back()->with('success', 'Deadline card updated successfully.');
    }

    public function deleteDeadline($id)
    {
        Deadline::findOrFail($id)->delete();
        return back()->with('success', 'Deadline card deleted successfully.');
    }

    public function reorderDeadlines(Request $request)
    {
        $request->validate(['order' => 'required|array']);
        foreach ($request->order as $index => $id) {
            Deadline::where('id', $id)->update(['sort_order' => $index + 1]);
        }
        return response()->json(['success' => true]);
    }

    // CMS: Addons
    public function addons()
    {
        $addons = Addon::all();
        return view('admin.addons.index', compact('addons'));
    }

    public function storeAddon(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'badge_text' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);
        $data['is_active'] = $request->has('is_active');
        Addon::create($data);
        return back()->with('success', 'Add-on created successfully.');
    }

    public function updateAddon(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'badge_text' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);
        $data['is_active'] = $request->has('is_active');
        Addon::findOrFail($id)->update($data);
        return back()->with('success', 'Add-on updated successfully.');
    }

    public function deleteAddon($id)
    {
        Addon::findOrFail($id)->delete();
        return back()->with('success', 'Add-on deleted successfully.');
    }

    // CMS: Policies
    public function policies()
    {
        $policies = Policy::orderBy('sort_order')->get();
        return view('admin.policies.index', compact('policies'));
    }

    public function storePolicy(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content_html' => 'required|string',
            'sort_order' => 'integer',
            'is_active' => 'boolean'
        ]);
        $data['is_active'] = $request->has('is_active');
        Policy::create($data);
        return back()->with('success', 'Policy created successfully.');
    }

    public function updatePolicy(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content_html' => 'required|string',
            'sort_order' => 'integer',
            'is_active' => 'boolean'
        ]);
        $data['is_active'] = $request->has('is_active');
        Policy::findOrFail($id)->update($data);
        return back()->with('success', 'Policy updated successfully.');
    }

    public function deletePolicy($id)
    {
        Policy::findOrFail($id)->delete();
        return back()->with('success', 'Policy deleted successfully.');
    }

    public function submitPaperSettings()
    {
        $settings = SiteSetting::where('group', 'submit_paper')->get();
        return view('admin.settings.submit_paper', compact('settings'));
    }

    public function updateSubmitPaperSettings(Request $request)
    {
        $settings = $request->except(['_token']);
        foreach ($settings as $key => $value) {
            if ($request->hasFile($key)) {
                $value = $this->safeUploadImage($request->file($key), 'images/settings');
            } elseif ($value === null && SiteSetting::where('key', $key)->value('type') === 'image') {
                continue;
            } elseif ($value === null && SiteSetting::where('key', $key)->value('type') === 'file') {
                continue;
            }
            SiteSetting::where('key', $key)->update(['value' => $value]);
        }
        return back()->with('success', 'Submit Paper settings updated successfully.');
    }

    // CMS: Page Banners
    public function pageBanners()
    {
        $settings = \App\Models\SiteSetting::where('group', 'page_banners')->get();
        // Group by page prefix, e.g., 'banner_registration_title' -> 'registration'
        $groupedSettings = [];
        foreach ($settings as $setting) {
            $parts = explode('_', $setting->key);
            if (count($parts) >= 3) {
                // banner_{page}_type
                $type = array_pop($parts); // title or image
                array_shift($parts); // remove 'banner'
                $page = implode('_', $parts); // e.g., registration, plenary_speakers
                $groupedSettings[$page][$type] = $setting;
            }
        }
        return view('admin.settings.page_banners', compact('groupedSettings'));
    }

    public function updatePageBanners(Request $request)
    {
        $settings = $request->except(['_token']);
        foreach ($settings as $key => $value) {
            if ($request->hasFile($key)) {
                $value = $this->safeUploadImage($request->file($key), 'images/settings');
            } elseif ($value === null && SiteSetting::where('key', $key)->value('type') === 'image') {
                continue;
            }
            SiteSetting::where('key', $key)->update(['value' => $value]);
        }
        return back()->with('success', 'Page Banners updated successfully.');
    }

    // --- Submit Paper Form Builder ---
    public function submitPaperFormFields()
    {
        $fields = \App\Models\SubmitPaperField::orderBy('sort_order')->get();
        return view('admin.submit_paper_fields.index', compact('fields'));
    }

    public function storeSubmitPaperFormField(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:submit_paper_fields,name',
            'label' => 'required|string|max:255',
            'type' => 'required|string',
            'placeholder' => 'nullable|string',
            'grid_column' => 'required|string',
            'sort_order' => 'integer',
            'options' => 'nullable|string', 
        ]);
        
        $data['is_required'] = $request->has('is_required');
        
        if (!empty($data['options'])) {
            $data['options'] = array_map('trim', explode(',', $data['options']));
        } else {
            $data['options'] = null;
        }

        \App\Models\SubmitPaperField::create($data);
        return back()->with('success', 'Field added successfully.');
    }

    public function updateSubmitPaperFormField(Request $request, $id)
    {
        $field = \App\Models\SubmitPaperField::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:submit_paper_fields,name,' . $id,
            'label' => 'required|string|max:255',
            'type' => 'required|string',
            'placeholder' => 'nullable|string',
            'grid_column' => 'required|string',
            'sort_order' => 'integer',
            'options' => 'nullable|string',
        ]);
        
        $data['is_required'] = $request->has('is_required');

        if (!empty($data['options'])) {
            $data['options'] = array_map('trim', explode(',', $data['options']));
        } else {
            $data['options'] = null;
        }

        $field->update($data);
        return back()->with('success', 'Field updated successfully.');
    }

    public function destroySubmitPaperFormField($id)
    {
        \App\Models\SubmitPaperField::findOrFail($id)->delete();
        return back()->with('success', 'Field deleted successfully.');
    }

    // --- Speakers CMS ---

    public function speakers(Request $request)
    {
        $type = $request->query('type', 'keynote'); // Default to keynote
        $speakers = \App\Models\Speaker::where('type', $type)->orderBy('sort_order')->get();
        return view('admin.speakers.index', compact('speakers', 'type'));
    }

    public function storeSpeaker(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|in:keynote,distinguished,pre_conference',
            'name' => 'required|string|max:255',
            'h_index' => 'nullable|string|max:50',
            'university' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'title' => 'nullable|string',
            'field' => 'nullable|string',
            'sort_order' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if (empty($data['country'])) {
            $data['country'] = 'India';
        }
        if (empty($data['university'])) {
            $data['university'] = '-';
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->safeUploadImage($request->file('image'), 'images/speakers');
        } else {
            $data['image_path'] = null;
        }

        \App\Models\Speaker::create($data);
        return back()->with('success', 'Speaker added successfully.');
    }

    public function updateSpeaker(Request $request, $id)
    {
        $speaker = \App\Models\Speaker::findOrFail($id);
        
        $data = $request->validate([
            'type' => 'required|string|in:keynote,distinguished,pre_conference',
            'name' => 'required|string|max:255',
            'h_index' => 'nullable|string|max:50',
            'university' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'title' => 'nullable|string',
            'field' => 'nullable|string',
            'sort_order' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if (empty($data['country'])) {
            $data['country'] = 'India';
        }
        if (empty($data['university'])) {
            $data['university'] = '-';
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->safeUploadImage($request->file('image'), 'images/speakers');
        }

        $speaker->update($data);
        return back()->with('success', 'Speaker updated successfully.');
    }

    public function destroySpeaker($id)
    {
        $speaker = \App\Models\Speaker::findOrFail($id);
        $speaker->delete();
        return back()->with('success', 'Speaker deleted successfully.');
    }

    // --- Topics CMS ---

    public function topics(Request $request)
    {
        $column = $request->query('column', 1);
        $topics = \App\Models\Topic::where('column_number', $column)->orderBy('sort_order')->get();
        $settings = \App\Models\SiteSetting::where('group', 'topics')->get()->groupBy('group');
        return view('admin.topics.index', compact('topics', 'column', 'settings'));
    }

    public function updateTopicsSettings(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            if (str_ends_with($key, '_file')) {
                if ($request->hasFile($key)) {
                    $originalKey = str_replace('_file', '', $key);
                    $path = $this->safeUploadImage($request->file($key), 'images/settings');
                    \App\Models\SiteSetting::updateOrCreate(['key' => $originalKey, 'group' => 'topics'], ['value' => $path]);
                }
                continue;
            }
            \App\Models\SiteSetting::updateOrCreate(['key' => $key, 'group' => 'topics'], ['value' => $value]);
        }
        return back()->with('success', 'Topic settings updated successfully.');
    }

    public function storeTopic(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'column_number' => 'required|integer|in:1,2,3',
            'sort_order' => 'required|integer',
        ]);

        \App\Models\Topic::create($data);
        return back()->with('success', 'Topic added successfully.');
    }

    public function updateTopic(Request $request, $id)
    {
        $topic = \App\Models\Topic::findOrFail($id);
        
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'column_number' => 'required|integer|in:1,2,3',
            'sort_order' => 'required|integer',
        ]);

        $topic->update($data);
        return back()->with('success', 'Topic updated successfully.');
    }

    public function destroyTopic($id)
    {
        $topic = \App\Models\Topic::findOrFail($id);
        $topic->delete();
        return back()->with('success', 'Topic deleted successfully.');
    }

    // --- Highlights CMS ---

    public function highlights(Request $request)
    {
        $column = $request->query('column', 1);
        $highlights = \App\Models\Highlight::where('column_number', $column)->orderBy('sort_order')->get();
        $settings = \App\Models\SiteSetting::where('group', 'highlights')->get()->groupBy('group');
        return view('admin.highlights.index', compact('highlights', 'column', 'settings'));
    }

    public function updateHighlightsSettings(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            if (str_ends_with($key, '_file')) {
                if ($request->hasFile($key)) {
                    $originalKey = str_replace('_file', '', $key);
                    $path = $this->safeUploadImage($request->file($key), 'images/settings');
                    \App\Models\SiteSetting::updateOrCreate(['key' => $originalKey, 'group' => 'highlights'], ['value' => $path]);
                }
                continue;
            }
            \App\Models\SiteSetting::updateOrCreate(['key' => $key, 'group' => 'highlights'], ['value' => $value]);
        }
        return back()->with('success', 'Highlight settings updated successfully.');
    }

    public function storeHighlight(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'column_number' => 'required|integer|in:1,2,3',
            'sort_order' => 'required|integer',
        ]);

        \App\Models\Highlight::create($data);
        return back()->with('success', 'Highlight added successfully.');
    }

    public function updateHighlight(Request $request, $id)
    {
        $topic = \App\Models\Highlight::findOrFail($id);
        
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'column_number' => 'required|integer|in:1,2,3',
            'sort_order' => 'required|integer',
        ]);

        $topic->update($data);
        return back()->with('success', 'Highlight updated successfully.');
    }

    public function destroyHighlight($id)
    {
        $topic = \App\Models\Highlight::findOrFail($id);
        $topic->delete();
        return back()->with('success', 'Highlight deleted successfully.');
    }

    // --- Committee CMS ---

    public function committee(Request $request)
    {
        $currentTab = $request->query('tab', 'all');
        $category = $request->query('category', 'leadership');

        $leadership = \App\Models\CommitteeMember::where('category', 'leadership')->orderBy('sort_order')->get()->groupBy('subcategory');
        $organizing = \App\Models\CommitteeMember::where('category', 'organizing_committee')->orderBy('sort_order')->get();
        $advisory = \App\Models\CommitteeMember::where('category', 'advisory_committee')->orderBy('sort_order')->get();
        $allMembers = \App\Models\CommitteeMember::orderBy('category')->orderBy('sort_order')->get();

        $settings = \App\Models\SiteSetting::where('group', 'committee_page')->pluck('value', 'key')->toArray();
        $bannerSettings = \App\Models\SiteSetting::where('group', 'page_banners')->pluck('value', 'key')->toArray();
        $trackSettings = \App\Models\SiteSetting::where('group', 'schedule')->pluck('value', 'key')->toArray();

        return view('admin.committee.index', compact(
            'leadership', 'organizing', 'advisory', 'allMembers',
            'settings', 'bannerSettings', 'trackSettings',
            'currentTab', 'category'
        ));
    }

    public function updateCommitteeSettings(Request $request)
    {
        $settings = $request->except(['_token', 'track_names', 'track_colors', 'track_topics', 'track_adjudicators', 'track_staffs', 'sched_tracks_title', 'sched_tracks_sub']);
        foreach ($settings as $key => $value) {
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'committee_page']
            );
        }

        // Handle Track-Wise Incharge Title & Subtitle
        if ($request->has('sched_tracks_title')) {
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => 'sched_tracks_title'],
                ['value' => $request->sched_tracks_title, 'group' => 'schedule']
            );
        }
        if ($request->has('sched_tracks_sub')) {
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => 'sched_tracks_sub'],
                ['value' => $request->sched_tracks_sub, 'group' => 'schedule']
            );
        }

        // Handle Track-Wise Incharges array
        if ($request->has('track_names')) {
            \App\Models\SiteSetting::where('group', 'schedule')->where('key', 'like', 'track\_%\_%')->delete();
            $names = $request->track_names;
            $colors = $request->track_colors ?? [];
            $topics = $request->track_topics ?? [];
            $adjudicators = $request->track_adjudicators ?? [];
            $staffs = $request->track_staffs ?? [];

            $count = 0;
            foreach ($names as $idx => $name) {
                if (!empty(trim($name))) {
                    $count++;
                    \App\Models\SiteSetting::updateOrCreate(['key' => "track_{$count}_name"], ['value' => $name, 'group' => 'schedule']);
                    \App\Models\SiteSetting::updateOrCreate(['key' => "track_{$count}_topic"], ['value' => $topics[$idx] ?? '', 'group' => 'schedule']);
                    \App\Models\SiteSetting::updateOrCreate(['key' => "track_{$count}_color"], ['value' => $colors[$idx] ?? '#009688', 'group' => 'schedule']);
                    if (isset($adjudicators[$idx])) {
                        \App\Models\SiteSetting::updateOrCreate(['key' => "track_{$count}_adjudicator"], ['value' => $adjudicators[$idx], 'group' => 'schedule']);
                    }
                    if (isset($staffs[$idx])) {
                        \App\Models\SiteSetting::updateOrCreate(['key' => "track_{$count}_staff"], ['value' => $staffs[$idx], 'group' => 'schedule']);
                    }
                }
            }
            \App\Models\SiteSetting::updateOrCreate(['key' => 'track_count'], ['value' => $count, 'group' => 'schedule']);
        }

        return back()->with('success', 'Committee and Track Incharge settings updated successfully.');
    }

    public function storeCommitteeMember(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'category' => 'required|string|in:leadership,organizing_committee,advisory_committee',
            'subcategory' => 'nullable|string|in:chief_patron,patrons,convenor,co_convenors,organizing_secretaries,national,international,other',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        \App\Models\CommitteeMember::create($data);
        return back()->with('success', 'Committee member added successfully.');
    }

    public function updateCommitteeMember(Request $request, $id)
    {
        $member = \App\Models\CommitteeMember::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'category' => 'required|string|in:leadership,organizing_committee,advisory_committee',
            'subcategory' => 'nullable|string|in:chief_patron,patrons,convenor,co_convenors,organizing_secretaries,national,international,other',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        $member->update($data);
        return back()->with('success', 'Committee member updated successfully.');
    }

    public function destroyCommitteeMember($id)
    {
        $member = \App\Models\CommitteeMember::findOrFail($id);
        $member->delete();
        return back()->with('success', 'Committee member deleted successfully.');
    }
    // --- Sponsors CMS ---

    public function sponsors()
    {
        $settings = \App\Models\SiteSetting::where('group', 'sponsors')->get()->groupBy('group');
        $tiers = \App\Models\SponsorPackage::where('type', 'tier')->orderBy('sort_order')->get();
        $additional = \App\Models\SponsorPackage::where('type', 'additional')->orderBy('sort_order')->get();
        return view('admin.sponsors.index', compact('settings', 'tiers', 'additional'));
    }

    public function updateSponsorSettings(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            \App\Models\SiteSetting::updateOrCreate(['key' => $key, 'group' => 'sponsors'], ['value' => $value]);
        }
        return back()->with('success', 'Sponsor settings updated successfully.');
    }

    public function storeSponsorPackage(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'features' => 'nullable|string',
            'ribbon_color' => 'nullable|string|max:255',
            'type' => 'required|string|in:tier,additional',
            'sort_order' => 'required|integer',
        ]);

        if (!empty($data['features'])) {
            $data['features'] = array_map('trim', explode("\n", $data['features']));
        } else {
            $data['features'] = null;
        }

        \App\Models\SponsorPackage::create($data);
        return back()->with('success', 'Sponsor package added successfully.');
    }

    public function updateSponsorPackage(Request $request, $id)
    {
        $package = \App\Models\SponsorPackage::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'features' => 'nullable|string',
            'ribbon_color' => 'nullable|string|max:255',
            'type' => 'required|string|in:tier,additional',
            'sort_order' => 'required|integer',
        ]);

        if (!empty($data['features'])) {
            $data['features'] = array_map('trim', explode("\n", $data['features']));
        } else {
            $data['features'] = null;
        }

        $package->update($data);
        return back()->with('success', 'Sponsor package updated successfully.');
    }

    public function destroySponsorPackage($id)
    {
        $package = \App\Models\SponsorPackage::findOrFail($id);
        $package->delete();
        return back()->with('success', 'Sponsor package deleted successfully.');
    }

    // --- Awards CMS ---
    public function awards()
    {
        $settings = \App\Models\SiteSetting::whereIn('group', ['awards_page', 'awards', 'page_banners'])->pluck('value', 'key')->all();
        $awards = \App\Models\Award::orderBy('sort_order')->get();
        return view('admin.awards.index', compact('settings', 'awards'));
    }

    public function updateAwardsSettings(Request $request)
    {
        if ($request->has('awards_banner_title')) {
            \App\Models\SiteSetting::updateOrCreate(['key' => 'banner_awards_title', 'group' => 'page_banners'], ['value' => $request->awards_banner_title]);
            \App\Models\SiteSetting::updateOrCreate(['key' => 'awards_banner_title', 'group' => 'awards_page'], ['value' => $request->awards_banner_title]);
        }
        if ($request->has('awards_section_title')) \App\Models\SiteSetting::updateOrCreate(['key' => 'awards_section_title'], ['value' => $request->awards_section_title, 'group' => 'awards_page']);
        if ($request->has('awards_section_sub')) \App\Models\SiteSetting::updateOrCreate(['key' => 'awards_section_sub'], ['value' => $request->awards_section_sub, 'group' => 'awards_page']);
        if ($request->has('awards_footer_icon')) \App\Models\SiteSetting::updateOrCreate(['key' => 'awards_footer_icon'], ['value' => $request->awards_footer_icon, 'group' => 'awards_page']);
        if ($request->has('awards_footer_note')) \App\Models\SiteSetting::updateOrCreate(['key' => 'awards_footer_note'], ['value' => $request->awards_footer_note, 'group' => 'awards_page']);

        if ($request->has('award_titles')) {
            \App\Models\SiteSetting::where('group', 'awards_page')->where(function($q) {
                $q->where('key', 'like', 'award\_%\_title')
                  ->orWhere('key', 'like', 'award\_%\_amount')
                  ->orWhere('key', 'like', 'award\_%\_icon')
                  ->orWhere('key', 'like', 'award\_%\_proforma');
            })->delete();

            $titles = $request->input('award_titles', []);
            $amounts = $request->input('award_amounts', []);
            $icons = $request->input('award_icons', []);
            $proformas = $request->input('award_proformas', []);

            $count = 0;
            foreach ($titles as $idx => $title) {
                if (!empty(trim($title))) {
                    $count++;
                    \App\Models\SiteSetting::updateOrCreate(['key' => "award_{$count}_title", 'group' => 'awards_page'], ['value' => trim($title)]);
                    \App\Models\SiteSetting::updateOrCreate(['key' => "award_{$count}_amount", 'group' => 'awards_page'], ['value' => trim($amounts[$idx] ?? '')]);
                    \App\Models\SiteSetting::updateOrCreate(['key' => "award_{$count}_icon", 'group' => 'awards_page'], ['value' => trim($icons[$idx] ?? 'fa-solid fa-award')]);
                    if (isset($proformas[$idx])) {
                        \App\Models\SiteSetting::updateOrCreate(['key' => "award_{$count}_proforma", 'group' => 'awards_page'], ['value' => trim($proformas[$idx])]);
                    }
                }
            }
            \App\Models\SiteSetting::updateOrCreate(['key' => 'awards_count', 'group' => 'awards_page'], ['value' => $count]);
        }

        return back()->with('success', 'Conference Awards settings updated successfully.');
    }

    public function storeAward(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'icon_color' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'eligibility' => 'nullable|string',
            'guidelines' => 'nullable|string',
            'sort_order' => 'required|integer',
        ]);
        
        \App\Models\Award::create($data);
        return back()->with('success', 'Award created successfully.');
    }

    public function updateAward(Request $request, $id)
    {
        $award = \App\Models\Award::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'icon_color' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'eligibility' => 'nullable|string',
            'guidelines' => 'nullable|string',
            'sort_order' => 'required|integer',
        ]);

        $award->update($data);
        return back()->with('success', 'Award updated successfully.');
    }

    public function destroyAward($id)
    {
        \App\Models\Award::findOrFail($id)->delete();
        return back()->with('success', 'Award deleted successfully.');
    }



    // CMS: Event Details (Schedule, Deadlines, Venue)
    public function eventDetails()
    {
        $groupedSettings = \App\Models\SiteSetting::whereIn('group', ['venue', 'deadlines'])->get()->groupBy('group');
        $settings = \App\Models\SiteSetting::whereIn('group', ['venue', 'schedule', 'deadlines'])->pluck('value', 'key');
        
        // Inject the grouped collections so views expecting them (venue, deadlines) don't break
        $settings['venue'] = $groupedSettings->get('venue', collect([]));
        $settings['deadlines'] = $groupedSettings->get('deadlines', collect([]));

        $deadlines = \App\Models\Deadline::orderBy('sort_order')->get();
        return view('admin.event_details.index', compact('settings', 'deadlines'));
    }

    public function updateEventDetails(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            if (str_ends_with($key, '_file')) {
                if ($request->hasFile($key)) {
                    $originalKey = str_replace('_file', '', $key);
                    $path = $this->safeUploadImage($request->file($key), 'images/event');
                    \App\Models\SiteSetting::updateOrCreate(
                        ['key' => $originalKey],
                        ['value' => $path, 'group' => 'venue']
                    );
                }
                continue;
            }
            
            // Assume schedule settings starts with sched_ or day1_ or day2_
            $group = (str_starts_with($key, 'sched_') || str_starts_with($key, 'day1_') || str_starts_with($key, 'day2_')) ? 'schedule' : 'venue';
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value, 'group' => $group]
            );
        }
        return back()->with('success', 'Event Details updated successfully.');
    }

    public function homeSettings()
    {
        return view('admin.home.index');
    }

    public function updateHomeSettings(Request $request)
    {
        return back()->with('success', 'Home Settings updated successfully.');
    }

    public function preConferenceSettings()
    {
        $settings = \App\Models\SiteSetting::where('group', 'pre_conference')->pluck('value', 'key')->all();
        return view('admin.pre_conference.index', compact('settings'));
    }

    public function updatePreConferenceSettings(Request $request)
    {
        $fields = [
            'pre_conf_hero_title',
            'pre_conf_hero_sub1',
            'pre_conf_hero_sub2',
            'pre_conf_hero_sub3',
            'pre_conf_preamble_title',
            'pre_conf_preamble_icon',
            'pre_conf_preamble',
            'pre_conf_obj_title',
            'pre_conf_obj_icon',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                \App\Models\SiteSetting::updateOrCreate(
                    ['key' => $field, 'group' => 'pre_conference'],
                    ['value' => $request->input($field)]
                );
            }
        }

        // Save Objectives List
        if ($request->has('pre_conf_obj')) {
            \App\Models\SiteSetting::where('group', 'pre_conference')->where('key', 'like', 'pre_conf_obj_%')->delete();
            $objs = $request->input('pre_conf_obj', []);
            $count = 0;
            foreach ($objs as $obj) {
                if (!empty(trim($obj))) {
                    $count++;
                    \App\Models\SiteSetting::create([
                        'key' => "pre_conf_obj_{$count}",
                        'value' => trim($obj),
                        'group' => 'pre_conference'
                    ]);
                }
            }
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => 'pre_conf_obj_count', 'group' => 'pre_conference'],
                ['value' => $count]
            );
        }

        return back()->with('success', 'Pre-Conference Workshop settings updated successfully.');
    }

    public function mccMemorialSettings()
    {
        $settings = \App\Models\SiteSetting::where('group', 'mcc_memorial')->get();
        $images = [];
        $files = array_merge(
            glob(public_path('images/mcc_memorial_*.*')) ?: [],
            glob(storage_path('app/public/images/mcc_memorial_*.*')) ?: []
        );
        $seen = [];
        foreach ($files as $file) {
            $base = basename($file);
            if (!isset($seen[$base])) {
                $seen[$base] = true;
                $images[] = [
                    'name' => $base,
                    'url' => asset('images/' . $base),
                    'path' => $file
                ];
            }
        }
        usort($images, function($a, $b) {
            preg_match('/mcc_memorial_(\d+)/', $a['name'], $matchA);
            preg_match('/mcc_memorial_(\d+)/', $b['name'], $matchB);
            $numA = (int)($matchA[1] ?? 0);
            $numB = (int)($matchB[1] ?? 0);
            return $numA <=> $numB;
        });

        return view('admin.mcc_memorial.index', compact('settings', 'images'));
    }

    public function updateMccMemorialSettings(Request $request)
    {
        $settings = $request->except(['_token']);
        foreach ($settings as $key => $value) {
            if ($request->hasFile($key)) {
                $value = $this->safeUploadImage($request->file($key), 'images');
            }
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value, 'group' => 'mcc_memorial']
            );
        }
        return back()->with('success', 'MCC Memorial Settings updated successfully.');
    }

    public function uploadMccMemorialImages(Request $request)
    {
        $request->validate([
            'gallery_images' => 'required|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:10240'
        ]);

        $files = array_merge(
            glob(public_path('images/mcc_memorial_*.*')) ?: [],
            glob(storage_path('app/public/images/mcc_memorial_*.*')) ?: []
        );
        $maxNum = 0;
        foreach ($files as $f) {
            if (preg_match('/mcc_memorial_(\d+)/', basename($f), $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) $maxNum = $num;
            }
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $maxNum++;
                $ext = $file->getClientOriginalExtension();
                $filename = 'mcc_memorial_' . $maxNum . '.' . $ext;
                $this->safeUploadImage($file, 'images', $filename);
            }
        }

        return back()->with('success', 'MCC Memorial gallery image(s) uploaded successfully.');
    }

    public function deleteMccMemorialImage(Request $request)
    {
        $filename = basename($request->input('filename'));
        if (!empty($filename) && str_startswith($filename, 'mcc_memorial_')) {
            $filePath = public_path('images/' . $filename);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $storagePath = storage_path('app/public/images/' . $filename);
            if (file_exists($storagePath)) {
                @unlink($storagePath);
            }
        }

        // Also check if setting key matches
        if ($request->has('setting_key')) {
            \App\Models\SiteSetting::where('key', $request->input('setting_key'))->delete();
        }

        return back()->with('success', 'Image deleted successfully.');
    }

    public function themeSettings()
    {
        $settings = \App\Models\SiteSetting::where('group', 'theme')->get();
        return view('admin.theme_settings.index', compact('settings'));
    }

    public function updateThemeSettings(Request $request)
    {
        $settings = $request->except(['_token']);
        foreach ($settings as $key => $value) {
            if ($request->hasFile($key)) {
                $value = $this->safeUploadImage($request->file($key), 'images/settings');
            }
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value, 'group' => 'theme']
            );
        }
        return back()->with('success', 'Theme Settings updated successfully.');
    }

    public function contactSettings()
    {
        $settings = \App\Models\SiteSetting::where('group', 'contact')->pluck('value', 'key')->toArray();
        return view('admin.contact.index', compact('settings'));
    }

    public function updateContactSettings(Request $request)
    {
        $keys = ['contact_hero_title', 'contact_page_title', 'contact_website', 'contact_email'];
        foreach ($keys as $key) {
            if ($request->has($key)) {
                \App\Models\SiteSetting::updateOrCreate(
                    ['key' => $key, 'group' => 'contact'],
                    ['value' => $request->input($key)]
                );
            }
        }

        if ($request->has('person_titles')) {
            \App\Models\SiteSetting::where('group', 'contact')->where('key', 'like', 'contact_person_%')->delete();
            $titles = $request->input('person_titles', []);
            $phones = $request->input('person_phones', []);
            $count = 0;
            foreach ($titles as $idx => $title) {
                if (!empty(trim($title))) {
                    $count++;
                    \App\Models\SiteSetting::create([
                        'key' => "contact_person_{$count}_title",
                        'value' => trim($title),
                        'group' => 'contact'
                    ]);
                    \App\Models\SiteSetting::create([
                        'key' => "contact_person_{$count}_phone",
                        'value' => trim($phones[$idx] ?? ''),
                        'group' => 'contact'
                    ]);
                }
            }
        }

        return back()->with('success', 'Contact settings updated successfully.');
    }

    public function navigationSettings()
    {
        $settings = \App\Models\SiteSetting::where('group', 'navigation')->pluck('value', 'key')->toArray();
        return view('admin.navigation.index', compact('settings'));
    }

    public function updateNavigationSettings(Request $request)
    {
        $settings = $request->except(['_token']);
        foreach ($settings as $key => $value) {
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value, 'group' => 'navigation']
            );
        }
        return back()->with('success', 'Navigation menu settings updated successfully.');
    }

    public function topbarSettings()
    {
        $settings = \App\Models\SiteSetting::whereIn('group', ['topbar', 'contact'])->pluck('value', 'key')->toArray();
        
        $customTickerItems = [];
        foreach ($settings as $k => $v) {
            if (preg_match('/^topbar_ticker_(\d+)$/', $k, $m)) {
                $customTickerItems[(int)$m[1]] = $v;
            }
        }
        ksort($customTickerItems);
        $customTickerItems = array_values($customTickerItems);

        return view('admin.topbar.index', compact('settings', 'customTickerItems'));
    }

    public function updateTopbarSettings(Request $request)
    {
        // Save phone numbers
        if ($request->has('contact_phone')) {
            \App\Models\SiteSetting::updateOrCreate(['key' => 'contact_phone'], ['value' => $request->contact_phone, 'group' => 'contact']);
        }
        if ($request->has('contact_phone_2')) {
            \App\Models\SiteSetting::updateOrCreate(['key' => 'contact_phone_2'], ['value' => $request->contact_phone_2, 'group' => 'contact']);
        }
        if ($request->has('contact_phone_3')) {
            \App\Models\SiteSetting::updateOrCreate(['key' => 'contact_phone_3'], ['value' => $request->contact_phone_3, 'group' => 'contact']);
        }

        // Additional phone array if submitted
        \App\Models\SiteSetting::where('group', 'contact')->where('key', 'like', 'contact_phone_%')->whereNotIn('key', ['contact_phone_2', 'contact_phone_3'])->delete();
        $phones = $request->input('additional_phones', []);
        $count = 3;
        if (is_array($phones)) {
            foreach ($phones as $ph) {
                if (!empty(trim($ph))) {
                    $count++;
                    \App\Models\SiteSetting::updateOrCreate(['key' => "contact_phone_{$count}"], ['value' => trim($ph), 'group' => 'contact']);
                }
            }
        }

        // Topbar general settings
        $topbarKeys = ['topbar_enabled', 'topbar_bg_color', 'topbar_text_color', 'topbar_speed', 'topbar_format'];
        foreach ($topbarKeys as $tKey) {
            if ($request->has($tKey)) {
                \App\Models\SiteSetting::updateOrCreate(['key' => $tKey], ['value' => $request->input($tKey), 'group' => 'topbar']);
            }
        }

        // Custom Ticker Items
        \App\Models\SiteSetting::where('group', 'topbar')->where('key', 'like', 'topbar_ticker_%')->delete();
        $items = $request->input('custom_ticker_items', []);
        $tCount = 0;
        if (is_array($items)) {
            foreach ($items as $item) {
                if (!empty(trim($item))) {
                    $tCount++;
                    \App\Models\SiteSetting::updateOrCreate(['key' => "topbar_ticker_{$tCount}"], ['value' => trim($item), 'group' => 'topbar']);
                }
            }
        }
        \App\Models\SiteSetting::updateOrCreate(['key' => 'topbar_ticker_count'], ['value' => $tCount, 'group' => 'topbar']);

        return back()->with('success', 'Header Topbar and Announcement Ticker settings updated successfully.');
    }

    public function posterPresentationSettings()
    {
        $settings = \App\Models\SiteSetting::where('group', 'poster_presentation')->pluck('value', 'key')->toArray();
        $guidelines = [];
        if (!empty($settings['poster_guidelines_json'])) {
            $guidelines = json_decode($settings['poster_guidelines_json'], true) ?: [];
        }
        return view('admin.poster_presentation.index', compact('settings', 'guidelines'));
    }

    public function updatePosterPresentationSettings(Request $request)
    {
        $settings = $request->except(['_token', 'poster_guideline_items']);
        foreach ($settings as $key => $value) {
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value, 'group' => 'poster_presentation']
            );
        }

        if ($request->has('poster_guideline_items')) {
            $items = array_values(array_filter(array_map('trim', $request->input('poster_guideline_items', [])), 'strlen'));
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => 'poster_guidelines_json'],
                ['value' => json_encode($items), 'group' => 'poster_presentation']
            );
        }

        return back()->with('success', 'Poster Presentation content updated successfully.');
    }

    public function oralPresentationSettings()
    {
        $settings = \App\Models\SiteSetting::where('group', 'oral_presentation')->pluck('value', 'key')->toArray();
        $guidelines = [];
        if (!empty($settings['oral_guidelines_json'])) {
            $guidelines = json_decode($settings['oral_guidelines_json'], true) ?: [];
        }
        return view('admin.oral_presentation.index', compact('settings', 'guidelines'));
    }

    public function updateOralPresentationSettings(Request $request)
    {
        $settings = $request->except(['_token', 'oral_guideline_items']);
        foreach ($settings as $key => $value) {
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value, 'group' => 'oral_presentation']
            );
        }

        if ($request->has('oral_guideline_items')) {
            $items = array_values(array_filter(array_map('trim', $request->input('oral_guideline_items', [])), 'strlen'));
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => 'oral_guidelines_json'],
                ['value' => json_encode($items), 'group' => 'oral_presentation']
            );
        }

        return back()->with('success', 'Oral Presentation content updated successfully.');
    }

    public function innovationPitchSettings()
    {
        $settings = \App\Models\SiteSetting::where('group', 'innovation_pitch')->pluck('value', 'key')->toArray();
        $guidelines = [];
        if (!empty($settings['pitch_guidelines_json'])) {
            $guidelines = json_decode($settings['pitch_guidelines_json'], true) ?: [];
        }
        return view('admin.innovation_pitch.index', compact('settings', 'guidelines'));
    }

    public function updateInnovationPitchSettings(Request $request)
    {
        $settings = $request->except(['_token', 'pitch_guideline_items']);
        foreach ($settings as $key => $value) {
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value, 'group' => 'innovation_pitch']
            );
        }

        if ($request->has('pitch_guideline_items')) {
            $items = array_values(array_filter(array_map('trim', $request->input('pitch_guideline_items', [])), 'strlen'));
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => 'pitch_guidelines_json'],
                ['value' => json_encode($items), 'group' => 'innovation_pitch']
            );
        }

        return back()->with('success', 'Innovation Pitch content updated successfully.');
    }

    public function hackathonSettings()
    {
        $settings = \App\Models\SiteSetting::where('group', 'hackathon')->pluck('value', 'key')->toArray();
        $guidelines = [];
        if (!empty($settings['hackathon_guidelines_json'])) {
            $guidelines = json_decode($settings['hackathon_guidelines_json'], true) ?: [];
        }
        return view('admin.hackathon.index', compact('settings', 'guidelines'));
    }

    public function updateHackathonSettings(Request $request)
    {
        $settings = $request->except(['_token', 'hackathon_guideline_items', 'hackathon_flyer_image']);
        foreach ($settings as $key => $value) {
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value, 'group' => 'hackathon']
            );
        }

        if ($request->hasFile('hackathon_flyer_image')) {
            $flyerPath = $this->safeUploadImage($request->file('hackathon_flyer_image'), 'images/event');
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => 'hackathon_flyer_image'],
                ['value' => $flyerPath, 'group' => 'hackathon']
            );
        }

        if ($request->has('hackathon_guideline_items')) {
            $items = array_values(array_filter(array_map('trim', $request->input('hackathon_guideline_items', [])), 'strlen'));
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => 'hackathon_guidelines_json'],
                ['value' => json_encode($items), 'group' => 'hackathon']
            );
        }

        return back()->with('success', 'Hackathon content updated successfully.');
    }

    // CMS: Partner Logos
    public function partnerLogosSettings()
    {
        $logos = \App\Models\PartnerLogo::orderBy('sort_order')->get();
        return view('admin.partner_logos.index', compact('logos'));
    }

    public function storePartnerLogo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'link_url' => 'nullable|string',
        ]);

        $logoPath = '';
        if ($request->hasFile('logo')) {
            $logoPath = $this->safeUploadImage($request->file('logo'), 'images');
        }

        $maxSort = \App\Models\PartnerLogo::max('sort_order') ?? 0;

        \App\Models\PartnerLogo::create([
            'name' => $request->name,
            'logo_path' => $logoPath,
            'link_url' => $request->link_url ?? '/',
            'sort_order' => $maxSort + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'Partner logo added successfully.');
    }

    public function updatePartnerLogo(Request $request, $id)
    {
        $logo = \App\Models\PartnerLogo::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'link_url' => 'nullable|string',
        ]);

        $data = [
            'name' => $request->name,
            'link_url' => $request->link_url ?? '/',
            'is_active' => $request->has('is_active') ? true : false,
        ];

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->safeUploadImage($request->file('logo'), 'images');
        }

        $logo->update($data);

        return back()->with('success', 'Partner logo updated successfully.');
    }

    public function destroyPartnerLogo($id)
    {
        $logo = \App\Models\PartnerLogo::findOrFail($id);
        $logo->delete();
        return back()->with('success', 'Partner logo deleted successfully.');
    }

    public function reorderPartnerLogos(Request $request)
    {
        $order = $request->input('order', []);
        foreach ($order as $index => $id) {
            \App\Models\PartnerLogo::where('id', $id)->update(['sort_order' => $index + 1]);
        }
        return response()->json(['status' => 'success']);
    }

    private function safeUploadImage($file, $subfolder = 'images', $customFilename = null)
    {
        $filename = $customFilename ?: (time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName()));
        $cleanSub = trim($subfolder, '/');
        $primaryDir = public_path($cleanSub);
        $storageDir = storage_path('app/public/' . $cleanSub);

        // Ensure directories exist with 0755
        if (!file_exists($primaryDir)) {
            @mkdir($primaryDir, 0755, true);
        }
        @chmod($primaryDir, 0755);

        if (!file_exists($storageDir)) {
            @mkdir($storageDir, 0755, true);
        }
        @chmod($storageDir, 0755);

        // Test if primaryDir is actually writable
        $primaryWritable = false;
        if (file_exists($primaryDir) && is_dir($primaryDir)) {
            $testFile = $primaryDir . '/.perm_test_' . uniqid();
            if (@file_put_contents($testFile, '1') !== false) {
                @unlink($testFile);
                $primaryWritable = true;
            }
        }

        if ($primaryWritable) {
            try {
                $file->move($primaryDir, $filename);
                @chmod($primaryDir . '/' . $filename, 0644);

                // Also sync to ../public_html if running on cPanel structure
                $publicHtmlDir = base_path('../public_html/' . $cleanSub);
                if (file_exists(base_path('../public_html')) && is_dir(base_path('../public_html'))) {
                    if (!file_exists($publicHtmlDir)) {
                        @mkdir($publicHtmlDir, 0755, true);
                    }
                    @copy($primaryDir . '/' . $filename, $publicHtmlDir . '/' . $filename);
                    @chmod($publicHtmlDir . '/' . $filename, 0644);
                }

                return $cleanSub . '/' . $filename;
            } catch (\Throwable $e) {
                // If moving to primary still fails, proceed to storage fallback
            }
        }

        // Fallback: move to storage/app/public/...
        try {
            $file->move($storageDir, $filename);
            @chmod($storageDir . '/' . $filename, 0644);
            // Also attempt to copy to primaryDir if permissions permit
            @copy($storageDir . '/' . $filename, $primaryDir . '/' . $filename);
            @chmod($primaryDir . '/' . $filename, 0644);

            // Also sync to ../public_html if present
            $publicHtmlDir = base_path('../public_html/' . $cleanSub);
            if (file_exists(base_path('../public_html')) && is_dir(base_path('../public_html'))) {
                if (!file_exists($publicHtmlDir)) {
                    @mkdir($publicHtmlDir, 0755, true);
                }
                @copy($storageDir . '/' . $filename, $publicHtmlDir . '/' . $filename);
                @chmod($publicHtmlDir . '/' . $filename, 0644);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('safeUploadImage failed: ' . $e->getMessage());
        }

        return $cleanSub . '/' . $filename;
    }

    public function stallBookingSettings()
    {
        $settings = \App\Models\SiteSetting::where('group', 'stall_booking')->pluck('value', 'key')->toArray();
        $brochures = isset($settings['stall_brochures_json']) ? json_decode($settings['stall_brochures_json'], true) : [];
        $sponsors = isset($settings['stall_sponsors_json']) ? json_decode($settings['stall_sponsors_json'], true) : [];

        $homeBrochures = isset($settings['stall_home_brochures_json']) ? json_decode($settings['stall_home_brochures_json'], true) : [];
        if (empty($homeBrochures)) {
            $homeBrochures = [
                [
                    'title' => 'Partnership Avenues for Analytical Service Providers',
                    'image' => file_exists(public_path('images/stall_booking/stall_brochure_1.png')) ? 'images/stall_booking/stall_brochure_1.png' : 'images/stall_booking/stall_brochure_1.jpg',
                    'url' => '/page/stall-booking-details',
                ],
                [
                    'title' => 'Partnership Avenues for Hospitals & Health Sectors',
                    'image' => 'images/stall_booking/stall_brochure_3.png',
                    'url' => '/page/stall-booking-details',
                ],
                [
                    'title' => 'Tariff Details & Sponsorship Opportunities',
                    'image' => 'images/stall_booking/stall_brochure_4.png',
                    'url' => '/page/stall-booking-details',
                ],
            ];
        }

        return view('admin.stall_booking.index', compact('settings', 'brochures', 'sponsors', 'homeBrochures'));
    }

    public function updateStallBookingSettings(Request $request)
    {
        $keys = [
            'stall_banner_title',
            'stall_banner_subtitle',
            'stall_sponsors_badge',
            'stall_sponsors_title',
            'stall_cta_text',
            'stall_cta_btn_label',
            'stall_cta_btn_url',
            'stall_home_show',
            'stall_home_title',
            'stall_home_btn_label',
            'stall_home_btn_url',
            'stall_home_btn_show',
        ];
        foreach ($keys as $key) {
            if ($request->has($key)) {
                \App\Models\SiteSetting::updateOrCreate(
                    ['key' => $key, 'group' => 'stall_booking'],
                    ['value' => $request->input($key)]
                );
            }
        }

        // Process Home Screen Showcase Brochures
        $hbTitles = $request->input('home_brochure_titles', []);
        $hbUrls = $request->input('home_brochure_urls', []);
        $hbExistingImages = $request->input('home_brochure_existing_images', []);
        $hbUploadedFiles = $request->file('home_brochure_files', []);

        $homeBrochures = [];
        foreach ($hbTitles as $idx => $title) {
            if (empty(trim($title))) continue;

            $imgPath = $hbExistingImages[$idx] ?? 'images/stall_booking/stall_brochure_1.png';
            if (isset($hbUploadedFiles[$idx]) && $hbUploadedFiles[$idx]->isValid()) {
                $imgPath = $this->safeUploadImage($hbUploadedFiles[$idx], 'images/stall_booking');
            }

            $homeBrochures[] = [
                'title' => trim($title),
                'url' => trim($hbUrls[$idx] ?? '/page/stall-booking-details'),
                'image' => $imgPath
            ];
        }

        // Check if adding a new home brochure
        if ($request->filled('new_home_brochure_title') && $request->hasFile('new_home_brochure_file')) {
            $file = $request->file('new_home_brochure_file');
            if ($file->isValid()) {
                $imgPath = $this->safeUploadImage($file, 'images/stall_booking');
                $homeBrochures[] = [
                    'title' => trim($request->input('new_home_brochure_title')),
                    'url' => trim($request->input('new_home_brochure_url', '/page/stall-booking-details')),
                    'image' => $imgPath
                ];
            }
        }

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'stall_home_brochures_json', 'group' => 'stall_booking'],
            ['value' => json_encode(array_values($homeBrochures))]
        );

        // Process Brochures
        $bTitles = $request->input('brochure_titles', []);
        $bExistingImages = $request->input('brochure_existing_images', []);
        $bUploadedFiles = $request->file('brochure_files', []);

        $brochures = [];
        foreach ($bTitles as $idx => $title) {
            if (empty(trim($title))) continue;

            $imgPath = $bExistingImages[$idx] ?? 'images/stall_booking/stall_brochure_1.png';
            if (isset($bUploadedFiles[$idx]) && $bUploadedFiles[$idx]->isValid()) {
                $imgPath = $this->safeUploadImage($bUploadedFiles[$idx], 'images/stall_booking');
            }

            $brochures[] = [
                'title' => trim($title),
                'image' => $imgPath
            ];
        }

        // Check if adding a new brochure
        if ($request->filled('new_brochure_title') && $request->hasFile('new_brochure_file')) {
            $file = $request->file('new_brochure_file');
            if ($file->isValid()) {
                $imgPath = $this->safeUploadImage($file, 'images/stall_booking');
                $brochures[] = [
                    'title' => trim($request->input('new_brochure_title')),
                    'image' => $imgPath
                ];
            }
        }

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'stall_brochures_json', 'group' => 'stall_booking'],
            ['value' => json_encode(array_values($brochures))]
        );

        // Process Sponsors
        $sNames = $request->input('sponsor_names', []);
        $sLinks = $request->input('sponsor_links', []);
        $sExistingLogos = $request->input('sponsor_existing_logos', []);
        $sUploadedFiles = $request->file('sponsor_files', []);

        $sponsors = [];
        foreach ($sNames as $idx => $name) {
            if (empty(trim($name))) continue;

            $logoPath = $sExistingLogos[$idx] ?? 'images/sponsors/anderson_logo.svg';
            if (isset($sUploadedFiles[$idx]) && $sUploadedFiles[$idx]->isValid()) {
                $logoPath = $this->safeUploadImage($sUploadedFiles[$idx], 'images/sponsors');
            }

            $sponsors[] = [
                'name' => trim($name),
                'link' => trim($sLinks[$idx] ?? '#'),
                'logo' => $logoPath
            ];
        }

        // Check if adding a new sponsor
        if ($request->filled('new_sponsor_name') && $request->hasFile('new_sponsor_file')) {
            $file = $request->file('new_sponsor_file');
            if ($file->isValid()) {
                $logoPath = $this->safeUploadImage($file, 'images/sponsors');
                $sponsors[] = [
                    'name' => trim($request->input('new_sponsor_name')),
                    'link' => trim($request->input('new_sponsor_link', '#')),
                    'logo' => $logoPath
                ];
            }
        }

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'stall_sponsors_json', 'group' => 'stall_booking'],
            ['value' => json_encode(array_values($sponsors))]
        );

        return back()->with('success', 'Stall Booking and Merchandise settings updated successfully.');
    }

    public function conferenceBrochuresSettings()
    {
        return redirect()->route('admin.stall_booking_details');
    }

    public function updateConferenceBrochuresSettings(Request $request)
    {
        return $this->updateStallBookingDetailsSettings($request);
    }

    public function stallBookingDetailsSettings()
    {
        $detailsSettings = \App\Models\SiteSetting::where('group', 'stall_booking_details')->pluck('value', 'key')->toArray();
        $stallSettings = \App\Models\SiteSetting::where('group', 'stall_booking')->pluck('value', 'key')->toArray();
        $settings = array_merge($stallSettings, $detailsSettings);

        $homeBrochures = isset($stallSettings['stall_home_brochures_json']) ? json_decode($stallSettings['stall_home_brochures_json'], true) : [];
        if (empty($homeBrochures)) {
            $homeBrochures = [
                [
                    'title' => 'Partnership Avenues for Analytical Service Providers',
                    'image' => file_exists(public_path('images/stall_booking/stall_brochure_1.png')) ? 'images/stall_booking/stall_brochure_1.png' : 'images/stall_booking/stall_brochure_1.jpg',
                    'url' => '/page/stall-booking-details',
                ],
                [
                    'title' => 'Partnership Avenues for Hospitals & Health Sectors',
                    'image' => 'images/stall_booking/stall_brochure_3.png',
                    'url' => '/page/stall-booking-details',
                ],
                [
                    'title' => 'Tariff Details & Sponsorship Opportunities',
                    'image' => 'images/stall_booking/stall_brochure_4.png',
                    'url' => '/page/stall-booking-details',
                ],
            ];
        }
        return view('admin.stall_booking.details', compact('settings', 'homeBrochures'));
    }

    public function updateStallBookingDetailsSettings(Request $request)
    {
        // 1. Details Page Banner & Content settings
        $detailsKeys = [
            'stall_details_badge',
            'stall_details_title',
            'stall_details_subtitle',
            'stall_details_heading',
            'stall_details_content',
        ];
        foreach ($detailsKeys as $key) {
            if ($request->has($key)) {
                \App\Models\SiteSetting::updateOrCreate(
                    ['key' => $key, 'group' => 'stall_booking_details'],
                    ['value' => $request->input($key)]
                );
            }
        }

        // 2. Home Section Controls
        $stallKeys = [
            'stall_home_show',
            'stall_home_title',
            'stall_home_btn_label',
            'stall_home_btn_url',
            'stall_home_btn_show',
        ];
        foreach ($stallKeys as $key) {
            if ($request->has($key)) {
                \App\Models\SiteSetting::updateOrCreate(
                    ['key' => $key, 'group' => 'stall_booking'],
                    ['value' => $request->input($key)]
                );
            }
        }

        // 3. Brochure Images Management
        $hbTitles = $request->input('home_brochure_titles', []);
        $hbUrls = $request->input('home_brochure_urls', []);
        $hbExistingImages = $request->input('home_brochure_existing_images', []);
        $hbUploadedFiles = $request->file('home_brochure_files', []);

        $homeBrochures = [];
        if (!empty($hbTitles)) {
            foreach ($hbTitles as $idx => $title) {
                if (empty(trim($title))) continue;

                $imgPath = $hbExistingImages[$idx] ?? 'images/stall_booking/stall_brochure_1.png';
                if (isset($hbUploadedFiles[$idx]) && $hbUploadedFiles[$idx]->isValid()) {
                    $imgPath = $this->safeUploadImage($hbUploadedFiles[$idx], 'images/stall_booking');
                }

                $homeBrochures[] = [
                    'title' => trim($title),
                    'url' => trim($hbUrls[$idx] ?? '/page/stall-booking-details'),
                    'image' => $imgPath
                ];
            }
        }

        if ($request->filled('new_home_brochure_title') && $request->hasFile('new_home_brochure_file')) {
            $file = $request->file('new_home_brochure_file');
            if ($file->isValid()) {
                $imgPath = $this->safeUploadImage($file, 'images/stall_booking');
                $homeBrochures[] = [
                    'title' => trim($request->input('new_home_brochure_title')),
                    'url' => trim($request->input('new_home_brochure_url', '/page/stall-booking-details')),
                    'image' => $imgPath
                ];
            }
        }

        if (!empty($homeBrochures)) {
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => 'stall_home_brochures_json', 'group' => 'stall_booking'],
                ['value' => json_encode(array_values($homeBrochures))]
            );
        }

        return back()->with('success', 'Stall Booking Details and Conference Brochures updated successfully.');
    }

    public function visitSettings()
    {
        $settings = \App\Models\SiteSetting::where('group', 'visit')->pluck('value', 'key')->toArray();
        
        // Load places list or default 14 items
        $placesJson = $settings['visit_places_json'] ?? null;
        if ($placesJson) {
            $places = json_decode($placesJson, true) ?: [];
        } else {
            $places = [
                ['title' => 'Marina Beach', 'image' => 'images/marina_beach.jpg', 'desc' => 'One of India’s longest urban beaches and an iconic landmark of Chennai.'],
                ['title' => 'Kapaleeshwarar Temple, Mylapore', 'image' => 'images/kapaleeshwarar_temple.jpg', 'desc' => 'A historic temple showcasing traditional Dravidian architecture.'],
                ['title' => 'Santhome Basilica', 'image' => 'images/santhome_basilica.jpg', 'desc' => 'A significant Christian heritage site built over the traditional tomb of St. Thomas the Apostle.'],
                ['title' => 'Fort St. George', 'image' => 'images/fort_st_george.jpg', 'desc' => 'A historic colonial landmark and an important part of Chennai’s history.'],
                ['title' => 'Government Museum, Egmore', 'image' => 'images/government_museum.jpg', 'desc' => 'Home to an extensive collection of archaeology, art and bronze sculptures.'],
                ['title' => 'Elliot’s Beach, Besant Nagar', 'image' => 'images/elliots_beach.jpg', 'desc' => 'A popular destination for a relaxing evening by the sea.'],
                ['title' => 'Guindy National Park', 'image' => 'images/guindy_national_park.jpg', 'desc' => 'A unique urban national park known for its native flora and fauna.'],
                ['title' => 'Chennai Rail Museum', 'image' => 'images/chennai_rail_museum.jpg', 'desc' => 'Showcasing India’s railway heritage through vintage locomotives and exhibits.'],
                ['title' => 'DakshinaChitra', 'image' => 'images/dakshinachitra.jpg', 'desc' => 'A cultural museum showcasing the traditional architecture, crafts and lifestyles of South India.'],
                ['title' => 'Birla Planetarium', 'image' => 'images/birla_planetarium.jpg', 'desc' => 'A popular destination for astronomy and science enthusiasts.'],
                ['title' => 'Arignar Anna Zoological Park (Vandalur)', 'image' => 'images/arignar_anna_zoological_park.jpg', 'desc' => 'One of India’s largest zoological parks, home to diverse wildlife and natural habitats.'],
                ['title' => 'Cholamandal Artists’ Village', 'image' => 'images/cholamandal_artists_village.jpg', 'desc' => 'A renowned artists’ community showcasing contemporary Indian art, sculptures and creative works.'],
                ['title' => 'Theosophical Society, Adyar', 'image' => 'images/theosophical_society.jpg', 'desc' => 'A peaceful heritage space known for its lush greenery, gardens and serene surroundings.'],
                ['title' => 'Semmozhi Poonga', 'image' => 'images/semmozhi_poonga.jpg', 'desc' => 'A vibrant botanical garden in the heart of Chennai with a wide collection of exotic plants.']
            ];
        }

        return view('admin.visit.index', compact('settings', 'places'));
    }

    public function updateVisitSettings(Request $request)
    {
        $keys = ['visit_hero_title', 'visit_section_title', 'visit_section_subtitle'];
        foreach ($keys as $key) {
            if ($request->has($key)) {
                \App\Models\SiteSetting::updateOrCreate(
                    ['key' => $key, 'group' => 'visit'],
                    ['value' => $request->input($key)]
                );
            }
        }

        // Process places list
        $titles = $request->input('place_titles', []);
        $descs = $request->input('place_descs', []);
        $existingImages = $request->input('place_existing_images', []);
        $uploadedImages = $request->file('place_new_images', []);

        $places = [];
        foreach ($titles as $idx => $title) {
            if (empty(trim($title))) continue;

            $imgPath = $existingImages[$idx] ?? 'images/marina_beach.jpg';
            if (isset($uploadedImages[$idx]) && $uploadedImages[$idx]->isValid()) {
                $imgPath = $this->safeUploadImage($uploadedImages[$idx], 'images');
            }

            $places[] = [
                'title' => trim($title),
                'desc' => trim($descs[$idx] ?? ''),
                'image' => $imgPath
            ];
        }

        // Check if adding new place item
        if ($request->filled('new_place_title')) {
            $newImgPath = 'images/marina_beach.jpg';
            if ($request->hasFile('new_place_image') && $request->file('new_place_image')->isValid()) {
                $newImgPath = $this->safeUploadImage($request->file('new_place_image'), 'images');
            }
            $places[] = [
                'title' => trim($request->input('new_place_title')),
                'desc' => trim($request->input('new_place_desc', '')),
                'image' => $newImgPath
            ];
        }

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'visit_places_json', 'group' => 'visit'],
            ['value' => json_encode(array_values($places))]
        );

        return back()->with('success', 'Visit and Places of Interest settings updated successfully.');
    }
}




