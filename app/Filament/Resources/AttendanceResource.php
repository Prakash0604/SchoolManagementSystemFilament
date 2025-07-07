<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('academic_year_id')
                    ->relationship('academicYear', 'name')
                    ->label('Academic Year')
                    ->reactive()
                    ->required(),

                Select::make('grade_id')
                    ->relationship('grade', 'name')
                    ->label('Grade')
                    ->reactive()
                    ->required(),

                Select::make('section_id')
                    ->label('Section')
                    ->reactive()
                    ->required()
                    ->options(
                        fn(callable $get) =>
                        \App\Models\Section::where('grade_id', $get('grade_id'))->where('academic_year_id', $get('academic_year_id'))
                            ->pluck('name', 'id')
                    ),

                DatePicker::make('attendance_date')
                    ->required(),

                Placeholder::make('student_toggles')
                    ->label('Student Attendance')
                    ->columnSpanFull()
                    ->content(function (callable $get, callable $set) {
                        $gradeId = $get('grade_id');
                        $sectionId = $get('section_id');
                        $academicYearId = $get('academic_year_id');

                        if (!$gradeId || !$sectionId) {
                            return 'Select grade and section to load students.';
                        }

                        $students = Student::whereHas('academic', function ($q) use ($gradeId, $sectionId, $academicYearId) {
                            $q->where('grade_id', $gradeId)->where('section_id', $sectionId)->where('academic_year_id', $academicYearId);
                        })->get();
                        return view('filament.attendance.attendance-student-list', [
                            'students' => $students,
                        ]);
                    })
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
