<?php

use App\Http\Controllers\MessageController;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::get('/sent_queues', [MessageController::class, 'sentQueues'])->name('message.sent_queues')->middleware(['auth']);
Route::get('/queue_details', [MessageController::class, 'queueDetails'])->name('message.queue_details')->middleware(['auth']);
Route::get('/send_message', [MessageController::class, 'sendMessage'])->name('message.send_message')->middleware(['auth']);
Route::post('/create_message_queue', [MessageController::class, 'createMessageQueue'])->name('message.create_message_queue')->middleware(['auth']);
Route::post('/create_list_from_file', [MessageController::class, 'reedFile'])->name('message.create_list_from_file')->middleware(['auth']);
