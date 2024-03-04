@extends('layouts.app')

@section('title', 'Profile')

@push('style')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
<link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profile</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Profile</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Hi, {{$nama}}</h2>
            <p class="section-lead">
                Mohon untuk melengkapi formulir dibawah.
            </p>

            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-5">
                    <div class="card profile-widget">
                        <div class="profile-widget-header">
                            <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="rounded-circle profile-widget-picture">
                        </div>
                        <div class="profile-widget-description">
                            <div class="profile-widget-name" id="profile-nama">{{$nama}}
                                <div class="text-muted d-inline font-weight-normal">
                                    <div class="slash"></div> {{$instansi}}
                                </div>
                            </div>
                            <p id="profile-bio">
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-7">
                    <div class="card">
                        <form id="editProfileForm">
                            @csrf
                            <div class="card-header">
                                <h4>Edit Profile</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-5 col-12">
                                        <label>Nama Lengkap</label>
                                        <input type="text" id="id" name="id" class="form-control" value="" hidden>
                                        <input type="text" id="name" name="name" class="form-control" value="" required="">
                                        <div class="invalid-feedback">
                                            Mohon untuk diisi!
                                        </div>
                                    </div>
                                    <div class="form-group col-md-7 col-12">
                                        <label>Username</label>
                                        <input type="text" id="username" name="username" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-7 col-12">
                                        <label>Email</label>
                                        <input type="email" id="email" name="email" class="form-control" value="" required="">
                                        <div class="invalid-feedback">
                                            Mohon untuk diisi!
                                        </div>
                                    </div>
                                    <div class="form-group col-md-5 col-12">
                                        <label>Phone</label>
                                        <input type="tel" id="phone" name="phone" class="form-control" value="" required="">
                                        <div class="invalid-feedback">
                                            Mohon untuk diisi!
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>Bio</label>
                                        <textarea id="bio" name="bio" class="form-control summernote-simple"></textarea>
                                    </div>
                                </div>
                                <p class="text-danger">Peringatan : Hanya jika ingin diganti!</p>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Password</label>
                                        <input type="password" id="password" name="password" class="form-control" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary" id="simpan" type="submit">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<!-- JS Libraies -->
<script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
<script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
<!-- Page Specific JS File -->

<script>
    $(document).ready(function() {
        $.ajax({
            url: '{{route("profile_show")}}', // Ganti dengan URL target Anda
            type: 'POST',
            data: {
                id: '{{Auth::user()->id}}',
                '_token': '{{ csrf_token() }}',
            },
            success: function(res) {
                // Handle respons dari server di sini
                console.log('Data berhasil diload');
                $("#profile-bio").html(res.data.bio);
                $("#editProfileForm [name='id']").val(res.data.id);
                $("#editProfileForm [name='name']").val(res.data.name);
                $("#editProfileForm [name='username']").val(res.data.username);
                $("#editProfileForm [name='email']").val(res.data.email);
                $("#editProfileForm [name='phone']").val(res.data.phone);
                $("#editProfileForm [name='bio']").summernote('code', res.data.bio);

            },
            error: function(xhr, status, error) {
                // Handle error di sini
                console.error(error);
                console.error('Terjadi kesalahan');
            }
        });

        $('#editProfileForm').submit(function(e) {
            e.preventDefault(); // Menghentikan pengiriman form standar

            // Mengambil data dari form
            var formData = $(this).serialize();

            // Melakukan request AJAX untuk menyimpan perubahan
            $.ajax({
                url: '{{ route("profile_update") }}', // Ganti dengan URL target Anda
                type: 'POST',
                data: formData,
                success: function(res) {
                    // Handle respons dari server di sini
                    console.log('Perubahan berhasil disimpan');
                    // Tambahkan kode di sini untuk menampilkan pesan sukses atau melakukan tindakan setelah perubahan disimpan
                    Swal.fire(
                        'Sukses',
                        'Data berhasil diperbarui',
                        'success'
                    );
                    $("#editProfileForm [name='phone']").val(res.data.phone);
                    $("#profile-nama").text(res.data.name);
                    $("#profile-bio").html(res.data.bio);
                },
                error: function(xhr, status, error) {
                    // Handle error di sini
                    console.error(error);
                    console.error('Terjadi kesalahan saat menyimpan perubahan');
                    // Tambahkan kode di sini untuk menampilkan pesan error atau melakukan tindakan lainnya saat terjadi kesalahan
                }
            });
        });
    });
</script>
@endpush