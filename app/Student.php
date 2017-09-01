<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Student extends Model
{

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'institution','nickname'
    ];


    /**
     * Get the user that is a Student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Many to many students register to competitions.
     */
    public function competition()
    {
        return $this->belongsToMany(Competition::class)->withTimestamps();
    }
 
    public function answer(){
        return $this->hasMany(Answer::class);
    }

    public function score()
    {
        return $this->hasMany(Score::class);
    }

    public function student_hint()
    {
        return $this->hasMany(Student_hint::class);
    }
}
