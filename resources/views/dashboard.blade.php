@extends('layouts.app')

@section('content')
<div class="container-fluid pb-4">
    
    {{-- ALERT SUCCESS --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-left-success" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    {{-- ========================================================== --}}
    {{-- TAMPILAN DASHBOARD: ADMIN / HRD / MANAGER (KEPALA CABANG)  --}}
    {{-- ========================================================== --}}
    @if(in_array($userRole, ['admin', 'hrd', 'manager']))
        
        {{-- Banner Welcome Admin --}}
        <div class="card shadow-sm border-0 mb-4 bg-gradient-primary text-white" style="border-radius: 15px;">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="font-weight-bold mb-1">Selamat Datang, {{ auth()->user()->nama }} <span style="font-size: 1.5rem;">👋</span></h3>
                    <p class="mb-0 opacity-8">Hari ini adalah {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}. Pantau performa tim dan operasional perusahaan di sini.</p>
                </div>
                <div class="d-none d-lg-block">
                    <i class="fas fa-building fa-4x opacity-5"></i>
                </div>
            </div>
        </div>

        {{-- 4 Kotak Statistik Premium (Gaya SB Admin 2) --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
                <div class="card border-left-primary shadow-sm h-100 py-2 widget-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Karyawan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKaryawan }} <small class="text-muted text-sm font-weight-normal">Orang</small></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
                <div class="card border-left-success shadow-sm h-100 py-2 widget-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir Hari Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $hadirHariIni }} <small class="text-muted text-sm font-weight-normal">Orang</small></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
                <div class="card border-left-warning shadow-sm h-100 py-2 widget-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pengajuan Cuti</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cutiPending }} <small class="text-muted text-sm font-weight-normal">Pending</small></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-envelope-open-text fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-danger shadow-sm h-100 py-2 widget-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Laporan Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLaporan }} <small class="text-muted text-sm font-weight-normal">Berkas</small></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-signature fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Area Grafik & Aktivitas --}}
        <div class="row">
            {{-- Grafik --}}
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card shadow-sm border-0 rounded-lg h-100">
                    <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-bar mr-2"></i>Grafik Peringkat Karyawan (SAW) - {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</h6>
                    </div>
                    <div class="card-body">
                        @if(empty($chart_labels))
                            <div class="text-center py-5 mt-4">
                                <i class="fas fa-chart-area fa-4x text-gray-300 mb-3"></i>
                                <h6 class="text-muted font-weight-bold">Data penilaian bulan ini belum tersedia.</h6>
                                <p class="small text-muted">Lakukan input penilaian pada menu Penilaian Kinerja.</p>
                            </div>
                        @else
                            <div class="chart-container" style="position: relative; height: 350px; width: 100%;">
                                <canvas id="rankingChart"></canvas>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Feed Aktivitas --}}
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow-sm border-0 rounded-lg h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-bell text-warning mr-2"></i>Aktivitas Pengajuan</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($aktivitasTerbaru as $aktivitas)
                            <li class="list-group-item d-flex align-items-center p-3 hover-bg-light">
                                <div class="{{ $aktivitas['color'] }} text-white rounded-circle p-3 mr-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                    <i class="{{ $aktivitas['icon'] }}"></i>
                                </div>
                                <div>
                                    <span class="text-sm text-dark d-block mb-1"><strong>{{ $aktivitas['nama'] }}</strong> {{ $aktivitas['keterangan'] }}.</span>
                                    <small class="text-muted font-weight-bold" style="font-size: 11px;"><i class="far fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($aktivitas['waktu'])->diffForHumans() }}</small>
                                </div>
                            </li>
                            @empty
                            <li class="list-group-item text-center p-5 text-muted border-0">
                                <i class="fas fa-inbox fa-3x mb-3 text-gray-300"></i>
                                <p class="mb-0 text-sm">Belum ada aktivitas pengajuan terbaru.</p>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer bg-light text-center border-0 py-3">
                        <a href="#" class="text-primary font-weight-bold text-sm text-decoration-none">Kelola Persetujuan <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

    {{-- ========================================================== --}}
    {{-- TAMPILAN DASHBOARD: KHUSUS KARYAWAN                        --}}
    {{-- ========================================================== --}}
    @else
        
        {{-- Banner Welcome Karyawan --}}
        <div class="card shadow-sm border-0 mb-4 text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 15px;">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="font-weight-bold mb-1">Halo, {{ auth()->user()->nama }}! <span style="font-size: 1.5rem;">✨</span></h3>
                    <p class="mb-0 opacity-8">Selamat bekerja di hari {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}. Jangan lupa untuk melakukan absensi hari ini.</p>
                </div>
                <div class="d-none d-md-block">
                    <i class="fas fa-smile-beam fa-4x opacity-5"></i>
                </div>
            </div>
        </div>

        {{-- Kotak PINTASAN AKSI (Modern Tiles) --}}
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('absensi') }}" class="text-decoration-none">
                    <div class="card action-tile bg-primary text-white text-center border-0 shadow-sm h-100 py-4">
                        <div class="card-body">
                            <i class="fas fa-fingerprint fa-3x mb-3 opacity-8"></i>
                            <h5 class="font-weight-bold mb-0">Absen Sekarang</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <a href="{{ route('cuti.index') }}" class="text-decoration-none">
                    <div class="card action-tile bg-warning text-dark text-center border-0 shadow-sm h-100 py-4">
                        <div class="card-body">
                            <i class="fas fa-calendar-plus fa-3x mb-3 opacity-8"></i>
                            <h5 class="font-weight-bold mb-0">Ajukan Cuti</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-12 mb-3">
                <a href="{{ route('laporan.index') }}" class="text-decoration-none">
                    <div class="card action-tile bg-info text-white text-center border-0 shadow-sm h-100 py-4">
                        <div class="card-body">
                            <i class="fas fa-file-upload fa-3x mb-3 opacity-8"></i>
                            <h5 class="font-weight-bold mb-0">Kirim Laporan Kerja</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Statistik Pribadi Karyawan --}}
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <div class="card border-left-success shadow-sm h-100 py-2 widget-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kehadiran Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $hadirBulanIni }} <small class="text-muted text-sm font-weight-normal">Hari</small></div>
                            </div>
                            <div class="col-auto"><i class="fas fa-calendar-check fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <div class="card border-left-info shadow-sm h-100 py-2 widget-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sisa Kuota Cuti</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $sisaCuti }} <small class="text-muted text-sm font-weight-normal">Hari</small></div>
                            </div>
                            <div class="col-auto"><i class="fas fa-plane-departure fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card border-left-secondary shadow-sm h-100 py-2 widget-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Pengajuan Diproses</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengajuanDiproses }} <small class="text-muted text-sm font-weight-normal">Berkas</small></div>
                            </div>
                            <div class="col-auto"><i class="fas fa-hourglass-half fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Pengumuman / Catatan --}}
        <div class="card shadow-sm border-0 rounded-lg border-left-primary">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-light rounded-circle p-3 mr-3 d-flex align-items-center justify-content-center" style="background-color: #e3f2fd; color: #007bff; width: 50px; height: 50px;">
                        <i class="fas fa-bullhorn fa-lg"></i>
                    </div>
                    <h6 class="font-weight-bold text-primary mb-0 m-0">Papan Informasi Pimpinan</h6>
                </div>
                <p class="text-gray-800 mb-0" style="line-height: 1.6;">
                    Tetap semangat dan jaga produktivitas! Penilaian kinerja (Metode SAW) dilakukan pada setiap akhir bulan oleh Kepala Cabang. Karyawan dengan peringkat terbaik akan mendapatkan apresiasi dan bonus dari perusahaan. Pastikan laporan kerja dan kehadiran Anda selalu maksimal.
                </p>
            </div>
        </div>

    @endif
