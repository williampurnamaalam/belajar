@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- 1. ALERT PESAN --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <strong>Gagal!</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-2"></i>
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    {{-- 2. ROW RINGKASAN STATISTIK --}}
    <div class="row">
        <div class="col-md-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ $stats['hadir'] ?? 0 }}</h3>
                    <p>Hadir</p>
                </div>
                <div class="icon"><i class="fas fa-user-check"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner">
                    <h3 class="text-white">{{ $stats['cuti'] ?? 0 }}</h3>
                    <p class="text-white">Cuti / Izin / Sakit</p>
                </div>
                <div class="icon"><i class="fas fa-envelope-open-text"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3>{{ $stats['alpa'] ?? 0 }}</h3>
                    <p>Alpa</p>
                </div>
                <div class="icon"><i class="fas fa-user-times"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ $stats['lembur'] ?? 0 }}</h3>
                    <p>Jam Lembur</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- 3. FORM ABSENSI GPS & IP ATAU STATUS CUTI --}}
        <div class="col-lg-4">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header text-center">
                    <h5 class="font-weight-bold mb-0">Presensi Harian</h5>
                    <span class="text-muted small">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <div class="card-body text-center">
                    
                    {{-- LOGIKA BLOKIR TOMBOL ABSEN JIKA SEDANG CUTI/IZIN --}}
                    @if($cutiAktif)
                        <div class="alert alert-warning shadow-sm border-0 text-center" style="background-color: #fff3cd; border-left: 5px solid #ffc107 !important;">
                            <i class="fas fa-calendar-check fa-4x mb-3 mt-2 text-warning"></i>
                            <h5 class="font-weight-bold">Status: Sedang {{ ucfirst($cutiAktif->jenis_cuti) }}</h5>
                            <p class="mb-2 text-dark small">Anda terdaftar dalam masa {{ $cutiAktif->jenis_cuti }} yang telah disetujui HRD.</p>
                            <hr>
                            <p class="mb-0 small text-muted">
                                Mulai: <strong>{{ \Carbon\Carbon::parse($cutiAktif->tanggal_mulai)->translatedFormat('d M Y') }}</strong><br>
                                Sampai: <strong>{{ \Carbon\Carbon::parse($cutiAktif->tanggal_selesai)->translatedFormat('d M Y') }}</strong>
                            </p>
                        </div>
                    @else
                        {{-- TAMPILAN NORMAL JIKA TIDAK CUTI --}}
                        <p class="text-muted mb-3">Area Kerja Anda: <br>
                            <span class="badge badge-light p-2 border">
                                <i class="fas fa-building mr-1"></i> {{ $area->lokasi ?? 'Tidak Terdaftar' }}
                            </span>
                        </p> 
                        
                        <div id="location-status" class="alert alert-warning py-1 small mb-3">
                            <i class="fas fa-map-marker-alt mr-2"></i> Mencari sinyal GPS...
                        </div>

                        <hr>

                        @if(!$absenHariIni)
                            <form action="{{ route('absensi.store') }}" method="POST" id="form-absen">
                                @csrf
                                <input type="hidden" name="area_id" value="{{ $area->id ?? '' }}">
                                <input type="hidden" name="lat" id="lat">
                                <input type="hidden" name="lon" id="lon">
                                <button type="submit" id="btn-absen" class="btn btn-primary btn-block btn-lg shadow-sm" disabled>
                                    <i class="fas fa-sign-in-alt mr-2"></i> Absen Masuk
                                </button>
                            </form>
                        @elseif($absenHariIni && !$absenHariIni->jam_keluar)
                            <div class="alert alert-success py-2 small mb-3">
                                <i class="fas fa-check-circle mr-2"></i> Masuk: <strong>{{ $absenHariIni->jam_masuk }}</strong>
                            </div>
                            @php
                                $jedaMenit = 10;
                                $waktuMasuk = \Carbon\Carbon::parse($absenHariIni->jam_masuk);
                                $selisihMenit = $waktuMasuk->diffInMinutes(now());
                                $bolehPulang = $selisihMenit >= $jedaMenit;
                                $sisaMenit = $jedaMenit - $selisihMenit;
                            @endphp
                            <form action="{{ route('absensi.update', $absenHariIni->id) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="lat" id="lat">
                                <input type="hidden" name="lon" id="lon">
                                
                                @if($bolehPulang)
                                    <button type="submit" id="btn-absen" class="btn btn-danger btn-block btn-lg shadow-sm" disabled>
                                        <i class="fas fa-sign-out-alt mr-2"></i> Absen Pulang
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary btn-block btn-lg shadow-sm" disabled>
                                        <i class="fas fa-hourglass-half mr-2"></i> Jeda Pulang ({{ $sisaMenit }}m lagi)
                                    </button>
                                @endif
                            </form>
                        @else
                            <div class="alert alert-info py-2 small mb-3">
                                <i class="fas fa-check-double mr-2"></i> Selesai! Pulang: <strong>{{ $absenHariIni->jam_keluar }}</strong>
                            </div>
                            <button class="btn btn-secondary btn-block btn-lg shadow-sm" disabled>
                                <i class="fas fa-home mr-2"></i> Sudah Presensi
                            </button>
                        @endif
                    @endif

                </div>
                <div class="card-footer text-center bg-light">
                    <small class="text-muted">IP Public: <strong>{{ request()->ip() }}</strong></small>
                </div>
            </div>
        </div>

        {{-- 4. TABEL RIWAYAT ABSENSI --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-history mr-2 text-primary"></i>Riwayat Bulan Ini
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-valign-middle mb-0 text-nowrap">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th class="text-center">Masuk</th>
                                    <th class="text-center">Keluar</th>
                                    <th class="text-center">Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $row)
                                <tr>
                                    <td class="small font-weight-bold">{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}</td>
                                    <td class="text-center"><span class="text-success font-weight-bold">{{ $row->jam_masuk ?: '--:--' }}</span></td>
                                    <td class="text-center"><span class="text-danger font-weight-bold">{{ $row->jam_keluar ?: '--:--' }}</span></td>
                                    <td class="text-center">
                                        @php
                                            $status = strtolower($row->status);
                                            $badge = 'secondary';
                                            if($status == 'hadir') $badge = 'success';
                                            elseif(in_array($status, ['cuti', 'izin', 'sakit'])) $badge = 'warning';
                                            elseif($status == 'alpa') $badge = 'danger';
                                        @endphp
                                        <span class="badge badge-{{ $badge }} px-3">{{ strtoupper($row->status) }}</span>
                                    </td>
                                    <td class="small">
                                        @if(str_contains(strtolower($row->keterangan), 'telat'))
                                            <span class="text-danger font-weight-bold"><i class="fas fa-clock mr-1"></i> {{ $row->keterangan }}</span>
                                        @else
                                            <span class="text-muted">{{ $row->keterangan ?: '-' }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat presensi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const latInput = document.getElementById('lat');
        const lonInput = document.getElementById('lon');
        const btnAbsen = document.getElementById('btn-absen');
        const statusLocation = document.getElementById('location-status');

        if (statusLocation && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                if(latInput) latInput.value = position.coords.latitude;
                if(lonInput) lonInput.value = position.coords.longitude;
                statusLocation.classList.replace('alert-warning', 'alert-success');
                statusLocation.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Lokasi GPS Terkunci';
                if(btnAbsen) btnAbsen.disabled = false;
            }, function(error) {
                statusLocation.classList.replace('alert-warning', 'alert-danger');
                statusLocation.innerHTML = '<i class="fas fa-times-circle mr-2"></i> GPS Tidak Aktif / Ditolak';
            }, { enableHighAccuracy: true });
        }
    });
</script>
@endpush