<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Login - Dana Pensiun</title>
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <!-- Main CSS -->
  <link href="{{ url ('frontend/styles/main.css') }}" rel="stylesheet">
  <!-- Bootsraps CSS -->
  <link href="{{ url ('frontend/libraries/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Font Awesome Icon -->
  <link href="{{ url ('frontend/libraries/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

</head>

<body class="d-flex align-items-center py-4 bg-light">
  <main class="form-signin m-auto">
    <form class="bg-white px-3 py-3 rounded" action="{{ route('auth') }}" method="post">
      @csrf

      <div class="text-center">
        <img src="{{ url ('frontend/images/dapen_login.svg') }}" class="mb-4" width="50%">
        <h1 class="h6 mb-4 fw-normal">Masukkan data diri anda untuk akses akun anda!</h1>
      </div>

      <div class="input-group mb-3 border-bottom">
        <span class="input-group-text border border-0 bg-white"><i class="fa fa-envelope color-blue mr-5" aria-hidden="true"></i></span>
        <div class="form-floating">
          <input type="text" class="form-control border border-0" id="username" name="username" value="{{ old('username')}}" placeholder="Username">
          <label for="username">E-mail/Username</label>
        </div>
      </div>
      <div class="input-group mb-3 border-bottom">
        <span class="input-group-text border border-0 bg-white px-3"><i class="fa fa-lock color-blue mr-5" aria-hidden="true"></i></span>
        <div class="form-floating">
          <input type="password" class="form-control border border-0" id="password" name="password" placeholder="Password">
          <label for="password">Password</label>
        </div>
      </div>

      <div class="form-check text-start my-3">
        <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
        <label class="form-check-label text-secondary" for="flexCheckDefault">
          Ingat saya
        </label>
      </div>
      <button class="btn btn-blue text-white w-100 py-2" type="submit">Log in</button>
    </form>
  </main>
  
  @include('notification/sweetalert')
  <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
  <script src="{{ url ('frontend/libraries/bootstrap/js/bootstrap.bundle.min.js') }}" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>