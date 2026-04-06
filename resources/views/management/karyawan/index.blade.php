
@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Daftar Seluruh Karyawan</h3>
                <div class="card-tools">
                    <a href="/karyawan/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Karyawan
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table id="table-karyawan" class="table table-hover table-striped table-bordered text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Lengkap</th>
                            <th>NIP / NIK</th>
                            <th>Jabatan</th>
                            <th>Divisi</th>
                            <th>role</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                <tbody>
                    @foreach($karyawans as $key => $k)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $k->profile_picture ? asset('storage/' . $k->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($k->nama) . '&background=0D8ABC&color=fff' }}" 
                                    class="img-circle mr-2" style="width: 30px; height: 30px; object-fit: cover;">
                                <span>{{ $k->nama }}</span>
                            </div>
                        </td>
                        <td>
                            <small class="d-block text-muted">NIP: {{ $k->nip }}</small>
                            <small class="d-block text-muted">NIK: {{ $k->nik }}</small>
                        </td>
                        <td>{{ $k->jabatan->jabatan ?? '-' }}</td>
                        <td>{{ $k->divisi->divisi ?? '-' }}</td>
                        <td>{{ $k->role->role ?? '-' }}</td>
                        <td>
                            <span class="badge badge-success">Aktif</span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('karyawan.show', $k->id) }}" class="btn btn-info btn-sm mx-1 shadow-sm" title="Lihat Profil">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('karyawan.edit', $k->id) }}" class="btn btn-warning btn-sm mx-1 shadow-sm text-white" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('karyawan.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ $k->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mx-1 shadow-sm" title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
        $('#table-karyawan').DataTable({
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
