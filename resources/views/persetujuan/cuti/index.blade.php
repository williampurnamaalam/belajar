@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        {{-- FORM PENGAJUAN CUTI --}}
        <div class="col-lg-4">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h5 class="font-weight-bold mb-0"><i class="fas fa-paper-plane mr-2"></i>Form Pengajuan Cuti</h5>
                </div>
                <form action="{{ route('cuti.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Jenis Cuti</label>
                            <select name="jenis_cuti" class="form-control" required>
                                <option value="">-- Pilih Jenis Cuti --</option>
                                <option value="cuti">cuti</option>
                                <option value="sakit">sakit</option>
                                <option value="izin">izin</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Selesai</label>
                                    <input type="date" name="tanggal_selesai" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alasan / Keterangan</label>
                            <textarea name="alasan" class="form-control" rows="3" placeholder="Jelaskan alasan Anda..." required></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block shadow-sm">
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- RIWAYAT PENGAJUAN --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="font-weight-bold mb-0"><i class="fas fa-history mr-2 text-primary"></i>Riwayat Pengajuan Anda</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tgl Pengajuan</th>
                                    <th>Jenis</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatCuti as $cuti)
                                <tr>
                                    <td>{{ $cuti->created_at->format('d M Y') }}</td>
                                    <td><span class="badge badge-light border">{{ $cuti->jenis_cuti }}</span></td>
                                    <td class="small">
                                        {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d/m/y') }} - 
                                        {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d/m/y') }}
                                    </td>
                                    <td>
                                        @if($cuti->status == 'pending')
                                            <span class="badge badge-warning text-white">Menunggu</span>
                                        @elseif($cuti->status == 'disetujui')
                                            <span class="badge badge-success">Disetujui</span>
                                        @else
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" title="Detail" data-toggle="modal" data-target="#modalDetail{{ $cuti->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data pengajuan.</td>
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