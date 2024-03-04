@extends('layouts.app')

@section('title', 'Blank Page')

@push('style')
<!-- CSS Libraries -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kelola Akun</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Akun</div>
            </div>
        </div>

        <!-- Tabel Akun -->
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <div class="row">
                        <div class="col-10">
                            <h4>Daftar Akun Mitra</h4>
                        </div>
                        <div class="col btn-group">
                            <button href="#" class="btn btn-primary" data-toggle="modal" id="tambah" data-target="#modalTambah">Tambah Akun</button>
                            <!-- <a href="#" class="btn btn-warning">Import Excel</a> -->
                        </div>
                    </div>
                </div>
                <table id="table1" class="table table-bordered table-striped table-hover" style="width: 100%;">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Instansi</th>
                            <th>Level</th>
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
                    <h4 class="modal-title" id="judul_modal">Tambah Akun</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control rounded-0 @error('name') is-invalid @enderror" id="name" name="name" value="" placeholder="Nama">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control rounded-0 @error('username') is-invalid @enderror" id="username" name="username" value="" placeholder="Username">
                    </div>
                    <!-- <div class="form-group" id="form-password">
                        <label for="password">Password</label>
                        <input type="password" class="form-control rounded-0 @error('password') is-invalid @enderror" id="password" name="password" value="" placeholder="Password">
                    </div> -->
                    <div class="form-group">
                        <label for="instansi_id">Instansi</label>
                        <select name="instansi_id" class="form-control rounded-0 @error('instansi_id') is-invalid @enderror" id="instansi_id">
                            <option value="1">Belum Dipilih</option>
                            @foreach ($dataInstansi as $instansi)
                            <option value="{{$instansi->id}}">{{ $instansi->nama_instansi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level">Level</label>
                        <select name="level" class="form-control rounded-0 @error('level') is-invalid @enderror" id="level">
                            <option value="admin">Admin</option>
                            <option value="staff">Staff/Karyawan</option>
                            <option value="mitra">Mitra</option>
                        </select>
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
                url: "{{ route('kelolaAkun') }}"
            },
            columns: [{
                    "data": null,
                    "sortable": true,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1
                    }
                },
                {
                    data: 'name',
                },
                {
                    data: 'username',
                },
                {
                    data: 'email',
                },
                {
                    data: 'nama_instansi',
                },
                {
                    data: 'level',
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
        $('#form-password').removeAttr("hidden")
        $('#judul_modal').text('Tambah Data Baru');
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
            url: "{{ route('kelolaAkun_tambah') }}",
            type: "POST",
            data: {
                name: $('#name').val(),
                instansi_id: $('#instansi_id').val(),
                username: $('#username').val(),
                email: $('#email').val(),
                password: '123456',
                level: $('#level').val(),
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
                $("#modalTambah [name='name']").val('');
                $("#modalTambah [name='username']").val('');
                $("#modalTambah [name='instansi_id']").val('');
                $("#modalTambah [name='email']").val('');
                $("#modalTambah [name='password']").val('');
                $("#modalTambah [name='level']").val('');
            },
            error: function(err) {
                console.log(err)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi Kesalahan',
                })
                $("#modalTambah [name='noPeserta']").addClass("is-invalid")
                $("#modalTambah [name='name']").addClass("is-invalid")
                $("#modalTambah [name='nik']").addClass("is-invalid")
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
            url: "{{ route('kelolaAkun_show') }}",
            type: 'POST',
            data: {
                id: id,
                "_token": "{{ csrf_token() }}"
            },
            success: function(res) {
                $("#modalTambah [name='id']").val(res.data.id)                
                $("#modalTambah [name='name']").val(res.data.name);
                $("#modalTambah [name='username']").val(res.data.username);
                $("#modalTambah [name='instansi_id']").val(res.data.instansi_id);
                $("#modalTambah [name='level']").val(res.data.level);

                $("#modalTambah [name='noPeserta']").removeClass("is-invalid")
                $("#modalTambah [name='nik']").removeClass("is-invalid")
                $("#modalTambah [name='name']").removeClass("is-invalid")
            }
        })
    })

    function update() {
        $.ajax({
            url: "{{ route('kelolaAkun_update') }}",
            type: "POST",
            data: {
                id: $('#id').val(),
                no_peserta: $('#noPeserta').val(),
                nik: $('#nik').val(),
                name: $('#name').val(),
                level: $('#level').val(),
                "_token": "{{ csrf_token() }}"
            },
            success: function(res) {
                console.log(res);
                $("#table1").DataTable().ajax.reload();
                // alert
                Swal.fire(
                    'Sukses',
                    'Data berhasil diperbarui',
                    'success'
                );
                $("#modalTambah .close").click();
                $("#modalTambah [name='name']").val('');
                $("#modalTambah [name='instansi_id']").val('');
                $("#modalTambah [name='username']").val('');
                $("#modalTambah [name='level']").val('');
                $('#simpan').text('Simpan');
            },
            error: function(err) {
                console.log(err)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi Kesalahan',
                })
                $("#modalTambah [name='noPeserta']").addClass("is-invalid")
                $("#modalTambah [name='nik']").addClass("is-invalid")
                $("#modalTambah [name='name']").addClass("is-invalid")
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
                    url: "{{ route('kelolaAkun_destroy') }}",
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