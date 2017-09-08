<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use App\Quiz;
use App\Question;

class ChangeQuestion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:changequestion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if question need to be changed for a live Quiz';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //iterate 60 times, every second of a minute
        for($i = 0; $i<60; $i++){
            //take all live quizzes
            $quizzes= Quiz::live();
            foreach($quizzes as $quiz){
                //find the question that is live
                $question=Question::find($quiz->isLive);

                //find finish time of this question 
                $updated = $quiz->updated_at;
                $finish= $updated->addMinutes($question->minutes)->addSeconds($question->seconds);
                
                //if time now passed finished time
                if ($finish < Carbon::now()){
                    //Next Question 
                    $quiz->nextQuestion();
                }
            }
            sleep(1);//sleep 1s
        }
    }

}
