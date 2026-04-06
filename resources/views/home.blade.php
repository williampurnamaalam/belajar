@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="font-weight-bold">Selamat Datang, {{ auth()->user()->nama }} 👋</h2>
                    <p class="mb-0 opacity-8">Hari ini adalah {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}. Pantau performa tim Anda di sini.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Karyawan</span>
                    <span class="info-box-number">150 <small>Orang</small></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Hadir Hari Ini</span>
                    <span class="info-box-number">142 <small>Orang</small></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning elevation-1 text-white"><i class="fas fa-file-signature"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pengajuan Cuti</span>
                    <span class="info-box-number">5 <small>Pending</small></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Terlambat</span>
                    <span class="info-box-number">3 <small>Orang</small></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-8">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-chart-line mr-1"></i> Grafik Kehadiran Mingguan
                    </h3>
                </div>
                <div class="card-body text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-chart-area fa-4x mb-3 opacity-2"></i>
                        <p>Visualisasi grafik kehadiran akan muncul di sini.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold text-dark">Aktivitas Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="badge badge-info p-2 mr-3"><i class="fas fa-user-plus"></i></div>
                                <div>
                                    <small class="text-muted d-block">10 menit yang lalu</small>
                                    <span>Karyawan baru <strong>Andi Wijaya</strong> telah ditambahkan.</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="badge badge-warning p-2 mr-3 text-white"><i class="fas fa-envelope-open-text"></i></div>
                                <div>
                                    <small class="text-muted d-block">1 jam yang lalu</small>
                                    <span><strong>Siti Aminah</strong> mengajukan cuti tahunan.</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="badge badge-success p-2 mr-3"><i class="fas fa-money-bill-wave"></i></div>
                                <div>
                                    <small class="text-muted d-block">3 jam yang lalu</small>
                                    <span>Payroll untuk periode Maret telah selesai diproses.</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="#" class="text-sm font-weight-bold">Lihat Semua Aktivitas <i class="fas fa-arrow-circle-right ml-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #00c6ff);
        color: white;
    }
    .opacity-8 { opacity: 0.8; }
    .info-box {
        border-radius: 10px;
        transition: transform 0.2s ease;
    }
    .info-box:hover {
        transform: translateY(-5px);
    }
    .badge {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
</style>
@endsection