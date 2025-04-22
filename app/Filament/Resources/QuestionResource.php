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
        return __('filament.resources.questions.labels.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.questions.labels.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.resources.questions');
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('disease_id')
                ->label(__('filament.resources.questions.fields.disease'))
                ->relationship('disease', 'name')
                ->searchable()
                ->nullable()
                ->helperText(__('filament.resources.questions.helpers.disease')),

            TextInput::make('text')
                ->label(__('filament.resources.questions.fields.text'))
                ->required()
                ->maxLength(255),

            Select::make('gender')
                ->label(__('filament.resources.questions.fields.gender'))
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                ])
                ->nullable()
                ->helperText(__('filament.resources.questions.helpers.gender')),

            Select::make('criticality_level_id')
                ->label(__('filament.resources.questions.fields.criticality_level'))
                ->relationship('criticalityLevel', 'name')
                ->nullable(),
            FileUpload::make('icon'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('text')->label(__('filament.resources.questions.fields.text'))->searchable(),
                TextColumn::make('disease.name')->label(__('filament.resources.questions.fields.disease')),
                TextColumn::make('gender')->label(__('filament.resources.questions.fields.gender')),
                TextColumn::make('criticalityLevel.name')->label(__('filament.resources.questions.fields.criticality_level')),
            ])
            ->defaultSort('id', 'asc');
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
