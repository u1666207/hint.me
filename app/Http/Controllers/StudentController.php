<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Student;
use App\Competition;
use App\Quiz;
use App\Question;
use App\Short;
use App\Multiple;
use App\Answer;
use App\Score;
use App\Student_hint;
use App\Hint;
use Carbon\Carbon;

class StudentController extends Controller
{
    
	    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('student');

    }


    public function index(Request $request,$id)
    {
        $student=Student::find($id); 
        $competitions = $student->competition;
        

        $term=$request->get('search');
        if ($term){
            $searchComps=Competition::search($term)->get();
        }else{
            $searchComps=collect([]);
        }

        //dd($searchComps);
        return view('student.dashboard')->with('competitions',$competitions)->with('id',$id)->with('searchComps',$searchComps);
        
    }

    public function edit()
    {
        return view('student.edit')->with('user',Auth::user());
    }

    public function update(Request $request, $id)
    {
        //validate
        $this->validate($request, [
            'first_name' => 'nullable|string|max:25',
            'last_name' => 'nullable|string|max:25',
            'institution' => 'nullable|string',
            'nickname'=> 'nullable|string',
        ]);

        //update data
        $student=Student::find($id);

        $student->first_name = $request->input('first_name');
        $student->last_name = $request->input('last_name');
        $student->institution = $request->input('institution');
        $student->nickname = $request->input('nickname');

        $student->save();


        // Need Flash message here


        //redirect
        return redirect()->route('home.student', ['id' => $id]);
    }

    public function comp($id,Competition $competition)
    {
        //Check pivot table if the student is registered
        $hasStudent = $competition->student->contains($id);
        $quizzes = Quiz::where('competition_id',$competition->id)->get();
        

        if($hasStudent){
            $registered=true;
        }else{
            $registered=false;
        }

        return view('student.competition.show')->with('competition',$competition)->with('registered',$registered)->with('id',$id)->with('quizzes',$quizzes);

    }

    public function compRegister(Request $request,$id,Competition $competition)
    {
        if ($request->code == $competition->code){
            //Attach student to competition
            $competition->student()->attach($id);
        }
        

        //redirect
        return redirect()->route('student.comp.show', ['id' => $id,'competition'=>$competition]);

    }

    public function compDeRegister($id,Competition $competition)
    {
        
        //Detach student from competition
        $competition->student()->detach($id);

        //redirect
        return redirect()->route('home.student', ['id' => $id]);

    }

    ///////////////////
    ////////////////////////////
    
    // THE FOLLOWING FUNCTIONS /
    // REFER TO THE LIVE QUIZ  /
    
    ////////////////////////////
    ////////////////////
    

    //access live quiz
    public function liveQuiz(Competition $competition, Quiz $quiz)
    {
           
        $questions=Question::where('quiz_id',$quiz->id)->with(['multiple','short','hint'])->get();   
        $user=Auth::user();
        $student=Student::where('user_id',$user->id)->first();
        if (!(Score::where('student_id',$student->id)
            ->where('quiz_id',$quiz->id)->exists())){
    
            $score= Score::forceCreate([
                'points' => 0,
                'student_id' => $student->id,
                'quiz_id' => $quiz->id,

            ]);

        };

        return view('student.quiz.live')->with('competition',$competition)->with('quiz',$quiz)->with('questions',$questions);

    }

    //answer to question from student
    public function response()
    {
        
        $user = Auth::user();
        $student = Student::where('user_id',$user->id)->first();


        if (!(Answer::where('student_id',$student->id)
            ->where('question_id',request('question_id'))->exists())){
            $answer= Answer::forceCreate([
                'answer' => request('answer'),
                'student_id' => $student->id,
                'question_id' => request('question_id'),

            ]);
            
            //get question and find correct answer
            $question = Question::where('id',$answer->question_id)->first();
            if ($question->isMultiple){
                $multiple=Multiple::where('question_id',$question->id)->where('isCorrect',1)->first();                   
                $correct=$multiple->answer;
            }else{
                $short=Short::where('question_id',$question->id)->first();                   
                $correct=$short->correct_answer;
            }
            

            $quiz = Quiz::where('id',$question->quiz_id)->first();
            $score=Score::where('student_id',$student->id)
                ->where('quiz_id',$quiz->id)->first();
            //CHECK ig answer is correct to update score
            if (request('answer') == $correct ){
                $points = $score->points + $question->correct_points;
            }else{
                $points = $score->points - $question->wrong_points;
            }
            //UPDATE SCORE
            $score->update(['points' => $points]);
        }
      

        return;
        
    }

    //getHint post 
    public function getHint()
    {
        
        $user = Auth::user();
        $student = Student::where('user_id',$user->id)->first();


        if (!(Student_hint::where('student_id',$student->id)
            ->where('hint_id',request('hint_id'))->exists())){
            $student_hint= Student_hint::forceCreate([
                'student_id' => $student->id,
                'hint_id' => request('hint_id'),

            ]);
            
            //find hint, question and quiz from hint_id      
            $hint = Hint::where('id',request('hint_id'))->first();
            $question= Question::where('id',$hint->question_id)->first();
            $quiz = Quiz::where('id',$question->quiz_id)->first();
            
            //get quiz score
            $score=Score::where('student_id',$student->id)
                ->where('quiz_id',$quiz->id)->first();
            
            //reduce points
            $points = $score->points - $hint->cost;
            
            //UPDATE SCORE
            $score->update(['points' => $points]);
        }
      

        return;
        
    }

    //update scores of leaderboard
    public function getScores()
    {
           
        $scores=Score::where('quiz_id',request('quiz_id'))->orderBy('points', 'desc')->with('student')->get();   
        
        return response(['scores'=>$scores]);

    }


    //update live question
    public function getQuestion()
    {
        $quiz = Quiz::find(request('quiz_id'));

        if(!($quiz->isLive==0)){
            $question=Question::where('id',$quiz->isLive)->with(['multiple','short','hint'])->first(); 
            
            $updated = $quiz->updated_at;
            $finish= $updated->addMinutes($question->minutes)->addSeconds($question->seconds);

            $seconds=$finish->diffInSeconds(Carbon::now());
            
          
            $seconds = gmdate('i:s', $seconds);
            return response(['liveQuestion'=>$question,'seconds'=>$seconds]);
        }else{
            return response(['liveQuestion'=>'null','seconds'=>' ']);
        }

    }

}
