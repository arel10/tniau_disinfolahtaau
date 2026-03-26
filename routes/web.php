<?php

use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\BeritaPelayananController;
use App\Http\Controllers\Admin\CustomMenuController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ELibraryAdminController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\GaleriController as AdminGaleriController;
use App\Http\Controllers\Admin\HasilSurveiController;
use App\Http\Controllers\Admin\HubungiKamiController;
use App\Http\Controllers\Admin\InstansiTerkaitController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KategoriGaleriController;
use App\Http\Controllers\Admin\KompensasiPelayananController;
use App\Http\Controllers\Admin\KontakController as AdminKontakController;
use App\Http\Controllers\Admin\LayananPengaduanController;
use App\Http\Controllers\Admin\LogoFooterController;
use App\Http\Controllers\Admin\MediaSosialController;
use App\Http\Controllers\Admin\MenuUtamaController;
use App\Http\Controllers\Admin\PiaAdminController;
use App\Http\Controllers\Admin\PiaLogoItemController;
use App\Http\Controllers\Admin\ProfilController as AdminProfilController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\Sp4nLaporController;
use App\Http\Controllers\Admin\StandarPelayananController;
use App\Http\Controllers\Admin\StrukturController;
use App\Http\Controllers\Admin\SurveiKepuasanController;
use App\Http\Controllers\Admin\TutorialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WhistleBlowingController as AdminWhistleBlowingController;
use App\Http\Controllers\Admin\ZiPageController;
use App\Http\Controllers\Admin\ZiPembangunanController;
use App\Http\Controllers\Admin\ZiPemantauanController;
use App\Http\Controllers\Admin\ZiPenetapanController;
use App\Http\Controllers\Admin\ZiPenetapanKategoriController;
use App\Http\Controllers\Admin\ZiPerancanganController;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\BeritaPelayananPublicController;
use App\Http\Controllers\CustomPageController;
use App\Http\Controllers\ELibraryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\HasilSurveiPublicController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KompensasiPelayananPublicController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LayananPengaduanPublicController;
use App\Http\Controllers\PiaPageController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Sp4nLaporPublicController;
use App\Http\Controllers\StandarPelayananPublicController;
use App\Http\Controllers\SurveiKepuasanPublicController;
use App\Http\Controllers\VisitorChatController;
use App\Http\Controllers\WhistleBlowingController;
use App\Http\Controllers\ZiPublicController;
use Illuminate\Support\Facades\Route;

// ================= Live Chat (Public API) =================
Route::prefix('livechat')->group(function () {
    Route::get('/status', [VisitorChatController::class, 'status'])->name('livechat.status');
    Route::post('/start', [VisitorChatController::class, 'startOrResume'])->name('livechat.start');
    Route::post('/send', [VisitorChatController::class, 'send'])->name('livechat.send');
    Route::get('/poll', [VisitorChatController::class, 'poll'])->name('livechat.poll');
    Route::post('/end', [VisitorChatController::class, 'end'])->name('livechat.end');
    Route::post('/page-state', [VisitorChatController::class, 'recordVisitorPageState'])->name('livechat.page-state');
    Route::post('/reset', [VisitorChatController::class, 'resetVisitorState'])->name('livechat.reset');
});

// ================= e-Library =================
// Public
Route::get('/e-library', [ELibraryController::class, 'index'])->name('e-library.index');
Route::get('/e-library/{slug}', [ELibraryController::class, 'show'])->name('e-library.show');
Route::post('/e-library/{slug}/track-view', [ELibraryController::class, 'trackView'])->name('e-library.track-view');
Route::get('/e-library/{slug}/download', [ELibraryController::class, 'download'])->name('e-library.download');

