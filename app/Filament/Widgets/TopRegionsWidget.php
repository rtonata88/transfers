<?php

namespace App\Filament\Widgets;

use App\Models\Region;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopRegionsWidget extends BaseWidget
{
    protected static ?string $heading = 'Employees by Region';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Region::query()
                    ->leftJoin('employee_profiles', 'regions.id', '=', 'employee_profiles.current_region_id')
                    ->select('regions.id', 'regions.name', DB::raw('count(employee_profiles.id) as employee_count'))
                    ->groupBy('regions.id', 'regions.name')
                    ->orderByDesc('employee_count')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Region'),
                Tables\Columns\TextColumn::make('employee_count')
                    ->label('Employees')
                    ->badge()
                    ->color('primary'),
            ])
            ->paginated([5]);
    }
}
