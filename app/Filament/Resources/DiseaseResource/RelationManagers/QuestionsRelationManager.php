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

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $recordTitleAttribute = 'text';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.resources.questions.labels.plural');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.questions.labels.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.questions.labels.plural');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('text')
                ->label(__('filament.resources.questions.fields.text'))
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('criticality_level_id')
                ->label(__('filament.resources.questions.fields.criticality_level'))
                ->relationship('criticalityLevel', 'name')
                ->required(),
            Forms\Components\FileUpload::make('icon')
                ->label('Symbol')
                ->directory('icons')
                ->image()
                ->imageEditor()
                ->nullable(),
            Forms\Components\Select::make('gender')
                ->label(__('filament.resources.questions.fields.gender'))
                ->options([
                    'male' => __('filament.resources.questions.options.gender.male'),
                    'female' => __('filament.resources.questions.options.gender.female'),
                ])
                ->nullable()
                ->helperText(__('filament.resources.questions.helpers.gender')),

            Forms\Components\Toggle::make('reverse_meaning')
                ->label(__('filament.resources.questions.fields.reverse_meaning'))
                ->helperText(__('filament.resources.questions.helpers.reverse_meaning'))
                ->default(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ColorColumn::make('criticalityLevel.color')
                ->label(__('filament.resources.questions.fields.criticality_level'))
                ->sortable(),
            Tables\Columns\ImageColumn::make('icon'),
            Tables\Columns\TextColumn::make('text')
                ->label(__('filament.resources.questions.fields.text'))
                ->searchable(),
            Tables\Columns\TextColumn::make('gender')
                ->label(__('filament.resources.questions.fields.gender'))
                ->formatStateUsing(fn(string $state = null): string => $state
                    ? ($state === 'male'
                        ? __('filament.resources.questions.options.gender.male')
                        : __('filament.resources.questions.options.gender.female'))
                    : 'Alle'),
            Tables\Columns\IconColumn::make('reverse_meaning')
                ->label(__('filament.resources.questions.fields.reverse_meaning'))
                ->boolean(),
        ])->filters([
            Tables\Filters\SelectFilter::make('criticality_level_id')
                ->relationship('criticalityLevel', 'name')
                ->label(__('filament.resources.questions.fields.criticality_level')),
            Tables\Filters\SelectFilter::make('gender')
                ->options([
                    'male' => __('filament.resources.questions.options.gender.male'),
                    'female' => __('filament.resources.questions.options.gender.female'),
                    null => 'Alle',
                ])
                ->label(__('filament.resources.questions.fields.gender')),
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
            ->select('questions.*')
            ->join('criticality_levels', 'questions.criticality_level_id', '=', 'criticality_levels.id')
            ->orderBy('criticality_levels.sort_order')
            ->orderBy('questions.id')
        );
    }
}
