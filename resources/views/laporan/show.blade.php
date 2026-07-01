@extends('layouts.app')

@section('css')
<style>
    .detail-card {
        border-radius: 10px;
        border: none;
    }
    .detail-label {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #868e96;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 2px;
    }
    .detail-value {
        font-size: 1.05rem;
        color: #212529;
    }
    .deskripsi-box {
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
        font-size: 1rem;
        line-height: 1.6;
    }
    .bukti-wrapper {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 15px;
        /* Memastikan konten di dalamnya tidak keluar jalur */
        overflow: hidden; 
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .bukti-img {
        /* Solusi Auto-Resize: Memaksa gambar selebar kontainer dan mengecil otomatis */
        width: 100%;
        max-width: 100%;
        height: auto;
        /* Memastikan gambar tidak terpotong/ter-crop */
        object-fit: contain; 
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: transform 0.2s;
    }
    .bukti-img:hover {
        transform: scale(1.005);
    }
    
    /* STRATEGI CETAK (PRINT CLEANUP) */
    @media print {
        .no-print, .main-header, .main-sidebar, .main-footer, .btn, .breadcrumb {
            display: none !important;
        }
        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .deskripsi-box {
            border: 1px solid #ced4da !important;
            background: white !important;
        }
        .bukti-wrapper {
            border: 1px solid #ced4da !important;
            background: white !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    {{-- BAR TOMBOL NAVIGASI --}}
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="javascript:history.back()" class="btn btn-light border shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn btn-primary shadow-sm">
            <i class="fas fa-print mr-1"></i> Cetak Detail Laporan
        </button>
    </div>

    {{-- KARTU INFORMASI UTAMA --}}
    <div class="card detail-card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <h5 class="mb-0 font-weight-bold text-dark">
                <i class="fas fa-file-alt text-primary mr-2"></i>Rincian Laporan Tugas Kerja
            </h5>
        </div>
        
        <div class="card-body">
            {{-- GRID DATA METADATA LAPORAN --}}
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                    <span class="detail-label">Nama Karyawan</span>
                    <div class="detail-value font-weight-bold text-capitalize text-dark">
                        {{ $laporan->user->nama ?? 'Tidak Diketahui' }}
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <span class="detail-label">Tanggal Pengiriman</span>
                    <div class="detail-value text-muted">
                        <i class="far fa-calendar-alt mr-1"></i>
                        {{ \Carbon\Carbon::parse($laporan->tanggal_kirim)->format('d F Y') }}
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <span class="detail-label">Nominal Transaksi / Benefit</span>
                    <div class="detail-value font-weight-bold text-success">
                        Rp {{ number_format($laporan->nominal_transaksi ?? 0, 0, ',', '.') }}
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <span class="detail-label">Judul Laporan</span>
                    <div class="detail-value font-weight-bold text-primary text-truncate" title="{{ $laporan->judul_laporan }}">
                        {{ $laporan->judul_laporan }}
                    </div>
                </div>
            </div>

            <hr class="my-3">

            {{-- DETAIL DESKRIPSI (KONTEN LAPORAN) --}}
            <div class="mb-2">
                <span class="detail-label mb-2">Deskripsi / Detail Penjelasan</span>
                <div class="p-3 deskripsi-box rounded text-dark" style="white-space: pre-line; min-height: 120px;">
                    {{ $laporan->deskripsi }}
                </div>
            </div>
        </div>
    </div>

    {{-- KARTU LAMPIRAN BUKTI (AUTO-FIT DENGAN LEBAR HALAMAN) --}}
    <div class="card detail-card shadow-sm">
        <div class="card-header bg-light py-3 border-bottom">
            <h6 class="mb-0 font-weight-bold text-secondary">
                <i class="fas fa-paperclip mr-2"></i>Dokumen / Lampiran Bukti Kerja
            </h6>
        </div>
        <div class="card-body">
            @if($laporan->file)
                @php
                    $ekstensi = pathinfo($laporan->file, PATHINFO_EXTENSION);
                    $isGambar = in_array(strtolower($ekstensi), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                @endphp

                <div class="bukti-wrapper text-center">
                    @if($isGambar)
                        <div class="w-100 mb-2">
                            <a href="{{ asset('storage/laporan_tugas/' . $laporan->file) }}" target="_blank" title="Klik untuk melihat ukuran penuh asli">
                                <img src="{{ asset('storage/laporan_tugas/' . $laporan->file) }}" class="bukti-img" alt="Pratinjau Bukti Kerja">
                            </a>
                        </div>
                        <p class="small text-muted no-print mb-0 mt-2">
                            <i class="fas fa-search-plus mr-1"></i> Gambar di atas telah disesuaikan otomatis agar muat di layar. Klik gambar untuk membuka ukuran asli penuh di tab baru.
                        </p>
                    @else
                        <div class="py-4">
                            <i class="fas fa-file-invoice fa-4x text-info mb-3"></i>
                            <h6 class="font-weight-bold text-dark">{{ $laporan->file }}</h6>
                            <p class="small text-muted">Berkas non-gambar tidak dapat ditampilkan langsung.</p>
                        </div>
                    @endif
                    
                    <div class="mt-3 no-print w-100">
                        <a href="{{ asset('storage/laporan_tugas/' . $laporan->file) }}" target="_blank" class="btn btn-outline-info btn-sm px-4">
                            <i class="fas fa-external-link-alt mr-1"></i> Buka File / Unduh Original
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-exclamation-circle fa-2x mb-2 text-warning"></i>
                    <p class="mb-0 small font-weight-bold">Tidak ada file bukti yang dilampirkan pada laporan ini.</p>
                </div>
            @endif
        </div>
        <div class="card-footer bg-white text-center text-muted small py-3 border-top">
            Sistem Informasi Manajemen Karyawan &copy; {{ date('Y') }}
        </div>
    </div>
</div>
@endsection