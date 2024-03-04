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
                            <h4>Tabel Bulan</h4>
                        </div>
                    </div>
                </div>
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <table id="table" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-1 text-center">No</th>
                                    <th class="col-lg-8 text-center">Bulan</th>
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

<!-- Modal Tambah -->
<form enctype="multipart/form-data">
    <div class="modal fade" id="modalVerifikasi">
        <input type="hidden" id="id" name="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul_modal"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tahun">Nama Tahun</label>
                        <input type="text" class="form-control rounded-0 @error('tahun') is-invalid @enderror" id="tahun" name="tahun" value="" placeholder="">
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
        $('#table').DataTable({
            serverside: true,
            responsive: true,
            ajax: {
                url: "/staff/{{$type_menu}}/{{$instansi_id}}/{{$tahun}}"
            },
            columns: [{
                    "data": null,
                    "sortable": true,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1
                    }
                },
                {
                    data: 'bulan',
                },
                {
                    data: 'Aksi',
                }
            ]
        })
    }

    // Show Update Data
    $(document).on('click', '.verifikasi', function() {
        let id = $(this).attr('id')
        $('#tambah').click()
        $('#simpan').text('Simpan')
        $('#judul_modal').text('Verifikasi Data')
        $("#simpan").removeClass("btn btn-primary")
        $("#simpan").addClass("btn btn-warning")
        $.ajax({
            url: "/staff/{{$type_menu}}/{{$instansi_id}}/{{$tahun}}/show",
            type: 'POST',
            data: {
                id: id,
                "_token": "{{ csrf_token() }}"
            },
            success: function(res) {
                $("#modalTambah [name='id']").val(res.data.id)
            }
        })
    })
</script>

@endpush