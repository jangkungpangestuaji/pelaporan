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
                            <h4>Daftar Peserta Pensiun</h4>
                        </div>
                        <div class="col btn-group">
                            <button href="#" class="btn btn-secondary" data-toggle="modal" id="tambah" data-target="#modalTambah">Tambah Peserta</button>
                        </div>
                    </div>
                </div>
                <table id="table1" class="table table-bordered table-striped table-hover" style="width: 100%;">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>No Peserta</th>
                            <th>NIK</th>
                            <th>Nama</th>
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
                url: "{{ route('dataPesertaPensiun') }}"
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
        $('#judul_modal').text('Tambah Data Baru');
        $("#modalTambah [name='nama']").val('')
        $("#modalTambah [name='noPeserta']").val('')
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
            url: "{{ route('dataPesertaPensiun_tambah') }}",
            type: "POST",
            data: {
                instansi_id: '{{Auth::user()->instansi_id}}',
                no_peserta: $('#noPeserta').val(),
                nik: $('#nik').val(),
                nama: $('#nama').val(),
                "_token": "{{ csrf_token() }}"
            },
            success: function(res) {
                console.log(res);
                $("#table1").DataTable().ajax.reload();
                // alert
                Swal.fire(
                    'Sukses',
                    'Data berhasil ditambahkan',
                    'success'
                );
                $("#modalTambah .close").click();
                $("#modalTambah [name='instansi_id']").val('');
                $("#modalTambah [name='noPeserta']").val('');
                $("#modalTambah [name='nik']").val('');
                $("#modalTambah [name='nama']").val('');
            },
            error: function(err) {
                console.log(err)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi Kesalahan',
                })
                $("#modalTambah [name='noPeserta']").addClass("is-invalid")
                $("#modalTambah [name='nama']").addClass("is-invalid")
                $("#modalTambah [name='nik']").addClass("is-invalid")
            }
        })
    }

    // Show Update Data     
    $(document).on('click', '.update', function() {
        // console.log('terbuka')
        let id = $(this).attr('id')
        $('#tambah').click()
        $('#simpan').text('Perbaharui Data')
        $('#judul_modal').text('Ubah Data')
        $("#simpan").removeClass("btn btn-primary")
        $("#simpan").addClass("btn btn-warning")
        $.ajax({
            url: "{{ route('dataPesertaPensiun_show') }}",
            type: 'POST',
            data: {
                id: id,
                "_token": "{{ csrf_token() }}"
            },
            success: function(res) {
                $("#modalTambah [name='id']").val(res.data.id)
                $("#modalTambah [name='noPeserta']").val(res.data.no_peserta)
                $("#modalTambah [name='nik']").val(res.data.nik)
                $("#modalTambah [name='nama']").val(res.data.nama)
                $("#modalTambah [name='noPeserta']").removeClass("is-invalid")
                $("#modalTambah [name='nik']").removeClass("is-invalid")
                $("#modalTambah [name='nama']").removeClass("is-invalid")
            }
        })
    })

    function update() {
        // Menampilkan konfirmasi SweetAlert
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin memperbarui data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Perbarui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            // Jika pengguna mengonfirmasi
            if (result.isConfirmed) {
                // Melakukan AJAX request
                $.ajax({
                    url: "{{ route('dataPesertaPensiun_update') }}",
                    type: "POST",
                    data: {
                        id: $('#id').val(),
                        no_peserta: $('#noPeserta').val(),
                        nik: $('#nik').val(),
                        nama: $('#nama').val(),
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        console.log(res);
                        $("#table1").DataTable().ajax.reload();
                        // Menampilkan pesan sukses dengan SweetAlert
                        Swal.fire(
                            'Sukses',
                            'Data berhasil diperbarui',
                            'success'
                        );
                        // Menutup modal tambah
                        $("#modalTambah .close").click();
                        // Mengosongkan input
                        $("#modalTambah [name='noPeserta']").val('');
                        $("#modalTambah [name='nik']").val('');
                        $("#modalTambah [name='nama']").val('');
                        $('#simpan').text('Simpan');
                    },
                    error: function(err) {
                        console.log(err)
                        // Menampilkan pesan kesalahan dengan SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi Kesalahan',
                        });
                        // Menandai input yang bermasalah dengan class "is-invalid"
                        $("#modalTambah [name='noPeserta']").addClass("is-invalid")
                        $("#modalTambah [name='nik']").addClass("is-invalid")
                        $("#modalTambah [name='nama']").addClass("is-invalid")
                    }
                });
            }
        });
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
                    url: "{{ route('dataPesertaPensiun_destroy') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function() {
                        $("#table1").DataTable().ajax.reload();
                    }
                })
            }
        })
    })
</script>

@endpush
@push('addon-script')

@endpush