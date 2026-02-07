@extends('layouts.app')

@push('scriptcss')
    <style>
        /* Membuat border putus-putus yang halus */
        .drag-area {
            border: 2px dashed #dee2e6;
            /* Warna border bootstrap default */
            border-radius: 10px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            text-align: center;
            padding: 20px;
            cursor: pointer;
            min-height: 200px;
            /* Tinggi minimal agar proporsional */
        }

        /* Saat user drag file di atas area */
        .drag-area.active {
            border-color: #0d6efd;
            /* Bootstrap Primary Color */
            background-color: #e9f2ff;
        }

        /* Agar tombol di dalam drag area tidak mengganggu event drag */
        .drag-area button {
            pointer-events: none;
        }

        .file-info.d-none {
            display: none !important;
        }

        /* Style saat terjadi error (lupa upload) */
        .drag-area.is-invalid {
            border-color: #dc3545 !important;
            /* Warna merah */
            background-color: #fff8f8;
            animation: shake 0.5s;
            /* Efek getar sedikit */
        }

        /* Animasi getar agar user sadar */
        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }

            100% {
                transform: translateX(0);
            }
        }

        /* Progress Bar Styles */
        .progress-container {
            display: none;
            margin-top: 15px;
        }

        .progress-container.show {
            display: block;
        }

        .progress {
            height: 25px;
            border-radius: 8px;
            background-color: #e9ecef;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            background: linear-gradient(90deg, #0d6efd, #0b5ed7);
            height: 100%;
            border-radius: 8px;
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
            min-width: 40px;
        }

        .upload-status {
            text-align: center;
            margin-top: 10px;
            font-size: 13px;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .upload-status.uploading {
            color: #0d6efd;
        }

        .upload-status.success {
            color: #198754;
        }

        .upload-status.error {
            color: #dc3545;
        }

        .spinner-small {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(13, 110, 253, 0.25);
            border-radius: 50%;
            border-top-color: #0d6efd;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-xl">
        <h1 class="app-page-title pb-2"><i class="fas fa-hashtag me-2"></i>Upload Dokumen</h1>

        <form action="{{ route('document.store') }}" method="post" id="saveDocument" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-transparent py-3">
                            <h5 class="card-title mb-0 text-primary">Informasi Dokumen</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control text-capitalize" id="floatingTitle" name="title"
                                    placeholder="" required autofocus>
                                <label for="floatingTitle">Judul Dokumen<span class="text-danger">*</span></label>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingNumber"
                                            name="document_number" placeholder="">
                                        <label for="floatingNumber">Nomor Dokumen</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control flatpicker" id="floatingDate"
                                            name="document_date" placeholder="" value="{{ now()->format('Y-m-d') }}" required>
                                        <label for="floatingDate">Tanggal Dokumen<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="floatingSecurity" name="security_level" required>
                                            <option value="" selected>Silahkan pilih</option>
                                            <option value="public">Public</option>
                                            <option value="internal">Internal</option>
                                            {{-- <option value="rahasia">Rahasia</option> --}}
                                        </select>
                                        <label for="floatingSecurity">Sifat Dokumen<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="floatingCategory" name="category_id" required>
                                            <option value="" selected>Silahkan pilih</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="floatingCategory">Kategori Dokumen<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea name="description" class="form-control" placeholder="" id="floatingDescription" style="height: 100px"></textarea>
                                <label for="floatingDescription">Keterangan</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-transparent py-3">
                            <h5 class="card-title mb-0 text-primary">Lampiran File <span class="text-danger">*</span></h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="text-muted small mb-3">Format: PDF (Max 10MB)</p>

                            <div class="drag-area flex-grow-1 d-flex flex-column justify-content-center" id="dragArea">
                                <div class="icon mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                                        fill="currentColor" class="bi bi-cloud-arrow-up-fill text-primary"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2m2.354 5.146a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0z" />
                                    </svg>
                                </div>
                                <h6 class="drag-text">Drag & Drop File Disini</h6>
                                <span class="text-muted my-2">atau</span>

                                <button type="button" class="btn btn-outline-primary btn-sm px-4 rounded-pill">Pilih
                                    File</button>

                                <input type="file" id="fileInput" name="document_file" hidden
                                    accept=".pdf,application/pdf">
                                <input type="text" name="fiscal_year_id"
                                    value="{{ $activeFiscalYear ? $activeFiscalYear->id : '' }}" hidden>
                                <input type="text" name="upload_by" value="{{ Auth::user()->id }}" hidden>
                            </div>

                            <div id="fileErrorMsg" class="text-danger small mt-2 text-center" style="display: none;">
                                <i class="fas fa-exclamation-circle me-1"></i> File dokumen wajib diupload!
                            </div>

                            <!-- Progress Bar Container -->
                            <div class="progress-container" id="progressContainer">
                                <div class="progress">
                                    <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%"
                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                        <span id="progressText">0%</span>
                                    </div>
                                </div>
                                <div class="upload-status uploading" id="uploadStatus">
                                    <div class="spinner-small"></div>
                                    <span id="statusText">Mengupload...</span>
                                </div>
                            </div>

                            <div class="file-info mt-3 alert alert-success d-none py-2 text-center" id="fileInfo">
                                <small>File: <span id="fileName" class="fw-bold"></span></small>
                                <button type="button" class="btn-close btn-close-sm float-end" id="removeFile"></button>
                            </div>

                            <div class="mt-auto pt-4">
                                <button class="btn app-btn-primary w-100 py-2 fw-bold text-uppercase" type="submit"
                                    id="btnSave">
                                    <i class="fas fa-save me-2"></i> Upload Dokumen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scriptjs')
    <script>
        const form = document.getElementById("saveDocument");
        const dropArea = document.getElementById("dragArea");
        const input = document.getElementById("fileInput");
        const fileInfo = document.getElementById("fileInfo");
        const fileNameDisplay = document.getElementById("fileName");
        const removeFileBtn = document.getElementById("removeFile");
        const errorMsg = document.getElementById("fileErrorMsg");
        const progressContainer = document.getElementById("progressContainer");
        const progressBar = document.getElementById("progressBar");
        const progressText = document.getElementById("progressText");
        const uploadStatus = document.getElementById("uploadStatus");
        const statusText = document.getElementById("statusText");
        const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB
        let uploadedFile = null;

        // ---------------------------------------------------------
        // 1. SUBMIT FORM: upload when user clicks the submit button
        // ---------------------------------------------------------
        form.addEventListener("submit", function(e) {
            e.preventDefault();

            // Validate that a file has been chosen
            if (!uploadedFile) {
                dropArea.classList.add("is-invalid");
                errorMsg.style.display = "block";
                dropArea.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }

            // Validate file type and size again
            const f = uploadedFile;
            const ext = (f.name.split('.').pop() || '').toLowerCase();
            if (f.type !== 'application/pdf' && ext !== 'pdf') {
                showFileError('Format tidak valid. Hanya file PDF yang diperbolehkan.');
                return;
            }
            if (f.size > MAX_FILE_SIZE) {
                showFileError('Ukuran file melebihi batas 10MB.');
                return;
            }

            // Prepare form data (include all form fields + file)
            const formData = new FormData(form);

            // Show progress UI
            progressContainer.classList.add('show');
            dropArea.style.display = 'none';
            fileInfo.classList.add('d-none');

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            // Send entire form via jQuery AJAX with progress
            $.ajax({
                url: form.action,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                xhr: function() {
                    const x = $.ajaxSettings.xhr();
                    if (x.upload) {
                        x.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                const percentComplete = Math.round((e.loaded / e.total) * 100);
                                updateProgress(percentComplete);
                            }
                        }, false);
                    }
                    return x;
                },
                success: function(response, textStatus, jqXHR) {
                    progressBar.style.width = '100%';
                    progressBar.style.background = 'linear-gradient(90deg, #198754, #155724)';
                    progressText.textContent = '100%';
                    uploadStatus.classList.remove('uploading');
                    uploadStatus.classList.add('success');
                    statusText.innerHTML = '<i class="fas fa-check-circle"></i> Upload berhasil!';

                    // After short delay, reload to allow server flash messages / redirect behavior
                    // setTimeout(() => { window.location.reload(); }, 1000);
                    // toastr.success('Berhasil upload dokumen', 'SUCCESS');
                    Swal.fire({
                        title: 'Upload Berhasil',
                        text: 'Dokumen berhasil diunggah.',
                        icon: 'success',
                        confirmButtonText: 'Oke',
                        confirmButtonColor: '#28a745',
                        allowOutsideClick: false, // Allows clicking outside to close
                        allowEscapeKey: false, // Allows pressing Esc to close
                        showCloseButton: false // Optional: adds an 'X' in the corner
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    showUploadError('Gagal mengupload dokumen');
                }
            });
        });

        // ---------------------------------------------------------
        // 2. LOGIKA UPLOAD FILE
        // ---------------------------------------------------------

        dropArea.onclick = () => input.click();

        input.addEventListener("change", function() {
            const file = this.files[0];
            handleFile(file);
        });

        dropArea.addEventListener("dragover", (event) => {
            event.preventDefault();
            dropArea.classList.add("active");
        });

        dropArea.addEventListener("dragleave", () => {
            dropArea.classList.remove("active");
        });

        dropArea.addEventListener("drop", (event) => {
            event.preventDefault();
            dropArea.classList.remove("active");

            if (event.dataTransfer.files.length > 0) {
                input.files = event.dataTransfer.files;
                handleFile(event.dataTransfer.files[0]);
            }
        });

        function handleFile(file) {
            if (file) {
                const ext = (file.name.split('.').pop() || '').toLowerCase();
                if (file.type !== 'application/pdf' && ext !== 'pdf') {
                    showFileError('Format tidak valid. Hanya file PDF yang diperbolehkan.');
                    return;
                }
                if (file.size > MAX_FILE_SIZE) {
                    showFileError('Ukuran file melebihi batas 5MB.');
                    return;
                }

                dropArea.classList.remove("is-invalid");
                errorMsg.style.display = "none";
                uploadedFile = file;

                // Show file info and hide drop area; actual upload occurs on submit
                fileNameDisplay.textContent = file.name;
                fileInfo.classList.remove("d-none");
                dropArea.style.display = 'none';
            }
        }

        // upload handled on form submit; no auto-upload function required

        function updateProgress(percent) {
            progressBar.style.width = percent + '%';
            progressBar.setAttribute('aria-valuenow', percent);
            progressText.textContent = percent + '%';
        }

        function showUploadError(message) {
            progressBar.style.width = '100%';
            progressBar.style.background = 'linear-gradient(90deg, #dc3545, #c82333)';
            uploadStatus.classList.remove('uploading');
            uploadStatus.classList.add('error');
            statusText.innerHTML = '<i class="fas fa-times-circle"></i> ' + message;

            setTimeout(() => {
                resetUpload();
            }, 2500);
        }

        function resetUpload() {
            uploadedFile = null;
            input.value = '';
            progressContainer.classList.remove('show');
            progressBar.style.width = '0%';
            progressBar.style.background = 'linear-gradient(90deg, #0d6efd, #0b5ed7)';
            progressText.textContent = '0%';
            uploadStatus.classList.remove('uploading', 'success', 'error');
            uploadStatus.classList.add('uploading');
            statusText.innerHTML = '<div class="spinner-small"></div> Mengupload...';
            dropArea.style.display = 'flex';
            fileInfo.classList.add('d-none');
        }

        function showFileError(message) {
            errorMsg.textContent = message;
            errorMsg.style.display = 'block';
            fileInfo.classList.add('d-none');
            dropArea.classList.add('is-invalid');
            progressContainer.classList.remove('show');
            input.value = '';
            uploadedFile = null;
        }

        removeFileBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            resetUpload();
        });
    </script>
@endpush