</div>

{{-- STYLING CSS PREMIUM --}}
<style>
    /* Utility Classes */
    .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); }
    .opacity-8 { opacity: 0.8; }
    .opacity-5 { opacity: 0.3; }
    .text-gray-300 { color: #dddfeb !important; }
    .text-gray-800 { color: #5a5c69 !important; }
    
    /* Hover Effects */
    .hover-bg-light:hover { background-color: #f8f9fc; transition: 0.2s ease; }
    
    /* Widget Cards (Admin & Employee Stats) */
    .widget-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border-radius: 0.75rem; }
    .widget-card:hover { transform: translateY(-3px); box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important; }
    
    /* Colored Left Borders */
    .border-left-primary { border-left: 0.35rem solid #4e73df !important; }
    .border-left-success { border-left: 0.35rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.35rem solid #36b9cc !important; }
    .border-left-warning { border-left: 0.35rem solid #f6c23e !important; }
    .border-left-danger { border-left: 0.35rem solid #e74a3b !important; }
    .border-left-secondary { border-left: 0.35rem solid #858796 !important; }

    /* Action Tiles (Employee Shortcut Buttons) */
    .action-tile { border-radius: 1rem; transition: all 0.3s cubic-bezier(.25,.8,.25,1); }
    .action-tile:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important; filter: brightness(1.05); }
</style>
@endsection

@push('scripts')
{{-- Script Chart.js hanya dirender jika user bukan Karyawan --}}
@if(in_array($userRole, ['admin', 'hrd', 'manager']))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if(!empty($chart_labels))
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('rankingChart').getContext('2d');
            
            // Konfigurasi Gradasi Warna Bar
            var gradientBlue = ctx.createLinearGradient(0, 0, 0, 400);
            gradientBlue.addColorStop(0, 'rgba(78, 115, 223, 1)');
            gradientBlue.addColorStop(1, 'rgba(78, 115, 223, 0.5)');

            var rankingChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chart_labels),
                    datasets: [{
                        label: 'Nilai Akhir SAW',
                        data: @json($chart_data),
                        backgroundColor: gradientBlue,
                        hoverBackgroundColor: '#2e59d9',
                        borderColor: '#4e73df',
                        borderWidth: 1, 
                        borderRadius: 6, // Rounded bars
                        barPercentage: 0.5,
                        categoryPercentage: 0.8
                    }]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: false,
                    scales: { 
                        y: { 
                            beginAtZero: true, 
                            max: 1.0,
                            grid: { color: "rgba(234, 236, 244, 1)", zeroLineColor: "rgba(234, 236, 244, 1)", drawBorder: false },
                            ticks: { maxTicksLimit: 6, padding: 10, color: '#858796' }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: '#858796', font: { weight: 'bold' } }
                        }
                    },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyColor: "#858796",
                            titleColor: '#6e707e',
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            padding: 15,
                            displayColors: false,
                            boxPadding: 3
                        }
                    }
                }
            });
        });
    @endif
</script>
@endif
@endpush