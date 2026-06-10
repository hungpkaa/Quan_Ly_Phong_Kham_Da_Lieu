<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminInvoiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorMedicalRecordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientAreaController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::get('/', [ServiceController::class, 'index_home'])->name('services.index_home');
Route::post('/', [SupportController::class, 'store'])->name('support.store_home');

Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/search-doctors', [DoctorController::class, 'search'])->name('doctors.search');
Route::get('/doctors/search-list', [DoctorController::class, 'search_doctors_list'])->name('doctors.search_list');
Route::get('/get-doctors/{specialty}', [DoctorController::class, 'getDoctorsBySpecialty']);

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/get-working-hours', [AdminController::class, 'getWorkingHours']);
Route::get('/getDoctorScheduleWithFutureDates/{doctor}', [AdminController::class, 'getDoctorScheduleWithFutureDates']);

Route::get('/support', [SupportController::class, 'create'])->name('support.create');
Route::post('/support', [SupportController::class, 'store'])->name('support.store');


Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');
Route::post('/chatbot/clear', [ChatbotController::class, 'clearHistory'])->name('chatbot.clear');

/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::get('/appointments/search', [AppointmentController::class, 'searchAppointments'])->name('appointments.search');
});

Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/account', [PatientAreaController::class, 'account'])->name('account');
    Route::post('/progress', [PatientAreaController::class, 'storeProgress'])->name('progress.store');
});

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/get-doctors-by-specialty', [AdminController::class, 'getDoctorsBySpecialty']);

    Route::post('/services/store', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::post('/services/{id}/update', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/doctors', [AdminController::class, 'showDoctors'])->name('doctors.index');
        Route::post('/doctors', [AdminController::class, 'storeDoctor'])->name('doctors.store');
        Route::get('/doctors/{id}/edit', [AdminController::class, 'editDoctor'])->name('doctors.edit');
        Route::post('/doctors/{id}/update', [AdminController::class, 'updateDoctor'])->name('doctors.update');
        Route::delete('/doctors/{id}', [AdminController::class, 'destroyDoctor'])->name('doctors.destroy');

        Route::get('/appointments', [AdminController::class, 'showAppointments'])->name('appointments.index');
        Route::post('/appointments/store', [AdminController::class, 'storeAppointment'])->name('appointments.store');
        Route::post('/appointments/{id}/update', [AdminController::class, 'updateAppointment'])->name('appointments.update');
        Route::delete('/appointments/{id}', [AdminController::class, 'deleteAppointment'])->name('appointments.destroy');
        Route::put('/appointments/{id}/approve', [AdminController::class, 'approveAppointment'])->name('appointments.approve');
        Route::put('/appointments/{id}/reject', [AdminController::class, 'rejectAppointment'])->name('appointments.reject');
        Route::get('/patients', [AdminController::class, 'showAllPatients'])->name('patients');

        Route::get('/medicalrecords', [MedicalRecordController::class, 'index'])->name('medicalrecords.index');
        Route::get('/medicalrecords/{id}/edit', [MedicalRecordController::class, 'edit'])->name('medicalrecords.edit');
        Route::post('/medicalrecords', [MedicalRecordController::class, 'store'])->name('medicalrecords.store');
        Route::put('/medicalrecords/{id}', [MedicalRecordController::class, 'update'])->name('medicalrecords.update');
        Route::delete('/medicalrecords/{id}', [MedicalRecordController::class, 'destroy'])->name('medicalrecords.destroy');

        Route::get('/supports', [SupportController::class, 'index'])->name('supports.index');
        Route::delete('/supports/{id}', [SupportController::class, 'destroy'])->name('supports.destroy');

        Route::get('/manageservices', [ServiceController::class, 'manageServices'])->name('manageservices');
        Route::get('/workingschedule', [AdminController::class, 'showshift'])->name('workingschedule');
        Route::post('/workingschedule/{doctor}', [AdminController::class, 'updateSchedule'])->name('updateSchedule');

        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', [AdminInvoiceController::class, 'index'])->name('index');
            Route::get('/create', [AdminInvoiceController::class, 'create'])->name('create');
            Route::post('/', [AdminInvoiceController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminInvoiceController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AdminInvoiceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminInvoiceController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminInvoiceController::class, 'destroy'])->name('destroy');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Doctor routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admindoctor'])->prefix('admindoctor')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'showDashboard'])->name('admindoctor.dashboard');
    Route::get('/schedule', [DoctorController::class, 'showSchedule'])->name('doctor.schedule');
    Route::get('/patients', [DoctorController::class, 'showPatients'])->name('doctor.patients');

    Route::get('/medicalrecords', [DoctorMedicalRecordController::class, 'index'])->name('admindoctor.medicalrecords.index');
    Route::get('/medicalrecords/create', [DoctorMedicalRecordController::class, 'createFromAppointment'])->name('admindoctor.medicalrecords.create');
    Route::post('/medicalrecords', [DoctorMedicalRecordController::class, 'store'])->name('admindoctor.medicalrecords.store');
    Route::get('/medicalrecords/{id}/edit', [DoctorMedicalRecordController::class, 'edit'])->name('admindoctor.medicalrecords.edit');
    Route::put('/medicalrecords/{id}', [DoctorMedicalRecordController::class, 'update'])->name('admindoctor.medicalrecords.update');
    Route::delete('/medicalrecords/{id}', [DoctorMedicalRecordController::class, 'destroy'])->name('admindoctor.medicalrecords.destroy');

    Route::get('/progress', [\App\Http\Controllers\DoctorProgressController::class, 'index'])->name('admindoctor.progress.index');
    Route::delete('/progress/{id}', [\App\Http\Controllers\DoctorProgressController::class, 'destroy'])->name('admindoctor.progress.destroy');

    Route::get('/invoices/{id}/print', [InvoiceController::class, 'print'])->name('admindoctor.invoices.print');
    Route::resource('invoices', InvoiceController::class)->names('admindoctor.invoices');
});
