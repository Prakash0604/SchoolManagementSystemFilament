<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherSubjectResource\Pages;
use App\Filament\Resources\TeacherSubjectResource\RelationManagers;
use App\Models\TeacherSubject;
use App\Models\TeacherSubjectList;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeacherSubjectResource extends Resource
{
    protected static ?string $model = TeacherSubject::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Teacher')
                    ->required(),

                Select::make('academic_year_id')
                    ->relationship('academicYear', 'name')
                    ->required()
                    ->reactive(),

                Repeater::make('subjectLists')
                    ->relationship()
                    ->columnSpanFull()
                    ->label('Subjects Assigned')
                    ->schema([
                        Select::make('grade_id')
                            ->relationship('grade', 'name')
                            ->required()
                            ->reactive(),

                        Select::make('section_id')
                            ->label('Section')
                            ->options(function (callable $get) {
                                $academicYearId = $get('../../academic_year_id');
                                $gradeId = $get('grade_id');

                                if (!$academicYearId || !$gradeId) {
                                    return [];
                                }

                                return \App\Models\Section::where('academic_year_id', $academicYearId)
                                    ->where('grade_id', $gradeId)
                                    ->pluck('name', 'id');
                            })
                            ->required()
                            ->reactive(),

                        Select::make('subject_id')
                            ->relationship('subject', 'name')
                            ->required(),
                    ])
                    ->columns(3)
                    ->minItems(1)
                    ->createItemButtonLabel('Add Subject'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Teacher'),
                Tables\Columns\TextColumn::make('academicYear.name'),
                Tables\Columns\TextColumn::make('subjectLists_count')
                    ->label('Total Subjects')
                    ->counts('subjectLists'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Student Info
                Section::make('Teacher Detail')
                    ->schema([
                        TextEntry::make('user.name')->label('Teacher Name'),
                        TextEntry::make('academicYear.name'),
                    ])
                    ->columns(2),

                Section::make('Assigned Subjects')
                    ->schema([
                        // RepeatableEntry::make('subjectLists')
                        //     ->label('Subjects Assigned')
                        //     ->schema([
                        //         TextEntry::make('grade.name')->label('Grade'),
                        //         TextEntry::make('section.name')->label('Section'),
                        //         TextEntry::make('subject.name')->label('Subject'),
                        //     ])
                        //     ->columns(3),
                        ViewEntry::make('subjectLists')
                            ->view('filament.teacher-subject.subject-list-with-delete')
                            ->label('Subjects')
                    ])
                // ->columnSpanFull(),
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
            'index' => Pages\ListTeacherSubjects::route('/'),
            'create' => Pages\CreateTeacherSubject::route('/create'),
            'edit' => Pages\EditTeacherSubject::route('/{record}/edit'),
            'view' => Pages\ViewTeacherSubjectListData::route('/{record}'),

        ];
    }
}
