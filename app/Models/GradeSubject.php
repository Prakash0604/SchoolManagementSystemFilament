<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeSubject extends Model
{
    use HasFactory;
    protected $fillable = ['grade_id', 'academic_year_id'];


    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function subjectLists(){
        return $this->hasMany(GradeSubjectList::class);
    }

    protected static function booted(): void
    {
        static::deleting(function ($student) {
            $student->subjectLists()->delete();
        });
    }
}