// Admin
Route::middleware(['auth'])->prefix('admin/e-library')->name('admin.e-library.')->group(function () {
    Route::get('/', [ELibraryAdminController::class, 'index'])->name('index');
    Route::get('/create', [ELibraryAdminController::class, 'create'])->name('create');
    Route::post('/', [ELibraryAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ELibraryAdminController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ELibraryAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [ELibraryAdminController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/move-up', [ELibraryAdminController::class, 'moveUp'])->name('move-up');
    Route::post('/{id}/move-down', [ELibraryAdminController::class, 'moveDown'])->name('move-down');
    Route::post('/{id}/toggle-publish', [ELibraryAdminController::class, 'togglePublish'])->name('toggle-publish');
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Berita
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/kategori/{slug}', [BeritaController::class, 'byKategori'])->name('berita.kategori');

// Galeri
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');
Route::get('/galeri/kategori/{kategori}', [GaleriController::class, 'byKategori'])->name('galeri.kategori');
// Album (group) view and item viewer
Route::get('/galeri/album/{group}', [GaleriController::class, 'album'])->name('galeri.album');
Route::get('/galeri/album/{group}/item/{galeri}', [GaleriController::class, 'albumItem'])->name('galeri.album.item');
Route::get('/galeri/{galeri}', [GaleriController::class, 'show'])->name('galeri.show');

// Zona Integritas (public, dipisah)
Route::get('/zona-integritas', [ZiPublicController::class, 'zonaIntegritas'])->name('zona.index');
Route::get('/zona-integritas/perancangan', [ZiPublicController::class, 'perancangan'])->name('zona.perancangan');
Route::get('/zona-integritas/penetapan', [ZiPublicController::class, 'penetapan'])->name('zona.penetapan');
Route::get('/zona-integritas/pembangunan', [ZiPublicController::class, 'pembangunan'])->name('zona.pembangunan');
Route::get('/zona-integritas/pemantauan', [ZiPublicController::class, 'pemantauan'])->name('zona.pemantauan');

// Placeholder pages
Route::get('/pia', [PiaPageController::class, 'index'])->name('pia');
Route::get('/pelayanan-publik/berita', [BeritaPelayananPublicController::class, 'index'])->name('pelayanan.berita');
Route::get('/pelayanan-publik/standar', [StandarPelayananPublicController::class, 'index'])->name('pelayanan.standar');
Route::get('/pelayanan-publik/pengaduan', [LayananPengaduanPublicController::class, 'index'])->name('pelayanan.pengaduan');
Route::get('/pelayanan-publik/kompensasi', [KompensasiPelayananPublicController::class, 'index'])->name('pelayanan.kompensasi');
Route::get('/pelayanan-publik/survei', [SurveiKepuasanPublicController::class, 'index'])->name('pelayanan.survei');
Route::get('/pelayanan-publik/hasil-survei', [HasilSurveiPublicController::class, 'index'])->name('pelayanan.hasil-survei');
Route::get('/tutorial', function () {
    $tutorials = \App\Models\Tutorial::latest()->get();

    return view('public.tutorial', compact('tutorials'));
})->name('tutorial');
Route::get('/sp4n-lapor', [Sp4nLaporPublicController::class, 'index'])->name('sp4n-lapor');
Route::get('/whistle-blowing', [WhistleBlowingController::class, 'index'])->name('whistle-blowing');
// Events public
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Kontak
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak.index');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

// Profil
Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
Route::get('/profil/kata-pengantar', [ProfilController::class, 'kataPengantar'])->name('profil.kata-pengantar');
Route::get('/profil/struktur-organisasi', [ProfilController::class, 'strukturOrganisasi'])->name('profil.struktur');
Route::get('/profil/sejarah', [ProfilController::class, 'sejarah'])->name('profil.sejarah');

// Language Switcher
Route::get('/lang/{lang}', [LanguageController::class, 'switch'])->name('lang.switch');

// Custom Pages (dynamic menus)
Route::get('/halaman/{slug}/{childSlug?}', [CustomPageController::class, 'show'])->name('custom.page');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===== FORGOT PASSWORD OTP =====
Route::get('/forgot-password-otp', [ForgotPasswordOtpController::class, 'showRequestForm'])->name('password.otp.request');
Route::post('/forgot-password-otp/send', [ForgotPasswordOtpController::class, 'sendOtp'])->name('password.otp.send');
Route::get('/forgot-password-otp/verify', [ForgotPasswordOtpController::class, 'showVerifyForm'])->name('password.otp.verify.form');
Route::post('/forgot-password-otp/reset', [ForgotPasswordOtpController::class, 'verifyAndReset'])->name('password.otp.reset');

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected by AdminMiddleware)
|--------------------------------------------------------------------------
*/

Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {

    // Preview Site (Lihat Website) - set session flag lalu redirect ke home
    Route::get('/preview-site', function () {
        session(['from_admin_site_preview' => true]);

        return redirect()->route('home');
    })->name('preview-site');

    // Exit Preview - hapus session flag lalu kembali ke admin dashboard
    Route::get('/exit-preview', function () {
        session()->forget('from_admin_site_preview');

        return redirect()->route('admin.dashboard');
    })->name('exit-preview');

    // Admin Profile
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // SP4N-lapor!
    Route::prefix('sp4n-lapor')->name('sp4n-lapor.')->group(function () {
        Route::get('/', [Sp4nLaporController::class, 'index'])->name('index');
        Route::post('/', [Sp4nLaporController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [Sp4nLaporController::class, 'edit'])->name('edit');
        Route::put('/{id}', [Sp4nLaporController::class, 'update'])->name('update');
        Route::delete('/{id}', [Sp4nLaporController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-publish', [Sp4nLaporController::class, 'togglePublish'])->name('toggle-publish');
    });
    // e-Library redirect (old URL)
    Route::get('elibrary', function () {
        return redirect()->route('admin.e-library.index');
    })->name('elibrary.index');
    // Events Management (admin)
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [AdminEventController::class, 'index'])->name('index');
        Route::get('/create', [AdminEventController::class, 'create'])->name('create');
        Route::post('/', [AdminEventController::class, 'store'])->name('store');
        Route::get('/{event}', [AdminEventController::class, 'show'])->name('show');
        Route::get('/{event}/edit', [AdminEventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [AdminEventController::class, 'update'])->name('update');
        Route::delete('/{event}', [AdminEventController::class, 'destroy'])->name('destroy');
        Route::post('/{event}/toggle-publish', [AdminEventController::class, 'togglePublish'])->name('toggle-publish');
        Route::post('/{event}/media', [AdminEventController::class, 'addMedia'])->name('media.store');
        Route::delete('/{event}/media/{media}', [AdminEventController::class, 'destroyMedia'])->name('media.destroy');
    });

    // Berita Management
    Route::resource('berita', AdminBeritaController::class)->parameters(['berita' => 'berita']);

    // Kategori Management
    Route::resource('kategori', KategoriController::class)->parameters(['kategori' => 'kategori']);

    // Kategori Galeri Management
    Route::resource('kategori-galeri', KategoriGaleriController::class)->parameters(['kategori_galeri' => 'kategoriGaleri']);

    // Galeri Management
    Route::resource('galeri', AdminGaleriController::class)->parameters(['galeri' => 'galeri']);

    // Kontak Management
    Route::get('kontak', [AdminKontakController::class, 'index'])->name('kontak.index');
    Route::get('kontak/{kontak}', [AdminKontakController::class, 'show'])->name('kontak.show');
    Route::post('kontak/{kontak}/status', [AdminKontakController::class, 'updateStatus'])->name('kontak.updateStatus');
    Route::delete('kontak/{kontak}', [AdminKontakController::class, 'destroy'])->name('kontak.destroy');
    Route::post('kontak/mark-as-read', [AdminKontakController::class, 'markAsRead'])->name('kontak.markAsRead');
    Route::post('kontak/bulk-delete', [AdminKontakController::class, 'bulkDelete'])->name('kontak.bulkDelete');

    // Struktur Organisasi Management (edit only)
    Route::get('struktur', [StrukturController::class, 'index'])->name('struktur.index');
    Route::get('struktur/{struktur}/edit', [StrukturController::class, 'edit'])->name('struktur.edit');
    Route::put('struktur/{struktur}', [StrukturController::class, 'update'])->name('struktur.update');

    // Whistle Blowing Management
    Route::get('whistle-blowing', [AdminWhistleBlowingController::class, 'index'])->name('whistle-blowing.index');
    Route::get('whistle-blowing/create', [AdminWhistleBlowingController::class, 'create'])->name('whistle-blowing.create');
    Route::post('whistle-blowing', [AdminWhistleBlowingController::class, 'store'])->name('whistle-blowing.store');
    Route::get('whistle-blowing/{whistleBlowing}/edit', [AdminWhistleBlowingController::class, 'edit'])->name('whistle-blowing.edit');
    Route::put('whistle-blowing/{whistleBlowing}', [AdminWhistleBlowingController::class, 'update'])->name('whistle-blowing.update');
    Route::delete('whistle-blowing/{whistleBlowing}', [AdminWhistleBlowingController::class, 'destroy'])->name('whistle-blowing.destroy');

    // Tutorial Management
    Route::resource('tutorial', TutorialController::class);

    // Pelayanan Publik Management
    Route::prefix('pelayanan-publik')->name('pelayanan-publik.')->group(function () {
        // Berita Pelayanan Publik CRUD
        Route::prefix('berita')->name('berita.')->group(function () {
            Route::get('/', [BeritaPelayananController::class, 'index'])->name('index');
            Route::post('/', [BeritaPelayananController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [BeritaPelayananController::class, 'edit'])->name('edit');
            Route::put('/{id}', [BeritaPelayananController::class, 'update'])->name('update');
            Route::delete('/{id}', [BeritaPelayananController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-publish', [BeritaPelayananController::class, 'togglePublish'])->name('toggle-publish');
        });
        // Standar Pelayanan Publik CRUD
        Route::prefix('standar')->name('standar.')->group(function () {
            Route::get('/', [StandarPelayananController::class, 'index'])->name('index');
            Route::post('/', [StandarPelayananController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [StandarPelayananController::class, 'edit'])->name('edit');
            Route::put('/{id}', [StandarPelayananController::class, 'update'])->name('update');
            Route::delete('/{id}', [StandarPelayananController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-publish', [StandarPelayananController::class, 'togglePublish'])->name('toggle-publish');
        });
        Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
            Route::get('/', [LayananPengaduanController::class, 'index'])->name('index');
            Route::post('/', [LayananPengaduanController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [LayananPengaduanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [LayananPengaduanController::class, 'update'])->name('update');
            Route::delete('/{id}', [LayananPengaduanController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-publish', [LayananPengaduanController::class, 'togglePublish'])->name('toggle-publish');
        });
        Route::prefix('kompensasi')->name('kompensasi.')->group(function () {
            Route::get('/', [KompensasiPelayananController::class, 'index'])->name('index');
            Route::post('/', [KompensasiPelayananController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [KompensasiPelayananController::class, 'edit'])->name('edit');
            Route::put('/{id}', [KompensasiPelayananController::class, 'update'])->name('update');
            Route::delete('/{id}', [KompensasiPelayananController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-publish', [KompensasiPelayananController::class, 'togglePublish'])->name('toggle-publish');
        });
        Route::prefix('survei')->name('survei.')->group(function () {
            Route::get('/', [SurveiKepuasanController::class, 'index'])->name('index');
            Route::post('/', [SurveiKepuasanController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [SurveiKepuasanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SurveiKepuasanController::class, 'update'])->name('update');
            Route::delete('/{id}', [SurveiKepuasanController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-publish', [SurveiKepuasanController::class, 'togglePublish'])->name('toggle-publish');
        });
        Route::prefix('hasil-survei')->name('hasil-survei.')->group(function () {
            Route::get('/', [HasilSurveiController::class, 'index'])->name('index');
            Route::post('/', [HasilSurveiController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [HasilSurveiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [HasilSurveiController::class, 'update'])->name('update');
            Route::delete('/{id}', [HasilSurveiController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-publish', [HasilSurveiController::class, 'togglePublish'])->name('toggle-publish');
        });
    });

    // Profil Management
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [AdminProfilController::class, 'index'])->name('index');
        Route::get('/kata-pengantar', [AdminProfilController::class, 'kataPengantar'])->name('kata-pengantar');
        Route::put('/kata-pengantar', [AdminProfilController::class, 'kataPengantarUpdate'])->name('kata-pengantar.update');
        Route::get('/tentang', [AdminProfilController::class, 'tentang'])->name('tentang');
        Route::put('/tentang', [AdminProfilController::class, 'tentangUpdate'])->name('tentang.update');
        Route::get('/sejarah', [AdminProfilController::class, 'sejarah'])->name('sejarah');
        Route::post('/sejarah', [AdminProfilController::class, 'sejarahStore'])->name('sejarah.store');
        Route::delete('/sejarah/{id}', [AdminProfilController::class, 'sejarahDestroy'])->name('sejarah.destroy');
    });

    // Zona Integritas Pages (admin, per type)
    Route::prefix('zona-integritas/pages')->name('zi.pages.')->group(function () {
        Route::get('{type}', [ZiPageController::class, 'index'])->name('index');
        Route::get('{type}/create', [ZiPageController::class, 'create'])->name('create');
        Route::post('{type}', [ZiPageController::class, 'store'])->name('store');
        Route::get('{type}/{ziPage}/edit', [ZiPageController::class, 'edit'])->name('edit');
        Route::put('{type}/{ziPage}', [ZiPageController::class, 'update'])->name('update');
        Route::delete('{type}/{ziPage}', [ZiPageController::class, 'destroy'])->name('destroy');
    });

    // Penetapan Management
    Route::prefix('zona-integritas/penetapan')->name('zi.penetapan.')->group(function () {
        Route::get('/', [ZiPenetapanController::class, 'index'])->name('index');
        Route::get('/create', [ZiPenetapanController::class, 'create'])->name('create');
        Route::post('/', [ZiPenetapanController::class, 'store'])->name('store');
        Route::get('/{penetapan}/edit', [ZiPenetapanController::class, 'edit'])->name('edit');
        Route::put('/{penetapan}', [ZiPenetapanController::class, 'update'])->name('update');
        Route::delete('/{penetapan}', [ZiPenetapanController::class, 'destroy'])->name('destroy');

        // Kategori Penetapan Management
        Route::prefix('kategori')->name('kategori.')->group(function () {
            Route::get('/', [ZiPenetapanKategoriController::class, 'index'])->name('index');
            Route::get('/create', [ZiPenetapanKategoriController::class, 'create'])->name('create');
            Route::post('/', [ZiPenetapanKategoriController::class, 'store'])->name('store');
            Route::get('/{kategori}/edit', [ZiPenetapanKategoriController::class, 'edit'])->name('edit');
            Route::put('/{kategori}', [ZiPenetapanKategoriController::class, 'update'])->name('update');
            Route::delete('/{kategori}', [ZiPenetapanKategoriController::class, 'destroy'])->name('destroy');
        });
    });

    // PERANCANGAN Management (admin)
    Route::prefix('zona-integritas/perancangan')->name('zi.perancangan.')->group(function () {
        Route::get('/', [ZiPerancanganController::class, 'index'])->name('index');
        Route::get('/create', [ZiPerancanganController::class, 'create'])->name('create');
        Route::post('/', [ZiPerancanganController::class, 'store'])->name('store');
        Route::get('/{perancangan}/edit', [ZiPerancanganController::class, 'edit'])->name('edit');
        Route::put('/{perancangan}', [ZiPerancanganController::class, 'update'])->name('update');
        Route::delete('/{perancangan}', [ZiPerancanganController::class, 'destroy'])->name('destroy');
        Route::delete('/photo/{photo}', [ZiPerancanganController::class, 'destroyPhoto'])->name('photo.destroy');
    });

    // Pembangunan Management (admin)
    Route::prefix('zona-integritas/pembangunan')->name('zi.pembangunan.')->group(function () {
        Route::get('/', [ZiPembangunanController::class, 'index'])->name('index');
        Route::get('/create', [ZiPembangunanController::class, 'create'])->name('create');
        Route::post('/', [ZiPembangunanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ZiPembangunanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ZiPembangunanController::class, 'update'])->name('update');
        Route::delete('/{id}', [ZiPembangunanController::class, 'destroy'])->name('destroy');
    });

    // Pemantauan Management (admin)
    Route::prefix('zona-integritas/pemantauan')->name('zi.pemantauan.')->group(function () {
        Route::get('/', [ZiPemantauanController::class, 'index'])->name('index');
        Route::get('/create', [ZiPemantauanController::class, 'create'])->name('create');
        Route::post('/', [ZiPemantauanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ZiPemantauanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ZiPemantauanController::class, 'update'])->name('update');
        Route::delete('/{id}', [ZiPemantauanController::class, 'destroy'])->name('destroy');
    });

    // Setting Management
    Route::get('setting/background', [SettingController::class, 'background'])->name('setting.background');
    Route::put('setting/background', [SettingController::class, 'backgroundUpdate'])->name('setting.background.update');
    Route::get('setting/alamat', [SettingController::class, 'alamat'])->name('setting.alamat');
    Route::put('setting/alamat', [SettingController::class, 'alamatUpdate'])->name('setting.alamat.update');
    Route::get('setting/instansi-terkait', [InstansiTerkaitController::class, 'index'])->name('setting.instansi-terkait');
    Route::post('setting/instansi-terkait', [InstansiTerkaitController::class, 'store'])->name('setting.instansi-terkait.store');
    Route::put('setting/instansi-terkait/{instansiTerkait}', [InstansiTerkaitController::class, 'update'])->name('setting.instansi-terkait.update');
    Route::delete('setting/instansi-terkait/{instansiTerkait}', [InstansiTerkaitController::class, 'destroy'])->name('setting.instansi-terkait.destroy');
    Route::get('setting/media-sosial', [MediaSosialController::class, 'index'])->name('setting.media-sosial');
    Route::post('setting/media-sosial', [MediaSosialController::class, 'store'])->name('setting.media-sosial.store');
    Route::put('setting/media-sosial/{mediaSosial}', [MediaSosialController::class, 'update'])->name('setting.media-sosial.update');
    Route::delete('setting/media-sosial/{mediaSosial}', [MediaSosialController::class, 'destroy'])->name('setting.media-sosial.destroy');
    Route::get('setting/hubungi-kami', [HubungiKamiController::class, 'index'])->name('setting.hubungi-kami');
    Route::post('setting/hubungi-kami', [HubungiKamiController::class, 'store'])->name('setting.hubungi-kami.store');
    Route::put('setting/hubungi-kami/{hubungiKami}', [HubungiKamiController::class, 'update'])->name('setting.hubungi-kami.update');
    Route::delete('setting/hubungi-kami/{hubungiKami}', [HubungiKamiController::class, 'destroy'])->name('setting.hubungi-kami.destroy');

    Route::get('setting/menu-utama', [MenuUtamaController::class, 'index'])->name('setting.menu-utama');
    Route::post('setting/menu-utama', [MenuUtamaController::class, 'store'])->name('setting.menu-utama.store');
    Route::delete('setting/menu-utama/{menuUtama}', [MenuUtamaController::class, 'destroy'])->name('setting.menu-utama.destroy');

    Route::get('setting/logo-footer', [LogoFooterController::class, 'index'])->name('setting.logo-footer');
    Route::put('setting/logo-footer', [LogoFooterController::class, 'update'])->name('setting.logo-footer.update');

    // PIA Management
    Route::prefix('pia')->name('pia.')->group(function () {
        Route::get('/', [PiaAdminController::class, 'index'])->name('index');
        Route::put('/history', [PiaAdminController::class, 'updateHistory'])->name('history.update');
        Route::delete('/history', [PiaAdminController::class, 'destroyHistory'])->name('history.destroy');
        Route::get('/history/revisions', [PiaAdminController::class, 'revisions'])->name('history.revisions');
        Route::post('/logo-items', [PiaLogoItemController::class, 'store'])->name('logo-items.store');
        Route::put('/logo-items/{logoItem}', [PiaLogoItemController::class, 'update'])->name('logo-items.update');
        Route::delete('/logo-items/{logoItem}', [PiaLogoItemController::class, 'destroy'])->name('logo-items.destroy');
    });

    // Custom Menu Builder
    Route::prefix('custom-menu')->name('custom-menu.')->group(function () {
        Route::get('/', [CustomMenuController::class, 'index'])->name('index');
        Route::post('/', [CustomMenuController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CustomMenuController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CustomMenuController::class, 'update'])->name('update');
        Route::delete('/{id}', [CustomMenuController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-publish', [CustomMenuController::class, 'togglePublish'])->name('toggle-publish');
        Route::post('/reorder', [CustomMenuController::class, 'reorder'])->name('reorder');
        // Widget management
        Route::post('/{menuId}/widget', [CustomMenuController::class, 'addWidget'])->name('widget.add');
        Route::post('/{menuId}/widgets/save', [CustomMenuController::class, 'saveWidgets'])->name('widgets.save');
        Route::get('/{menuId}/widget/{widgetId}/remove', [CustomMenuController::class, 'removeWidget'])->name('widget.remove');
        Route::post('/{menuId}/widgets/reorder', [CustomMenuController::class, 'reorderWidgets'])->name('widgets.reorder');
        Route::post('/{menuId}/template/apply', [CustomMenuController::class, 'applyTemplate'])->name('template.apply');
    });

    // Live Chat
    Route::prefix('live-chat')->name('live-chat.')->group(function () {
        Route::get('/', [AdminChatController::class, 'index'])->name('index');
        Route::delete('/', [AdminChatController::class, 'destroySelected'])->name('destroy-selected');
        Route::get('/{chatSession}', [AdminChatController::class, 'show'])->name('show');
        Route::post('/{chatSession}/reply', [AdminChatController::class, 'sendReply'])->name('reply');
        Route::get('/{chatSession}/poll', [AdminChatController::class, 'poll'])->name('poll');
        Route::delete('/{chatSession}/messages/{chatMessage}', [AdminChatController::class, 'destroyMessage'])->name('messages.destroy');
    });

    // User Management (Admin only)
    Route::resource('users', UserController::class)->except(['show']);
});
