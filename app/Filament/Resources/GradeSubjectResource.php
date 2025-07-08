<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradeSubjectResource\Pages;
use App\Filament\Resources\GradeSubjectResource\RelationManagers;
use App\Models\GradeSubject;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GradeSubjectResource extends Resource
{
    protected static ?string $model = GradeSubject::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Academic Setup';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('academic_year_id')->label('Academic Year')->relationship('academicYear','name'),
                Select::make('grade_id')->label('Grade Name')->relationship('grade','name'),
                 Repeater::make('subjectLists')
                    ->relationship()
                    ->columnSpanFull()
                    ->label('Subjects Assigned')
                    ->schema([
                        Select::make('subject_id')
                            ->relationship('subject', 'name')
                            ->required(),
                    ])
                    // ->columns(3)
                    ->minItems(1)
                    ->createItemButtonLabel('Add Subject'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('academicYear.name')->label('Academic Year')->searchable(),
                TextColumn::make('grade.name')->label('Grade')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListGradeSubjects::route('/'),
            'create' => Pages\CreateGradeSubject::route('/create'),
            'edit' => Pages\EditGradeSubject::route('/{record}/edit'),
        ];
    }
}
