<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\AbstractSubmitted;
use App\Mail\SubmissionConfirmation;
use Illuminate\Support\Facades\Validator;
use App\Models\PaperSubmission;
use Exception;

class PaperSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $fields = \App\Models\SubmitPaperField::orderBy('sort_order')->get();
        $rules = [
            'abstract_file' => 'required|file|mimes:doc,docx,pdf|max:5120',
        ];

        foreach ($fields as $field) {
            $rule = [];
            if ($field->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }
            if ($field->type === 'email') {
                $rule[] = 'email';
            }
            $rules['dynamic_' . $field->name] = implode('|', $rule);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Store the file temporarily
            $file = $request->file('abstract_file');
            $originalName = $file->getClientOriginalName();
            $destDir = storage_path('app/public/abstracts');
            if (!file_exists($destDir)) {
                @mkdir($destDir, 0777, true);
            }
            @chmod($destDir, 0777);
            $path = $file->storeAs('abstracts', time() . '_' . $originalName, 'public');
            $absolutePath = storage_path('app/public/' . $path);

            // Extract dynamic form data
            $formData = [];
            $submitterEmail = null;
            $emailData = [];

            foreach ($fields as $field) {
                $val = $request->input('dynamic_' . $field->name);
                $formData[$field->name] = $val;
                $emailData[$field->label] = $val; // For the email template

                if ($field->name === 'email' || strtolower($field->label) === 'email') {
                    $submitterEmail = $val;
                }
            }

            // Send email to Admin
            Mail::to('salamzubi8@gmail.com')->send(new AbstractSubmitted(
                $emailData,
                $absolutePath,
                $originalName
            ));

            // Save to database
            PaperSubmission::create([
                'form_data' => $formData,
                'abstract_file_path' => $path,
            ]);

            // Send thank-you confirmation email to the submitter
            if ($submitterEmail) {
                Mail::to($submitterEmail)->send(new SubmissionConfirmation(
                    $emailData
                ));
            }

            return response()->json([
                'success' => true,
                'message' => 'Abstract submitted successfully!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email. Please check server configuration.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
