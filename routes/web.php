<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\StudyProgramController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', [Controller::class, 'index'])->name('home');

Route::resource('mentor', MentorController::class)->only('store', 'create');
Route::resource('student', StudentsController::class)->only('store', 'create');
Route::get('/mail/{key}', [MailController::class, 'verify']);


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect('/');
    })->name('dashboard');

    Route::post('/mentees/remove/{mentor}', [MentorController::class, 'removeMentees'])->name('remove.mentees');
    Route::post('/mentor/confirm/{mentor}', [MentorController::class, 'confirmMentor'])->name('confirm.mentor');
    Route::post('/mentor/sendMenteesData/{mentor}', [MentorController::class, 'sendMenteeData'])->name('sendMenteesData');
    Route::post('/mentor/custom/');

    Route::post('/student/sendMentorData/{student}', [StudentsController::class, 'sendMentorData'])->name('sendMentorData');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('mentor', MentorController::class)->except('store', 'create', 'show');
    Route::resource('student', StudentsController::class)->except('store', 'create', 'show');
    Route::resource('faculty', FacultyController::class)->except('create', 'edit', 'index', 'show');
    Route::resource('programs', StudyProgramController::class)->except('create', 'show');
    Route::resource('event', EventController::class)->except('create', 'show');
});

require __DIR__.'/auth.php';
