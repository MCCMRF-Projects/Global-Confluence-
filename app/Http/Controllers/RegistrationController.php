<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationNotification;
use App\Mail\RegistrationConfirmation;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validation
        $rules = [
            'reg_category' => 'required',
            'consent' => 'accepted',
            'fields' => 'required|array',
        ];

        $dynamicFields = \App\Models\RegistrationField::all();
        foreach ($dynamicFields as $field) {
            if ($field->is_required && $field->name !== 'interested_in') {
                $rules['fields.' . $field->name] = 'required';
            }
        }

        $request->validate($rules);

        // 2. Calculation
        $totalAmount = (float) str_replace(',', '', $request->reg_category);
        
        $addons = $request->input('addons', []);
        foreach ($addons as $addonPrice) {
            $totalAmount += (float) str_replace(',', '', $addonPrice);
        }

        // 3. Storing
        $registration = new \App\Models\Registration();
        
        // Save the dynamic fields as JSON
        $fieldsData = $request->input('fields', []);

        // Ensure upload directories exist and are writable
        foreach (['abstracts', 'id_cards', 'receipts'] as $folder) {
            $dir = storage_path('app/public/' . $folder);
            if (!file_exists($dir)) {
                @mkdir($dir, 0777, true);
            }
            @chmod($dir, 0777);
        }

        // Handle abstract file upload with original filename preservation
        if ($request->hasFile('abstract_file')) {
            $file = $request->file('abstract_file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
            $filename = time() . '_' . $cleanName . '.' . $extension;
            
            $path = $file->storeAs('abstracts', $filename, 'public');
            $fieldsData['abstract_file'] = $path;
            $fieldsData['abstract_original_name'] = $file->getClientOriginalName();
        }

        // Handle ID card file upload
        if ($request->hasFile('id_card_file')) {
            $file = $request->file('id_card_file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
            $filename = time() . '_id_' . $cleanName . '.' . $extension;
            
            $path = $file->storeAs('id_cards', $filename, 'public');
            $fieldsData['id_card_file'] = $path;
            $fieldsData['id_card_original_name'] = $file->getClientOriginalName();
        }

        // Handle Payment Receipt file upload
        if ($request->hasFile('payment_receipt_file')) {
            $file = $request->file('payment_receipt_file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
            $filename = time() . '_receipt_' . $cleanName . '.' . $extension;
            
            $path = $file->storeAs('receipts', $filename, 'public');
            $fieldsData['payment_receipt_file'] = $path;
            $fieldsData['payment_receipt_original_name'] = $file->getClientOriginalName();
        }

        $registration->form_data = $fieldsData;
        
        $registration->title = $fieldsData['title'] ?? null;
        $registration->name = $fieldsData['name'] ?? 'N/A';
        $registration->email = $fieldsData['email'] ?? 'N/A';
        $registration->phone = $fieldsData['phone'] ?? null;
        $registration->gender = $fieldsData['gender'] ?? null;
        $registration->organization = $fieldsData['organization'] ?? null;
        $registration->city = $fieldsData['city'] ?? null;
        $registration->country = $fieldsData['country'] ?? null;
        $registration->postal_code = $fieldsData['postal_code'] ?? null;
        $registration->interested_in = $fieldsData['interested_in'] ?? $fieldsData['presentation_event_type'] ?? $fieldsData['registration_type'] ?? 'Participation';
        $registration->registration_type = $fieldsData['registration_type'] ?? 'Participation';
        $registration->presentation_event_type = $fieldsData['presentation_event_type'] ?? null;
        $registration->presentation_track = $fieldsData['presentation_track'] ?? null;
        $registration->abstract_file = $fieldsData['abstract_file'] ?? null;
        $registration->id_card_file = $fieldsData['id_card_file'] ?? null;
        $registration->payment_receipt_file = $fieldsData['payment_receipt_file'] ?? null;
        $registration->reg_category = $request->input('reg_category');
        $registration->payment_method = !empty($fieldsData['transaction_id']) ? ('Txn: ' . $fieldsData['transaction_id']) : 'N/A';

        $catName = $request->input('reg_category_name');
        if (!$catName) {
            $catPriceNum = (int)$request->input('reg_category');
            $matchingFee = \App\Models\RegistrationFee::all()->first(function($f) use ($catPriceNum) {
                return (int)str_replace(',', '', $f->price_inr) === $catPriceNum;
            });
            $catName = $matchingFee ? $matchingFee->category_name : 'Registration';
        }
        $registration->category_name = $catName;
        $registration->total_amount = $totalAmount;
        $registration->addons = $addons;
        $registration->payment_status = 'completed'; // auto complete for demo purposes
        
        $registration->save();

        // 4. Send admin notification email
        try {
            Mail::to('salamzubi8@gmail.com')->send(new RegistrationNotification($registration));
        } catch (\Exception $e) {
            // Log silently, don't break user flow
        }

        // 5. Send confirmation email to registrant
        if ($registration->email && $registration->email !== 'N/A') {
            try {
                Mail::to($registration->email)->send(new RegistrationConfirmation($registration));
            } catch (\Exception $e) {
                // Log silently, don't break user flow
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Registration completed successfully!');
    }
}
