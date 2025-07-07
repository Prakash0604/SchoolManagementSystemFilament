<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubjectList extends Model
{
    use HasFactory;
    protected $fillable = ['teacher_subject_id', 'grade_id', 'section_id', 'subject_id'];
    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
