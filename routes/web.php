<?php

use App\Http\Controllers\Front\EventController as FrontEventController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Organizer\EventController as OrganizerEventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontEventController::class, 'index'])->name('front.index');
Route::get('/events/{event:slug}', [FrontEventController::class, 'show'])->name('front.events.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return app(\App\Http\Controllers\User\DashboardController::class)->index();
    })->name('dashboard');

    Route::get('/events/{event}/register', [\App\Http\Controllers\User\EventRegistrationController::class, 'create'])->name('user.events.register.create');
    Route::post('/events/{event}/register', [\App\Http\Controllers\User\EventRegistrationController::class, 'store'])->name('user.events.register');

    Route::post('/events/{event}/bookmark', [\App\Http\Controllers\User\BookmarkController::class, 'toggle'])->name('user.events.bookmark');

    Route::get('/notifications/{id}/read', [\App\Http\Controllers\User\NotificationController::class, 'markAsReadAndRedirect'])->name('notifications.read');

    Route::post('/request-organizer', [\App\Http\Controllers\User\OrganizerRequestController::class, 'store'])->name('user.organizer.request');

    Route::get('/events/{event}/teams', [\App\Http\Controllers\User\TeamController::class, 'index'])->name('user.teams.index');
    Route::post('/events/{event}/teams', [\App\Http\Controllers\User\TeamController::class, 'store'])->name('user.teams.store');
    Route::post('/teams/{team}/join', [\App\Http\Controllers\User\TeamController::class, 'joinRequest'])->name('user.teams.join');
    Route::post('/teams/{team}/invite', [\App\Http\Controllers\User\TeamController::class, 'invite'])->name('user.teams.invite');
    Route::get('/teams/{team}', [\App\Http\Controllers\User\TeamController::class, 'show'])->name('user.teams.show');
    Route::delete('/teams/{team}', [\App\Http\Controllers\User\TeamController::class, 'destroy'])->name('user.teams.destroy');
    Route::delete('/teams/{team}/members/{user}', [\App\Http\Controllers\User\TeamController::class, 'kickMember'])->name('user.teams.kick');
    Route::post('/teams/{team}/leave', [\App\Http\Controllers\User\TeamController::class, 'leave'])->name('user.teams.leave');
    Route::post('/team-requests/invite/{invite}', [\App\Http\Controllers\User\TeamController::class, 'respondInvite'])->name('user.teams.respondInvite');
    Route::post('/team-requests/request/{joinRequest}', [\App\Http\Controllers\User\TeamController::class, 'respondRequest'])->name('user.teams.respondRequest');

    Route::get('/my-certificates', [\App\Http\Controllers\User\CertificateController::class, 'index'])->name('user.certificates.index');
    Route::post('/my-certificates', [\App\Http\Controllers\User\CertificateController::class, 'store'])->name('user.certificates.store');
    Route::delete('/my-certificates/{certificate}', [\App\Http\Controllers\User\CertificateController::class, 'destroy'])->name('user.certificates.destroy');

    Route::prefix('organizer')->name('organizer.')->middleware(['role:organizer|admin'])->group(function () {
        Route::resource('events', OrganizerEventController::class);

        // Dynamic Form Fields
        Route::get('events/{event}/fields', [\App\Http\Controllers\Organizer\EventFieldController::class, 'index'])->name('events.fields.index');
        Route::post('events/{event}/fields', [\App\Http\Controllers\Organizer\EventFieldController::class, 'store'])->name('events.fields.store');
        Route::delete('events/{event}/fields/{field}', [\App\Http\Controllers\Organizer\EventFieldController::class, 'destroy'])->name('events.fields.destroy');

        // Registrations / Participants
        Route::get('events/{event}/registrations', [\App\Http\Controllers\Organizer\ParticipantController::class, 'index'])->name('events.registrations.index');
        Route::post('events/{event}/registrations/{registration}/status', [\App\Http\Controllers\Organizer\ParticipantController::class, 'updateStatus'])->name('events.registrations.status');

        Route::get('events/{event}/certificates', [\App\Http\Controllers\Organizer\CertificateController::class, 'index'])->name('events.certificates.index');
        Route::post('events/{event}/certificates', [\App\Http\Controllers\Organizer\CertificateController::class, 'store'])->name('events.certificates.store');
    });

    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class);

        Route::get('events', [\App\Http\Controllers\Admin\EventController::class, 'index'])->name('events.index');
        Route::post('events/{event}/approve', [\App\Http\Controllers\Admin\EventController::class, 'approve'])->name('events.approve');
        Route::post('events/{event}/reject', [\App\Http\Controllers\Admin\EventController::class, 'reject'])->name('events.reject');
        Route::delete('events/{event}', [\App\Http\Controllers\Admin\EventController::class, 'destroy'])->name('events.destroy');

        Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.updateRole');
        Route::post('users/{user}/toggle-block', [\App\Http\Controllers\Admin\UserController::class, 'toggleBlock'])->name('users.toggleBlock');
        Route::post('organizer-requests/{organizerRequest}', [\App\Http\Controllers\Admin\UserController::class, 'handleOrganizerRequest'])->name('users.handleOrganizerRequest');

        Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity_logs.index');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
