<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiseaseResource\Pages;
use App\Filament\Resources\DiseaseResource\RelationManagers;
use App\Models\Disease;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiseaseResource extends Resource
{
    protected static ?string $model = Disease::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('filament.resources.diseases.labels.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.diseases.labels.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.resources.diseases');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label(__('filament.resources.diseases.fields.name'))
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')
                ->label(__('filament.resources.diseases.fields.name'))
                ->sortable()
                ->searchable(),
        ]);
    }

    public static function getRelations(): array
    {
        return [RelationGroup::make('QuestionsAndOutcomes', [
            RelationManagers\QuestionsRelationManager::class,
            RelationManagers\OutcomesRelationManager::class,
        ])];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiseases::route('/'),
            'create' => Pages\CreateDisease::route('/create'),
            'edit' => Pages\EditDisease::route('/{record}/edit'),
        ];
    }
}
