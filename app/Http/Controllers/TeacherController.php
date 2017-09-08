<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Teacher;
use App\Competition;
use App\Quiz;
use App\Question;
use App\Score;
use App\Multiple;
use App\Hint;
use App\Short;
use App\Student;
use App\Student_hint;
use Carbon\Carbon;



class TeacherController extends Controller
{

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');

    }
    
    public function index($id)
    {
        $competitions = Competition::where('teacher_id', $id)->orderBy('created_at', 'desc')->get();
        return view('teacher.dashboard')->with('competitions',$competitions);
    }

    public function edit()
    {
        return view('teacher.edit')->with('user',Auth::user());
    }

    public function update(Request $request, $id)
    {
        //validate
        $this->validate($request, [
            'first_name' => 'nullable|string|max:25',
            'last_name' => 'nullable|string|max:25',
            'institution' => 'nullable|string',
        ]);

        //update data
        $teacher=Teacher::find($id);

        $teacher->first_name = $request->input('first_name');
        $teacher->last_name = $request->input('last_name');
        $teacher->institution = $request->input('institution');

        $teacher->save();


        // Need Flash message here


        //redirect
        return redirect()->route('home.teacher', ['id' => $id]);
    }

    ///////////////////
    // Live quiz     //
    // functions     //
    ///////////////////

    //access live quiz and watch
    public function watchQuiz(Competition $competition, Quiz $quiz)
    {
           
        $questions=Question::where('quiz_id',$quiz->id)->with(['multiple','short','hint'])->get();   
        
        return view('teacher.quiz.live')->with('competition',$competition)->with('quiz',$quiz)->with('questions',$questions);

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

