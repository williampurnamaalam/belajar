@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-trophy mr-2 text-warning"></i>Hasil Penilaian & Ranking Karyawan (Metode SAW)
            </h5>
        </div>
        
        <div class="card-body bg-light">
            {{-- FORM FILTER PERIODE --}}
            <form action="{{ route('penilaian.ranking') }}" method="GET" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="small font-weight-bold">Bulan</label>
                        <select name="bulan" class="form-control custom-select">
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
                        <select name="tahun" class="form-control custom-select">
                            @for($y = date('Y'); $y >= date('Y')-2; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block shadow-sm">
                            <i class="fas fa-sync-alt mr-1"></i> Proses SAW
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(empty($hasil_akhir))
        <div class="alert alert-warning text-center shadow-sm">
            <i class="fas fa-info-circle mr-2"></i> Belum ada data penilaian diinput oleh Kepala Cabang untuk periode ini.
        </div>
    @else
        <div class="row">
            {{-- TABEL MATRIKS NORMALISASI (R) --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white"><h6 class="font-weight-bold mb-0">Matriks Ternormalisasi (R)</h6></div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-bordered table-striped text-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nama Karyawan</th>
                                    @foreach($kriterias as $k) <th>{{ $k->kode_kriteria }}</th> @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasil_akhir as $row)
                                <tr>
                                    <td class="text-left font-weight-bold">{{ $row['karyawan']->nama }}</td>
                                    @foreach($kriterias as $k) <td>{{ $row['normalisasi'][$k->kode_kriteria] }}</td> @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TABEL HASIL AKHIR & RANKING (V) --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white"><h6 class="font-weight-bold mb-0"><i class="fas fa-medal mr-2"></i>Hasil Akhir & Ranking (V)</h6></div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-bordered table-hover text-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="15%">Peringkat</th>
                                    <th>Nama Karyawan</th>
                                    <th width="25%">Nilai Akhir SAW</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasil_akhir as $index => $row)
                                <tr class="{{ $index == 0 ? 'bg-warning' : '' }}">
                                    <td class="align-middle">
                                        @if($index == 0) <i class="fas fa-crown text-danger fa-2x"></i> 
                                        @else <span class="h5 font-weight-bold">{{ $index + 1 }}</span> @endif
                                    </td>
                                    <td class="text-left align-middle font-weight-bold h6">{{ $row['karyawan']->nama }}</td>
                                    <td class="align-middle font-weight-bold h5 text-success">{{ number_format($row['nilai_akhir'], 3) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection