<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quiz;
use App\Competition;
use App\Question;
use App\Student;
use App\Score;
use App\Answer;
use App\Student_hint;
use App\Hint;
use Excel;


class QuizController extends Controller
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
        $this->middleware('teacherCreator');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Competition $competition)
    {
        return view('teacher.quiz.create')->with('competition', $competition);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Competition $competition)
    {
        //validate
        $this->validate($request, [
            'name' => 'required|string|min:1|max:255',       
        ]);

        //update data
        $quiz=new Quiz;

        $quiz->name = $request->input('name');
        $quiz->competition_id = $competition->id;
        $quiz->save();

         //redirect
        return redirect()->route('quiz.show',['competition'=>$competition,'quiz'=>$quiz]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function show(Competition $competition, Quiz $quiz)
    {
        $questions= Question::where('quiz_id',$quiz->id)->get();
        return view('teacher.quiz.show')->with('competition',$competition)->with('quiz',$quiz)->with('questions',$questions);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit(Competition $competition,Quiz $quiz)
    {
        return view('teacher.quiz.edit')->with('competition',$competition)->with('quiz',$quiz);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Competition $competition, Quiz $quiz)
    {
    	//validate
        $this->validate($request, [
            'name' => 'required|string|min:1|max:255',
        
            
        ]);

        //update data
        $quiz->name = $request->input('name');

        $quiz->save();

         //redirect
        return redirect()->route('competition.show',$competition->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quiz  $competition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Competition $competition, Quiz $quiz)
    {

        $quiz->destroyQuiz();
        
         //redirect
        return redirect()->route('competition.show',$competition->id);
        

    }

    //Function to launch a quiz
    // $quiz->isLive >0
    public function launch(Competition $competition, Quiz $quiz)
    {
        //update data
        $question = Question::where('quiz_id',$quiz->id)->first();
        if ($question){
            $quiz->isLive = $question->id;
            $quiz->save();
        }
         //redirect
        return redirect()->route('competition.show',$competition->id);
        

    }

    //Download Data of Quiz with Excel Extension library
    //Download a csv file
    public function getData(Competition $competition, Quiz $quiz)
    {

        $competitionId=$competition->id;
        //GET Registered students of competition with competitionID
        $students = Student::whereHas('competition',function($q) use ($competitionId){
            $q->where('competition_id',$competitionId);
        })->get();
        $questions = Question::where('quiz_id',$quiz->id)->get(); //Get the questions of the quiz
        $array=array();    //Array that data will be saved initiated to empty
        //Loop through students
        foreach ($students as $student) {
            $value_array=array();//array that is going to be the value of $array with $student->id as key
                                 //this will correspond to rows in the csv table
            $hints_taken = Student_hint::where('student_id',$student->id)->get(); //Find hints taken by this student      
            $value_array = array_add($value_array,'Student Email',$student->user->email);  //first row is Student Email
            //Loop through questions
            foreach($questions as $question) {
                //find answer of student in this question
                $answer = Answer::where('student_id',$student->id)->where('question_id',$question->id)->first();
                //if there is not an answer
                if(is_null($answer)){
                    //add next collumn with string 'no answer'
                    $value_array = array_add($value_array,'QuestionID= '.$question->id,'no answer');
                }else{
                    //add next collumn with the answer
                    $value_array = array_add($value_array,'QuestionID= '.$question->id,$answer->answer);
                }
                
                //Next collumn is boolean//Yes: if a hint was used for this question//No: if a hint was not used for this question
                $value_array = array_add($value_array,'Used hint for '.$question->id,'no');
                foreach ($hints_taken as $hint_taken) {
                    $hint = Hint::find($hint_taken->hint_id);
                    if ($hint->question_id == $question->id){
                        $value_array = array_set($value_array,'Used hint for '.$question->id,'yes');
                    }
                }
            }
            //Last collumn adds score of student if it exists
            $score = Score::where('quiz_id',$quiz->id)->where('student_id',$student->id)->first();
            if(is_null($score)){
                $value_array = array_add($value_array,'score',null);
            }else{
                $value_array = array_add($value_array,'score',$score->points);
            }
            //For each student, the key is the student_id and the value the array that was created
            $array[$student->id] = $value_array;
        }

        //Export CSV file using Excel
        Excel::create('Filename', function($excel) use($array) {
        
            $excel->sheet('Sheetname', function($sheet) use($array) {
                $sheet->fromArray($array);
            });
        
        })->export('csv');


         //redirect to competition page
        return redirect()->route('competition.show',$competition->id);
        

    }

    //Reset Data of Quiz
    public function resetQuiz(Competition $competition, Quiz $quiz)
    {
        //find questions of this quiz
        $questions = Question::where('quiz_id',$quiz->id)->get();
   
        foreach($questions as $question){
            //delete answers of srudents
            $answers = Answer::where('question_id',$question->id)->delete();
            //delete scores of srudents
            $scores = Score::where('quiz_id',$quiz->id)->delete();

            //get Hints
            $hints = Hint::where('question_id',$question->id)->get();
            foreach ($hints as $hint) {
                //delete actions of students for each hint
                $student_hint = Student_hint::where('hint_id',$hint->id)->delete();
            }

        }

        //set $quiz-isLive to null, which corresponds to offline
        $quiz->update([
                'isLive' => null
            ]);

         //redirect
        return redirect()->route('competition.show',$competition->id);
        

    }




}
