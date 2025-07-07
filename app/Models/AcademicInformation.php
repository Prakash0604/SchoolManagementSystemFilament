<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicInformation extends Model
{
    use HasFactory;
    protected $fillable=['academic_year_id','grade_id','section_id','student_id','roll_number'];

    public function academicYear(){
        return $this->belongsTo(AcademicYear::class);
    }

    public function grade(){
        return $this->belongsTo(Grade::class);
    }

    public function section(){
        return $this->belongsTo(Section::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
