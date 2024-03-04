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
                            <h4>Daftar Tahun</h4>
                        </div>
                        <div class="col btn-group">
                            <!-- <button href="#" class="btn btn-secondary" data-toggle="modal" id="tambah" data-target="#modalTambah">Tambah Peserta</button> -->
                            <!-- <a href="#" class="btn btn-warning">Import Excel</a> -->
                        </div>
                    </div>
                </div>
                <table id="table1" class="table table-bordered table-striped table-hover" style="width: 100%;">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Tahun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah -->
<form enctype="multipart/form-data">
    <div class="modal fade" id="modalTambah">
        <input type="hidden" id="id" name="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul_modal">Tambah Data Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="noPeserta">No. Peserta</label>
                        <input type="text" class="form-control rounded-0 @error('noPeserta') is-invalid @enderror" id="noPeserta" name="noPeserta" value="" placeholder="No. Peserta">
                    </div>
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="text" class="form-control rounded-0 @error('nik') is-invalid @enderror" id="nik" name="nik" value="" placeholder="NIK">
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control rounded-0 @error('nama') is-invalid @enderror" id="nama" name="nama" value="" placeholder="Nama">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="simpan" class="btn btn-primary">Simpan</button>
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
        $('#table1').DataTable({
            serverside: true,
            responsive: true,
            ajax: {
                url: "{{ route('uploadBuktiPembayaran') }}"
            },
            columns: [{
                    "data": null,
                    "sortable": true,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1
                    }
                },
                {
                    data: 'tahun',
                },
                {
                    data: 'Aksi',
                }
            ]
        })
    }
</script>

@endpush
@push('addon-script')

@endpush