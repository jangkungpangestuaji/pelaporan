<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DapenController;
use App\Http\Controllers\VerifikasiBerkasController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::redirect('/', '/home');
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'auth'])->name('auth');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'register'])->name('register');

// Dashboard
Route::get('/home', function () {
    return view('pages.home', ['type_menu' => 'dashboard']);
})->middleware('auth');

// Menu Admin
Route::middleware(['admin', 'auth'])->group(function () {
    Route::get('/admin/akun', [AdminController::class, 'index'])->name('kelolaAkun');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('kelolaAkun_tambah');
    Route::post('/admin/show', [AdminController::class, 'show'])->name('kelolaAkun_show');
    Route::post('/admin/update', [AdminController::class, 'update'])->name('kelolaAkun_update');
    Route::post('/admin/destroy', [AdminController::class, 'destroy'])->name('kelolaAkun_destroy');

    Route::get('/admin/instansi', [AdminController::class, 'instansi'])->name('instansi');
    Route::post('/admin/instansi/store', [AdminController::class, 'store_instansi'])->name('instansi_tambah');
    Route::post('/admin/instansi/show', [AdminController::class, 'show_instansi'])->name('instansi_show');
    Route::post('/admin/instansi/update', [AdminController::class, 'update_instansi'])->name('instansi_update');
    Route::post('/admin/instansi/destroy', [AdminController::class, 'destroy_instansi'])->name('instansi_destroy');
});

// Menu Staff/Karyawan
Route::middleware(['staff', 'auth'])->group(function () {
    Route::get('/staff/dataPesertaPensiun', [DapenController::class, 'index'])->name('staffDataPensiun');
    Route::get('/staff/dataPesertaPensiun/{id}', [DapenController::class, 'getDataByInstansi'])->name('staffDataPensiunByInstansi');
    Route::get('/staff/dataPesertaPensiun/{id}/{tahun}', [DapenController::class, 'getDataByTahun'])->name('staffDataPensiunByTahun');
    Route::get('/staff/dataPesertaPensiun/{id}/{tahun}/{bulan}', [DapenController::class, 'getDataByBulan'])->name('staffDataPensiunByBulan');
    Route::get('/staff/export_excel/{id}/{tahun}/{bulan}', [DapenController::class, 'export_excel']);
    Route::post('/staff/dataPesertaPensiun/{id}/{tahun}/{bulan}/show', [DapenController::class, 'index'])->name('staffDataPensiunByBulan_show');
    
    Route::get('/staff/verifikasi', [VerifikasiBerkasController::class, 'showInstansi'])->name('verifikasi');
    Route::get('/staff/verifikasi/{instansi}', [VerifikasiBerkasController::class, 'showTahun']);
    Route::get('/staff/verifikasi/{instansi}/{tahun}', [VerifikasiBerkasController::class, 'getDataByTahun2']);
    Route::post('/staff/verifikasi/{instansi}/{tahun}/show', [VerifikasiBerkasController::class, 'show2']);
    Route::post('/staff/verifikasi/{instansi}/{tahun}/verifikasi', [VerifikasiBerkasController::class, 'verifikasi']);
});

