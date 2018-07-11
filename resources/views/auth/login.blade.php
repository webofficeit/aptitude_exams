{{--@extends('layouts.app')--}}
@include('layouts.header')

<body>

<div class="main-wrapper">
    <div class="an-loader-container">
        <img src="./assets/img/loader.png" alt="">
    </div>

    <div class="row" style="height: 60px; background-color: #2D70B6">

    </div>

    <div class="row" style="height: 150px; background-color: #666666">

        <div class="col-sm-3 p30lft p10top">

           <span class="white-font"> System Name: </span> <br>

            <h1 class="yellow-font">C001</h1>

            <span class="white-font">
                Click here if the Name and Photograph shown on the screen is not yours
            </span>

        </div>


        <div class="col-sm-5">

        </div>


        <div class="col-sm-4 p30lft">

            <div class="col-sm-7 p10top text-right" style="padding-right: 0px">
                <span class="white-font"> Candidate Name: </span> <br>

                <h1 class="yellow-font">
                    Your Name
                </h1>

                <span class="white-font">
                    Subject: <span class="yellow-font">Mock Exam</span>
                </span>

            </div>

            <div class="col-sm-5" style="padding-right: 0px;">

                <img src="/assets/img/NewCandidateImage.jpg" style="background-color: #FAFBFC; padding:12px; float: right;" />

            </div>


        </div>

    </div>

    <div class="an-page-content">
        <div class="an-flex-center-center">



            <div class="container">
                <div class="row">

                    <div class="col-md-6 col-md-offset-3">


                        <div class="col-md-10 col-md-offset-1">

                            <div class="an-login-container">
                                <div class="back-to-home">
                                    <h3 class="an-logo-heading text-center wow fadeInDown">
                                        <a class="an-logo-link" href="#">
                                            <img src="{{asset('/assets/img/aptitude_logo.png')}}" width="300" />
                                            <span>

                                        </span>
                                        </a>
                                    </h3>
                                </div>
                                <div class="an-single-component with-shadow" style="background-color: #F5F5F5">

                                    <div class="an-component-header" style="background-color: #DDDDDD">
                                        <h6>Login</h6>
                                        {{--<div class="component-header-right">--}}
                                            {{--<p class="sign-up-link">Don't have account? <a href="{{route('register')}}">Sign Up</a></p>--}}
                                        {{--</div>--}}
                                    </div>

                                    <div class="an-component-body">
                                        {{--<div class="an-social-login">--}}
                                        {{--<label>Login with</label>--}}
                                        {{--<a class="an-social-icon facebook" href="#"><i class="ion-social-facebook"></i></a>--}}
                                        {{--<a class="an-social-icon twitter" href="#"><i class="ion-social-twitter"></i></a>--}}
                                        {{--<a class="an-social-icon google-plus" href="#"><i class="ion-social-googleplus"></i></a>--}}
                                        {{--<a class="an-social-icon dribble" href="#"><i class="ion-social-dribbble"></i></a>--}}
                                        {{--<a class="an-social-icon github" href="#"><i class="ion-social-github"></i></a>--}}
                                        {{--<a class="an-social-icon instagram" href="#"><i class="ion-social-instagram"></i></a>--}}
                                        {{--<a class="an-social-icon yahoo" href="#"><i class="ion-social-yahoo"></i></a>--}}
                                        {{--<a class="an-social-icon linkedin" href="#"><i class="ion-social-linkedin"></i></a>--}}
                                        {{--</div>--}}
                                        <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                                            {{ csrf_field() }}

                                            <label>Email</label>
                                            <div  class="an-input-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                                <div class="an-input-group-addon"><i class="ion-ios-person"></i></div>
                                                <input id="email" type="email" class="an-form-control" name="email" value="{{ old('email') }}" required autofocus>

                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                                @endif

                                            </div>

                                            <label>Password</label>
                                            <div class="an-input-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                                <div class="an-input-group-addon"><i class="ion-locked"></i></div>
                                                <input id="password" type="password" class="an-form-control" name="password" required>

                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                                @endif
                                            </div>

                                            <div class="remembered-section">
                          {{--<span class="an-custom-checkbox">--}}
                                                                      {{--<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me--}}

                              {{--<input type="checkbox" id="check-1" name="remember" {{ old('remember') ? 'checked' : '' }} >--}}
                            {{--<label for="check-1">Remember me</label>--}}
                          {{--</span>--}}
                                                <a href="{{ route('password.request') }}">Forgot password?</a>
                                            </div>

                                            <button type="submit" class="an-btn an-btn-default an-btn-blue fluid">Sign In</button>
                                        </form>

                                    </div> <!-- end .AN-COMPONENT-BODY -->
                                </div> <!-- end .AN-SINGLE-COMPONENT -->
                            </div> <!-- end an-login-container -->

                        </div>

                    </div>
                </div> <!-- end row -->
            </div>
        </div> <!-- end an-flex-center-center -->
    </div> <!-- end .AN-PAGE-CONTENT -->
    <footer class="an-footer">
        <p>COPYRIGHT 2017 © Aptitude. ALL RIGHTS RESERVED</p>
    </footer> <!-- end an-footer -->

</div> <!-- end .MAIN-WRAPPER -->
<script src="./assets/js-plugins/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="./assets/js-plugins/bootstrap.min.js" type="text/javascript"></script>
<script src="./assets/js-plugins/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<script src="./assets/js-plugins/wow.min.js" type="text/javascript"></script>

<!--  MAIN SCRIPTS START FROM HERE  above scripts from plugin   -->
<script src="./assets/js/scripts.js" type="text/javascript"></script>

</body>


