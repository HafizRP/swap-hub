<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SkillSwapController;
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

    // Projects
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.members.add');
    Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('projects.members.remove');
    Route::post('/projects/{project}/validate/{user}', [ProjectController::class, 'validateMember'])->name('projects.members.validate');

    // Skill Swaps
    Route::get('/skill-swaps', [SkillSwapController::class, 'index'])->name('skill-swaps.index');
    Route::get('/skill-swaps/create', [SkillSwapController::class, 'create'])->name('skill-swaps.create');
    Route::post('/skill-swaps', [SkillSwapController::class, 'store'])->name('skill-swaps.store');
    Route::get('/skill-swaps/{skillSwapRequest}', [SkillSwapController::class, 'show'])->name('skill-swaps.show');
    Route::post('/skill-swaps/{skillSwapRequest}/accept', [SkillSwapController::class, 'accept'])->name('skill-swaps.accept');
    Route::post('/skill-swaps/{skillSwapRequest}/complete', [SkillSwapController::class, 'complete'])->name('skill-swaps.complete');
    Route::delete('/skill-swaps/{skillSwapRequest}', [SkillSwapController::class, 'destroy'])->name('skill-swaps.destroy');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('chat.messages.send');
    Route::get('/chat/{conversation}/messages', [ChatController::class, 'getMessages'])->name('chat.messages.get');
    Route::post('/chat/direct/{user}', [ChatController::class, 'createDirectConversation'])->name('chat.direct');
});

// GitHub OAuth
Route::get('/auth/github', [\App\Http\Controllers\GitHubAuthController::class, 'redirect'])->name('auth.github');
Route::get('/auth/github/callback', [\App\Http\Controllers\GitHubAuthController::class, 'callback']);

// GitHub Webhook (Excluded from CSRF)
Route::post('/webhooks/github', [\App\Http\Controllers\GitHubWebhookController::class, 'handle'])->name('github.webhook');

require __DIR__.'/auth.php';
