<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ToggleStatusTrait;

class Subject extends Model
{
    use HasFactory,ToggleStatusTrait;
    protected $fillable=['name','status'];
}
