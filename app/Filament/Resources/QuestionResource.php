<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getModelLabel(): string
    {
        return __('filament.resources.questions.general.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.questions.general.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.questions.general.plural');
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('text')
                ->label(__('filament.resources.questions.fields.text'))
                ->required()
                ->maxLength(255),

            Select::make('gender')
                ->label(__('filament.resources.questions.fields.gender'))
                ->options([
                    'male' => __('filament.resources.questions.options.gender.male'),
                    'female' => __('filament.resources.questions.options.gender.female'),
                ])
                ->nullable()
                ->helperText(__('filament.resources.questions.helpers.gender')),

            Select::make('criticality_level_id')
                ->label(__('filament.resources.questions.fields.criticality_level'))
                ->relationship('criticalityLevel', 'name')
                ->required(),
            FileUpload::make('icon'),

            Forms\Components\Toggle::make('reverse_meaning')
                ->label(__('filament.resources.questions.fields.reverse_meaning'))
                ->helperText(__('filament.resources.questions.helpers.reverse_meaning'))
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('icon'),
                TextColumn::make('text')->label(__('filament.resources.questions.fields.text'))->searchable(),
                TextColumn::make('gender')->label(__('filament.resources.questions.fields.gender'))->formatStateUsing(fn (string $state): string => __("filament.resources.questions.options.gender.{$state}")),
                TextColumn::make('criticalityLevel.name')->label(__('filament.resources.questions.fields.criticality_level')),
                Tables\Columns\IconColumn::make('reverse_meaning')
                    ->label(__('filament.resources.questions.fields.reverse_meaning'))
                    ->boolean(),
            ])
            ->defaultSort('id', 'asc')
            ->modifyQueryUsing(fn(Builder $query) => $query
                ->select('questions.*')
                ->whereNull('disease_id')
            );
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
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
