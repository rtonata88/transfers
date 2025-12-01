<?php

namespace App\Filament\Widgets;

use App\Models\TransferRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TransferRequestsChart extends ChartWidget
{
    protected static ?string $heading = 'Transfer Requests Overview';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = TransferRequest::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statuses = ['pending', 'accepted', 'declined', 'cancelled', 'completed'];
        $counts = [];

        foreach ($statuses as $status) {
            $counts[] = $data[$status] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Transfer Requests',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#f59e0b', // pending - amber
                        '#10b981', // accepted - green
                        '#ef4444', // declined - red
                        '#6b7280', // cancelled - gray
                        '#3b82f6', // completed - blue
                    ],
                ],
            ],
            'labels' => ['Pending', 'Accepted', 'Declined', 'Cancelled', 'Completed'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
