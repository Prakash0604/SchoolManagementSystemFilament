<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ToggleStatusTrait;

class AcademicYear extends Model
{
    use HasFactory, ToggleStatusTrait;
    protected $fillable = ['name', 'start_date', 'end_date', 'status'];

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class);
    }
}
