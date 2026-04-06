@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>20</h3>
                    <p>Hadir</p>
                </div>
                <div class="icon"><i class="fas fa-user-check"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner">
                    <h3 class="text-white">2</h3>
                    <p class="text-white">Cuti / Izin</p>
                </div>
                <div class="icon"><i class="fas fa-envelope-open-text"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3>0</h3>
                    <p>Alpa</p>
                </div>
                <div class="icon"><i class="fas fa-user-times"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>12</h3>
                    <p>Jam Lembur</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header text-center">
                    <h5 class="font-weight-bold mb-0">Presensi Hari Ini</h5>
                    <span class="text-muted small">Senin, 06 April 2026</span>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-3">Area Kerja: <br><strong>Kantor Pusat - PT Percobaan 1</strong></p> 
                    <div class="alert alert-success py-2 small mb-3">
                        <i class="fas fa-check-circle mr-2"></i> Masuk pada 08:00:15
                    </div>
                    <hr>
                    <button type="button" class="btn btn-danger btn-block btn-lg shadow-sm">
                        <i class="fas fa-sign-out-alt mr-2"></i> Absen Pulang
                    </button>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-history mr-2 text-primary"></i>Riwayat Absensi April 2026
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-valign-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 150px">Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Baris 1: Hadir Normal --}}
                                <tr>
                                    <td>06 Apr 2026</td>
                                    <td><span class="text-success font-weight-bold text-sm">08:00:15</span></td>
                                    <td><span class="text-muted">--:--</span></td>
                                    <td><span class="badge badge-success px-3">Hadir</span></td>
                                </tr>
                                {{-- Baris 2: Hadir Lengkap --}}
                                <tr>
                                    <td>05 Apr 2026</td>
                                    <td><span class="text-success font-weight-bold text-sm">07:55:00</span></td>
                                    <td><span class="text-danger font-weight-bold text-sm">17:05:22</span></td>
                                    <td><span class="badge badge-success px-3">Hadir</span></td>
                                </tr>
                                {{-- Baris 3: Izin --}}
                                <tr>
                                    <td>04 Apr 2026</td>
                                    <td><span class="text-muted">--:--</span></td>
                                    <td><span class="text-muted">--:--</span></td>
                                    <td><span class="badge badge-warning text-white px-3">Izin</span></td>
                                </tr>
                                {{-- Baris 4: Hadir --}}
                                <tr>
                                    <td>03 Apr 2026</td>
                                    <td><span class="text-success font-weight-bold text-sm">08:10:00</span></td>
                                    <td><span class="text-danger font-weight-bold text-sm">17:00:00</span></td>
                                    <td><span class="badge badge-success px-3">Hadir</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white text-right">
                    <small class="text-muted">Menampilkan 4 data terbaru</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection