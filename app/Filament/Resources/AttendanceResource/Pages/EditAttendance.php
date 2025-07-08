<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\AttendanceData;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\AttendanceResource;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    public array $students = [];
    public array $attendance_data = [];

    public function mount($record): void
    {
        parent::mount($record); // ✅ Ensure $this->record is an Attendance model

        // ✅ Load students based on academic data
        $this->students = Student::whereHas('academic', function ($query) {
            $query->where('academic_year_id', $this->record->academic_year_id)
                  ->where('grade_id', $this->record->grade_id)
                  ->where('section_id', $this->record->section_id);
        })->get()->toArray();

        // ✅ Load attendance_data related to this record
        foreach ($this->record->attendanceData as $entry) {
            $this->attendance_data[$entry->student_id] = [
                'student_id' => $entry->student_id,
                'type' => $entry->attendance_type,
            ];
        }
    }

    // Optional: Update logic
    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // ✅ Update main attendance record
        $record->update($data);

        // ✅ Delete old data and recreate (or use update/create logic)
        $record->attendanceData()->delete();

        foreach ($this->attendance_data as $entry) {
            AttendanceData::create([
                'attendance_id' => $record->id,
                'student_id' => $entry['student_id'],
                'attendance_type' => $entry['type'],
            ]);
        }

        return $record;
    }
}
