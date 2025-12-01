<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeProfileResource\Pages;
use App\Models\EmployeeProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeProfileResource extends Resource
{
    protected static ?string $model = EmployeeProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled(fn (string $context): bool => $context === 'edit'),
                    ]),
                Forms\Components\Section::make('Employment Details')
                    ->schema([
                        Forms\Components\TextInput::make('persal_number')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\Select::make('employer_id')
                            ->relationship('employer', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('job_title_id')
                            ->relationship('jobTitle', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('salary_level')
                            ->maxLength(50),
                        Forms\Components\Toggle::make('is_on_probation')
                            ->label('On Probation')
                            ->helperText('Users on probation are not eligible for transfers'),
                    ])->columns(2),
                Forms\Components\Section::make('Current Location')
                    ->schema([
                        Forms\Components\Select::make('current_region_id')
                            ->relationship('currentRegion', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('current_town_id', null)),
                        Forms\Components\Select::make('current_town_id')
                            ->relationship('currentTown', 'name', fn (Builder $query, callable $get) =>
                                $query->where('region_id', $get('current_region_id'))
                            )
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
                Forms\Components\Section::make('Contact Details')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('alternative_email')
                            ->email()
                            ->maxLength(255),
                    ])->columns(2),
                Forms\Components\Section::make('Transfer Preferences')
                    ->schema([
                        Forms\Components\Textarea::make('reason_for_transfer')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_available_for_transfer')
                            ->label('Available for Transfer')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('persal_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employer.name')
                    ->label('Employer')
                    ->limit(30)
                    ->sortable(),
                Tables\Columns\TextColumn::make('jobTitle.name')
                    ->label('Job Title')
                    ->searchable()
                    ->sortable()
                    ->limit(25),
                Tables\Columns\TextColumn::make('currentRegion.name')
                    ->label('Region')
                    ->sortable(),
                Tables\Columns\TextColumn::make('currentTown.name')
                    ->label('Town')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_on_probation')
                    ->label('Probation')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available_for_transfer')
                    ->label('Available')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('employer')
                    ->relationship('employer', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('jobTitle')
                    ->relationship('jobTitle', 'name')
                    ->label('Job Title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('currentRegion')
                    ->relationship('currentRegion', 'name')
                    ->label('Region')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_on_probation')
                    ->label('Probation Status'),
                Tables\Filters\TernaryFilter::make('is_available_for_transfer')
                    ->label('Available for Transfer'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployeeProfiles::route('/'),
            'create' => Pages\CreateEmployeeProfile::route('/create'),
            'edit' => Pages\EditEmployeeProfile::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
