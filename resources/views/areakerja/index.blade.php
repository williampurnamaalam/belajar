@extends('layouts.app')

@section('title', 'Data Area Kerja')

@section('content')
<div class="row">
    <div class="col-12">
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

        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title text-bold text-dark">Daftar Seluruh Area Kerja</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modal-tambah">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Area
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table id="table-area" class="table table-hover table-striped table-bordered text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%" class="text-center">NO</th>
                            <th>LOKASI</th>
                            <th class="text-center">JUMLAH KARYAWAN</th> {{-- Kolom Baru --}}
                            <th>DETAIL INFORMASI</th>
                            <th width="15%" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($areakerjas as $key => $area)
                        <tr>
                            <td class="text-center align-middle">{{ $key + 1 }}</td>
                            <td class="align-middle font-weight-bold text-dark">{{ $area->lokasi }}</td>
                            <td class="text-center align-middle">
                                <span class="badge badge-pill badge-primary px-3 shadow-sm">
                                    <i class="fas fa-users mr-1"></i> {{ $area->karyawans->count() }} Orang
                                </span>
                            </td>
                            <td class="align-middle text-muted small">{{ Str::limit($area->detail ?? '-', 40) }}</td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <a href="{{ route('areakerja.insert', $area->id) }}" class="btn btn-success btn-sm shadow-sm" title="Kelola Tim Area">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                    <a href="{{ route('areakerja.show', $area->id) }}" class="btn btn-info btn-sm shadow-sm" title="Lihat Detail Halaman">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-warning btn-sm shadow-sm" data-toggle="modal" data-target="#modal-edit-{{ $area->id }}" title="Edit">
                                        <i class="fas fa-edit text-white"></i>
                                    </button>
                                    <form action="{{ route('areakerja.destroy', $area->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus area {{ $area->lokasi }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm shadow-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL & EDIT (LOOPING) --}}
@foreach($areakerjas as $key => $area)
    {{-- Modal Show --}}
    <div class="modal fade" id="modal-show-{{ $area->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-info-circle mr-2"></i> Detail Area Kerja</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-sm mb-0">
                        <tr><th class="pl-4 py-3" width="40%">ID</th><td class="py-3">: #{{ $area->id }}</td></tr>
                        <tr><th class="pl-4 py-3">Nama Lokasi</th><td class="py-3">: {{ $area->lokasi }}</td></tr>
                        <tr><th class="pl-4 py-3">Detail Informasi</th><td class="py-3">: {{ $area->detail ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="modal-edit-{{ $area->id }}" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Area Kerja</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('areakerja.update', $area->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Nama Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" value="{{ $area->lokasi }}" required>
                        </div>
                        <div class="form-group mt-3">
                            <label class="font-weight-bold">Detail Informasi</label>
                            <textarea name="detail" class="form-control" rows="3">{{ $area->detail }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Tambah Area Kerja Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('areakerja.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi') }}" placeholder="Contoh: Gedung A Lantai 1" required>
                        @error('lokasi') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label class="font-weight-bold">Detail Informasi</label>
                        <textarea name="detail" class="form-control @error('detail') is-invalid @enderror" rows="3" placeholder="Keterangan tambahan...">{{ old('detail') }}</textarea>
                        @error('detail') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Area</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<style>
    /* Styling Header Tabel */
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

    /* Datatables Customization */
    .dataTables_wrapper .dataTables_filter { padding: 1rem; }
    .dataTables_wrapper .dataTables_info, 
    .dataTables_wrapper .dataTables_paginate { padding: 1rem; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(function () {
        $('#table-area').DataTable({
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

    @if($errors->any())
        $('#modal-tambah').modal('show');
    @endif
</script>
@endpush