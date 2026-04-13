@extends('layouts.app')

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Riwayat Presensi: <strong>{{ $karyawan->nama }}</strong></h3>
        <div class="card-tools">
            <span class="badge badge-light border px-2 py-1">
                <i class="far fa-calendar-alt mr-1"></i> {{ now()->subMonth()->format('M Y') }} - {{ now()->format('M Y') }}
            </span>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
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
                    
                    {{-- Logika Jam Masuk & Pulang (Sembunyikan jam jika Cuti/Izin/Sakit/Alpa) --}}
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

                    {{-- Logika Badge Status & Ikon --}}
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
@endsection