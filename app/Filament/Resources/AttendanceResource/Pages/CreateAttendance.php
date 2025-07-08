<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\AttendanceData;
use App\Models\Student;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Set;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;
    public bool $isAttendanceAlreadyTaken = false;

    public array $students = [];
    public array $attendance_data = [];

    protected function getFormSchema(): array
    {
        return [
            // Add any static inputs like Selects for academic_year, section, date, etc.
        ];
    }

    public function mount(): void
    {
        parent::mount();

        // Get all students (customize filter as needed)
        $this->students = Student::all()->toArray();

        // Pre-fill attendance data
        foreach ($this->students as $student) {
            $this->attendance_data[$student['id']] = [
                'student_id' => $student['id'],
                'type' => 'Present',
            ];
        }
    }


    public function checkAttendance(Set $set, callable $get)
    {
        $academicYear = $get('academic_year_id');
        $grade = $get('grade_id');
        $section = $get('section_id');
        $date = $get('attendance_date');

        if ($academicYear && $grade && $section && $date) {
            $exists = \App\Models\Attendance::where('academic_year_id', $academicYear)
                ->where('grade_id', $grade)
                ->where('section_id', $section)
                ->whereDate('attendance_date', $date)
                ->exists();

            $set('is_already_taken', $exists);
        }
    }

    protected function canCreate(): bool
    {
        return $this->form->getState()['is_already_taken'] != true;
    }


    public function setAll(string $type): void
    {
        foreach ($this->attendance_data as $id => $entry) {
            $this->attendance_data[$id]['type'] = ucfirst($type);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $exists = \App\Models\Attendance::where('academic_year_id', $data['academic_year_id'])
            ->where('grade_id', $data['grade_id'])
            ->where('section_id', $data['section_id'])
            ->whereDate('attendance_date', $data['attendance_date'])
            ->exists();

        if ($exists) {
            // Throw validation error
            throw \Illuminate\Validation\ValidationException::withMessages([
                'attendance_date' => ['Attendance already taken for selected criteria.'],
            ]);
        }

        return $data;
    }


    protected function handleRecordCreation(array $data): Model
    {
        $attendance = Attendance::create($data);

        foreach ($this->attendance_data as $entry) {
            AttendanceData::create([
                'attendance_id' => $attendance->id,
                'student_id' => $entry['student_id'],
                'attendance_type' => $entry['type'],
            ]);
        }

        return $attendance;
    }
}
