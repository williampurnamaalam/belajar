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
        {{-- FORM PENGAJUAN DANA (SEBELAH KIRI) --}}
        <div class="col-lg-4">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h5 class="font-weight-bold mb-0"><i class="fas fa-wallet mr-2"></i>Form Pengajuan Dana</h5>
                </div>
                <form action="{{ route('dana.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nominal Pengajuan (Rp)</label>
                            <input type="number" name="nominal" class="form-control" placeholder="Contoh: 150000" min="1000" required>
                            <small class="text-muted">Masukkan angka tanpa titik/koma.</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Keperluan / Keterangan</label>
                            <textarea name="keperluan" class="form-control" rows="5" placeholder="Jelaskan rincian penggunaan dana ini..." required></textarea>
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

        {{-- RIWAYAT PENGAJUAN DANA (SEBELAH KANAN) --}}
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
                                    <th>Nominal (Rp)</th>
                                    <th>Keperluan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($danas as $dana)
                                <tr>
                                    <td>{{ $dana->created_at->format('d M Y') }}</td>
                                    <td class="font-weight-bold text-success">
                                        {{ number_format($dana->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="small">
                                        {{ \Illuminate\Support\Str::limit($dana->keperluan, 30) }}
                                    </td>
                                    <td>
                                        @if(strtolower($dana->status) == 'pending')
                                            <span class="badge badge-warning text-white">Menunggu</span>
                                        @elseif(strtolower($dana->status) == 'disetujui' || strtolower($dana->status) == 'approved')
                                            <span class="badge badge-success">Disetujui</span>
                                        @else
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" title="Detail" data-toggle="modal" data-target="#modalDetail{{ $dana->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- MODAL DETAIL UNTUK MELIHAT CATATAN ADMIN --}}
                                <div class="modal fade" id="modalDetail{{ $dana->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title font-weight-bold">Detail Pengajuan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Tanggal Pengajuan:</strong><br> {{ $dana->created_at->format('d F Y H:i') }}</p>
                                                <p><strong>Nominal:</strong><br> Rp {{ number_format($dana->nominal, 0, ',', '.') }}</p>
                                                <p><strong>Keperluan:</strong><br> {{ $dana->keperluan }}</p>
                                                <hr>
                                                <p><strong>Status:</strong><br> 
                                                    @if(strtolower($dana->status) == 'pending')
                                                        <span class="badge badge-warning text-white">Menunggu Persetujuan</span>
                                                    @elseif(strtolower($dana->status) == 'disetujui' || strtolower($dana->status) == 'approved')
                                                        <span class="badge badge-success">Disetujui</span>
                                                    @else
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @endif
                                                </p>
                                                <p><strong>Catatan dari Admin/Pimpinan:</strong><br> 
                                                    <em class="text-muted">{{ $dana->catatan_admin ?? 'Belum ada catatan.' }}</em>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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