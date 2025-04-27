<?php

namespace App\Filament\Resources\DiseaseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OutcomesRelationManager extends RelationManager
{
    protected static string $relationship = 'outcomes';

    protected static ?string $recordTitleAttribute = 'description';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.resources.outcomes.labels.plural');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.outcomes.labels.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.outcomes.labels.plural');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('criticality_level_id')
                ->label(__('filament.resources.outcomes.fields.criticality_level'))
                ->relationship('criticalityLevel', 'name')
                ->required(),
            Forms\Components\TextInput::make('title')
                ->label(__('filament.resources.outcomes.fields.title'))
                ->required(),
            Forms\Components\Textarea::make('description')
                ->label(__('filament.resources.outcomes.fields.description'))
                ->required()
                ->columnSpanFull()
                ->helperText(__('filament.resources.outcomes.helpers.description')),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ColorColumn::make('criticalityLevel.color')
                ->label(__('filament.resources.outcomes.fields.criticality_level'))
                ->sortable(),
            Tables\Columns\TextColumn::make('title')
                ->label(__('filament.resources.outcomes.fields.title'))
                ->sortable(),
            Tables\Columns\TextColumn::make('description')
                ->label(__('filament.resources.outcomes.fields.description'))
                ->limit(50)
                ->searchable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('criticality_level_id')
                ->relationship('criticalityLevel', 'name')
                ->label(__('filament.resources.outcomes.fields.criticality_level')),
        ])->headerActions([
            Tables\Actions\CreateAction::make()
                ->modalWidth('4xl'),
        ])->actions([
            Tables\Actions\EditAction::make()
                ->modalWidth('4xl'),
            Tables\Actions\DeleteAction::make(),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])->modifyQueryUsing(fn(Builder $query) => $query
            ->select('outcomes.*')
            ->join('criticality_levels', 'outcomes.criticality_level_id', '=', 'criticality_levels.id')
            ->orderBy('criticality_levels.sort_order')
            ->orderBy('outcomes.id')
        );
    }
}
