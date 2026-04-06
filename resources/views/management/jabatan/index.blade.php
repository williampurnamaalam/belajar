@extends('layouts.app')

@section('title', 'Data Jabatan')

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
                <h3 class="card-title text-bold text-dark">Daftar Seluruh Jabatan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modal-tambah">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Jabatan
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table id="table-jabatan" class="table table-hover table-striped table-bordered text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%" class="text-center">NO</th>
                            <th>NAMA JABATAN</th>
                            <th width="15%" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jabatans as $key => $j)
                        <tr>
                            <td class="text-center align-middle">{{ $key + 1 }}</td>
                            <td class="align-middle font-weight-bold text-dark">{{ $j->jabatan }}</td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <button class="btn btn-info btn-sm shadow-sm" data-toggle="modal" data-target="#modal-show-{{ $j->id }}" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm shadow-sm" data-toggle="modal" data-target="#modal-edit-{{ $j->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('jabatan.destroy', $j->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jabatan {{ $j->jabatan }}?')">
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


@foreach($jabatans as $key => $j)
    <div class="modal fade" id="modal-show-{{ $j->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-info-circle mr-2"></i> Detail Jabatan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-sm mb-0">
                        <tr><th class="pl-4 py-3" width="40%">ID</th><td class="py-3">: #{{ $j->id }}</td></tr>
                        <tr><th class="pl-4 py-3">Nama Jabatan</th><td class="py-3">: {{ $j->jabatan }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit-{{ $j->id }}" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Jabatan</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('jabatan.update', $j->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Nama Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="{{ $j->jabatan }}" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach


<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Tambah Jabatan Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('jabatan.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Jabatan</label>
                        <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}" required>
                        @error('jabatan') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary">Simpan Jabatan</button>
                </div>
            </form>
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

    .btn-xs-custom {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-xs-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.12) !important;
        filter: brightness(90%);
    }

    .badge {
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 4px;
    }

    .dataTables_wrapper .dataTables_filter {
        padding: 1rem;
    }
    .dataTables_wrapper .dataTables_info, 
    .dataTables_wrapper .dataTables_paginate {
        padding: 1rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(function () {
        $('#table-jabatan').DataTable({
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