// Menu Mitra
Route::middleware(['mitra', 'auth'])->group(function () {
    Route::get('/mitra/pesertaPensiun', [MitraController::class, 'index'])->name('dataPesertaPensiun');
    Route::post('/mitra/pesertaPensiun/store', [MitraController::class, 'store'])->name('dataPesertaPensiun_tambah');
    Route::post('/mitra/pesertaPensiun/show', [MitraController::class, 'show'])->name('dataPesertaPensiun_show');
    Route::post('/mitra/pesertaPensiun/update', [MitraController::class, 'update'])->name('dataPesertaPensiun_update');
    Route::post('/mitra/pesertaPensiun/destroy', [MitraController::class, 'destroy'])->name('dataPesertaPensiun_destroy');
    
    Route::get('/mitra/dataPensiun', [MitraController::class, 'index_2'])->name('dataPensiun');
    Route::get('/mitra/dataPensiun/{id}', [MitraController::class, 'getDataById'])->name('dataPensiunById');
    Route::get('/mitra/dataPensiun/{id}/{bulan}', [MitraController::class, 'getDataByBulan'])->name('dataPensiunByBulan');
    Route::post('/mitra/dataPensiun/{id}/{bulan}/store', [MitraController::class, 'store_2'])->name('dataPensiun_tambah');
    Route::post('/mitra/dataPensiun/{id}/{bulan}/show', [MitraController::class, 'show_2'])->name('dataPensiun_show');
    Route::post('/mitra/dataPensiun/{id}/{bulan}/update', [MitraController::class, 'update_2'])->name('dataPensiun_update');
    Route::post('/mitra/dataPensiun/{id}/{bulan}/destroy', [MitraController::class, 'destroy_2'])->name('dataPensiun_destroy');
    Route::post('/mitra/dataPensiun/{id}/{bulan}/import', [MitraController::class, 'import'])->name('dataPesertaPensiun_import');

    Route::get('/mitra/uploadBuktiPembayaran', [VerifikasiBerkasController::class, 'index'])->name('uploadBuktiPembayaran');
    Route::get('/mitra/uploadBuktiPembayaran/{id}', [VerifikasiBerkasController::class, 'getDataByTahun'])->name('uploadBuktiPembayaranByTahun');
    Route::post('/mitra/uploadBuktiPembayaran/{id}/{bulan}/upload', [VerifikasiBerkasController::class, 'upload'])->name('uploadBuktiPembayaranByTahun_upload');
    Route::post('/mitra/uploadBuktiPembayaran/{id}/{bulan}/show', [VerifikasiBerkasController::class, 'show'])->name('uploadBuktiPembayaranByTahun_show');
    Route::post('/mitra/uploadBuktiPembayaran/{id}/{bulan}/update', [VerifikasiBerkasController::class, 'update'])->name('uploadBuktiPembayaranByTahun_update');
});

Route::get('/profile', [UserController::class, 'index'])->name('profile');
Route::post('/profile/show', [UserController::class, 'show'])->name('profile_show');
Route::post('/profile/update', [UserController::class, 'update'])->name('profile_update');

// Layout
Route::get('/layout-default-layout', function () {
    return view('pages.layout-default-layout', ['type_menu' => 'layout']);
});

// Blank Page
Route::get('/blank-page', function () {
    return view('pages.blank-page', ['type_menu' => '']);
});

