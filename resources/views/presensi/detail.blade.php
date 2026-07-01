@extends('layouts.app')

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Riwayat Presensi: <strong>{{ $karyawan->nama }}</strong></h3>
        <div class="card-tools">
            {{-- Mengubah badge agar menampilkan nama bulan yang sedang difilter --}}
            <span class="badge badge-primary px-3 py-2">
                <i class="far fa-calendar-alt mr-1"></i> Periode: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
            </span>
        </div>
    </div>
    
    <div class="card-body">
        {{-- KELOMPOK FORM FILTER BULAN & TAHUN --}}
        <form action="{{ url()->current() }}" method="GET" class="mb-4 p-3 bg-light rounded border no-print">
            <div class="row align-items-end">
                <div class="col-md-5 col-sm-6 mb-2 mb-md-0">
                    <label for="bulan" class="small font-weight-bold text-secondary mb-1">PILIH BULAN</label>
                    <select name="bulan" id="bulan" class="form-control">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
                    <label for="tahun" class="small font-weight-bold text-secondary mb-1">PILIH TAHUN</label>
                    <select name="tahun" id="tahun" class="form-control">
                        {{-- Menampilkan opsi tahun dari tahun sekarang mundur ke 3 tahun ke belakang --}}
                        @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 col-sm-12">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter mr-1"></i> Terapkan Filter
                    </button>
                </div>
            </div>
        </form>

        {{-- CONTAINER TABEL UTAMA --}}
        <div class="table-responsive p-0 border rounded">
            <table class="table table-bordered table-striped table-hover m-0" id="table-detail-presensi">
                <thead class="bg-light">
                    <tr>
                        <th width="20%">TANGGAL</th>
                        <th width="15%" class="text-center">MASUK</th>
                        <th width="15%" class="text-center">PULANG</th>
                        <th width="20%" class="text-center">STATUS</th>
                        <th width="30%">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $data)
                    <tr>
                        <td class="font-weight-bold align-middle">
                            {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}
                        </td>
                        
                        <td class="text-center align-middle">
                            @if(strtolower($data->status) == 'hadir')
                                <span class="text-success font-weight-bold">{{ $data->jam_masuk ?? '--:--' }}</span>
                            @else
                                <span class="text-muted"><i class="fas fa-minus"></i></span>
                            @endif
                        </td>
                        <td class="text-center align-middle">
                            @if(strtolower($data->status) == 'hadir')
                                <span class="text-danger font-weight-bold">{{ $data->jam_pulang ?? '--:--' }}</span>
                            @else
                                <span class="text-muted"><i class="fas fa-minus"></i></span>
                            @endif
                        </td>

                        <td class="text-center align-middle">
                            @php
                                $status = strtolower($data->status);
                                $badgeClass = 'secondary';
                                $icon = 'fa-info-circle';

                                switch($status) {
                                    case 'hadir':
                                        $badgeClass = 'success';
                                        $icon = 'fa-check-circle';
                                        break;
                                    case 'izin':
                                        $badgeClass = 'warning';
                                        $icon = 'fa-envelope-open-text';
                                        break;
                                    case 'sakit':
                                        $badgeClass = 'info';
                                        $icon = 'fa-briefcase-medical';
                                        break;
                                    case 'cuti':
                                        $badgeClass = 'primary';
                                        $icon = 'fa-plane-departure';
                                        break;
                                    case 'alpa':
                                    case 'alpha':
                                        $badgeClass = 'danger';
                                        $icon = 'fa-times-circle';
                                        break;
                                }
                            @endphp
                            <span class="badge badge-{{ $badgeClass }} px-3 py-2" style="font-size: 0.85rem;">
                                <i class="fas {{ $icon }} mr-1"></i> {{ strtoupper($status) }}
                            </span>
                        </td>
                        <td class="small text-muted align-middle">
                            {{ $data->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 text-light"></i><br>
                            Belum ada data presensi periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection