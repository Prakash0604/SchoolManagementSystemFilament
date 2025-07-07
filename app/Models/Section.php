<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ToggleStatusTrait;

class Section extends Model
{
    use HasFactory, ToggleStatusTrait;

    protected $fillable = ['name', 'grade_id', 'status','academic_year_id'];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function academicYear(){
        return $this->belongsTo(AcademicYear::class);
    }
}
