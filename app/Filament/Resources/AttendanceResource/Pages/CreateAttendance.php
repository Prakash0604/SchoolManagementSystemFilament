<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Filament\Resources\ViewResource\Pages\Student;
use App\Models\Attendance;
use Filament\Actions;
use App\Models\Student as Stu;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class CreateAttendance extends CreateRecord 
{
    protected static string $resource = AttendanceResource::class;
    // use InteractsWithForms;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Your save logic here
        return $data;
    }

    protected function getFormSchema(): array
    {
        return [
            // your schema
        ];
    }
    public function getFormStatePath(): string
    {
        return parent::getFormStatePath();
    }


public function mount(): void
{
    parent::mount(); // Required by Filament

    $students = Stu::all();

    $attendanceData = [];

    foreach ($students as $student) {
        $attendanceData[$student->id] = [
            'student_id' => $student->id,
            'type' => 'present',
        ];
    }

    // ✅ This is the correct way
    $this->fillForm([
        'attendance_data' => $attendanceData,
    ]);
}



    protected function initializeAttendanceData(): array
    {
        $students = Student::all();
        $data = [];

        foreach ($students as $student) {
            $data[$student->id] = [
                'student_id' => $student->id,
                'type' => 'present',
            ];
        }

        return $data;
    }
}
