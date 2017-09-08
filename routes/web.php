<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Register Login
Auth::routes();
//COMPETITION resource routes for teachers
Route::resource('competition', 'CompetitionController');

//Student Pages
Route::get('/student/edit', 'StudentController@edit')->name('student.edit');
Route::post('/student/{id}/edit', 'StudentController@update')->name('student.update');
Route::get('/student/{id}', 'StudentController@index')->name('home.student');


//Competition routes for students
Route::get('/student/{id}/{competition}','StudentController@comp')->name('student.comp.show');
Route::get('/student/{id}/{competition}/deregister','StudentController@compDeRegister')->name('student.comp.deregister');
Route::post('/student/{id}/{competition}','StudentController@compRegister')->name('student.comp.register');


//Live Quiz route for teacher
Route::get('/live/watch/{competition}/{quiz}','TeacherController@watchQuiz')->name('teacher.quiz.live');
Route::get('/live/watch/scores','TeacherController@getScores')->name('teacher.quiz.scores');
Route::get('/live/watch/question','TeacherController@getQuestion')->name('teacher.quiz.question');
//Live Quiz routes for student
Route::get('/live/{competition}/{quiz}','StudentController@liveQuiz')->name('student.quiz.live');
Route::get('/live/scores','StudentController@getScores')->name('student.quiz.scores');
Route::get('/live/question','StudentController@getQuestion')->name('student.quiz.question');
Route::post('/live/response','StudentController@response')->name('student.quiz.response');
Route::post('/live/gethint','StudentController@getHint')->name('student.quiz.hint');


//Quiz routes for teacher
//Resource routes
Route::get('download/quiz/{competition}/{quiz}/','QuizController@getData')->name('quiz.data');
Route::get('reset/quiz/{competition}/{quiz}/','QuizController@resetQuiz')->name('quiz.reset');
Route::get('/quiz/{competition}/{quiz}/edit','QuizController@edit')->name('quiz.edit');
Route::delete('/quiz/{competition}/{quiz}/delete','QuizController@destroy')->name('quiz.destroy');
Route::get('/quiz/{competition}/{quiz}/launch','QuizController@launch')->name('quiz.launch');
Route::get('/quiz/{competition}/{quiz}','QuizController@show')->name('quiz.show');
Route::post('/quiz/{competition}/{quiz}','QuizController@update')->name('quiz.update');
Route::get('/quiz/{competition}/','QuizController@create')->name('quiz.create');
Route::post('/quiz/{competition}/','QuizController@store')->name('quiz.store');


//Question routes for teacher
//Resource routes
Route::get('/question/{competition}/{quiz}/{question}/edit','QuestionController@edit')->name('question.edit');
Route::post('/question/{competition}/{quiz}/{question}/update','QuestionController@update')->name('question.update');
Route::delete('/question/{competition}/{quiz}/{question}/delete','QuestionController@destroy')->name('question.destroy');
Route::get('/question/{competition}/{quiz}/{type}','QuestionController@create')->name('question.create');
Route::post('/question/{competition}/{quiz}/{type}','QuestionController@store')->name('question.store');




//Teacher Pages
Route::get('/teacher/edit', 'TeacherController@edit')->name('teacher.edit');
Route::post('/teacher/{id}/edit', 'TeacherController@update')->name('teacher.update');
Route::get('/teacher/{id}', 'TeacherController@index')->name('home.teacher');




Route::get('/home', 'HomeController@index')->name('home');







