<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{

		    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','isLive'
    ];

         /**
     * Get the user that is a Teacher.
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function question()
    {
        
        return $this->hasMany(Question::class);
        
    }

    public function score()
    {
        return $this->hasMany(Score::class);
    }

    public function destroyQuiz()
    {   
        //get the questions of this quiz
        $questions= Question::where('quiz_id',$this->id)->get();
        foreach ($questions as $question) {
            //destroy each question
            $question->destroyQuestion();
        }

        //delete quiz
        $this->delete();

        //Etsi gia na pellaneis return
        return true;
    }


    public function nextQuestion()
    {   
        $questions = $this->question->pluck('id')->filter(function($value,$key){
            return $value > $this->isLive;
        });
        if($questions->isEmpty()){
            $this->update([
            'isLive' => 0
        ]);
        }else{
            $liveQuestion=$questions->min();

            $this->update([
                'isLive' => $liveQuestion
            ]);
        }


        
        return true;
    }




    /////////////////////////
    //From here and below  //
    //SCOPES               //
    /////////////////////////
    public function scopeLive($query)
    {   

        return $query->where('isLive','>',0)->get();
    }
}
