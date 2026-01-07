<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/skills', [ProfileController::class, 'addSkill'])->name('profile.skills.add');
    Route::delete('/profile/skills/{skill}', [ProfileController::class, 'removeSkill'])->name('profile.skills.remove');
    Route::get('/profile/{user}/resume', [\App\Http\Controllers\ResumeController::class, 'download'])->name('profile.resume');

    // Projects
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/workspace', [ProjectController::class, 'workspace'])->name('projects.workspace');
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.members.add');
    Route::post('/projects/{project}/webhook/reconnect', [ProjectController::class, 'reconnectWebhook'])->name('projects.webhook.reconnect');
    Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('projects.members.remove');
    Route::post('/projects/{project}/validate/{user}', [ProjectController::class, 'validateMember'])->name('projects.members.validate');

    // Applications
    Route::post('/projects/{project}/apply', [ProjectController::class, 'apply'])->name('projects.apply');
    Route::post('/projects/{project}/applications/{user}/accept', [ProjectController::class, 'acceptApplication'])->name('projects.applications.accept');
    Route::post('/projects/{project}/applications/{user}/reject', [ProjectController::class, 'rejectApplication'])->name('projects.applications.reject');



    // Chat - Full Livewire SPA
    Route::get('/chat/{conversation?}', \App\Livewire\Chat\ChatPage::class)->name('chat');
    Route::post('/chat/direct/{user}', [ChatController::class, 'createDirectConversation'])->name('chat.direct');
})->middleware('verified');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/toggle-role', [\App\Http\Controllers\Admin\UserController::class, 'toggleRole'])->name('users.toggle-role');

    // Project Management
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);
    Route::post('projects/{project}/archive', [\App\Http\Controllers\Admin\ProjectController::class, 'archive'])->name('projects.archive');

    // System Health
    Route::get('/system-health', \App\Livewire\SystemHealth::class)->name('health.index');
});

// GitHub OAuth
Route::get('/auth/github', [\App\Http\Controllers\GitHubAuthController::class, 'redirect'])->name('auth.github');
Route::get('/auth/github/callback', [\App\Http\Controllers\GitHubAuthController::class, 'callback']);

// Google OAuth
Route::get('/auth/google', [\App\Http\Controllers\GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\GoogleAuthController::class, 'callback']);

// GitHub Webhook (Excluded from CSRF)
Route::post('/webhooks/github', [\App\Http\Controllers\GitHubWebhookController::class, 'handle'])->name('github.webhook');

require __DIR__ . '/auth.php';
