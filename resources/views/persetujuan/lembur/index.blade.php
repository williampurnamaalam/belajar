@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- ALERT PESAN SUCCESS/ERROR --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i> Terdapat kesalahan:
        <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        {{-- FORM PENGAJUAN LEMBUR --}}
        <div class="col-lg-4">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="font-weight-bold mb-0 text-info">
                        <i class="fas fa-business-time mr-2"></i>Form Pengajuan Lembur
                    </h5>
                    <p class="text-muted small mt-2">Ajukan rencana lembur Anda sebelum waktu pelaksanaan untuk direview oleh Atasan.</p>
                </div>
                <form action="{{ route('lembur.store') }}" method="POST">
                    @csrf
                    <div class="card-body pt-2">
                        <div class="form-group">
                            <label class="font-weight-bold">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="date" name="tanggal" class="form-control" min="{{ date('Y-m-d') }}" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jam Mulai <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="far fa-clock"></i></span>
                                        </div>
                                        <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jam Selesai <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-clock text-danger"></i></span>
                                        </div>
                                        <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label class="font-weight-bold">Target / Rencana Pekerjaan <span class="text-danger">*</span></label>
                            <textarea name="alasan" class="form-control" rows="4" placeholder="Contoh: Menyelesaikan rekap laporan bulanan divisi marketing..." required>{{ old('alasan') }}</textarea>
                            <small class="text-muted">Jelaskan secara singkat apa yang akan Anda kerjakan selama waktu lembur.</small>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-right">
                        <button type="reset" class="btn btn-default mr-2">Reset</button>
                        <button type="submit" class="btn btn-info px-4 shadow-sm font-weight-bold">
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- RIWAYAT PENGAJUAN LEMBUR --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white pt-4">
                    <h5 class="font-weight-bold mb-0 text-dark">
                        <i class="fas fa-history mr-2 text-info"></i>Riwayat Pengajuan Lembur Anda
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="15%">Diajukan Pada</th>
                                    <th width="20%">Jadwal Lembur</th>
                                    <th width="25%">Rencana Jam</th>
                                    <th width="15%" class="text-center">Status</th>
                                    <th width="25%">Catatan HRD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatLembur as $lembur)
                                <tr>
                                    <td class="align-middle text-muted small">
                                        {{ $lembur->created_at->format('d M Y') }}<br>
                                        {{ $lembur->created_at->format('H:i') }}
                                    </td>
                                    <td class="align-middle font-weight-bold">
                                        {{ \Carbon\Carbon::parse($lembur->tanggal)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge badge-light border px-2 py-1">
                                            <i class="far fa-clock text-info mr-1"></i> 
                                            {{ \Carbon\Carbon::parse($lembur->jam_mulai)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($lembur->jam_selesai)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($lembur->status == 'pending')
                                            <span class="badge badge-warning text-white px-3 py-1">Menunggu</span>
                                        @elseif($lembur->status == 'disetujui')
                                            <span class="badge badge-success px-3 py-1">Disetujui</span>
                                        @else
                                            <span class="badge badge-danger px-3 py-1">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="align-middle small text-muted">
                                        {{ $lembur->catatan_admin ?? '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 opacity-25 text-light"></i><br>
                                        Belum ada riwayat pengajuan lembur.
                                    </td>
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