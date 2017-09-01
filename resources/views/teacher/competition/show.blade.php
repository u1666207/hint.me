@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Quiz Competition: <b><I>{{$competition->name}}</I></b></div>
                <div class="panel-body">
                    <p align="right">
                        <a href="{{ route('home') }}" class="btn btn-info btn-responsive" role="button">Back to Dashboard</a>
                    </p>

                    <div class="panel panel-primary">
                        <div class="panel-heading">Quizzes</div>
                        <div class="panel-body">
                            <a href="{{ route('quiz.create',['competition'=>$competition->id]) }}" class="btn btn-success btn-responsive" role="button">Add new quiz</a>
                            
                            <div class="table-responsive fixed-panel">
                                <table class="table table-sm">
                                    <thead> 
                                        <tr>
                                            <th>#</th>
                                            <th>Quiz</th>
                                            <th>Launch</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!$quizzes->isEmpty())
                                            @php ($i = 0)
                                            @foreach($quizzes as $quiz)
                                                <tr>
                                                    @php ($i =$i + 1)
                                                    <th>{{$i}}</th>
                                                    <th><a href="{{ route('quiz.show',['competition'=>$competition->id,'quiz'=>$quiz->id]) }}" >{{ $quiz->name }}</a></th>
                                                    

                                                    @if($quiz->isLive == 0)
                                                        <th><a href="{{ route('quiz.launch',['competition'=>$competition->id,'quiz'=>$quiz->id]) }}" class="btn btn-warning btn-responsive btn-sm" role="button"><b>Launch Now!</b></a></th> 
                                                    @elseif($quiz->isLive > 0)
                                                        <th><b style="color:red;">Now Live!</b></th> 
                                                    @else
                                                        <th>Finished</th>
                                                    @endif
                                                    
                                                    
                                                    @if($quiz->isLive > 0)
                                                        <th><a href="{{ route('teacher.quiz.live',['competition'=>$competition->id,'quiz'=>$quiz->id]) }}" class="btn btn-success btn-responsive btn-sm" role="button"><b>Watch Live!</b></a></th> 
                                                    @elseif(is_null($quiz->isLive))
                                                        <th>
                                                            <button class="btn btn-responsive btn-sm" role="button">
                                                                <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>Data
                                                            </button>
                                                   
                                                            <button class="btn btn-responsive btn-sm" role="button">
                                                                <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>Reset
                                                            </button>
                                                         </th>
                                                    @else
                                                        <th></th>
                                                    @endif

                                                      
                                                    
                                                    
                                                    <th>
                                                   
                                                            <a href="{{ route('quiz.edit',['competition'=>$competition->id,'quiz'=>$quiz->id]) }}" class="btn btn-primary btn-responsive btn-sm btn-primary">Edit</a>
                                                    </th>
                                                    <th>
                                                      
                                                            {{ Form::open(['route' => ['quiz.destroy', $competition->id,$quiz->id], 'method' => 'delete']) }}
                                                                <button type="submit" class="btn btn-responsive btn-sm btn-danger" >Delete</button>
                                                            {{ Form::close() }}
                                                      
                                             
                                                    </th>
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>

                                </table>
                                @if($quizzes->isEmpty())
                                    <h3><I>No quizzes created</I></h3>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            


        </div>
    </div>
</div>
@endsection


