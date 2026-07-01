@extends('layouts.app')

@section('css')
{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<style>
    /* CSS UNTUK TAMPILAN MONITOR */
    table.dataTable { width: 100% !important; margin: 0 !important; }
    .dataTables_filter { text-align: right; }
    .dataTables_paginate { float: right; }
    
    .filter-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        /* PERBAIKAN OVERLAP AGAR BISA DIKLIK */
        position: relative; 
        z-index: 99; 
    }

    /* CSS KHUSUS UNTUK CETAK (PRINT) */
    @media print {
        .no-print, .main-footer, .filter-card, .dataTables_filter, .dataTables_length, 
        .dataTables_paginate, .dataTables_info, .btn, .main-header, .main-sidebar {
            display: none !important;
        }
        .card { border: none !important; box-shadow: none !important; }
        .content-wrapper { background: white !important; margin: 0 !important; padding: 0 !important; }
        table { width: 100% !important; border-collapse: collapse; }
        th, td { border: 1px solid #333 !important; padding: 8px !important; vertical-align: top !important; }
        tfoot th { background-color: #f2f2f2 !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-primary">
                    <i class="fas fa-tasks mr-2"></i>Monitoring Laporan Tugas Kerja
                </h5>
            </div>
        </div>
        
        <div class="card-body">
            {{-- BOX FILTER --}}
            <div class="filter-card p-3 mb-4 no-print">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="small font-weight-bold">Pilih Karyawan</label>
                        <select id="filterKaryawan" class="form-control custom-select">
                            <option value="">Semua Karyawan</option>
                            @foreach($karyawans as $k)
                                <option value="{{ $k->nama }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold">Bulan</label>
                        <select id="filterBulan" class="form-control custom-select">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold">Tahun</label>
                        <select id="filterTahun" class="form-control custom-select">
                            <option value="">Semua Tahun</option>
                            @for ($y = date('Y'); $y >= date('Y')-2; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2 mb-2 d-flex align-items-end">
                        <button id="resetFilter" class="btn btn-secondary btn-block">
                            <i class="fas fa-undo mr-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            {{-- TABEL UTAMA --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tableMonitoring">
                    <thead class="thead-light text-center">
                        <tr>
                            <th width="12%">Tanggal</th>
                            <th width="15%">Nama Karyawan</th>
                            <th>Judul & Deskripsi</th>
                            <th width="15%">Nominal (Rp)</th>
                            <th width="8%">Bukti</th>
                            <th width="10%" class="no-print">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daftarLaporan as $row)
                        <tr>
                            <td data-order="{{ $row->tanggal_kirim }}" class="text-center">
                                {{ \Carbon\Carbon::parse($row->tanggal_kirim)->format('d/m/Y') }}
                            </td>
                            <td class="font-weight-bold text-capitalize">{{ $row->user->nama ?? 'Unknown' }}</td>
                            
                            <td>
                                <strong>{{ $row->judul_laporan }}</strong>
                                <hr class="mt-1 mb-1" style="border-top: 1px dashed #ccc;">
                                <div style="white-space: pre-line;" class="small text-muted">{{ $row->deskripsi }}</div>
                            </td>

                            <td data-nominal="{{ $row->nominal_transaksi ?? 0 }}" class="text-right font-weight-bold text-success">
                                Rp {{ number_format($row->nominal_transaksi ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="text-center">
                                @if($row->file)
                                    <a href="{{ asset('storage/laporan_tugas/'.$row->file) }}" target="_blank" class="badge badge-info p-2 no-print" title="Lihat Bukti">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                @else
                                    <span class="text-muted small">Kosong</span>
                                @endif
                            </td>
                            <td class="text-center no-print">
                                {{-- LINK BARU MENUJU HALAMAN DETAIL MANDIRI --}}
                                <a href="{{ route('admin.laporan.show', $row->id) }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="3" class="text-right h6 font-weight-bold pt-3 pb-3">TOTAL PENDAPATAN (BERDASARKAN FILTER):</th>
                            <th id="totalPendapatan" class="text-right h6 font-weight-bold text-success pt-3 pb-3">Rp 0</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#tableMonitoring').DataTable({
            "order": [[0, "desc"]],
            "responsive": true,
            "autoWidth": false,
            "language": {
                "search": "Cari Cepat:",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            },
            
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
                var total = 0;
                
                api.rows({ filter: 'applied' }).nodes().each(function(node) {
                    var nominal = $(node).find('td:eq(3)').data('nominal');
                    total += parseFloat(nominal) || 0;
                });

                var formatRupiah = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', currency: 'IDR', minimumFractionDigits: 0 
                }).format(total);

                $('#totalPendapatan').html(formatRupiah);
            }
        });

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var selectedKaryawan = $('#filterKaryawan').val().toLowerCase();
            var selectedBulan = $('#filterBulan').val();
            var selectedTahun = $('#filterTahun').val();

            var rowKaryawan = data[1].toLowerCase(); 
            var rawDate = table.row(dataIndex).node().cells[0].getAttribute('data-order');
            
            if (!rawDate) return true;
            var parts = rawDate.split('-'); 
            
            var matchKaryawan = (selectedKaryawan === "") || (rowKaryawan.includes(selectedKaryawan));
            var matchBulan = (selectedBulan === "") || (selectedBulan === parts[1]);
            var matchTahun = (selectedTahun === "") || (selectedTahun === parts[0]);

            return matchKaryawan && matchBulan && matchTahun;
        });

        $('#filterKaryawan, #filterBulan, #filterTahun').on('change', function() {
            table.draw();
        });
    
        $('#resetFilter').on('click', function() {
            $('#filterKaryawan, #filterBulan, #filterTahun').val('').trigger('change');
        });
    });

    function bukaTabCetak() {
        let karyawan = $('#filterKaryawan').val();
        let bulan = $('#filterBulan').val();
        let tahun = $('#filterTahun').val();

        let url = `{{ route('admin.laporan.cetak') }}?karyawan=${karyawan}&bulan=${bulan}&tahun=${tahun}`;
        window.open(url, '_blank');
    }
</script>
@endpush