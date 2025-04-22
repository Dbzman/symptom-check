<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutcomeResource\Pages;
use App\Filament\Resources\OutcomeResource\RelationManagers;
use App\Models\Outcome;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OutcomeResource extends Resource
{
    protected static ?string $model = Outcome::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('filament.resources.outcomes.labels.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.outcomes.labels.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.resources.outcomes');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('disease_id')
                ->label(__('filament.resources.outcomes.fields.disease'))
                ->relationship('disease', 'name')
                ->required(),

            Select::make('criticality_level_id')
                ->label(__('filament.resources.outcomes.fields.criticality_level'))
                ->relationship('criticalityLevel', 'name')
                ->nullable(),

            TextInput::make('title')
                ->label(__('filament.resources.outcomes.fields.title'))
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->label(__('filament.resources.outcomes.fields.description'))
                ->rows(4)
                ->maxLength(1000)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->label(__('filament.resources.outcomes.fields.title'))->sortable()->searchable(),
            TextColumn::make('disease.name')->label(__('filament.resources.outcomes.fields.disease')),
            TextColumn::make('criticalityLevel.name')->label(__('filament.resources.outcomes.fields.criticality_level'))
            ,
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
            'index' => Pages\ListOutcomes::route('/'),
            'create' => Pages\CreateOutcome::route('/create'),
            'edit' => Pages\EditOutcome::route('/{record}/edit'),
        ];
    }
}
