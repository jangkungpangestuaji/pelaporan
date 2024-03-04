<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand mb-4">
            <a href="index.html">
                <!-- Stisla -->
                <img src="{{ asset('frontend/images/dapen_login.svg')}}" width="50%" alt="" srcset="">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">
                <img src="{{ asset('frontend/images/logo_dapen.svg')}}" width="40%" alt="" srcset="">
                
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ Request::is('home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('home') }}"><i class="fas fa-home"></i> <span>Home</span></a>
            </li>

            @can('staff')
            <li class="menu-header">Menu</li>
            <li class="nav-item dropdown {{ Request::is('staff/*') ? 'active' : ''}}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Pegawai</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('staff/dataPesertaPensiun*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('staffDataPensiun')}}"> <span>Data Pensiun</span></a>
                    </li>
                    <li class="{{ Request::is('staff/verifikasi*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('verifikasi')}}"> <span>Verifikasi Berkas</span></a>
                    </li>
                </ul>
            </li>
            @endcan

            @can('mitra')
            <li class="menu-header">Menu</li>
            <li class="nav-item dropdown {{ Request::is('mitra/*') ? 'active' : ''}}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Menu</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('mitra/pesertaPensiun') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dataPesertaPensiun')}}"> <span>Peserta Pensiun</span></a>
                    </li>
                    <li class="{{ Request::is('mitra/dataPensiun*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dataPensiun')}}"> <span>Iuran Pensiun</span></a>
                    </li>
                    <li class="{{ Request::is('mitra/uploadBuktiPembayaran*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('uploadBuktiPembayaran')}}"> <span>Upload Bukti Pembayaran</span></a>
                    </li>
                </ul>
            </li>
            @endcan

            @can('admin')
            <li class="menu-header">Menu</li>
            <li class="nav-item dropdown {{ Request::is('admin/*') ? 'active' : ''}}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-square"></i><span>Admin</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin/akun') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('kelolaAkun')}}"> <span>Kelola Akun</span></a>
                    </li>
                    <li class="{{ Request::is('admin/instansi') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('instansi')}}"> <span>Instansi</span></a>
                    </li>
                </ul>
            </li>
            @endcan

            <li class="menu-header">Profile</li>
            <li class="{{ Request::is('profile') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('profile')}}"> <i class="fas fa-user"></i><span>Profile</span></a>
            </li>
        </ul>

    </aside>
</div>