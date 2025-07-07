<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ToggleStatusTrait;


class Grade extends Model
{
    use HasFactory, ToggleStatusTrait;
    protected $fillable = ['name', 'status'];

    public function teacherSubjectLists()
    {
        return $this->hasMany(TeacherSubjectList::class);
    }
}
