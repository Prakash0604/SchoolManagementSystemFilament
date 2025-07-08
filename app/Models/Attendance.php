<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['academic_year_id', 'grade_id', 'section_id', 'attendance_date'];

    protected static function booted(): void
    {
        static::deleting(function ($student) {
            $student->attendanceData()->delete();
        });
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function attendanceData()
    {
        return $this->hasMany(AttendanceData::class);
    }


    public function student()
    {
        return $this->hasMany(Student::class);
    }
}
