{{--@extends('layouts.app')--}}
@include('layouts.header')

<body>

<div class="main-wrapper">
    <div class="an-loader-container">
        <img src="./assets/img/loader.png" alt="">
    </div>
    <div class="an-page-content">
        <div class="an-flex-center-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
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

                            <div class="an-single-component with-shadow">
                                <div class="an-component-header">
                                    <h6>Sign Up</h6>
                                    <div class="component-header-right">
                                        <p class="sign-up-link">Already member? <a href="{{route('login')}}">Log In</a></p>
                                    </div>
                                </div>
                                <div class="an-component-body">
                                    <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">

                                        {{ csrf_field() }}

                                        <label>Name</label>
                                        <div  class="an-input-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                            <div class="an-input-group-addon"><i class="ion-person"></i></div>
                                            <input id="name" type="text" class="an-form-control" name="name" value="{{ old('name') }}" required autofocus>

                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif

                                        </div>


                                        <label>Email</label>
                                        <div  class="an-input-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                            <div class="an-input-group-addon"><i class="ion-ios-email-outline"></i></div>
                                            <input id="email" type="email" class="an-form-control" name="email" value="{{ old('email') }}" required autofocus>

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif

                                        </div>

                                        <label>Gender</label>
                                        <div class="an-input-group {{ $errors->has('male') ? ' has-error' : '' }}">
                                            <div class="an-input"></div>
                                            <select id="gender" class="an-form-control" name="male" required>
                                                    <option value="1">Male</option>
                                                    <option value="0">Female</option>
                                            </select>

                                            @if ($errors->has('male'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('male') }}</strong>
                                                </span>
                                            @endif
                                        </div>



                                        <label>Type</label>
                                        <div class="an-input-group {{ $errors->has('exam_type_id') ? ' has-error' : '' }}">
                                            <div class="an-input"></div>
                                            <select id="type" type="text" class="an-form-control" name="exam_type_id" required>
                                                @foreach($types as $type)
                                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('exam_type_id'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('exam_type_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>




                                        <label>Password</label>
                                        <div class="an-input-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                            <div class="an-input-group-addon"><i class="ion-key"></i></div>
                                            <input id="password" type="password" class="an-form-control" name="password" required>

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>


                                        <label>Confirm Password</label>
                                        <div class="an-input-group">
                                            <div class="an-input-group-addon"><i class="ion-key"></i></div>
                                            <input id="password" type="password" class="an-form-control" name="password_confirmation" required>

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>



                                        <label>Mobile</label>
                                        <div class="an-input-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                            <div class="an-input-group-addon"><i class="ion-android-phone"></i></div>
                                            <input id="password" type="number" class="an-form-control" name="mobile" required>

                                            @if ($errors->has('mobile'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('mobile') }}</strong>
                                                </span>
                                            @endif
                                        </div>


                                        <div class="remembered-section no-flex">
                                              <span class="an-custom-radiobox">
                                                <input type="radio" name="male" value="true" id="radio-1" checked="">
                                                <label for="radio-1">Male</label>
                                              </span>
                                            <span class="an-custom-radiobox">
                                                <input type="radio" name="male" value="false" id="radio-2">
                                                <label for="radio-2">Female</label>
                                            </span>
                                        </div>

                                        <button type="submit" class="an-btn an-btn-default fluid">Sign Up</button>

                                    </form>

                                </div> <!-- end .AN-COMPONENT-BODY -->
                            </div>


                        </div> <!-- end an-login-container -->
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
