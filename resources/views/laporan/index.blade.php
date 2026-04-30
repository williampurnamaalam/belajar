@extends('layouts.app')

@section('css')
{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<style>
    .dataTables_filter { text-align: right; }
    .dataTables_paginate { float: right; }
    .custom-filter-box {
        background: #f4f6f9;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    {{-- ALERT PESAN --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        {{-- 1. FORM KIRIM LAPORAN --}}
        <div class="col-lg-4">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-upload mr-2"></i>Kirim Laporan Kerja</h5>
                </div>
                <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Judul Tugas / Transaksi</label>
                            <input type="text" name="judul_laporan" class="form-control" placeholder="Contoh: Penjualan Magia 2" required>
                        </div>
                        <div class="form-group">
                            <label>Nominal Transaksi (Rp)</label>
                            <input type="number" name="nominal_transaksi" class="form-control" placeholder="Contoh: 1500000" min="0">
                            <small class="text-muted">Kosongkan jika bukan laporan penjualan</small>
                        </div>
                        <div class="form-group">
                            <label>Keterangan / Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan detail tugas..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Lampiran File</label>
                            <div class="custom-file">
                                <input type="file" name="file_bukti" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Pilih file...</label>
                            </div>
                            <small class="text-muted">Format: JPG, PNG, PDF, DOCX (Max 5MB)</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block shadow-sm font-weight-bold">
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 2. RIWAYAT LAPORAN --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold text-dark">Riwayat Laporan Tugas</h5>
                </div>
                <div class="card-body">
                    
                    {{-- CUSTOM FILTER JS BOX --}}
                    <div class="custom-filter-box">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-muted">FILTER BULAN</label>
                                    <select id="filterBulan" class="form-control form-control-sm">
                                        <option value="">Semua Bulan</option>
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">
                                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-muted">FILTER TAHUN</label>
                                    <select id="filterTahun" class="form-control form-control-sm">
                                        <option value="">Semua Tahun</option>
                                        @for ($y = date('Y'); $y >= date('Y')-2; $y--)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="tableLaporan">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Judul</th>
                                    <th>File</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporan as $row)
                                <tr>
                                    {{-- Kolom Tanggal dengan data-order YYYY-MM-DD untuk filter JS --}}
                                    <td data-order="{{ $row->tanggal_kirim }}">
                                        {{ \Carbon\Carbon::parse($row->tanggal_kirim)->format('d/m/Y') }}
                                    </td>
                                    <td><strong>{{ $row->judul_laporan }}</strong></td>
                                    <td>
                                        @if($row->file)
                                            <a href="{{ asset('storage/laporan_tugas/'.$row->file) }}" target="_blank" class="badge badge-info p-2 shadow-xs">
                                                <i class="fas fa-file-download mr-1"></i> Lihat File
                                            </a>
                                        @else
                                            <span class="text-muted small italic">No File</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm">
                                            <button class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#detailModal{{$row->id}}" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <form action="{{ route('laporan.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL DETAIL --}}
                                <div class="modal fade" id="detailModal{{$row->id}}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title font-weight-bold"><i class="fas fa-info-circle mr-2"></i> Detail Laporan</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body p-4 text-dark">
                                                <h6 class="font-weight-bold">Judul Tugas:</h6>
                                                <p>{{ $row->judul_laporan }}</p>
                                                <hr>
                                                <h6 class="font-weight-bold">Isi Laporan / Keterangan:</h6>
                                                <p style="white-space: pre-line;">{{ $row->deskripsi }}</p>
                                                <hr>
                                                <small class="text-muted italic">Sent Date: {{ \Carbon\Carbon::parse($row->created_at)->format('d M Y, H:i') }}</small>
                                            </div>
                                            <div class="modal-footer bg-light py-2">
                                                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

@push('scripts')
{{-- DataTables JS --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. Inisialisasi DataTables
        var table = $('#tableLaporan').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "order": [[0, "desc"]], // Default terbaru
            "language": {
                "search": "Cari Cepat:",
                "zeroRecords": "Data tidak ditemukan",
                "paginate": { "next": ">", "previous": "<" }
            }
        });

        // 2. Custom Filter Logic (Bulan & Tahun)
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var selectedBulan = $('#filterBulan').val(); 
                var selectedTahun = $('#filterTahun').val(); 
                
                // Ambil tanggal format YYYY-MM-DD dari atribut data-order sel pertama (index 0)
                var rawDate = table.row(dataIndex).node().cells[0].getAttribute('data-order');
                
                if (!rawDate) return true;

                var dateArray = rawDate.split('-'); 
                var rowTahun = dateArray[0];
                var rowBulan = dateArray[1];

                var matchBulan = (selectedBulan === "") || (selectedBulan === rowBulan);
                var matchTahun = (selectedTahun === "") || (selectedTahun === rowTahun);

                return matchBulan && matchTahun;
            }
        );

        // 3. Event Listener saat Filter berubah
        $('#filterBulan, #filterTahun').on('change', function() {
            table.draw();
        });

        // 4. Update nama file di label input bootstrap
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush