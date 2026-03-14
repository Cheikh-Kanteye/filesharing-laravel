<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

// ── Entièrement public ─────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/share/{token}', [FileController::class, 'publicShare'])->name('files.public-share');
Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');

// ── Guest seulement ────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ── Authentifié ────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profil
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Dashboard & Recherche
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [DashboardController::class, 'search'])->name('search');
    Route::get('/api/search', [DashboardController::class, 'apiSearch'])->name('api.search');

    // Spaces — routes statiques EN PREMIER, puis le wildcard {space}
    Route::get('/spaces', [SpaceController::class, 'index'])->name('spaces.index');
    Route::get('/spaces/create', [SpaceController::class, 'create'])->name('spaces.create');
    Route::post('/spaces', [SpaceController::class, 'store'])->name('spaces.store');
    Route::get('/spaces/explore', [SpaceController::class, 'explore'])->name('spaces.explore');
    Route::get('/spaces/join/{token}', [SpaceController::class, 'joinViaLink'])->name('spaces.join');

    // Invitations reçues
    Route::get('/invitations', [SpaceController::class, 'myInvitations'])->name('invitations.index');
    Route::get('/invitations/{token}/accept', [SpaceController::class, 'acceptInvitation'])->name('invitations.accept');
    Route::delete('/invitations/{token}/decline', [SpaceController::class, 'declineInvitation'])->name('invitations.decline');

    // Fichiers (actions nécessitant un compte)
    Route::post('/spaces/{space}/files', [FileController::class, 'upload'])->name('files.upload');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
    Route::post('/files/{file}/copy', [FileController::class, 'copy'])->name('files.copy');
    Route::post('/files/{file}/share', [FileController::class, 'share'])->name('files.share');
    Route::put('/files/{file}/tags', [FileController::class, 'updateTags'])->name('files.tags.update');

    // Dossiers
    Route::post('/spaces/{space}/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');

    // Tags
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');

    // Space {space} — paramètre wildcard, doit être APRÈS toutes les routes statiques /spaces/*
    Route::get('/spaces/{space}/edit', [SpaceController::class, 'edit'])->name('spaces.edit');
    Route::put('/spaces/{space}', [SpaceController::class, 'update'])->name('spaces.update');
    Route::delete('/spaces/{space}', [SpaceController::class, 'destroy'])->name('spaces.destroy');
    Route::post('/spaces/{space}/join-public', [SpaceController::class, 'joinPublic'])->name('spaces.join-public');
    Route::post('/spaces/{space}/invite', [SpaceController::class, 'invite'])->name('spaces.invite');
    Route::delete('/spaces/{space}/leave', [SpaceController::class, 'leave'])->name('spaces.leave');
    Route::delete('/spaces/{space}/members/{user}', [SpaceController::class, 'removeMember'])->name('spaces.members.remove');
    Route::post('/spaces/{space}/join-link', [SpaceController::class, 'generateJoinLink'])->name('spaces.join-link.generate');
    Route::delete('/spaces/{space}/join-link', [SpaceController::class, 'revokeJoinLink'])->name('spaces.join-link.revoke');
});

// Route publique avec {space} — APRÈS toutes les routes statiques /spaces/*
Route::get('/spaces/{space}', [SpaceController::class, 'show'])->name('spaces.show');
