<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeExam extends Model
{
    use HasFactory;
    protected $fillable=['exam_id','grade_id','section_id'];

    public function exam(){
        return $this->belongsTo(Exam::class);
    }

    public function grade(){
        return $this->belongsTo(Grade::class);
    }

    public function section(){
        return $this->belongsTo(Section::class);
    }
}
