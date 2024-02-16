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
                        <div class="col-9">
                            <h4>Data Iuran Per Bulan</h4>
                        </div>
                        <div class="col btn-group">
                            <button class="btn btn-secondary" data-toggle="modal" id="tambah" data-target="#modalTambah">Tambah Peserta</button>
                            <button class="btn btn-success" data-toggle="modal" id="import" data-target="#modalTambah">Import Excel</button>
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
    <div class="modal fade" id="modalTambah">
        <input type="hidden" id="id" name="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul_modal">Tambah Bulan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group hidden">
                        <label for="nama_bulan">Bulan</label>
                        <select name="nama_bulan" class="form-control rounded-0 @error('nama_bulan') is-invalid @enderror" id="nama_bulan">
                            <option value="">-- BULAN --</option>
                            <option value="Januari">Januari</option>
                            <option value="Februari">Februari</option>
                            <option value="Maret">Maret</option>
                            <option value="April">April</option>
                            <option value="Mei">Mei</option>
                            <option value="Juni">Juni</option>
                            <option value="Juli">Juli</option>
                            <option value="Agustus">Agustus</option>
                            <option value="September">September</option>
                            <option value="Oktober">Oktober</option>
                            <option value="November">November</option>
                            <option value="Desember">Desember</option>
                        </select>
                    </div>
                    <div class="form-group hidden">
                        <label for="peserta">Peserta</label>
                        <select name="peserta" class="form-control rounded-0 @error('peserta') is-invalid @enderror" id="peserta">
                            <option value="">-- PILIH PESERTA --</option>
                            @foreach ($dataPeserta as $peserta)
                            <option value="{{$peserta->id}}">{{$peserta->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group hidden">
                        <label for="gaji_pokok">Gaji Pokok</label>
                        <input type="text" class="form-control rounded-0 @error('gaji_pokok') is-invalid @enderror" id="gaji_pokok" name="gaji_pokok" value="" placeholder="">
                    </div>
                    <div class="form-group hidden">
                        <label for="adj_gapok">Adj. Gapok</label>
                        <input type="text" class="form-control rounded-0 @error('adj_gapok') is-invalid @enderror" id="adj_gapok" name="adj_gapok" value="" placeholder="">
                    </div>
                    <div class="form-group hidden">
                        <label for="in_peserta">IN Peserta</label>
                        <input type="text" class="form-control rounded-0 @error('in_peserta') is-invalid @enderror" id="in_peserta" name="in_peserta" value="" placeholder="">
                    </div>
                    <div class="form-group hidden">
                        <label for="rapel_in_peserta">Rapel IN Peserta</label>
                        <input type="text" class="form-control rounded-0 @error('rapel_in_peserta') is-invalid @enderror" id="rapel_in_peserta" name="rapel_in_peserta" value="" placeholder="">
                    </div>
                    <div class="form-group hidden">
                        <label for="in_pk">IN PK</label>
                        <input type="text" class="form-control rounded-0 @error('in_pk') is-invalid @enderror" id="in_pk" name="in_pk" value="" placeholder="">
                    </div>
                    <div class="form-group hidden">
                        <label for="rapel_in_pk">RAPEL IN PK</label>
                        <input type="text" class="form-control rounded-0 @error('rapel_in_pk') is-invalid @enderror" id="rapel_in_pk" name="rapel_in_pk" value="" placeholder="">
                    </div>
                    <div class="form-group hidden">
                        <label for="jumlah">Jumlah</label>
                        <input type="text" class="form-control rounded-0 @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="" placeholder="">
                    </div>
                    <div class="form-group hidden">
                        <label for="jumlah">Jumlah</label>
                        <input type="text" class="form-control rounded-0 @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="file">Import</label>
                        <input type="file" class="form-control rounded-0 @error('file') is-invalid @enderror" id="file" name="file" value="" placeholder="">
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
                url: "{{ route('dataPesertaPensiunPerBulan') }}"
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
        
    //Reset Form
    $('#tambah').on('click', function() {
        $("#simpan").removeClass("btn btn-warning")
        $("#simpan").addClass("btn btn-primary")
        $('#simpan').text('Simpan')
        $('#judul_modal').text('Tambah Data Baru');
        $("#modalTambah [name='nama']").val('')
        $("#modalTambah [name='noPeserta']").val('')
        $(".unhidden").attr('hidden', true)
        $(".hidden").removeAttr('hidden', true)
    })
    
    $('#import').on('click', function() {
        $("#simpan").removeClass("btn btn-warning")
        $("#simpan").addClass("btn btn-primary")
        $('#simpan').text('Import')
        $('#judul_modal').text('Tambah Data Baru');
        $(".hidden").attr('hidden', true)
        $(".unhidden").removeAttr('hidden', true)
    })

    // Tambah Data
    $('#simpan').on('click', function() {
        if ($(this).text() === 'Simpan') {
            event.preventDefault()
            tambah()
        } else if($(this).text() === 'Import'){
            event.preventDefault()
            impor()
        }else {
            event.preventDefault()
            update()
        }
    })

    function tambah() {
        $('#simpan').text('Simpan')
        $.ajax({
            url: "{{ route('dataPesertaPensiunPerBulan_tambah') }}",
            type: "POST",
            data: {
                peserta_id: $('#peserta').val(),
                nama_bulan: $('#nama_bulan').val(),
                gaji_pokok: $('#gaji_pokok').val(),
                adj_gapok: $('#adj_gapok').val(),
                in_peserta: $('#in_peserta').val(),
                rapel_in_peserta: $('#rapel_in_peserta').val(),
                in_pk: $('#in_pk').val(),
                rapel_in_pk: $('#rapel_in_pk').val(),
                jumlah: $('#jumlah').val(),
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
            }
        })
    }

    function impor() {
        var fileInput = document.getElementById('file');
        var file = fileInput.files[0];
        var formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ route('dataPesertaPensiun_import') }}",
            type: "POST",
            data: formData,
            processData: false, // Set false agar jQuery tidak memproses FormData secara otomatis
            contentType: false, // Set false agar jQuery tidak menambahkan header Content-Type
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
            },
            error: function(err) {
                console.log(err)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi Kesalahan',
                })
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
            url: "{{ route('dataPesertaPensiunPerBulan_show') }}",
            type: 'POST',
            data: {
                id: id,
                "_token": "{{ csrf_token() }}"
            },
            success: function(res) {
                $("#modalTambah [name='id']").val(res.data.id)
                $("#modalTambah [name='peserta']").val(res.data.peserta_id)
                $("#modalTambah [name='nama_bulan']").val(res.data.nama_bulan)
                $("#modalTambah [name='gaji_pokok']").val(res.data.gaji_pokok)
                $("#modalTambah [name='adj_gapok']").val(res.data.adj_gapok)
                $("#modalTambah [name='in_peserta']").val(res.data.in_peserta)
                $("#modalTambah [name='rapel_in_peserta']").val(res.data.rapel_in_peserta)
                $("#modalTambah [name='in_pk']").val(res.data.in_pk)
                $("#modalTambah [name='rapel_in_pk']").val(res.data.rapel_in_pk)
                $("#modalTambah [name='jumlah']").val(res.data.jumlah)
            }
        })
    })

    function update() {
        $.ajax({
            url: "{{ route('dataPesertaPensiunPerBulan_update') }}",
            type: "POST",
            data: {
                id: $('#id').val(),
                peserta_id: $('#peserta').val(),
                nama_bulan: $('#nama_bulan').val(),
                gaji_pokok: $('#gaji_pokok').val(),
                adj_gapok: $('#adj_gapok').val(),
                in_peserta: $('#in_peserta').val(),
                rapel_in_peserta: $('#rapel_in_peserta').val(),
                in_pk: $('#in_pk').val(),
                rapel_in_pk: $('#rapel_in_pk').val(),
                jumlah: $('#jumlah').val(),
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
                $("#modalTambah [name='peserta']").val('');
                $("#modalTambah [name='nama_bulan']").val('');
                $("#modalTambah [name='gaji_pokok']").val('');
                $("#modalTambah [name='adj_gapok']").val('');
                $("#modalTambah [name='in_peserta']").val('');
                $("#modalTambah [name='rapel_in_peserta']").val('');
                $("#modalTambah [name='in_pk']").val('');
                $("#modalTambah [name='rapel_in_pk']").val('');
                $("#modalTambah [name='jumlah']").val('');
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
                    url: "{{ route('dataPesertaPensiunPerBulan_destroy') }}",
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
@push('addon-script')

@endpush