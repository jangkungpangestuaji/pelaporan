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
                            <h4>Daftar Bulan</h4>
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
                            <th>Bulan</th>
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

<p hidden>
    {{$url = url()->current()}}
</p>

<!-- Modal Tambah -->
<form enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="modal fade" id="modalUpload">
        <input type="hidden" id="id" name="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul_modal">Upload File Dalam Bentuk Pdf</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfViewer" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                    <div class="form-group">
                        <label for="file_name">File</label>
                        <input type="file" class="form-control rounded-0 @error('file_name') is-invalid @enderror" id="file_name" name="file_name" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <input type="textarea" class="form-control rounded-0 @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" placeholder="">
                    </div>
                    <p>Peringatan : Harap upload ulang file anda karena batasan dari browser</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="simpan" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
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
                url: "{{$url}}"
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
                    render: function(data, type, row) {
                        // Periksa apakah bulan saat ini sama dengan bulan pada baris data
                        var currentMonth = new Date().getMonth() + 1; // Mendapatkan bulan saat ini (mulai dari 1 untuk Januari)
                        var rowMonth = row.id;
                        var status = '{{$status}}';
                        // console.log(row);
                        if (rowMonth === currentMonth) {
                            if (status == 1) {
                                $('#simpan').text('Update');
                                return "<button type='button' id='" + row.id + "' class='update btn btn-warning' data-toggle='modal' data-target='#modalUpload'>Update</button>";
                            } else {
                                $('#simpan').text('Simpan');
                                // Bulan saat ini, button aktif
                                return "<button type='button' id='" + row.id + "' class='upload btn btn-warning' data-toggle='modal' id='upload' data-target='#modalUpload'>Upload</button>";
                            }
                        } else {
                            // Bukan bulan saat ini, button tidak aktif
                            return "<button type='button' id='" + row.id + "' class='upload btn btn-secondary' disabled>Belum Tersedia</button>";
                        }
                    }
                }
            ]
        })
    }

    // Tambah Data
    $('#simpan').on('click', function() {
        if ($(this).text() === 'Simpan') {
            event.preventDefault()
            upload()
        } else {
            event.preventDefault()
            update()
        }
    })

    function upload() {
        $('#simpan').text('Simpan'); // Mengubah teks tombol simpan jika perlu

        // Mendapatkan bulan saat ini
        var currentMonth = new Date().getMonth() + 1;

        // Mendapatkan file yang dipilih oleh pengguna
        var file = $('#file_name')[0].files[0];
        var deskripsi = $('#deskripsi').val();

        // Validasi file yang dipilih
        if (!file) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Anda harus memilih file!',
            });
            return; // Hentikan eksekusi fungsi jika tidak ada file yang dipilih
        }

        // Mengirim permintaan AJAX ke URL yang sesuai
        var formData = new FormData();
        formData.append('file_name', file);
        formData.append('deskripsi', deskripsi);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{$url}}/" + currentMonth, // Menggunakan URL yang diberikan dengan menambahkan bulan saat ini
            type: "POST", // Menggunakan metode POST
            data: formData,
            processData: false, // Menghindari pemrosesan data otomatis oleh jQuery
            contentType: false, // Tidak mengatur content-type secara otomatis
            success: function(res) { // Output response ke konsol jika perlu
                $("#table1").DataTable().ajax.reload(); // Memuat ulang data tabel jika perlu
                $("#modalUpload .close").click(); // Menutup modal jika perlu

                // Tampilkan pesan sukses kepada pengguna
                Swal.fire(
                    'Sukses',
                    'Data berhasil ditambahkan',
                    'success'
                );

                // Mendapatkan bulan yang baru saja diunggah
                var uploadedMonth = res.month; // Pastikan untuk mengganti 'month' dengan properti yang sesuai dari respons Anda

                // Memeriksa apakah bulan yang diunggah adalah bulan saat ini
                if (uploadedMonth == currentMonth) {
                    // Jika ya, ubah teks tombol upload menjadi "Update"
                    $('#' + uploadedMonth).text('Update');
                    $('#simpan').text('Update');
                }
            },
            error: function(err) {
                console.log(err); // Output error ke konsol untuk debugging
                // Tampilkan pesan error kepada pengguna
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi Kesalahan',
                });
            }
        });
    }

    // Show Update Data
    $(document).on('click', '.update', function() {
        let id = $(this).attr('id');
        $('#tambah').click();
        $('#simpan').text('Perbarui');
        $('#judul_modal').text('Perbarui File');
        $("#simpan").removeClass("btn btn-primary").addClass("btn btn-warning");

        // Mendapatkan bulan saat ini
        var currentMonth = new Date().getMonth() + 1;

        $.ajax({
            url: "{{$url}}/" + currentMonth + "/show",
            type: 'POST',
            data: {
                id: id,
                "_token": "{{ csrf_token() }}"
            },
            success: function(res) {
                // Memeriksa apakah data file_name ada dalam respons
                if (res.data && res.data[0].file_name) {
                    // Membuat URL PDF dari nama file yang diperoleh
                    var pdfUrl = "{{ asset ('uploads/') }}/" + res.data[0].file_name;
                    // Mengisi iframe dengan URL PDF
                    $("#pdfViewer").attr("src", pdfUrl);
                    var file_name = res.data[0].file_name;
                    console.log($("#modalUpload [name='file_name']").val());
                    // Memasukkan nilai ID, nama file, dan deskripsi ke dalam input
                    $("#modalUpload [name='id']").val(res.data[0].id);
                    $("#modalUpload [name='deskripsi']").val(res.data[0].deskripsi);
                    $("#modalUpload [name='file_name']").val(res.data[0].file_name);
                } else {
                    Swal.fire(
                        'Sukses',
                        'File PDF tidak ditemukan',
                        'success'
                    );
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log error message ke konsol
            }
        });
    });

    function update() {
        // Mendapatkan bulan saat ini
        var currentMonth = new Date().getMonth() + 1;

        // Mendapatkan file yang dipilih oleh pengguna
        var id = $('#id').val();
        var file = $('#file_name')[0].files[0];
        var deskripsi = $('#deskripsi').val();

        // Validasi file yang dipilih
        if (!file) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Anda harus memilih file!',
            });
            return; // Hentikan eksekusi fungsi jika tidak ada file yang dipilih
        }

        // Mengirim permintaan AJAX ke URL yang sesuai
        var formData = new FormData();
        formData.append('id', id);
        formData.append('file_name', file);
        formData.append('deskripsi', deskripsi);
        formData.append('_token', '{{ csrf_token() }}');

        console.log(file);
        $.ajax({
            url: "{{$url}}/" + currentMonth + "/update",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
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
                $("#modalTambah [name='nama']").addClass("is-invalid")
            }
        })
    }
</script>

@endpush
@push('addon-script')

@endpush