<?php

namespace App\Filament\Widgets;

use App\Models\EmployeeProfile;
use App\Models\TransferRequest;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $totalProfiles = EmployeeProfile::count();
        $activeProfiles = EmployeeProfile::where('is_available_for_transfer', true)->count();

        $pendingRequests = TransferRequest::where('status', 'pending')->count();
        $acceptedRequests = TransferRequest::where('status', 'accepted')->count();
        $completedRequests = TransferRequest::where('status', 'completed')->count();

        // Get new users this month
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            Stat::make('Total Users', $totalUsers)
                ->description("{$verifiedUsers} verified")
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),

            Stat::make('Employee Profiles', $totalProfiles)
                ->description("{$activeProfiles} available for transfer")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Pending Requests', $pendingRequests)
                ->description('Awaiting response')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Accepted Requests', $acceptedRequests)
                ->description("{$completedRequests} completed transfers")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('New Users This Month', $newUsersThisMonth)
                ->description('Registrations in ' . now()->format('F'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
        ];
    }
}
