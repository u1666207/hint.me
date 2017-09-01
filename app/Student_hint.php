<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_hint extends Model
{


 
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function hint()
    {
        return $this->belongsTo(Hint::class);
    }
}
