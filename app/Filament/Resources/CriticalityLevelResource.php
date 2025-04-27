<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CriticalityLevelResource\Pages;
use App\Filament\Resources\CriticalityLevelResource\RelationManagers;
use App\Models\CriticalityLevel;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CriticalityLevelResource extends Resource
{
    protected static ?string $model = CriticalityLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('filament.resources.criticality-levels.labels.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.criticality-levels.labels.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.resources.criticality-levels');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label(__('filament.resources.criticality-levels.fields.name'))
                ->required()
                ->maxLength(255),
            Select::make('color')
                ->label(__('filament.resources.criticality-levels.fields.color'))
                ->options([
                    'red' => __('filament.color.red'),
                    'orange' => __('filament.color.orange'),
                    'green' => __('filament.color.green'),
                    'gray' => __('filament.color.gray'),
                ])->default('gray'),
            Toggle::make('immediate_result')
                ->label(__('filament.resources.criticality-levels.fields.immediate_result'))
                ->helperText(__('filament.resources.criticality-levels.fields.immediate_result_help')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('sort_order')->label(__('filament.resources.criticality-levels.fields.sort_order'))->sortable(),
            ColorColumn::make('color')->label(__('filament.resources.criticality-levels.fields.color'))->sortable()->searchable(),
            TextColumn::make('name')->label(__('filament.resources.criticality-levels.fields.name'))->sortable()->searchable(),
            ToggleColumn::make('immediate_result')->label(__('filament.resources.criticality-levels.fields.immediate_result'))->disabled()->searchable(),
        ])->reorderable('sort_order')->defaultSort('sort_order');
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
            'index' => Pages\ListCriticalityLevels::route('/'),
            'create' => Pages\CreateCriticalityLevel::route('/create'),
            'edit' => Pages\EditCriticalityLevel::route('/{record}/edit'),
        ];
    }
}
