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
            <h1>Verifikasi Berkas</h1>
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
                            <!-- <a href="#" class="btn btn-warning">Import Excel</a> -->
                        </div>
                    </div>
                </div>
                <table id="table1" class="table table-bordered table-striped table-hover" style="width: 100%;">
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
    <div class="modal fade" id="modalVerifikasi">
        <input type="hidden" id="id" name="id">
        <div class="modal-dialog modal-xl">
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
                        <label for="deskripsi">Deskripsi</label>
                        <p id="deskripsi" name="deskripsi"></p>
                    </div>
                    <div class="form-group">
                        <label for="status">Ubah Status</label>
                        <select name="status" id="status">
                            <option value="1">Belum Terverifikasi</option>
                            <option value="2">Diverifikasi</option>
                            <option value="3">Verifikasi Ulang</option>
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
<p hidden>
    {{$url = url()->current()}}
</p>
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
                url: "/staff/verifikasi/{{$tahun}}/{{$bulan}}"
            },
            columns: [{
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
                    render: function(data, type, row) {
                        // Periksa apakah bulan saat ini sama dengan bulan pada baris data
                        var currentMonth = new Date().getMonth() + 1; // Mendapatkan bulan saat ini (mulai dari 1 untuk Januari)
                        var rowMonth = row.id;
                        var status = row.status;
                        console.log(row);
                        if (row.tahun_id == '{{$tahun}}'){
                            if (row.bulan_id == '{{$bulan}}') {
                                if (row.status == 1) {
                                    return "<button type='button' id='" + row.id + "' class='buka btn btn-primary' data-toggle='modal' data-target='#modalVerifikasi'>Verifikasi</button>";
                                } else if (row.status == 2) {
                                    return "<button type='button' id='" + row.id + "' class='buka btn btn-secondary' data-toggle='modal' data-target='#modalVerifikasi'>Telah diverifikasi</button>";
                                } else {
                                    return "<button type='button' id='" + row.id + "' class='buka btn btn-secondary' data-toggle='modal' data-target='#modalVerifikasi'>Meminta Ulang</button>";
                                }
                            } else {
                                if (row.status == 1) {
                                    return "<button type='button' id='" + row.id + "' class='buka btn btn-secondary'>Belum Diverifikasi</button>";
                                } else if (row.status == 2) {
                                    return "<button type='button' id='" + row.id + "' class='buka btn btn-secondary'>Telah diverifikasi</button>";
                                } else if (row.status == 3) {
                                    return "<button type='button' id='" + row.id + "' class='buka btn btn-secondary'>Tidak dapat diperbarui</button>";
                                }else {
                                    return "<button type='button' id='" + row.id + "' class='buka btn btn-secondary'>Tidak tersedia</button>";
                                }
                            }
                        } else {
                            return "<button type='button' id='" + row.id + "' class='buka btn btn-secondary'>Tidak tersedia</button>";
                        }
                    }
                }
            ]
        })
    }

    // Tambah Data
    $('#simpan').on('click', function() {
        event.preventDefault()
        verifikasi()
    })

    // Show Update Data
    $(document).on('click', '.buka', function() {
        let id = $(this).attr('id');
        $('#tambah').click();
        $('#simpan').text('Simpan');
        $('#judul_modal').text('Verifikasi Berkas');
        $.ajax({
            url: "{{$url}}/show",
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
                    $("#deskripsi").text(res.data[0].deskripsi);

                    $("#status").val(res.data[0].status);

                    // Memasukkan nilai ID, nama file, dan deskripsi ke dalam input
                    $("#id").val(res.data[0].id);
                } else {
                    Swal.fire(
                        'Error',
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

    function verifikasi() {
        $.ajax({
            url: "{{$url}}/verifikasi",
            type: "POST",
            data: {
                id: $('#id').val(),
                status: $('#status').val(),
                "_token": "{{ csrf_token() }}"
            },
            success: function(res, data) {
                console.log(data);
                $("#table1").DataTable().ajax.reload();
                // alert
                Swal.fire(
                    'Sukses',
                    'Data berhasil diperbarui',
                    'success'
                );
                $("#modalVerifikasi .close").click();
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