<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ToggleStatusTrait;

class Section extends Model
{
    use HasFactory, ToggleStatusTrait;

    protected $fillable = ['name', 'grade_id', 'status'];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
