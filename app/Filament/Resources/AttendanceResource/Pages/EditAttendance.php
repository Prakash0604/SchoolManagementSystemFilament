<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\AttendanceData;
use App\Models\Student;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    public array $students = [];
    public array $attendance_data = [];

    protected function getFormSchema(): array
    {
        return [
            // Add your static inputs here like academic_year, section, attendance_date, etc.
        ];
    }

    public function mount($record): void
    {
        parent::mount($record);

        $this->students = Student::all()->toArray();

        // Load related attendance data for this attendance record
        if ($record instanceof Attendance) {
            $attendanceData = $record->attendanceData()->get();

            $mappedAttendanceData = [];

            foreach ($attendanceData as $entry) {
                $mappedAttendanceData[$entry->student_id] = [
                    'student_id' => $entry->student_id,
                    'type' => $entry->attendance_type,
                ];
            }

            $this->attendance_data = $mappedAttendanceData;
        } else {
            // If for some reason $record is not a model (shouldn't happen), default all Present
            foreach ($this->students as $student) {
                $this->attendance_data[$student['id']] = [
                    'student_id' => $student['id'],
                    'type' => 'Present',
                ];
            }
        }
    }

    public function setAll(string $type): void
    {
        foreach ($this->attendance_data as $id => $entry) {
            $this->attendance_data[$id]['type'] = ucfirst($type);
        }
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        // Update or create attendanceData entries for students
        foreach ($this->attendance_data as $entry) {
            \App\Models\AttendanceData::updateOrCreate(
                [
                    'attendance_id' => $record->id,
                    'student_id' => $entry['student_id'],
                ],
                [
                    'attendance_type' => $entry['type'],
                ]
            );
        }

        return $record;
    }
}
