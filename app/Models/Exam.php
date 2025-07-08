<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable=['title','start_date','end_date','academic_year_id','publish_date'];

    public function academicYear(){
        return $this->belongsTo(AcademicYear::class);
    }
}
