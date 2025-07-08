<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubject extends Model
{
    use HasFactory;
    protected $fillable=['grade_exam_id','grade_subject_id','full_mark','pass_mark','total_mark'];

    public function gradeExam(){
        return $this->belongsTo(GradeExam::class);
    }

    public function gradeSubject(){
        return $this->belongsTo(GradeSubject::class);
    }
}
