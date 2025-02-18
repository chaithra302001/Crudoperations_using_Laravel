<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
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

Route::get('/welcome', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

//Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


//Route::post('/jobs', [JobController::class, 'store'])->name('job.store');
// Define the POST route for form submission
Route::post('/dashboard', [JobController::class, 'store'])->name('job.store');

// Define the GET route for viewing the dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/jobs', [JobController::class, 'index'])->name('job.index');
// Route for showing the edit form
Route::get('/jobs/{id}/edit', [JobController::class, 'edit'])->name('jobs.edit');
Route::put('/jobs/{jobId}', [JobController::class, 'update']);



//Route::delete('/jobs/{jobId}/delete', [JobController::class, 'destroy']);
Route::delete('/jobs/{jobId}', [JobController::class, 'destroy'])->name('jobs.destroy');












//Route::get('/jobs/fetch', [JobController::class, 'fetchJobs'])->name('job.fetch');


//Route::get('/jobs', [JobController::class, 'index'])->name('job.index');
