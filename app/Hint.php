<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hint extends Model
{
        		    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body','image','cost'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function student_hint()
    {
        return $this->hasMany(Student_hint::class);
    }

    public function destroyHint()
    {   

        //delete actions of students
        $student_hint = Student_hint::where('hint_id',$this->id)->delete();

        //delete hint
        $this->delete();

         //return
        return;
    }

}
