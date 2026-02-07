@extends('layouts.app')

@section('content')
    <div class="container-xl">

        <div class="row align-items-center justify-content-between mb-4">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">
                    <a href="{{ route('document.index') }}" class="btn btn-sm btn-light border me-2">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Detail Dokumen
                </h1>
            </div>
            <div class="col-auto">
                {{-- <span class="badge bg-success">Status: Aktif</span> --}}
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="app-card app-card-settings shadow-sm h-100">
                    <div class="app-card-header p-3 border-bottom">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <h4 class="app-card-title">
                                    <i class="fas fa-eye me-2 text-primary"></i>Pratinjau File
                                </h4>
                            </div>
                            <div class="col-auto">
                                @if ($document->security_level == 'public')
                                    <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-secondary"
                                        title="Buka Fullscreen">
                                        <i class="fas fa-maximize"></i> Fullscreen
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="app-card-body p-2">
                        <div
                            style="height: 700px; background-color: #f8f9fa; border-radius: 0 0 8px 8px; overflow: hidden; position: relative;">

                            @if ($document->security_level == 'public')
                                {{-- TAMPILKAN IFRAME JIKA PUBLIC --}}
                                <iframe src="{{ $url }}" width="100%" height="100%" style="border:none;"
                                    allow="autoplay"></iframe>
                            @else
                                {{-- TAMPILKAN PESAN DIBLOKIR JIKA INTERNAL/RAHASIA --}}
                                <div
                                    class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-5">

                                    <div class="mb-4">
                                        <div class="avatar avatar-xl bg-light rounded-circle shadow-sm d-flex align-items-center justify-content-center mx-auto"
                                            style="width: 150px; height: 150px;">
                                            <i class="fas fa-file-shield text-warning" style="font-size: 5rem;"></i>
                                        </div>
                                    </div>

                                    <h3 class="fw-bold text-dark">Pratinjau Tidak Tersedia</h3>
                                    <p class="text-muted mb-4" style="max-width: 400px;">
                                        Dokumen ini bersifat
                                        <span
                                            class="badge bg-warning text-dark">{{ ucfirst($document->security_level) }}</span>.
                                        Demi keamanan, pratinjau langsung dinonaktifkan.
                                    </p>

                                    {{-- @if (Gate::allows('download document'))
                                        <a href="{{ $downloadUrl ?? '#' }}" class="btn btn-primary">
                                            <i class="fas fa-download me-2"></i>Download File untuk Membaca
                                        </a>
                                    @endif --}}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="app-card app-card-settings shadow-sm h-100">
                    <div class="app-card-header p-3 border-bottom">
                        <h4 class="app-card-title">
                            <i class="fas fa-info-circle me-2 text-info"></i>Informasi Dokumen
                        </h4>
                    </div>

                    <div class="app-card-body p-4">
                        <table class="table table-borderless mb-4">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-muted" width="35%">Judul</th>
                                    <td class="text-end fw-bold">{{ $document->title ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Nomor Dok.</th>
                                    <td class="text-end">{{ $document->document_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Kategori</th>
                                    <td class="text-end">
                                        <span class="badge bg-light text-dark border">
                                            {{ $document->category->name ?? 'Umum' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Tahun</th>
                                    <td class="text-end">{{ $document->fiscal_year->year ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Sifat</th>
                                    <td class="text-end">
                                        @php
                                            $badgeColor = match ($document->security_level ?? '') {
                                                'rahasia' => 'bg-danger',
                                                'internal' => 'bg-warning text-dark',
                                                default => 'bg-success',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeColor }}">
                                            {{ ucfirst($document->security_level ?? 'Public') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Tanggal</th>
                                    <td class="text-end">
                                        {{ \Carbon\Carbon::parse($document->document_date)->translatedFormat('d F Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Pengunggah</th>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <div class="avatar avatar-xs me-2">
                                                <span class="avatar-title rounded-circle bg-primary text-white p-1"
                                                    style="font-size: 10px;">
                                                    {{ substr($document->uploader->name ?? 'A', 0, 2) }}
                                                </span>
                                            </div>
                                            {{ $document->uploader->name ?? 'System' }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <hr class="my-4">

                        <div class="mb-4">
                            <label class="form-label text-muted small">Deskripsi / Keterangan</label>
                            <p class="small text-dark border p-2 rounded bg-light">
                                {{ $document->description ?? 'Tidak ada keterangan tambahan untuk dokumen ini.' }}
                            </p>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('document.download', Crypt::encrypt($document->id)) }}" class="btn btn-primary text-white">
                                <i class="fas fa-download me-2"></i>Download Dokumen
                            </a>

                            {{-- @can('edit document') --}}
                            {{-- <a href="{{ route('document.edit', Crypt::encrypt($document->id)) }}"
                                    class="btn btn-outline-secondary">
                                    <i class="fas fa-pencil me-2"></i>Edit Data
                                </a> --}}
                            {{-- @endcan --}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scriptjs')
@endpush
