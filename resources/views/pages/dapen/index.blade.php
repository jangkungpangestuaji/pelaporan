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
            <h1>Dashboard</h1>
        </div>

        <!-- Tabel Peserta Pensiun -->
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <div class="row">
                        <div class="col-10">
                            <h4>Daftar Instansi</h4>
                        </div>
                        <div class="col btn-group">
                            <!-- <button href="#" class="btn btn-secondary" data-toggle="modal" id="tambah" data-target="#modalTambah">Tambah Peserta</button> -->
                            <!-- <a href="/staff/dataPesertaPensiunexport" class="export btn btn-warning">Export Excel</a> -->
                        </div>
                    </div>
                </div>
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <!-- <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-block btn-secondary text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    -- Nama Instansi --
                                </button>
                            </h2>
                        </div> -->

                        <!-- <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample"> -->
                            <table id="table" class="table table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Instansi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        <!-- </div> -->
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
                url: "/staff/{{$type_menu}}"
            },
            columns: [
                {
                    "data": null,
                    "sortable": true,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1
                    }
                },
                {
                    data: 'nama_instansi',
                },
                {
                    data: 'Aksi',
                }
            ]
        })
    }
</script>

@endpush