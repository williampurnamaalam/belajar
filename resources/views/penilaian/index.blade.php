@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0 alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-edit mr-2"></i>Input Penilaian Kinerja Karyawan (Skala 1-5)
            </h5>
        </div>
        
        <div class="card-body bg-light">
            <form action="{{ route('penilaian.input') }}" method="GET" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="small font-weight-bold">Periode Bulan</label>
                        <select name="bulan" class="form-control custom-select shadow-sm">
                            @foreach(range(1, 12) as $m)
                                @php $m_pad = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                                <option value="{{ $m_pad }}" {{ $bulan == $m_pad ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold">Tahun</label>
                        <select name="tahun" class="form-control custom-select shadow-sm">
                            @for($y = date('Y'); $y >= date('Y')-2; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary btn-block shadow-sm">
                            <i class="fas fa-filter mr-1"></i> Buka Form
                        </button>
                    </div>
                </div>
            </form>

            <hr>
            <div class="alert alert-info shadow-sm bg-white text-dark border-info mb-4">
                <h6 class="font-weight-bold text-info border-bottom pb-2 mb-3"><i class="fas fa-info-circle mr-2"></i>Panduan Skala Penilaian</h6>
                <div class="row small">
                    <div class="col-md-4">
                        <strong>C1 - Absensi/Kehadiran:</strong><br>
                        1 = Sangat Kurang (1-8 hr)<br>
                        2 = Kurang Baik (9-14 hr)<br>
                        3 = Cukup (15-19 hr)<br>
                        4 = Baik (20-25 hr)<br>
                        5 = Sangat Baik (>=26 hr)
                    </div>
                    <div class="col-md-4">
                        <strong>C2 - Target Kerja:</strong><br>
                        1 = Sangat Kurang (500rb - 1jt)<br>
                        2 = Kurang Baik (>1jt - 3jt)<br>
                        3 = Cukup (>3jt - 5jt)<br>
                        4 = Baik (>5jt - 10jt)<br>
                        5 = Sangat Baik (>10jt)
                    </div>
                    <div class="col-md-4">
                        <strong>C3 - Keterampilan:</strong><br>
                        1 = Sangat Kurang<br>
                        2 = Kurang Baik<br>
                        3 = Cukup<br>
                        4 = Baik<br>
                        5 = Sangat Baik
                    </div>
                </div>
            </div>
            @if($kriterias->isEmpty())
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Master Data Kriteria masih kosong. Silakan isi terlebih dahulu.
                </div>
            @elseif($area_id_penilai == null)
                <div class="alert alert-danger text-center shadow-sm">
                    <i class="fas fa-ban mr-2 fa-lg mb-2"></i><br>
                    <strong>Akses Ditolak!</strong><br>
                    Akun Anda belum didaftarkan ke dalam Tim/Area manapun (Tabel Team). Anda tidak dapat melakukan penilaian. Hubungi Administrator.
                </div>
            @elseif($karyawans->isEmpty())
                <div class="alert alert-info text-center shadow-sm border-info">
                    <i class="fas fa-users-slash mr-2 fa-lg mb-2"></i><br>
                    <strong>Belum Ada Karyawan!</strong><br>
                    Saat ini tidak ada karyawan yang terdaftar di area kerja Anda (Area ID: {{ $area_id_penilai }}), atau semua karyawan belum dimasukkan ke tabel tim.
                </div>
            @else
                <form action="{{ route('penilaian.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    
                    <div class="alert alert-success mb-3 py-2 px-3 shadow-sm border-0">
                        <i class="fas fa-map-marker-alt mr-2"></i> Menampilkan Karyawan di Area Kerja <strong class="text-capitalize">{{ auth()->user()->areakerja->first()?->lokasi ?? 'Kantor Utama' }}</strong>
                    </div>

                    <div class="table-responsive bg-white shadow-sm rounded border">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th width="5%" class="align-middle">No</th>
                                    <th width="20%" class="align-middle text-left">Nama Karyawan</th>
                                    {{-- Looping Header Kriteria --}}
                                    @foreach($kriterias as $k)
                                        <th class="align-middle" title="{{ $k->nama_kriteria }}">
                                            {{ $k->kode_kriteria }} <br>
                                            <span class="font-weight-normal text-warning" style="font-size: 12px;">({{ $k->nama_kriteria }})</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($karyawans as $index => $karyawan)
                                <tr>
                                    <td class="text-center align-middle">{{ $index + 1 }}</td>
                                    <td class="align-middle font-weight-bold text-capitalize">
                                        {{ $karyawan->nama }}
                                    </td>
                                    
                                    {{-- Looping Input Nilai Skala 1-5 --}}
                                    @foreach($kriterias as $kriteria)
                                        @php
                                            $nilai_lama = '';
                                            if(isset($penilaian_existing[$karyawan->id])) {
                                                $data_skor = $penilaian_existing[$karyawan->id]->where('kriteria_id', $kriteria->id)->first();
                                                $nilai_lama = $data_skor ? $data_skor->nilai : '';
                                            }
                                        @endphp
                                        <td class="p-2 align-middle">
                                            <select name="nilai[{{ $karyawan->id }}][{{ $kriteria->id }}]" class="form-control custom-select text-center font-weight-bold text-primary shadow-sm" required>
                                                <option value="" class="text-muted">-- Skor --</option>
                                                <option value="1" {{ $nilai_lama == 1 ? 'selected' : '' }}>1</option>
                                                <option value="2" {{ $nilai_lama == 2 ? 'selected' : '' }}>2</option>
                                                <option value="3" {{ $nilai_lama == 3 ? 'selected' : '' }}>3</option>
                                                <option value="4" {{ $nilai_lama == 4 ? 'selected' : '' }}>4</option>
                                                <option value="5" {{ $nilai_lama == 5 ? 'selected' : '' }}>5</option>
                                            </select>
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-right">
                        <button type="submit" class="btn btn-primary btn-lg shadow">
                            <i class="fas fa-save mr-2"></i> Simpan Penilaian Bulan {{ date('F', mktime(0,0,0,$bulan,1)) }}
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection