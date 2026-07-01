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

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
            <i class="fas fa-tasks text-primary mr-2"></i>Kelola Pengajuan Dana
        </h1>
    </div>

    {{-- TABEL SEMUA PENGAJUAN --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengajuan dari Karyawan</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-center">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Nama Karyawan</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%">Nominal (Rp)</th>
                            <th width="20%">Keperluan</th>
                            <th width="15%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($danas as $index => $dana)
                        <tr>
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle font-weight-bold text-dark text-left">
                                {{ $dana->user->nama ?? 'Karyawan Terhapus' }}
                            </td>
                            <td class="align-middle">{{ $dana->created_at->format('d M Y') }}</td>
                            <td class="align-middle font-weight-bold text-success">
                                {{ number_format($dana->nominal, 0, ',', '.') }}
                            </td>
                            <td class="align-middle text-left small">
                                {{ \Illuminate\Support\Str::limit($dana->keperluan, 30) }}
                            </td>
                            <td class="align-middle">
                                @if(strtolower($dana->status) == 'pending')
                                    <span class="badge badge-warning text-white">Menunggu</span>
                                @elseif(strtolower($dana->status) == 'disetujui')
                                    <span class="badge badge-success">Disetujui</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalProses{{ $dana->id }}">
                                    <i class="fas fa-edit"></i> Proses
                                </button>
                            </td>
                        </tr>

                        {{-- MODAL PROSES PERSETUJUAN (KHUSUS ADMIN) --}}
                        <div class="modal fade" id="modalProses{{ $dana->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title font-weight-bold">Tinjau Pengajuan Dana</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.dana.update', $dana->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4 bg-light">
                                            
                                            <div class="mb-3 p-3 bg-white border rounded shadow-sm">
                                                <small class="text-muted d-block mb-1">Diajukan oleh:</small>
                                                <h6 class="font-weight-bold mb-2">{{ $dana->user->nama ?? '-' }}</h6>
                                                
                                                <small class="text-muted d-block mb-1">Nominal:</small>
                                                <h5 class="font-weight-bold text-success mb-2">Rp {{ number_format($dana->nominal, 0, ',', '.') }}</h5>
                                                
                                                <small class="text-muted d-block mb-1">Keperluan Lengkap:</small>
                                                <p class="mb-0 text-dark" style="font-size: 14px;">{{ $dana->keperluan }}</p>
                                            </div>

                                            <div class="form-group">
                                                <label class="font-weight-bold">Ubah Status <span class="text-danger">*</span></label>
                                                <select name="status" class="form-control font-weight-bold" required>
                                                    <option value="Pending" {{ strtolower($dana->status) == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                                    <option value="Disetujui" {{ strtolower($dana->status) == 'disetujui' ? 'selected' : '' }}>Setujui (Approve)</option>
                                                    <option value="Ditolak" {{ strtolower($dana->status) == 'ditolak' ? 'selected' : '' }}>Tolak (Reject)</option>
                                                </select>
                                            </div>

                                            <div class="form-group mt-3">
                                                <label class="font-weight-bold">Catatan Pimpinan / Admin (Opsional)</label>
                                                <textarea name="catatan_admin" class="form-control" rows="3" placeholder="Misal: Dana bisa diambil di kasir besok siang...">{{ $dana->catatan_admin }}</textarea>
                                            </div>

                                        </div>
                                        <div class="modal-footer bg-white">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Keputusan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-check-double fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                                <h6 class="text-muted font-weight-bold">Belum ada pengajuan dana dari karyawan.</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection