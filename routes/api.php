<?php

use App\Enums\UserRole;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowRecordController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

$sanctumAUTH = 'auth:sanctum';
$mustBeAdminOrLibrarian = 'ability:' . UserRole::ADMIN . ',' . UserRole::LIBRARIAN;
$mustBeAdminOrMember = 'ability:' . UserRole::ADMIN . ',' . UserRole::MEMBER;
$mustBeAdmin = 'ability:' . UserRole::ADMIN;
$mustBeMember = 'ability:' . UserRole::MEMBER;

Route::prefix('books')->middleware('throttle:api')->group(function () use ($sanctumAUTH, $mustBeAdminOrLibrarian, $mustBeAdmin, $mustBeMember) {
    Route::get('/', [BookController::class, 'index']);
    Route::get('/search', [BookController::class, 'search']);
    Route::get('/{book}', [BookController::class, 'show']);
    Route::post('/', [BookController::class, 'store'])->middleware($sanctumAUTH, $mustBeAdminOrLibrarian);
    Route::put('/{book}', [BookController::class, 'update'])->middleware($sanctumAUTH, $mustBeAdminOrLibrarian);
    Route::delete('/{book}', [BookController::class, 'destroy'])->middleware($sanctumAUTH, $mustBeAdmin);
    Route::post('/{book}/borrow', [BookController::class, 'borrowBook'])->middleware($sanctumAUTH, $mustBeMember);
    Route::post('/{book}/return', [BookController::class, 'returnBook'])->middleware($sanctumAUTH, $mustBeMember);
});

Route::prefix('authors')->middleware('throttle:api')->group(function () use ($sanctumAUTH, $mustBeAdminOrLibrarian, $mustBeAdmin) {
    Route::get('/', [AuthorController::class, 'index']);
    Route::get('/{author}', [AuthorController::class, 'show']);
    Route::post('/', [AuthorController::class, 'store'])->middleware($sanctumAUTH, $mustBeAdminOrLibrarian);
    Route::put('/{author}', [AuthorController::class, 'update'])->middleware($sanctumAUTH, $mustBeAdminOrLibrarian);
    Route::delete('/{author}', [AuthorController::class, 'destroy'])->middleware($sanctumAUTH, $mustBeAdmin);
});

Route::prefix('users')->middleware('throttle:api')->group(function () use ($sanctumAUTH, $mustBeAdmin, $mustBeAdminOrMember) {
    Route::get('/', [UserController::class, 'index'])->middleware($sanctumAUTH, $mustBeAdmin);
    Route::get('/{user}', [UserController::class, 'show'])->middleware($sanctumAUTH, $mustBeAdmin);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{user}', [UserController::class, 'update'])->middleware($sanctumAUTH, $mustBeAdminOrMember);
    Route::delete('/{user}', [UserController::class, 'destroy'])->middleware($sanctumAUTH, $mustBeAdmin);
});

Route::post('/login', [UserController::class, 'login'])->middleware('throttle:api');
Route::post('/create-librarian', [UserController::class, 'createLibrarian'])->middleware($sanctumAUTH, $mustBeAdmin);

Route::prefix('borrow-records')->middleware('throttle:api')->group(function () use ($sanctumAUTH, $mustBeAdminOrLibrarian) {
    Route::get('/', [BorrowRecordController::class, 'index'])->middleware($sanctumAUTH, $mustBeAdminOrLibrarian);
    Route::get('/{borrowRecord}', [BorrowRecordController::class, 'show'])->middleware($sanctumAUTH, $mustBeAdminOrLibrarian);
});
