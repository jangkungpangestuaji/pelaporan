@extends('layouts/app')
@section('title', 'Dashboard')

@push('style')
<!-- CSS Libraries -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Pensiun</h1>
        </div>

        <!-- Tabel Peserta Pensiun -->
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <div class="row">
                        <div class="col-10">
                            <h4>Data Per Bulan</h4>
                        </div>
                        <div class="col btn-group">
                            <a href="{{ url('staff/export_excel/'.$instansi_id.'/'.$tahun.'/'.$bulan) }}" class="btn btn-success my-3" target="_blank">EXPORT EXCEL</a>
                        </div>
                    </div>
                </div>
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <table id="table" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-lg-1 text-center">No</th>
                                    <th class="col-lg-8 text-center">Nama</th>
                                    <th class="col-lg-8 text-center">No Peserta</th>
                                    <th class="col-lg-8 text-center">NIK</th>
                                    <th class="col-lg-8 text-center">Gaji Pokok</th>
                                    <th class="col-lg-8 text-center">Adj. Gaji Pokok</th>
                                    <th class="col-lg-8 text-center">IN Peserta</th>
                                    <th class="col-lg-8 text-center">Rapel IN Peserta</th>
                                    <th class="col-lg-8 text-center">IN PK</th>
                                    <th class="col-lg-8 text-center">Rapel IN PK</th>
                                    <th class="col-lg-8 text-center">Jumlah</th>
                                    <th class="col-sm-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<!-- JS Libraies -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
<!-- Page Specific JS File -->

<script>
    // Tampilkan data
    $(document).ready(function() {
        isi_table()
    })

    function isi_table() {
        $('#table').DataTable({
            serverside: true,
            responsive: true,
            ajax: {
                url: "/staff/dataPesertaPensiun/{{$instansi_id}}/{{$tahun}}/{{$bulan}}"
            },
            columns: [{
                    "data": null,
                    "sortable": true,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1
                    }
                },
                {
                    data: 'nama',
                },
                {
                    data: 'no_peserta',
                },
                {
                    data: 'nik',
                },
                {
                    data: 'gaji_pokok',
                },
                {
                    data: 'adj_gapok',
                },
                {
                    data: 'in_peserta',
                },
                {
                    data: 'rapel_in_peserta',
                },
                {
                    data: 'in_pk',
                },
                {
                    data: 'rapel_in_pk',
                },
                {
                    data: 'jumlah',
                },
                {
                    data: 'Aksi',
                }
            ]
        })
    }
</script>

@endpush