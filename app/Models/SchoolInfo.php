<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolInfo extends Model
{
    use HasFactory;
    protected $fillable=['school_name','email','address','phone','logo','website','slogan','school_theme'];
}
