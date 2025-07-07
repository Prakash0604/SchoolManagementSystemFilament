<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\AttendanceData;
use App\Models\Student;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

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

    public function setAll(string $type): void
    {
        foreach ($this->attendance_data as $id => $entry) {
            $this->attendance_data[$id]['type'] = ucfirst($type);
        }
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
