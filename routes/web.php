<?php
use App\Http\Controllers\Demo1\SecretaryServiceRequestController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Demo1\UsersController;
use App\Livewire\Auth\ForgotPasswordForm;
use App\Livewire\Auth\LoginForm;
use App\Livewire\Auth\RegisterForm;
use App\Livewire\Auth\ResetPasswordForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Demo1\ServiceRequestController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Guest Routes (Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', LoginForm::class)->name('login');
    Route::get('/register', RegisterForm::class)->name('register');
    Route::get('/forgot-password', ForgotPasswordForm::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPasswordForm::class)->name('password.reset');

});

    /*
|--------------------------------------------------------------------------
| Account Activation Routes
|--------------------------------------------------------------------------
*/
Route::get('/account/activate/{token}', [\App\Http\Controllers\Auth\AccountActivationController::class, 'show'])
    ->name('account.activate');

Route::post('/account/activate/{token}', [\App\Http\Controllers\Auth\AccountActivationController::class, 'activate'])
    ->name('account.activate.submit');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard - Redirection par rôle
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        $user = Auth::user();

        // Rediriger chaque rôle vers son dashboard spécifique
        return match($user->role) {
            'doctor' => view('demo1.doctor.dashboard'),
            'secretary' => view('demo1.secretary.dashboard'),
            'nurse' => view('demo1.nurse.dashboard'),
            'patient' => view('demo1.patient.dashboard'),
            default => view('demo1.dashboard'),
        };
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | User Management (Permissions gérées par UserPolicy)
    |--------------------------------------------------------------------------
    */
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('index');
        Route::get('/create', [UsersController::class, 'create'])->name('create');
        Route::post('/', [UsersController::class, 'store'])->name('store');
        Route::get('/{id}', [UsersController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UsersController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UsersController::class, 'update'])->name('update');
        Route::delete('/{id}', [UsersController::class, 'destroy'])->name('destroy');

        // Routes AJAX
        Route::patch('/{id}/toggle-status', [UsersController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/reset-password', [UsersController::class, 'resetPassword'])->name('reset-password');
    });

    /*
    |--------------------------------------------------------------------------
    | Appointment Management (Permissions gérées par AppointmentPolicy)
    |--------------------------------------------------------------------------
    */
    Route::resource('appointments', AppointmentController::class);

    // Routes AJAX pour les actions sur les rendez-vous
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::patch('{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('confirm');
        Route::patch('{appointment}/start', [AppointmentController::class, 'start'])->name('start');
        Route::patch('{appointment}/complete', [AppointmentController::class, 'complete'])->name('complete');
        Route::patch('{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('cancel');
        Route::post('check-availability', [AppointmentController::class, 'checkAvailability'])->name('check-availability');
    });

        /*
    |--------------------------------------------------------------------------
    | Service Requests (Demandes du site vitrine)
    |--------------------------------------------------------------------------
    */
    Route::prefix('service-requests')->name('service-requests.')->group(function () {
        Route::get('/', [ServiceRequestController::class, 'index'])->name('index');
        Route::get('/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('show');
        Route::post('/{serviceRequest}/contacted', [ServiceRequestController::class, 'markContacted'])->name('contacted');
        Route::post('/{serviceRequest}/convert', [ServiceRequestController::class, 'convertToAppointment'])->name('convert');
        Route::post('/{serviceRequest}/reject', [ServiceRequestController::class, 'reject'])->name('reject');
        Route::post('/{serviceRequest}/notes', [ServiceRequestController::class, 'addNotes'])->name('notes');
    });


        /*
    |--------------------------------------------------------------------------
    | Secretary Service Requests Actions (🔐 SECRÉTAIRE UNIQUEMENT)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:secretary')->prefix('secretary/service-requests')->name('secretary.service-requests.')->group(function () {
        // Listing et création (NOUVELLES ROUTES)
        Route::get('/', [SecretaryServiceRequestController::class, 'index'])->name('index');
        Route::get('create', [SecretaryServiceRequestController::class, 'create'])->name('create');
        Route::post('/', [SecretaryServiceRequestController::class, 'store'])->name('store');

        // Détails et actions existantes
        Route::get('{serviceRequest}', [SecretaryServiceRequestController::class, 'show'])->name('show');

        // Édition et mise à jour (✅ NOUVELLES ROUTES)
        Route::get('{serviceRequest}/edit', [SecretaryServiceRequestController::class, 'edit'])->name('edit');
        Route::put('{serviceRequest}', [SecretaryServiceRequestController::class, 'update'])->name('update');

        Route::post('/{serviceRequest}/mark-paid', [SecretaryServiceRequestController::class, 'markPaid'])->name('mark-paid');
        Route::post('/{serviceRequest}/send-to-doctor', [SecretaryServiceRequestController::class, 'sendToDoctor'])->name('send-to-doctor');
        Route::post('/{serviceRequest}/cancel-send', [SecretaryServiceRequestController::class, 'cancelSendToDoctor'])->name('cancel-send');
    });

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    })->name('logout');
});
