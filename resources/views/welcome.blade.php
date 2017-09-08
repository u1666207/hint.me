<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Hint Master</title>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->

        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                margin-top:5em;
                text-align: center;
            }

            .title {   
                font-size: 5em;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            li {
                display: block;
                color: black; 
                font-weight: bold;
            }

            li:before {
                /*Using a Bootstrap glyphicon as the bullet point*/
                content: "\e013";
                font-family: 'Glyphicons Halflings';
                font-size: 1vi;
                float: left;
                margin-top: 4px;
                
                color: black;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content"> 

                <div class="jumbotron">
                    <div class="title m-b-md">
                        <img src="{{ asset('hintmaster.png') }}">
                    </div>
                    <h4>Live Quiz competitions in the classroom with hints!</h4>
                    <hr>
                    <div class="col-md-12">
                        <div class="col col-md-6">
                            <div class="list-group">
                                <h4 class="list-group-item-heading">Teacher</h4>
                                <p class="list-group-item-text">
                                    <li class="list-group-item">Create Quiz Competition with questions</li>
                                    <li class="list-group-item">Add Hints to questions</li>
                                    <li class="list-group-item">Launch Quiz!</li>

                                </p>    
                            
                            </div>
                        </div>
                        <div class="col col-md-6">
                            <div class="list-group">
                                <h4 class="list-group-item-heading">Student</h4>
                                <p class="list-group-item-text">
                                    <li class="list-group-item">Register to Competitions</li>
                                    <li class="list-group-item">Take part in Quizzes</li>
                                    <li class="list-group-item">Achieve the highest score!</li>

                                </p>    
                            
                            </div>
                        </div>
                    </div>

                    

                    <p class="lead">
                        @if (Auth::check())
                            <a href="{{ url('/home') }}" class="btn btn-primary" role="button">Home</a>
                        @else
                            <a href="{{ url('/login') }}" class="btn btn-success" role="button">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                Login
                            </a>
                            <a href="{{ url('/register') }}" class="btn btn-warning" role="button">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                Register
                            </a>
                        @endif
                    </p>
                </div>

   
            </div>
        </div>
    </body>
        <script src="{{ asset('js/app.js') }}"></script>
</html>
