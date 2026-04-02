
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
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                       
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
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    .badge {
        padding: 0.5em 0.8em;
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
