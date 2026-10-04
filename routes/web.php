<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Health & Readiness Probes
Route::get('/healthz', [HealthController::class, 'liveness'])->name('healthz');
Route::get('/readyz', [HealthController::class, 'readiness'])->name('readyz');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search/live', [\App\Http\Controllers\SearchController::class, 'live'])->name('search.live');

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

    // Skill Swaps
    Route::get('/skills/swap', [\App\Http\Controllers\SkillSwapController::class, 'index'])->name('skills.swap.index');
    Route::get('/skills/swap/create', [\App\Http\Controllers\SkillSwapController::class, 'create'])->name('skills.swap.create');
    Route::post('/skills/swap', [\App\Http\Controllers\SkillSwapController::class, 'store'])->name('skills.swap.store');
    Route::post('/skills/swap/{skillSwap}/accept', [\App\Http\Controllers\SkillSwapController::class, 'accept'])->name('skills.swap.accept');
    Route::post('/skills/swap/{skillSwap}/complete', [\App\Http\Controllers\SkillSwapController::class, 'complete'])->name('skills.swap.complete');
    Route::post('/skills/swap/{skillSwap}/cancel', [\App\Http\Controllers\SkillSwapController::class, 'cancel'])->name('skills.swap.cancel');

    // Chat - Full Livewire SPA
    Route::get('/chat/{conversation?}', \App\Livewire\Chat\ChatPage::class)->name('chat');
    Route::post('/chat/direct/{user}', [ChatController::class, 'createDirectConversation'])->name('chat.direct');
    Route::post('/messages', [ChatController::class, 'storeMessage'])->name('messages.store');

    // Campus Features
    Route::get('/campus/credits', \App\Livewire\Campus\CreditLedger::class)->name('campus.credits');
    Route::get('/campus/code-reviews', \App\Livewire\Campus\CodeReviewBounty::class)->name('campus.code-reviews');
    Route::get('/campus/study-desk', \App\Livewire\Campus\StudyDesk::class)->name('campus.study-desk');
    Route::get('/campus/courses', \App\Livewire\Campus\CourseTagging::class)->name('campus.courses');
    Route::get('/projects/{project}/matchmaker', \App\Livewire\Campus\TeamMatchmaker::class)->name('projects.matchmaker');
    Route::get('/projects/{project}/milestones', \App\Livewire\Campus\MilestoneRoadmap::class)->name('projects.milestones');
    Route::get('/portfolio/{user}', \App\Livewire\Campus\StudentPortfolio::class)->name('portfolio.show');

    // System Usability Scale (SUS) Survey
    Route::get('/usability-survey', [\App\Http\Controllers\UsabilitySurveyController::class, 'create'])->name('survey.sus');
    Route::post('/usability-survey', [\App\Http\Controllers\UsabilitySurveyController::class, 'store'])->name('survey.sus.store');
})->middleware('verified');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/toggle-role', [\App\Http\Controllers\Admin\UserController::class, 'toggleRole'])->name('users.toggle-role');
    Route::post('users/{user}/toggle-suspension', [\App\Http\Controllers\Admin\UserController::class, 'toggleSuspension'])->name('users.toggle-suspension');

    // Project Management
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);
    Route::post('projects/{project}/archive', [\App\Http\Controllers\Admin\ProjectController::class, 'archive'])->name('projects.archive');
    // Credit & Bounty Moderation
    Route::get('/credits', [\App\Http\Controllers\Admin\CreditController::class, 'index'])->name('credits.index');
    Route::post('/credits/adjust', [\App\Http\Controllers\Admin\CreditController::class, 'adjust'])->name('credits.adjust');
    Route::get('/code-reviews', [\App\Http\Controllers\Admin\CodeReviewController::class, 'index'])->name('code-reviews.index');
    Route::post('/code-reviews/{codeReview}/cancel', [\App\Http\Controllers\Admin\CodeReviewController::class, 'cancel'])->name('code-reviews.cancel');

    // Skill Swap Moderation
    Route::get('/swaps', [\App\Http\Controllers\Admin\SkillSwapController::class, 'index'])->name('swaps.index');
    Route::post('/swaps/{skillSwap}/cancel', [\App\Http\Controllers\Admin\SkillSwapController::class, 'cancel'])->name('swaps.cancel');
    Route::post('/swaps/{skillSwap}/complete', [\App\Http\Controllers\Admin\SkillSwapController::class, 'complete'])->name('swaps.complete');

    // Campus Master Data
    Route::resource('skills', \App\Http\Controllers\Admin\SkillController::class)->except(['create', 'show', 'edit']);
    Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class)->except(['create', 'show', 'edit']);
    Route::resource('badges', \App\Http\Controllers\Admin\BadgeController::class)->except(['create', 'show', 'edit']);
    Route::post('/badges/assign', [\App\Http\Controllers\Admin\BadgeController::class, 'assign'])->name('badges.assign');

    // Audit Trail
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');

    // System Health
    Route::get('/system-health', \App\Livewire\SystemHealth::class)->name('health.index');

    // Usability Scale Report (Academic Research)
    Route::get('/usability', [\App\Http\Controllers\UsabilitySurveyController::class, 'adminReport'])->name('usability.index');
});

// GitHub OAuth
Route::get('/auth/github', [\App\Http\Controllers\GitHubAuthController::class, 'redirect'])->name('auth.github');
Route::get('/auth/github/callback', [\App\Http\Controllers\GitHubAuthController::class, 'callback']);

// Google OAuth
Route::get('/auth/google', [\App\Http\Controllers\GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\GoogleAuthController::class, 'callback']);

// GitHub Webhook (Excluded from CSRF)
Route::post('/webhooks/github', [\App\Http\Controllers\GitHubWebhookController::class, 'handle'])->middleware('throttle:60,1')->name('github.webhook');

// PWA Routes
Route::get('/manifest.json', function () {
    return response(file_get_contents(public_path('manifest.json')), 200, [
        'Content-Type' => 'application/manifest+json; charset=utf-8',
        'Cache-Control' => 'no-cache, must-revalidate',
    ]);
});

Route::get('/manifest.webmanifest', function () {
    return response(file_get_contents(public_path('manifest.webmanifest')), 200, [
        'Content-Type' => 'application/manifest+json; charset=utf-8',
        'Cache-Control' => 'no-cache, must-revalidate',
    ]);
});

Route::get('/sw.js', function () {
    return response(file_get_contents(public_path('sw.js')), 200, [
        'Content-Type' => 'application/javascript; charset=utf-8',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Service-Worker-Allowed' => '/',
    ]);
});

Route::view('/offline', 'offline')->name('pwa.offline');

require __DIR__.'/auth.php';
