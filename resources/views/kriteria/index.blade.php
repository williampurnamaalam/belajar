@extends('layouts.app')

@section('css')
{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<style>
    table.dataTable { width: 100% !important; margin: 0 !important; }
    .dataTables_filter { text-align: right; }
    .dataTables_paginate { float: right; }
    .badge-benefit { background-color: #28a745; color: white; }
    .badge-cost { background-color: #dc3545; color: white; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    
    {{-- ALERT TOTAL BOBOT --}}
    @if($totalBobot == 100)
        <div class="alert alert-success shadow-sm border-0 alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> <strong>Sempurna!</strong> Total bobot kriteria saat ini sudah <strong>100%</strong>. Sistem penilaian SAW siap digunakan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @else
        <div class="alert alert-warning shadow-sm border-0 alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Perhatian!</strong> Total bobot kriteria saat ini adalah <strong>{{ $totalBobot }}%</strong>. Aturan SAW mewajibkan total keseluruhan bobot harus tepat <strong>100%</strong>.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    {{-- ALERT NOTIFIKASI BERHASIL --}}
    @if(session('success'))
        <div class="alert alert-info shadow-sm border-0 alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-primary">
                    <i class="fas fa-list-ol mr-2"></i>Master Data Kriteria (SAW)
                </h5>
                <button class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modalTambahKriteria">
                    <i class="fas fa-plus mr-1"></i> Tambah Kriteria
                </button>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tableKriteria">
                    <thead class="thead-light text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Kode Kriteria</th>
                            <th>Nama Kriteria</th>
                            <th width="15%">Sifat / Jenis</th>
                            <th width="15%">Bobot (%)</th>
                            <th width="15%">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriteria as $index => $row)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center font-weight-bold">{{ $row->kode_kriteria }}</td>
                            <td>{{ $row->nama_kriteria }}</td>
                            <td class="text-center">
                                @if($row->jenis == 'Benefit')
                                    <span class="badge badge-benefit px-3 py-2"><i class="fas fa-arrow-up mr-1"></i> Benefit</span>
                                @else
                                    <span class="badge badge-cost px-3 py-2"><i class="fas fa-arrow-down mr-1"></i> Cost</span>
                                @endif
                            </td>
                            <td class="text-center font-weight-bold h6 text-primary">{{ $row->bobot }}%</td>
                            <td class="text-center">
                                {{-- Tombol Edit --}}
                                <button class="btn btn-sm btn-warning shadow-sm" data-toggle="modal" data-target="#modalEditKriteria{{ $row->id }}" title="Edit Kriteria">
                                    <i class="fas fa-edit text-white"></i>
                                </button>
                                
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('kriteria.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria ini? Menghapus kriteria dapat mempengaruhi hasil perhitungan SAW.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Hapus Kriteria">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================= --}}
{{-- MODAL TAMBAH KRITERIA --}}
{{-- ======================================================= --}}
<div class="modal fade" id="modalTambahKriteria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Tambah Kriteria Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('kriteria.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Kode Kriteria</label>
                        <input type="text" name="kode_kriteria" class="form-control" placeholder="Contoh: C1" required>
                        <small class="text-muted">Gunakan format C1, C2, C3, dst.</small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control" placeholder="Contoh: Absensi" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Sifat / Jenis Kriteria</label>
                        <select name="jenis" class="form-control custom-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Benefit">Benefit (Semakin besar nilai, semakin bagus)</option>
                            <option value="Cost">Cost (Semakin besar nilai, semakin buruk)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Bobot (%)</label>
                        <input type="number" name="bobot" class="form-control" placeholder="Contoh: 20" min="1" max="100" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================= --}}
{{-- MODAL EDIT KRITERIA --}}
{{-- ======================================================= --}}
@foreach($kriteria as $row)
<div class="modal fade" id="modalEditKriteria{{ $row->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i>Edit Kriteria</h5>
                <button type="button" class="close text-dark" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('kriteria.update', $row->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Kode Kriteria</label>
                        <input type="text" name="kode_kriteria" class="form-control" value="{{ $row->kode_kriteria }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control" value="{{ $row->nama_kriteria }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Sifat / Jenis Kriteria</label>
                        <select name="jenis" class="form-control custom-select" required>
                            <option value="Benefit" {{ $row->jenis == 'Benefit' ? 'selected' : '' }}>Benefit (Semakin besar nilai, semakin bagus)</option>
                            <option value="Cost" {{ $row->jenis == 'Cost' ? 'selected' : '' }}>Cost (Semakin besar nilai, semakin buruk)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Bobot (%)</label>
                        <input type="number" name="bobot" class="form-control" value="{{ $row->bobot }}" min="1" max="100" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning font-weight-bold"><i class="fas fa-save mr-1"></i> Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tableKriteria').DataTable({
            "order": [[1, "asc"]], // Urutkan berdasarkan Kode Kriteria (C1, C2, dst)
            "responsive": true,
            "autoWidth": false,
            "language": {
                "search": "Cari Kriteria:",
                "zeroRecords": "Kriteria tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ kriteria"
            }
        });
    });
</script>
@endpush