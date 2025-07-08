<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeSubjectList extends Model
{
    use HasFactory;
    protected $fillable = ['grade_subject_id', 'subject_id'];

    public function gradeSubject()
    {
        return $this->belongsTo(GradeSubject::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
