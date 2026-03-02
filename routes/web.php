<?php

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
Route::view('/rekapSolo', 'seni.rekapSolo');
Route::view('/entry-view', 'entryViews');
Route::view('/view-kp', 'monitor.kp');
Route::view('/login-monitor', 'monitor.login');
Route::get('/login-manual', function () {
    return view('auth.manual_login');
});
Route::get('/global-time', 'AdminController@globalTime');
Route::post('/delete-entry', 'AdminController@deleteEntry')->name('admin.deleteEntry');
Route::post('/create-entry', 'AdminController@createEntry')->name('admin.createEntry');

Route::middleware(['auth.custom'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.panel.arena');
    });
    Route::get('/score', function () {
        return view('loginscore');
    })->name('score');
    Route::get('/timeradmin', 'AdminController@timer')->name('admin.timer');
    Route::get('/save-time', 'RekapController@saveTime')->name('rekap.saveTime');
    Route::get('/login', function () {
        return view('auth.login');
    });

    Route::get('/login-juri', function () {
        return view('jurilogin');
    });
    Route::get('/timer', function () {
        return view('timer');
    });

    Route::get('/call-data-entry', 'AdminController@entryData')->name('callEntry');

    Route::get('/login-admin', function () {
        return view('admin.login');
    });
    Route::get('/redirect', 'AdminController@redirect');
    Route::prefix('admin')->group(function () {
        Route::post('/juri', 'AdminController@juri')->name('admin.juri');
        Route::post('/add-excel', 'AdminController@excel');
        Route::post('/excel-jadwal', 'AdminController@importJadwal');
        Route::post('/clear-data', 'AdminController@clearPeserta');
        Route::post('/clearJadwal', 'AdminController@clearJadwal');
        Route::post('/delete-jadwal', 'AdminController@deleteJadwal');
        Route::resource('/panel', AdminController::class);
        Route::prefix('panels')->group(function () {
            Route::get('/kelas', function () {
                $status = 'admin';
                return view('admin.kelas', compact('status'));
            });
            Route::post('/add-kelas', 'AdminController@modifyKelas')->name('admin.modifyKelas');
            Route::post('/arena-core', 'AdminController@modifyArena')->name('admin.arenamodify');
            Route::post('/arena-data', 'AdminController@getArenaData')->name('admin.getarena');
            Route::post('/get-peserta', 'AdminController@getPeserta')->name('admin.getPeserta');
            Route::post('/peserta/new', 'AdminController@newPeserta')->name('admin.newPeserta');
            Route::post('/peserta/edit', 'AdminController@editPeserta')->name('admin.editPeserta');
            Route::post('/peserta/clear', 'AdminController@clearPeserta')->name('admin.clearPeserta');
            Route::post('/sesi/new', 'AdminController@newSesi')->name('admin.addSesi');
            Route::post('/poll/new', 'AdminController@newPoll')->name('admin.addPoll');
            Route::post('/sesi/delete', 'AdminController@deleteSesi')->name('admin.deleteSesi');
            Route::post('/poll/delete', 'AdminController@deletePoll')->name('admin.deletePoll');
            Route::post('/add-peserta/{arena}', 'AdminController@addpesertas')->name('admin.addpesertas');
            Route::post('/edit-jadwal', 'AdminController@editJadwal')->name('admin.editjadwal');
            Route::get('/perserta', function () {
                $status = 'admin';
                return view('admin.perserta', compact('status'));
            });
            Route::get('/rekap-medali', function () {
                $status = 'admin';
                return view('admin.rekap-medali', compact('status'));
            });
            Route::get('/kontigen', function () {
                $status = 'admin';
                return view('admin.kontigen', compact('status'));
            });
            Route::post('/add-kontigen', 'AdminController@modifyKontigen')->name('admin.modifyKontigen');
            Route::get('/juri', function () {
                $status = 'admin';
                return view('admin.juri', compact('status'));
            });
            Route::prefix('settings')->group(function () {
                Route::get('/', function () {
                    $status = 'admin';
                    return view('admin.settings', compact('status'));
                });
                Route::post('/store', 'AdminController@modifySettings')->name('setting.store');
            });
            Route::get('/arena', function () {
                $status = 'admin';
                return view('admin.arena', compact('status'));
            })->name('admin.panel.arena');

            Route::get('/category', function () {
                $status = 'admin';
                return view('admin.category', compact('status'));
            });
            Route::post('add-category', 'AdminController@modifyCategory')->name('admin.modifyCategory');
        });
        Route::post('/arena-store', 'AdminController@arenastore')->name('arena.store');
        Route::get('/arena', 'AdminController@arena');
    });
    Route::prefix('tanding')->group(function () {
        Route::resource('juri', JuriController::class);
        Route::resource('dewan', DewanController::class);
        Route::get('/', function () {
            return view('penilaian.score');
        });
    });
    Route::prefix('seni')->group(function () {
        Route::resource('juri-seni', JuriSeniController::class);
        Route::resource('dewan-seni', DewanSeniController::class);
        Route::get('/', function () {
            return view('seni.score');
        });
        Route::get('/juri-solo', function () {
            return view('seni.ganda.juri');
        });
        Route::get('/juri-tunggal', function () {
            return view('seni.tunggal.juri');
        });
        Route::get('/dewan-solo', function () {
            return view('seni.ganda.dewan');
        });
        Route::get('/dewan-tunggal', function () {
            return view('seni.tunggal.dewan');
        });
    });
    Route::get('/sse', 'JuriController@stream');
    Route::post('/search-peserta', 'AdminController@searchPeserta');
    Route::post('/search-peserta-full', 'AdminController@searchPesertaFull');
    Route::get('/call-data', 'JuriController@data');
    Route::get('/take-timer-data', 'RekapController@takeTimer');
    Route::get('/rekapseni', 'RekapController@senirekap');
    Route::post('/rekapsend', 'RekapController@senidata')->name('rekap.seni.data');
    Route::post('/rekaptunggal', 'RekapController@senidata')->name('rekap.tunggal.data');
    Route::get('/call-data/seni', 'JuriController@dataseni');
    Route::get('/jadwal/score-seni', 'JuriController@scorejadwal')->name('jadwal.seni');

});
Route::get('/login', 'AuthController@index')->name('login');
Route::post('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::get('/socket', function () {
    return view('addon.tanding.test');
});