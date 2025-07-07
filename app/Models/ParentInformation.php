<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentInformation extends Model
{
    use HasFactory;
    protected $fillable=['parent_name','parent_relation','parent_phone','parent_email','student_id'];

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
