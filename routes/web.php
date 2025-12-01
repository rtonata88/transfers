<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// Public Routes
Volt::route('/', 'home')->name('home');
Volt::route('/about', 'about')->name('about');
Volt::route('/faq', 'faq')->name('faq');

// Protected Routes (Auth Required)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - redirects based on user type
    Route::get('/dashboard', function () {
        // Admins go to admin dashboard
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // Regular users need a profile
        if (!auth()->user()->hasProfile()) {
            return redirect()->route('profile.create');
        }
        return redirect()->route('dashboard.view');
    })->name('dashboard');

    Volt::route('/dashboard/view', 'dashboard')->name('dashboard.view')->middleware('profile.exists');

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Volt::route('/create', 'profile.create')->name('profile.create');

        Route::middleware(['profile.exists'])->group(function () {
            Volt::route('/', 'profile.show')->name('profile.show');
            Volt::route('/edit', 'profile.edit')->name('profile.edit');
        });
    });

    // Transfer Routes (requires profile)
    Route::middleware(['profile.exists'])->group(function () {
        Volt::route('/transfers', 'transfers.index')->name('transfers.index');
        Volt::route('/transfers/search', 'transfers.search')->name('transfers.search');
        Volt::route('/transfers/{profile}', 'transfers.show')->name('transfers.show');

        // Request Routes
        Volt::route('/requests', 'requests.index')->name('requests.index');
        Volt::route('/requests/incoming', 'requests.incoming')->name('requests.incoming');
        Volt::route('/requests/outgoing', 'requests.outgoing')->name('requests.outgoing');
    });

    // Settings Routes (from original)
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.admin.dashboard');
    })->name('dashboard');
});

// Temporary migration route - REMOVE AFTER USE
Route::get('/temp-migrate-notifications-7h4k9x', function () {
    if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
        return 'Notifications table already exists';
    }

    \Illuminate\Support\Facades\Schema::create('notifications', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->uuid('id')->primary();
        $table->string('type');
        $table->morphs('notifiable');
        $table->text('data');
        $table->timestamp('read_at')->nullable();
        $table->timestamps();
    });

    return 'Notifications table created successfully';
});

