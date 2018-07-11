@extends('layouts.app')

@section('content')



    <div class="col-md-4 col-md-offset-2 grid-item" >
        <div class="an-single-component with-shadow">
            <div class="an-component-header">
                <h6>Upcoming Exams</h6>
                {{--<div class="component-header-right">--}}
                {{--<form class="an-form" action="#">--}}
                {{--<div class="an-search-field">--}}
                {{--<input class="an-form-control" type="text" placeholder="Search...">--}}
                {{--<button class="an-btn an-btn-icon" type="submit"><i class="icon-search"></i></button>--}}
                {{--</div>--}}
                {{--</form>--}}
                {{--</div>--}}
            </div>
            <div class="an-component-body">
                <div class="an-user-lists customer-support">
                    <div class="an-lists-body an-customScrollbar">


                        @if(isset($upcomingExams) and $upcomingExams->count())
                            @foreach($upcomingExams as $exam)
                                <a href="#">
                                    <div class="list-user-single closed">
                                        <div class="list-name">
                                            {{--<span class="image"--}}
                                            {{--style="background: url('assets/img/users/user8.jpeg') center center no-repeat;--}}
                                            {{--background-size: cover;">--}}
                                            {{--</span>--}}
                                            {{$exam->name}}  <span> {{ \Carbon\Carbon::parse($exam->start_date_time)->diffForHumans()  }} </span>
                                            <div class="list-state">
                                                <span class="msg-tag read">Open</span>
                                            </div>
                                        </div>
                                        <p class="comment">

                                        </p>
                                    </div> <!-- end .USER-LIST-SINGLE -->
                                </a>
                            @endforeach

                        @else

                            <div class="list-user-single closed">
                                <div class="list-name">

                                    <a href="#">     <span>  None  </span>
                                    </a>


                                </div>
                                <p class="comment">

                                </p>
                            </div>


                        @endif




                    </div> <!-- end .AN-LISTS-BODY -->
                </div>
            </div> <!-- end .AN-COMPONENT-BODY -->
        </div> <!-- end .AN-SINGLE-COMPONENT messages -->
    </div>


    <div class="col-md-4 grid-item" >
        <div class="an-single-component with-shadow">
            <div class="an-component-header">
                <h6>LIVE Exams</h6>
                {{--<div class="component-header-right">--}}
                {{--<form class="an-form" action="#">--}}
                {{--<div class="an-search-field">--}}
                {{--<input class="an-form-control" type="text" placeholder="Search...">--}}
                {{--<button class="an-btn an-btn-icon" type="submit"><i class="icon-search"></i></button>--}}
                {{--</div>--}}
                {{--</form>--}}
                {{--</div>--}}
            </div>
            <div class="an-component-body">
                <div class="an-user-lists customer-support">
                    <div class="an-lists-body an-customScrollbar">


                        @if(isset($liveExams) and $liveExams->count())
                            @foreach($liveExams as $exam)
                                <a href="{{route('exams.show', ['id'=> $exam->id ] )}}">
                                    <div class="list-user-single closed">
                                        <div class="list-name">
                                            {{--<span class="image"--}}
                                            {{--style="background: url('assets/img/users/user8.jpeg') center center no-repeat;--}}
                                            {{--background-size: cover;">--}}
                                            {{--</span>--}}
                                            <div class="col-sm-12">"{{$exam->name}}" </div> <br/>
                                            <div class="col-sm-12">
                                                <small> Ends at  {{ \Carbon\Carbon::parse($exam->end_date_time)->format('h:m A d M')  }}.
                                                    <br> {{ \Carbon\Carbon::parse($exam->end_date_time)->diffForHumans()  }} </small>
                                            </div>

                                            <div class="list-state">
                                                <span class="msg-tag read">Open</span>
                                            </div>
                                        </div>
                                        <p class="comment">

                                        </p>
                                    </div> <!-- end .USER-LIST-SINGLE -->
                                </a>
                            @endforeach

                        @else

                            <div class="list-user-single closed">
                                <div class="list-name">

                                    <a href="#">     <span>  None  </span>
                                    </a>


                                </div>
                                <p class="comment">

                                </p>
                            </div>


                        @endif




                    </div> <!-- end .AN-LISTS-BODY -->
                </div>
            </div> <!-- end .AN-COMPONENT-BODY -->
        </div> <!-- end .AN-SINGLE-COMPONENT messages -->
    </div>

@endsection
