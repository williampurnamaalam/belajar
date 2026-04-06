@extends('layouts.app')

@section('title', 'Detail Area Kerja')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <i class="fas fa-map-marked-alt fa-3x text-primary"></i>
                    </div>
                    <h3 class="profile-username text-center font-weight-bold text-uppercase">{{ $area->lokasi }}</h3>
                    <p class="text-muted text-center small mb-4">ID Area: #{{ $area->id }}</p>
                    
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item border-top-0">
                            <b>Total Personil</b> 
                            <span class="float-right badge badge-primary p-2">{{ $area->karyawans->count() }} Orang</span>
                        </li>
                    </ul>
                    <div class="mt-4">
                        <strong class="text-dark"><i class="fas fa-info-circle mr-1 text-primary"></i> Deskripsi Lokasi</strong>
                        <p class="text-muted small mt-2 bg-light p-3 rounded border">
                            {{ $area->detail ?? 'Tidak ada informasi tambahan untuk area ini.' }}
                        </p>
                    </div>
                    <a href="{{ route('areakerja') }}" class="btn btn-outline-secondary btn-block mt-3 shadow-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali 
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-users-cog mr-2 text-primary"></i>Daftar Personil Aktif
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-personil-area" class="table table-hover w-100">
                            <thead>
                                <tr class="bg-light">
                                    <th>NAMA KARYAWAN</th>
                                    <th>JABATAN</th>
                                    <th>DIVISI</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($area->karyawans as $karyawan)
                                <tr>
                                    <td class="align-middle">
                                        <div class="font-weight-bold text-primary">{{ $karyawan->nama }}</div>
                                        <small class="text-muted">{{ $karyawan->nip ?? 'No NIP' }}</small>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge badge-info shadow-xs">{{ $karyawan->jabatan->jabatan ?? '-' }}</span>
                                    </td>
                                    <td class="align-middle text-muted">
                                        {{ $karyawan->divisi->divisi ?? '-' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route('karyawan.show', $karyawan->id) }}" class="btn btn-light btn-sm border shadow-sm" title="Lihat Profil">
                                            <i class="fas fa-external-link-alt text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Library DataTables Bootstrap 4 --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<style>
    #table-personil-area thead th {
        border-top: none;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        color: #555;
    }
    .dataTables_filter input {
        border-radius: 20px;
        padding-left: 15px;
        border: 1px solid #ddd;
    }
    .dataTables_filter input:focus {
        box-shadow: 0 0 5px rgba(0,123,255,.25);
        border-color: #80bdff;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table-personil-area').DataTable({
            "responsive": true,
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Cari nama karyawan...",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ personil",
                "zeroRecords": "Tidak ada personil yang ditemukan",
                "paginate": {
                    "previous": "<i class='fas fa-angle-left'></i>",
                    "next": "<i class='fas fa-angle-right'></i>"
                }
            },
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        });
    });
</script>
@endpush