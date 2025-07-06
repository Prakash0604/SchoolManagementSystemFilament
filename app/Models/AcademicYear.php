<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'start_date', 'end_date', 'status'];

    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->status === true) {
                $model->status = 'Active';
            } elseif ($model->status === false) {
                $model->status = 'Inactive';
            }
        });
    }
}