// Bootstrap
Route::get('/bootstrap-alert', function () {
    return view('pages.bootstrap-alert', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-badge', function () {
    return view('pages.bootstrap-badge', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-breadcrumb', function () {
    return view('pages.bootstrap-breadcrumb', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-buttons', function () {
    return view('pages.bootstrap-buttons', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-card', function () {
    return view('pages.bootstrap-card', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-carousel', function () {
    return view('pages.bootstrap-carousel', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-collapse', function () {
    return view('pages.bootstrap-collapse', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-dropdown', function () {
    return view('pages.bootstrap-dropdown', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-form', function () {
    return view('pages.bootstrap-form', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-list-group', function () {
    return view('pages.bootstrap-list-group', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-media-object', function () {
    return view('pages.bootstrap-media-object', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-modal', function () {
    return view('pages.bootstrap-modal', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-nav', function () {
    return view('pages.bootstrap-nav', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-navbar', function () {
    return view('pages.bootstrap-navbar', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-pagination', function () {
    return view('pages.bootstrap-pagination', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-popover', function () {
    return view('pages.bootstrap-popover', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-progress', function () {
    return view('pages.bootstrap-progress', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-table', function () {
    return view('pages.bootstrap-table', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-tooltip', function () {
    return view('pages.bootstrap-tooltip', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-typography', function () {
    return view('pages.bootstrap-typography', ['type_menu' => 'bootstrap']);
});


// components
Route::get('/components-article', function () {
    return view('pages.components-article', ['type_menu' => 'components']);
});
Route::get('/components-avatar', function () {
    return view('pages.components-avatar', ['type_menu' => 'components']);
});
Route::get('/components-chat-box', function () {
    return view('pages.components-chat-box', ['type_menu' => 'components']);
});
Route::get('/components-empty-state', function () {
    return view('pages.components-empty-state', ['type_menu' => 'components']);
});
Route::get('/components-gallery', function () {
    return view('pages.components-gallery', ['type_menu' => 'components']);
});
Route::get('/components-hero', function () {
    return view('pages.components-hero', ['type_menu' => 'components']);
});
Route::get('/components-multiple-upload', function () {
    return view('pages.components-multiple-upload', ['type_menu' => 'components']);
});
Route::get('/components-pricing', function () {
    return view('pages.components-pricing', ['type_menu' => 'components']);
});
Route::get('/components-statistic', function () {
    return view('pages.components-statistic', ['type_menu' => 'components']);
});
Route::get('/components-tab', function () {
    return view('pages.components-tab', ['type_menu' => 'components']);
});
Route::get('/components-table', function () {
    return view('pages.components-table', ['type_menu' => 'components']);
});
Route::get('/components-user', function () {
    return view('pages.components-user', ['type_menu' => 'components']);
});
Route::get('/components-wizard', function () {
    return view('pages.components-wizard', ['type_menu' => 'components']);
});

// forms
Route::get('/forms-advanced-form', function () {
    return view('pages.forms-advanced-form', ['type_menu' => 'forms']);
});
Route::get('/forms-editor', function () {
    return view('pages.forms-editor', ['type_menu' => 'forms']);
});
Route::get('/forms-validation', function () {
    return view('pages.forms-validation', ['type_menu' => 'forms']);
});

// google maps
// belum tersedia

// modules
Route::get('/modules-calendar', function () {
    return view('pages.modules-calendar', ['type_menu' => 'modules']);
});
Route::get('/modules-chartjs', function () {
    return view('pages.modules-chartjs', ['type_menu' => 'modules']);
});
Route::get('/modules-datatables', function () {
    return view('pages.modules-datatables', ['type_menu' => 'modules']);
});
Route::get('/modules-flag', function () {
    return view('pages.modules-flag', ['type_menu' => 'modules']);
});
Route::get('/modules-font-awesome', function () {
    return view('pages.modules-font-awesome', ['type_menu' => 'modules']);
});
Route::get('/modules-ion-icons', function () {
    return view('pages.modules-ion-icons', ['type_menu' => 'modules']);
});
Route::get('/modules-owl-carousel', function () {
    return view('pages.modules-owl-carousel', ['type_menu' => 'modules']);
});
Route::get('/modules-sparkline', function () {
    return view('pages.modules-sparkline', ['type_menu' => 'modules']);
});
Route::get('/modules-sweet-alert', function () {
    return view('pages.modules-sweet-alert', ['type_menu' => 'modules']);
});
Route::get('/modules-toastr', function () {
    return view('pages.modules-toastr', ['type_menu' => 'modules']);
});
Route::get('/modules-vector-map', function () {
    return view('pages.modules-vector-map', ['type_menu' => 'modules']);
});
Route::get('/modules-weather-icon', function () {
    return view('pages.modules-weather-icon', ['type_menu' => 'modules']);
});

// auth
Route::get('/auth-forgot-password', function () {
    return view('pages.auth-forgot-password', ['type_menu' => 'auth']);
});
Route::get('/auth-login', function () {
    return view('pages.auth-login', ['type_menu' => 'auth']);
});
Route::get('/auth-login2', function () {
    return view('pages.auth-login2', ['type_menu' => 'auth']);
});
Route::get('/auth-register', function () {
    return view('pages.auth-register', ['type_menu' => 'auth']);
});
Route::get('/auth-reset-password', function () {
    return view('pages.auth-reset-password', ['type_menu' => 'auth']);
});

// error
Route::get('/error-403', function () {
    return view('pages.error-403', ['type_menu' => 'error']);
});
Route::get('/error-404', function () {
    return view('pages.error-404', ['type_menu' => 'error']);
});
Route::get('/error-500', function () {
    return view('pages.error-500', ['type_menu' => 'error']);
});
Route::get('/error-503', function () {
    return view('pages.error-503', ['type_menu' => 'error']);
});

// features
Route::get('/features-activities', function () {
    return view('pages.features-activities', ['type_menu' => 'features']);
});
Route::get('/features-post-create', function () {
    return view('pages.features-post-create', ['type_menu' => 'features']);
});
Route::get('/features-post', function () {
    return view('pages.features-post', ['type_menu' => 'features']);
});
Route::get('/features-profile', function () {
    return view('pages.features-profile', ['type_menu' => 'features']);
});
Route::get('/features-settings', function () {
    return view('pages.features-settings', ['type_menu' => 'features']);
});
Route::get('/features-setting-detail', function () {
    return view('pages.features-setting-detail', ['type_menu' => 'features']);
});
Route::get('/features-tickets', function () {
    return view('pages.features-tickets', ['type_menu' => 'features']);
});

// utilities
Route::get('/utilities-contact', function () {
    return view('pages.utilities-contact', ['type_menu' => 'utilities']);
});
Route::get('/utilities-invoice', function () {
    return view('pages.utilities-invoice', ['type_menu' => 'utilities']);
});
Route::get('/utilities-subscribe', function () {
    return view('pages.utilities-subscribe', ['type_menu' => 'utilities']);
});

// credits
Route::get('/credits', function () {
    return view('pages.credits', ['type_menu' => '']);
});
