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
                            <h4>Data Peserta Pensiun</h4>
                        </div>
                        <div class="col btn-group">
                            <!-- <button href="#" class="btn btn-secondary" data-toggle="modal" id="tambah" data-target="#modalTambah">Tambah Peserta</button> -->
                            <!-- <a href="/staff/dataPesertaPensiun/{id}/export" type="button" class="btn btn-warning export" id="{{$id}}">Export Excel</a> -->
                            <!-- <button type="button" class="btn btn-warning export">Export Excel</button> -->
                            <a href="{{ url('staff/export_excel/'.$id) }}" class="btn btn-success my-3" target="_blank">EXPORT EXCEL</a>
                            <!-- <button type="button" class="btn btn-warning export" id="{{$id}}">Export Excel</button> -->
                        </div>
                    </div>
                </div>
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-block btn-secondary text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Januari
                                </button>
                            </h2>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <table id="table" class="table table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>No Peserta</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Bulan</th>
                                        <th>Gaji Pokok</th>
                                        <th>Adj. Gaji Pokok</th>
                                        <th>IN Pst</th>
                                        <th>RAPEL IN Pst</th>
                                        <th>IN PK</th>
                                        <th>RAPEL IN PK</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Collapsible Group Item #2
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body">
                                Some placeholder content for the second accordion panel. This panel is hidden by default.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah -->
<form enctype="multipart/form-data">
    <div class="modal fade" id="modalDetail">
        <input type="hidden" id="id" name="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul_modal">Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th scope="row">No</th>
                                <td>
                                    <p id=""></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">No. Peserta</th>
                                <td>
                                    <p id="no_peserta"></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">NIK</th>
                                <td>
                                    <p id="nik"></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Nama</th>
                                <td>
                                    <p id="nama"></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Bulan</th>
                                <td>
                                    <p id="nama_bulan"></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Gaji Pokok</th>
                                <td>
                                    <p id="gaji_pokok"></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Adj. Gaji Pokok</th>
                                <td>
                                    <p id="adj_gapok"></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">IN Pst</th>
                                <td>
                                    <p id="in_peserta"></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">RAPEL IN Pst</th>
                                <td>
                                    <p id="rapel_in_peserta"></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">IN PK</th>
                                <td>
                                    <p id="in_pk"></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">RAPEL IN PK</th>
                                <td>
                                    <p id="rapel_in_pk"></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Jumlah</th>
                                <td>
                                    <p id="jumlah"></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Modal Tambah End -->
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
                url: "/staff/dataPesertaPensiun/{{$id}}"
            },
            columns: [{
                    "data": null,
                    "sortable": true,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1
                    }
                },
                {
                    data: 'no_peserta',
                },
                {
                    data: 'nik',
                },
                {
                    data: 'nama',
                },
                {
                    data: 'nama_bulan',
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

    // Show Update Data     
    $(document).on('click', '.show-data', function() {
        let id = $(this).attr('id')
        $.ajax({
            url: "{{ route('dataPensiun_show') }}",
            type: 'POST',
            data: {
                id: id,
                "_token": "{{ csrf_token() }}"
            },
            success: function(res) {
                console.log(res);
                $("#modalTambah .close").click();
                $("#no_peserta").text(res.data[0].no_peserta)
                $("#nik").text(res.data[0].nik)
                $("#nama").text(res.data[0].nama)
                $("#nama_bulan").text(res.data[0].nama_bulan)
                $("#gaji_pokok").text(res.data[0].gaji_pokok)
                $("#adj_gapok").text(res.data[0].adj_gapok)
                $("#in_peserta").text(res.data[0].in_peserta)
                $("#rapel_in_peserta").text(res.data[0].rapel_in_peserta)
                $("#in_pk").text(res.data[0].in_pk)
                $("#rapel_in_pk").text(res.data[0].rapel_in_pk)
                $("#jumlah").text(res.data[0].jumlah)
            }
        })
    })

    // Export
    $(document).on('click', '.export', function() {
        let id = $(this).attr('id')
        $.ajax({
            url: "/staff/dataPesertaPensiun/{{$id}}/export",
            type: 'POST',
            data: {
                id: id,
                "_token": "{{ csrf_token() }}"
            },
            success: function(res) {
                console.log('Berhasil');
            },
            error: function() {
                console.log('Gagal');
            }
        })
    })
</script>

@endpush