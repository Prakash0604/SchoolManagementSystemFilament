<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use App\Models\Student;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;
    protected static ?string $navigationGroup = 'Academic Setup';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('academic_year_id')
                    ->label('Academic Year')
                    ->relationship('academicYear', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $state, $get) {
                        $set('grade_id', null);
                        $set('section_id', null);

                        $exists = \App\Models\Attendance::where('academic_year_id', $state)
                            ->where('grade_id', $get('grade_id'))
                            ->where('section_id', $get('section_id'))
                            ->whereDate('attendance_date', $get('attendance_date'))
                            ->exists();

                        $set('is_already_taken', $exists);
                    }),

                // Similarly for grade_id
                Select::make('grade_id')
                    ->relationship('grade', 'name')
                    ->label('Grade')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $state, $get) {
                        $set('section_id', null);

                        $exists = \App\Models\Attendance::where('academic_year_id', $get('academic_year_id'))
                            ->where('grade_id', $state)
                            ->where('section_id', $get('section_id'))
                            ->whereDate('attendance_date', $get('attendance_date'))
                            ->exists();

                        $set('is_already_taken', $exists);
                    }),

                // For section_id
                Select::make('section_id')
                    ->label('Section')
                    ->options(fn($get) => \App\Models\Section::where('grade_id', $get('grade_id'))
                        ->where('academic_year_id', $get('academic_year_id'))
                        ->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $state, $get) {
                        $exists = \App\Models\Attendance::where('academic_year_id', $get('academic_year_id'))
                            ->where('grade_id', $get('grade_id'))
                            ->where('section_id', $state)
                            ->whereDate('attendance_date', $get('attendance_date'))
                            ->exists();

                        $set('is_already_taken', $exists);
                    }),

                // For attendance_date
                DatePicker::make('attendance_date')
                    ->default(now())
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $state, $get) {
                        $exists = \App\Models\Attendance::where('academic_year_id', $get('academic_year_id'))
                            ->where('grade_id', $get('grade_id'))
                            ->where('section_id', $get('section_id'))
                            ->whereDate('attendance_date', $state)
                            ->exists();

                        $set('is_already_taken', $exists);
                    }),

                Placeholder::make('already_taken_warning')
                    ->visible(fn($get) => $get('is_already_taken') === true)
                    ->content('⚠️ Attendance already taken for selected date, grade, and section.'),


                // Fix Placeholder content callback signature:
                Placeholder::make('student_toggles')
                    ->label('Student Attendance')
                    ->columnSpanFull()
                    ->content(function ($get) {
                        $gradeId = $get('grade_id');
                        $sectionId = $get('section_id');
                        $academicYearId = $get('academic_year_id');

                        if (!$gradeId || !$sectionId) {
                            return 'Select grade and section to load students.';
                        }

                        $students = Student::whereHas('academic', function ($q) use ($gradeId, $sectionId, $academicYearId) {
                            $q->where('grade_id', $gradeId)
                                ->where('section_id', $sectionId)
                                ->where('academic_year_id', $academicYearId);
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
                TextColumn::make('academicYear.name'),
                TextColumn::make('grade.name'),
                TextColumn::make('section.name'),
                TextColumn::make('attendance_date')
            ])
            ->filters([
                SelectFilter::make('academic_year_id')->relationship('academicYear', 'name'),
                SelectFilter::make('grade_id')->relationship('grade', 'name'),
                SelectFilter::make('section_id')->relationship('section','name'),
                Filter::make('attendance_date')
                    ->form([
                        DatePicker::make('attendance_date')->label('From Date'),
                        DatePicker::make('attendance_date')->label('To Date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['attendance_date'], fn($q) => $q->whereDate('attendance_date', '>=', $data['date_from']))
                            ->when($data['attendance_date'], fn($q) => $q->whereDate('attendance_date', '<=', $data['date_to']));
                    }),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
