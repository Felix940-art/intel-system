<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\FrequencyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SreDashboardController;
use App\Http\Controllers\SreEntryController;
use App\Http\Controllers\GeoIntController;
use App\Http\Controllers\DForensicsController;
use App\Models\ActivityLog;

/*
|--------------------------------------------------------------------------
| ACTIVITY LOGS (ADMIN ONLY)
|--------------------------------------------------------------------------
*/

Route::get('/activity-logs', function () {
    return view('admin.activity_logs', [
        'logs' => ActivityLog::with('user')
            ->latest()
            ->paginate(50)
    ]);
})->middleware('auth', 'role:Admin')->name('activity-logs');

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USERS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | SIGINT ROOT
    |--------------------------------------------------------------------------
    */

    Route::get('/sigint', function () {
        return view('sigint.index');
    })->name('sigint');



    /*
    |--------------------------------------------------------------------------
    | SIGINT - FREQUENCY DATABASE
    |--------------------------------------------------------------------------
    */

    Route::prefix('sigint/frequency')
        ->name('sigint.frequency.')
        ->middleware(['role:Admin,Operator', 'module:sigint'])
        ->group(function () {

            Route::get('/', [FrequencyController::class, 'index'])->name('index');

            Route::get('/create', [FrequencyController::class, 'create'])->name('create');

            Route::post('/store', [FrequencyController::class, 'store'])->name('store');

            Route::get('/check-duplicate', [FrequencyController::class, 'checkDuplicate'])
                ->name('check-duplicate');

            /*
            | ADMIN ONLY
            */

            Route::middleware('role:Admin')->group(function () {

                Route::get('/analytics', [FrequencyController::class, 'analytics'])->name('analytics');

                Route::post('/import', [FrequencyController::class, 'import'])->name('import');

                Route::get('/export', [FrequencyController::class, 'export'])->name('export');

                Route::get('/export/pdf', [FrequencyController::class, 'exportPdf'])->name('export.pdf');

                Route::get('/{frequency}/edit', [FrequencyController::class, 'edit'])->name('edit');

                Route::put('/{frequency}', [FrequencyController::class, 'update'])->name('update');

                Route::delete('/{frequency}', [FrequencyController::class, 'destroy'])->name('destroy');

                Route::patch(
                    '/{frequency}/watchlist',
                    [FrequencyController::class, 'toggleWatchlist']
                )->name('watchlist');
            });
        });



    /*
    |--------------------------------------------------------------------------
    | SIGINT - SRE
    |--------------------------------------------------------------------------
    */

    Route::prefix('sigint/sre')
        ->name('sigint.sre.')
        ->middleware(['role:Admin,Operator', 'module:sigint'])
        ->group(function () {

            Route::get('/', [SreDashboardController::class, 'index'])->name('index');

            Route::get('/create', [SreEntryController::class, 'create'])->name('create');

            Route::post('/', [SreEntryController::class, 'store'])->name('store');

            Route::get('/{event}/edit', [SreEntryController::class, 'edit'])->name('edit');

            Route::put('/{event}', [SreEntryController::class, 'update'])->name('update');

            Route::delete('/{event}', [SreEntryController::class, 'destroy'])->name('destroy');

            Route::post('/import', [SreEntryController::class, 'import'])->name('import');

            Route::get('/export', [SreEntryController::class, 'export'])->name('export');

            Route::get('/export/pdf', [SreEntryController::class, 'exportPdf'])->name('export.pdf');
        });



    /*
    |--------------------------------------------------------------------------
    | GEOINT MODULE
    |--------------------------------------------------------------------------
    */

    Route::prefix('geoint')
        ->name('geoint.')
        ->middleware(['role:Admin,Operator', 'module:geoint'])
        ->group(function () {

            Route::get('/', [GeoIntController::class, 'index'])->name('index');

            Route::get('/create', [GeoIntController::class, 'create'])->name('create');

            Route::post('/', [GeoIntController::class, 'store'])->name('store');

            Route::get('/{record}/edit', [GeoIntController::class, 'edit'])->name('edit');

            Route::put('/{record}', [GeoIntController::class, 'update'])->name('update');

            Route::delete('/{record}', [GeoIntController::class, 'destroy'])->name('destroy');

            Route::get('/uav-intel', [GeoIntController::class, 'uavIntel'])->name('uav-intel');

            Route::get('/analytics', [GeoIntController::class, 'analytics'])->name('analytics');
        });



    /*
    |--------------------------------------------------------------------------
    | D-FORENSICS MODULE
    |--------------------------------------------------------------------------
    */

    Route::prefix('dforensics')
        ->name('dforensics.')
        ->middleware(['role:Admin,Operator', 'module:dforensics'])
        ->group(function () {

            Route::get('/', [DForensicsController::class, 'index'])->name('index');

            Route::get('/create', [DForensicsController::class, 'create'])->name('create');

            Route::post('/', [DForensicsController::class, 'store'])->name('store');

            Route::get('/{id}/edit', [DForensicsController::class, 'edit'])->name('edit');

            Route::put('/{id}', [DForensicsController::class, 'update'])->name('update');

            Route::delete('/{id}', [DForensicsController::class, 'destroy'])->name('destroy');

            Route::delete(
                '/document/{id}',
                [DForensicsController::class, 'deleteDocument']
            )->name('document.delete');

            Route::get(
                '/analysis',
                [DForensicsController::class, 'showAnalysis']
            )->name('analysis');
        });
});


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
