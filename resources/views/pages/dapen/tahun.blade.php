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
            <h1>Tahun</h1>
        </div>

        <!-- Tabel Peserta Pensiun -->
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <div class="row">
                        <div class="col-10">
                            <h4>Kelola Tahun</h4>
                        </div>
                        <div class="col btn-group">
                            <button href="#" class="btn btn-secondary" data-toggle="modal" id="tambah" data-target="#modalTambah">Tambah Tahun</button>
                        </div>
                    </div>
                </div>
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <table id="table" class="table table-bordered table-hover" style="width: 100%;">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-1 text-center">No</th>
                                    <th class="col-lg-8 text-center">Tahun</th>
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
    <div class="modal fade" id="modalTambah">
        <input type="hidden" id="id" name="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul_modal">Tambah Tahun</h4>
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
                url: "{{ route('kelolaTahunByStaff') }}"
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

    //Reset Form
    $('#tambah').on('click', function() {
        $("#simpan").removeClass("btn btn-warning")
        $("#simpan").addClass("btn btn-primary")
        $('#simpan').text('Simpan')
        $('#judul_modal').text('Tambah Instansi');
        $("#modalTambah [name='tahun']").val('')
    })

    // Tambah Data
    $('#simpan').on('click', function() {
        if ($(this).text() === 'Simpan') {
            event.preventDefault()
            tambah()
        } else {
            event.preventDefault()
            update()
        }
    })

    function tambah() {
        $('#simpan').text('Simpan')
        $.ajax({
            url: "{{ route('kelolaTahunByStaff_tambah') }}",
            type: "POST",
            data: {
                tahun: $('#tahun').val(),
                "_token": "{{ csrf_token() }}"
            },
            success: function(res) {
                console.log(res);
                $("#table").DataTable().ajax.reload();
                // alert
                Swal.fire(
                    'Sukses',
                    'Data berhasil ditambahkan',
                    'success'
                );
                $("#modalTambah .close").click();
                $("#modalTambah [name='tahun']").val('');
            },
            error: function(err) {
                console.log(err)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi Kesalahan',
                })
                $("#modalTambah [name='tahun']").addClass("is-invalid")
            }
        })
    }

    // Show Update Data     
    $(document).on('click', '.update', function() {
        let id = $(this).attr('id')
        $('#tambah').click()
        $('#simpan').text('Perbaharui Data')
        $('#judul_modal').text('Ubah Data')
        $("#simpan").removeClass("btn btn-primary")
        $("#simpan").addClass("btn btn-warning")
        $.ajax({
            url: "{{ route('kelolaTahunByStaff_show') }}",
            type: 'POST',
            data: {
                id: id,
                "_token": "{{ csrf_token() }}"
            },
            success: function(res) {
                $("#modalTambah [name='id']").val(res.data.id)
                $("#modalTambah [name='tahun']").val(res.data.tahun)
                $("#modalTambah [name='tahun']").removeClass("is-invalid")
            }
        })
    })

    function update() {
        $.ajax({
            url: "{{ route('kelolaTahunByStaff_update') }}",
            type: "POST",
            data: {
                id: $('#id').val(),
                tahun: $('#tahun').val(),
                "_token": "{{ csrf_token() }}"
            },
            success: function(res) {
                console.log(res);
                $("#table").DataTable().ajax.reload();
                // alert
                Swal.fire(
                    'Sukses',
                    'Data berhasil diperbarui',
                    'success'
                );
                $("#modalTambah .close").click();
                $("#modalTambah [name='tahun']").val('');
                $('#simpan').text('Simpan');
            },
            error: function(err) {
                console.log(err)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi Kesalahan',
                })
                $("#modalTambah [name='tahun']").addClass("is-invalid")
            }
        })
    }

    $(document).on('click', '.destroy', function() {
        Swal.fire({
            title: 'Apakah anda yakin ?',
            text: "Data akan dihapus secara permanen",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus data',
            cancelButtonText: 'Batalkan'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Data berhasil dihapus!',
                    'Data telah terhapus secara permanen.',
                    'success'
                )

                let id = $(this).attr('id')
                $.ajax({
                    url: "{{ route('kelolaTahunByStaff_destroy') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function() {
                        $("#table").DataTable().ajax.reload();
                    }
                })
            }
        })
    })
</script>

@endpush