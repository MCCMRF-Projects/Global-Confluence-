
@extends('layouts.app')

@section('content')

    @include('sections.topbar')
    @include('sections.navbar')

    @php
        $spSettings = \App\Models\SiteSetting::where('group', 'submit_paper')->pluck('value', 'key')->toArray();
    @endphp

    <!-- Page Banner -->
    <div class="page-banner" style="background-image: linear-gradient(rgba(10, 25, 47, 0.7), rgba(10, 25, 47, 0.8)), url('{{ !empty($spSettings['submit_paper_banner_image']) ? asset($spSettings['submit_paper_banner_image']) : asset('images/hero-bg.png') }}');">
        <div class="page-banner-content">
            <h1>{{ $spSettings['submit_paper_title'] ?? 'SUBMIT A PAPER' }}</h1>
        </div>
    </div>

    <!-- Submit Paper Form Section -->
    <section class="submit-paper-section">
        <div class="container submit-paper-container">
            @if(session('success'))
            <div id="successPopupModal" class="success-modal-overlay">
                <div class="success-modal-card">
                    <div class="success-modal-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h2 class="success-modal-title">Submission Successful!</h2>
                    <p class="success-modal-message">{{ session('success') }}</p>
                    <p class="success-modal-submessage">Your paper abstract submission has been received successfully.</p>
                    <button type="button" onclick="closeSuccessModal()" class="success-modal-btn">
                        OK, GOT IT
                    </button>
                </div>
            </div>

            <style>
                .success-modal-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(15, 23, 42, 0.75);
                    backdrop-filter: blur(6px);
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }
                .success-modal-card {
                    background: #ffffff;
                    border-radius: 24px;
                    padding: 45px 35px 35px;
                    text-align: center;
                    max-width: 480px;
                    width: 100%;
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
                    animation: successPopIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                }
                .success-modal-icon {
                    background: linear-gradient(135deg, #00a896, #028090);
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 25px;
                    color: #ffffff;
                    font-size: 2.5rem;
                    box-shadow: 0 10px 25px rgba(0, 168, 150, 0.4);
                }
                .success-modal-title {
                    font-size: 1.65rem;
                    font-weight: 800;
                    color: #0a192f;
                    margin: 0 0 12px 0;
                }
                .success-modal-message {
                    font-size: 1.05rem;
                    color: #00a896;
                    font-weight: 700;
                    margin: 0 0 8px 0;
                }
                .success-modal-submessage {
                    font-size: 0.95rem;
                    color: #64748b;
                    margin: 0 0 30px 0;
                    line-height: 1.5;
                }
                .success-modal-btn {
                    background: linear-gradient(135deg, #00a896, #028090);
                    color: #ffffff;
                    border: none;
                    padding: 15px 40px;
                    border-radius: 30px;
                    font-size: 1rem;
                    font-weight: 700;
                    letter-spacing: 1px;
                    cursor: pointer;
                    width: 100%;
                    transition: all 0.3s ease;
                    box-shadow: 0 8px 20px rgba(0, 168, 150, 0.3);
                }
                .success-modal-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 12px 25px rgba(0, 168, 150, 0.45);
                }
                @keyframes successPopIn {
                    0% { opacity: 0; transform: scale(0.7); }
                    100% { opacity: 1; transform: scale(1); }
                }
            </style>

            <script>
                function closeSuccessModal() {
                    const modal = document.getElementById('successPopupModal');
                    if (modal) {
                        modal.style.opacity = '0';
                        modal.style.transition = 'opacity 0.3s ease';
                        setTimeout(() => modal.remove(), 300);
                    }
                }
            </script>
            @endif

            <p class="submit-desc">
                {!! nl2br(e($spSettings['submit_paper_desc'] ?? 'Submit your abstract through our official website. Accepted abstracts and presentations will be showcased in the summit program and included in the abstract book.')) !!}
            </p>
            


            <form class="submit-form" action="{{ route('api.submit_paper') }}" method="POST" enctype="multipart/form-data" id="abstract-form">
                @csrf
                <div class="reg-form-grid">
                    @php
                        $formFields = \App\Models\SubmitPaperField::orderBy('sort_order')->get();
                    @endphp
                    
                    @foreach($formFields as $field)
                        <div class="form-group" style="grid-column: {{ $field->grid_column === 'span 12' ? '1 / -1' : $field->grid_column }};">
                            <label style="display: block; font-weight: 600; color: var(--navy-dark); margin-bottom: 8px; font-size: 0.95rem;">
                                {{ $field->label }} @if($field->is_required)<span style="color: #ef4444;">*</span>@endif
                            </label>
                            
                            @if($field->type === 'select')
                                <select name="dynamic_{{ $field->name }}" class="form-control" {{ $field->is_required ? 'required' : '' }}>
                                    <option value="">{{ $field->placeholder ?? 'Select ' . $field->label }}</option>
                                    @if($field->options)
                                        @foreach($field->options as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            @elseif($field->type === 'dynamic_select')
                                <select name="dynamic_{{ $field->name }}" class="form-control" {{ $field->is_required ? 'required' : '' }}>
                                    <option value="">{{ $field->placeholder ?? 'Select ' . $field->label }}</option>
                                    @if($field->options)
                                        @foreach($field->options as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    @endif
                                    <option value="Others">Others</option>
                                </select>
                            @elseif($field->type === 'textarea')
                                <textarea name="dynamic_{{ $field->name }}" class="form-control" placeholder="{{ $field->placeholder ?? $field->label }}" {{ $field->is_required ? 'required' : '' }} rows="4"></textarea>
                            @else
                                <input type="{{ $field->type }}" name="dynamic_{{ $field->name }}" class="form-control" placeholder="{{ $field->placeholder ?? $field->label }}" {{ $field->is_required ? 'required' : '' }}>
                            @endif
                        </div>
                    @endforeach
                    
                    <div class="form-group file-upload-group" style="grid-column: 1 / -1; margin-top: 20px;">
                        <label for="abstract_file" class="file-upload-label" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 50px 20px; border: 2px dashed var(--teal-accent); border-radius: 12px; background: #f8fafc; cursor: pointer; transition: all 0.3s ease; text-align: center;">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size: 3.5rem; color: var(--teal-accent); margin-bottom: 15px;"></i>
                            <span style="font-weight: 700; font-size: 1.3rem; color: var(--navy-dark); margin-bottom: 8px;">{{ $spSettings['submit_paper_upload_title'] ?? 'Click to upload or drag and drop' }}</span>
                            <span style="font-size: 0.95rem; color: #64748b;">{{ $spSettings['submit_paper_upload_subtitle'] ?? 'Supported formats: DOC, DOCX, PDF (Max size: 5MB)' }}</span>
                            <span id="file-chosen" style="margin-top: 20px; font-weight: 700; color: var(--green-accent); font-size: 1.1rem; display: none; background: rgba(0, 168, 150, 0.1); padding: 8px 16px; border-radius: 8px;"></span>
                        </label>
                        <input type="file" name="abstract_file" id="abstract_file" accept=".doc,.docx,.pdf" style="display: none;">
                        <span id="file-error" style="color: #ef4444; font-size: 0.95rem; margin-top: 10px; font-weight: 600; display: none;"><i class="fa-solid fa-circle-exclamation"></i> Please upload your abstract document (PDF, DOC, DOCX) before submitting.</span>
                    </div>
                </div>

                <div class="form-submit-wrap" style="display: flex; justify-content: center; margin-top: 40px;">
                    <button type="submit" class="btn btn-teal" style="padding: 16px 50px; font-size: 1.2rem; border-radius: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 10px 20px rgba(0, 168, 150, 0.2);">{{ $spSettings['submit_paper_btn_text'] ?? 'SUBMIT PAPER' }} <i class="fa-solid fa-paper-plane" style="margin-left: 10px;"></i></button>
                </div>
            </form>
        </div>
    </section>

    <!-- Success Modal -->
    <div id="success-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(10, 25, 47, 0.8); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
        <div style="background: #fff; padding: 50px; border-radius: 20px; text-align: center; max-width: 500px; width: 90%; box-shadow: 0 25px 50px rgba(0,0,0,0.2); animation: slideUp 0.4s ease;">
            <div style="width: 80px; height: 80px; background: rgba(0, 168, 150, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                <i class="fa-solid fa-check" style="font-size: 2.5rem; color: var(--teal-accent);"></i>
            </div>
            <h2 style="color: var(--navy-dark); margin-bottom: 15px; font-size: 2rem;">Submission Successful!</h2>
            <p style="color: #475569; font-size: 1.1rem; line-height: 1.6; margin-bottom: 30px;">Thank you for submitting your paper. Our reviewing committee will evaluate your abstract and notify you via email shortly.</p>
            <button id="close-modal" class="btn btn-teal" style="padding: 12px 30px; border-radius: 8px; font-weight: 700; cursor: pointer;">Back to Home</button>
        </div>
    </div>

    <style>
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .file-upload-label:hover {
            background: #f0fdfa !important;
            border-color: var(--green-accent) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('abstract_file');
            const fileChosen = document.getElementById('file-chosen');
            const form = document.querySelector('.submit-form');
            const modal = document.getElementById('success-modal');
            const closeModal = document.getElementById('close-modal');

            // Handle file selection display
            fileInput.addEventListener('change', function() {
                if(this.files && this.files.length > 0) {
                    fileChosen.style.display = 'inline-block';
                    fileChosen.innerHTML = '<i class="fa-solid fa-file-lines" style="margin-right: 8px;"></i>' + this.files[0].name;
                    document.getElementById('file-error').style.display = 'none'; // Hide error if file is chosen
                } else {
                    fileChosen.style.display = 'none';
                }
            });

            // Handle drag and drop styling
            const dropZone = document.querySelector('.file-upload-label');
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.style.background = '#f0fdfa';
                dropZone.style.borderColor = 'var(--green-accent)';
            }

            function unhighlight(e) {
                dropZone.style.background = '#f8fafc';
                dropZone.style.borderColor = 'var(--teal-accent)';
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if(files && files.length > 0) {
                    fileInput.files = files; // Assign files to input
                    fileChosen.style.display = 'inline-block';
                    fileChosen.innerHTML = '<i class="fa-solid fa-file-lines" style="margin-right: 8px;"></i>' + files[0].name;
                    document.getElementById('file-error').style.display = 'none'; // Hide error if file is chosen
                }
            }

            // Handle form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default submission
                
                if (fileInput.files.length === 0) {
                    document.getElementById('file-error').style.display = 'block';
                    return;
                }

                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> SUBMITTING...';
                submitBtn.disabled = true;

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;

                    if(data.success) {
                        // Show success modal
                        modal.style.display = 'flex';
                    } else {
                        alert('Validation Error: Please check all required fields and ensure your file is a PDF/DOC/DOCX under 5MB.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                    alert('There was an error sending the email. The administrator needs to configure the SMTP settings.');
                    
                    // Show modal anyway for demonstration purposes if email fails due to no SMTP setup
                    modal.style.display = 'flex';
                });
            });

            // Handle modal close
            closeModal.addEventListener('click', function() {
                modal.style.display = 'none';
                form.reset();
                fileChosen.style.display = 'none';
                window.location.href = '/'; // Redirect to home
            });
        });
    </script>

    @include('sections.footer')

@endsection
