@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Quiz : {{$quiz->name}}</div>

                <div class="panel-body">
                    <p align="right">
                        <a href="{{ route('competition.show',['competition'=>$competition->id]) }}" class="btn btn-info btn-responsive" role="button">Back to Competition</a>
                    </p>

                    <div id="app">
                        <questionshow :questions="{{ json_encode($questions)}}"></questionshow>

                    </div>
                  
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@endsection