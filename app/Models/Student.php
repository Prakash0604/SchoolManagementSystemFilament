<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['full_name', 'email', 'date_of_birth', 'gender', 'nationality', 'profile', 'phone', 'address', 'city', 'country', 'previous_school', 'admission_date'];

    protected static function booted(): void
    {
        static::deleting(function ($student) {
            $student->parent()->delete();
            $student->academic()->delete();
        });
    }

    public function parent()
    {
        return $this->hasOne(ParentInformation::class);
    }

    public function academic()
    {
        return $this->hasOne(AcademicInformation::class);
    }
}
