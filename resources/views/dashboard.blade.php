@extends('layouts.app')

@push('scriptcss')
    <style>
        /* --- Custom Soft Colors for Cards --- */
        .bg-primary-soft {
            background-color: rgba(21, 163, 184, 0.15);
            color: #15a3b8;
        }

        .bg-success-soft {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        .bg-warning-soft {
            background-color: rgba(255, 193, 7, 0.15);
            color: #d39e00;
        }

        .bg-danger-soft {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }

        .bg-purple-soft {
            background-color: rgba(111, 66, 193, 0.15);
            color: #6f42c1;
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            /* Rounded square looks more modern */
            font-size: 1.25rem;
        }

        .app-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }

        .app-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        /* Avatar styling for login list */
        .avatar-initial {
            width: 35px;
            height: 35px;
            background-color: #e9ecef;
            color: #495057;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8rem;
        }

        .table-custom th {
            font-weight: 600;
            color: #6c757d;
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .activity-time {
            font-size: 0.7rem;
            color: #adb5bd;
        }
    </style>
@endpush

@section('content')
    <div class="container-xl animate__animated animate__fadeIn">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="app-page-title mb-0 fw-bold text-dark">
                    Dashboard Overview
                </h1>
                <p class="text-muted small mb-0">Ringkasan aktivitas dan statistik sistem</p>
            </div>
            <div class="bg-white px-3 py-2 rounded shadow-sm border text-muted small">
                <i class="far fa-calendar-alt me-2 text-primary"></i>
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-xl-12">
                @php
                    // 1. Tentukan Total Kapasitas Google Drive Anda (Contoh: 15 GB untuk akun gratis, 100 GB, atau 1000 GB)
                    $totalCapacityGB = 1000;

                    // 2. Konversi Kapasitas ke Bytes (1 GB = 1073741824 Bytes)
                    $totalCapacityBytes = $totalCapacityGB * 1073741824;

                    // 3. Hitung Persentase (Cegah error division by zero)
                    $percentage = $totalCapacityBytes > 0 ? ($totalStorageUsed / $totalCapacityBytes) * 100 : 0;

                    // 4. Hitung Data Terpakai dalam GB
                    $usedGB = $totalStorageUsed / 1073741824;

                    // 5. Hitung Sisa dalam GB
                    $remainingGB = $totalCapacityGB - $usedGB;

                    // 6. Tentukan Warna Progress Bar
                    $colorClass = 'bg-primary';
                    if ($percentage > 70) {
                        $colorClass = 'bg-warning';
                    }
                    if ($percentage > 90) {
                        $colorClass = 'bg-danger';
                    }
                @endphp

                <div class="app-card shadow-sm h-100 border-0"
                    style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">
                    <div class="app-card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary-soft text-primary me-3">
                                <i class="fab fa-google-drive fa-lg"></i>
                            </div>
                            <h4 class="mb-0">Penyimpanan Google Drive</h4>
                        </div>

                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <div class="stats-figure fw-bold" style="font-size: 1.8rem;">
                                {{ number_format($usedGB, 2) }} <span class="fs-6 text-muted">GB Terpakai</span>
                            </div>
                            <div class="text-primary fw-bold">{{ number_format($percentage, 1) }}%</div>
                        </div>

                        <div class="progress mb-3" style="height: 12px; border-radius: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated {{ $colorClass }}"
                                role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <div class="p-2 border rounded bg-white">
                                    <small class="text-muted d-block">Total Kapasitas</small>
                                    <span class="fw-bold">{{ number_format($totalCapacityGB, 2) }} GB</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 border rounded bg-white">
                                    <small class="text-muted d-block">Tersisa</small>
                                    <span class="fw-bold {{ $remainingGB < 1 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($remainingGB, 2) }} GB
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="app-card h-100 p-3 bg-white">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-purple-soft me-3">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Kategori</div>
                            <h3 class="mb-0 fw-bold text-dark">{{ $totalCategories }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="app-card h-100 p-3 bg-white">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-success-soft me-3">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">TA. Aktif</div>
                            <h3 class="mb-0 fw-bold text-dark">{{ $activeFiscalYear }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="app-card h-100 p-3 bg-white">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-primary-soft me-3">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Total Dokumen</div>
                            <h3 class="mb-0 fw-bold text-dark">{{ $totalDocuments }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="app-card h-100 p-3 bg-white">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-warning-soft me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Pengguna</div>
                            <h3 class="mb-0 fw-bold text-dark">{{ $totalUsers }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-12 col-lg-8">
                <div class="app-card bg-white h-100">
                    <div class="app-card-header p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="app-card-title mb-0"><i class="fas fa-file-contract me-2 text-primary"></i>Dokumen
                            Terbaru</h5>
                        <a href="{{ route('document.index') }}" class="btn btn-sm btn-light text-primary">Lihat Semua</a>
                    </div>
                    <div class="app-card-body p-0 table-responsive">
                        <table class="table table-custom table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3">Judul</th>
                                    <th>Kategori</th>
                                    <th>Uploader</th>
                                    <th>Tanggal</th>
                                    <th class="text-end pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestDocuments as $doc)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <i class="far fa-file-pdf text-danger me-2 fs-5"></i>
                                                <span class="fw-bold text-dark text-truncate" style="max-width: 200px;">
                                                    {{ $doc->title }}
                                                </span>
                                            </div>
                                        </td>
                                        <td><span
                                                class="badge bg-light text-dark border">{{ $doc->category->name ?? '-' }}</span>
                                        </td>
                                        <td class="small text-muted">{{ $doc->uploader->name ?? 'System' }}</td>
                                        <td class="small">
                                            {{ \Carbon\Carbon::parse($doc->document_date)->format('d M Y') }}</td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('document.show', Crypt::encrypt($doc->id)) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada dokumen yang
                                            diunggah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="app-card bg-white h-100">
                    <div class="app-card-header p-3 border-bottom">
                        <h5 class="app-card-title mb-0"><i class="fas fa-user-clock me-2 text-warning"></i>Login Terakhir
                        </h5>
                    </div>
                    <div class="app-card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($latestLogins as $log)
                                <div class="list-group-item px-3 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="avatar-initial">
                                                {{ substr($log->user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-dark small fw-bold">{{ $log->user->name }}</h6>
                                            <small class="text-muted" style="font-size: 10px;">
                                                {{ $log->ip_address }} </small>
                                        </div>
                                        <div class="text-end">
                                            <div class="activity-time">
                                                {{ $log->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted small">
                                    Belum ada data login.
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="app-card-footer p-2 text-center border-top">
                        <a href="{{ route('user.index') }}" class="text-decoration-none small text-muted">Lihat Manajemen
                            User</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
