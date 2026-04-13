@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan Lembur')

@section('content')
<div class="row">
    <div class="col-12">
        {{-- ALERT STATUS --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-left: 5px solid #28a745 !important;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle mr-3 fa-lg"></i>
                    <div>
                        <span class="font-weight-bold">Berhasil!</span> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card card-outline card-info shadow-sm">
            <div class="card-header">
                <h3 class="card-title text-bold text-dark">
                    <i class="fas fa-business-time text-info mr-2"></i> Daftar Persetujuan Lembur Karyawan
                </h3>
            </div>
            <div class="card-body table-responsive">
                <table id="table-lembur" class="table table-hover table-striped table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%" class="text-center">NO</th>
                            <th width="20%">KARYAWAN</th>
                            <th width="15%">TANGGAL LEMBUR</th>
                            <th width="30%">WAKTU & TARGET PEKERJAAN</th>
                            <th width="15%" class="text-center">STATUS</th>
                            <th width="15%" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daftarLembur as $key => $row)
                        <tr>
                            <td class="text-center align-middle">{{ $key + 1 }}</td>
                            <td class="align-middle text-nowrap">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm mr-3 bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:35px; height:35px; font-weight: bold; font-size: 0.8rem;">
                                        {{ strtoupper(substr($row->user->nama ?? 'K', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-weight-bold d-block text-dark">{{ $row->user->nama ?? 'N/A' }}</span>
                                        <small class="text-muted">{{ $row->user->jabatan->jabatan ?? 'Staff' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle text-nowrap">
                                <span class="font-weight-bold">{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}</span><br>
                                <small class="text-muted">Diajukan: {{ $row->created_at->format('d/m/y H:i') }}</small>
                            </td>
                            <td class="align-middle">
                                <div class="font-weight-bold text-info mb-1">
                                    <i class="far fa-clock mr-1"></i> 
                                    {{ \Carbon\Carbon::parse($row->jam_mulai)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($row->jam_selesai)->format('H:i') }} WIB
                                </div>
                                <small class="text-muted text-wrap d-block" style="min-width: 200px;">
                                    <strong>Target:</strong> {{ $row->alasan }}
                                </small>
                            </td>
                            <td class="align-middle text-center">
                                @if($row->status == 'pending')
                                    <span class="badge badge-warning text-white px-3 py-1 shadow-sm">
                                        <i class="fas fa-spinner fa-spin mr-1"></i> MENUNGGU
                                    </span>
                                @elseif($row->status == 'disetujui')
                                    <span class="badge badge-success px-3 py-1 shadow-sm text-white">
                                        <i class="fas fa-check-circle mr-1"></i> DISETUJUI
                                    </span>
                                @else
                                    <span class="badge badge-danger px-3 py-1 shadow-sm text-white">
                                        <i class="fas fa-times-circle mr-1"></i> DITOLAK
                                    </span>
                                    <small class="d-block mt-1 text-muted">{{ $row->catatan_admin }}</small>
                                @endif
                            </td>
                            <td class="text-center align-middle text-nowrap">
                                @if($row->status == 'pending')
                                    <div class="btn-group">
                                        {{-- Form Setuju --}}
                                        <form action="{{ route('admin.lembur.approve', $row->id) }}" method="POST" onsubmit="return confirm('Setujui pengajuan lembur ini?')">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit" class="btn btn-success btn-sm shadow-sm" title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        {{-- Button Tolak --}}
                                        <button type="button" class="btn btn-danger btn-sm shadow-sm" data-toggle="modal" data-target="#modal-tolak-{{ $row->id }}" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @else
                                    <button class="btn btn-light btn-sm border disabled"><i class="fas fa-lock text-muted"></i></button>
                                @endif
                            </td>
                        </tr>

                        {{-- MODAL TOLAK --}}
                        @if($row->status == 'pending')
                        <div class="modal fade" id="modal-tolak-{{ $row->id }}" tabindex="-1" role="dialog" data-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Penolakan</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                    </div>
                                    <form action="{{ route('admin.lembur.approve', $row->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="ditolak">
                                        <div class="modal-body p-4 text-wrap">
                                            <div class="form-group text-left">
                                                <label class="font-weight-bold text-dark">Karyawan: {{ $row->user->nama }}</label>
                                                <p class="mb-1 text-info small">
                                                    <i class="far fa-clock"></i> Rencana: {{ \Carbon\Carbon::parse($row->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($row->jam_selesai)->format('H:i') }}
                                                </p>
                                                <hr>
                                                <p class="text-muted small">Berikan alasan mengapa pengajuan lembur ini ditolak:</p>
                                                <textarea name="catatan_admin" class="form-control" rows="4" placeholder="Contoh: Overtime tidak diperlukan untuk task ini, harap diselesaikan besok pagi..." required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm">Konfirmasi Tolak</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<style>
    .table thead th {
        border-top: 0;
        border-bottom-width: 1px;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 12px 15px;
    }
    .table td {
        vertical-align: middle !important;
        padding: 12px 15px;
        color: #495057;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(23, 162, 184, 0.05); /* Sedikit kebiruan sesuai tema info */
    }
    .text-wrap {
        white-space: normal !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(function () {
        $('#table-lembur').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
            }
        });
    });
</script>
@endpush