@include('layouts.header')

<body>
<div id="app" class="main-wrapper">
    <div class="an-loader-container">
        <img src="/assets/img/loader.png" alt="">
    </div>
    <header class="an-fixed-header an-header wow fadeInDown">
        <div class="an-topbar-left-part">
            <h3 class="an-logo-heading">
                <a class="an-logo-link" href="/">
                    <img src="{{asset('/assets/img/aptitude_logo.png')}}" height="35" />
                </a>
            </h3>
            {{--<button class="an-btn an-btn-icon toggle-button js-toggle-sidebar">--}}
            {{--<i class="icon-list"></i>--}}
            {{--</button>--}}
            {{--<form class="an-form" action="#">--}}
                {{--<div class="an-search-field topbar">--}}
                    {{--<input class="an-form-control" type="text" placeholder="Search...">--}}
                    {{--<button class="an-btn an-btn-icon" type="submit">--}}
                        {{--<i class="icon-search"></i>--}}
                    {{--</button>--}}
                {{--</div>--}}
            {{--</form>--}}
        </div> <!-- end .AN-TOPBAR-LEFT-PART -->

        @if(Auth::user())

            <div class="an-topbar-right-part">

                <div class="an-profile-settings">
                    <div class="btn-group an-notifications-dropown  profile">
                        <button type="button" class="an-btn an-btn-icon dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{--<span class="an-profile-img" style="background-image: url('/assets/img/users/user5.jpeg');"></span>--}}
                            <span class="an-user-name">
                                {{Auth::user()->name }}
                            </span>
                            <span class="an-arrow-nav"><i class="icon-arrow-down"></i></span>
                        </button>
                        <div class="dropdown-menu">
                            {{--<p class="an-info-count">Profile Settings</p>--}}
                            <ul class="an-profile-list">
                                {{--<li><a href="#"><i class="icon-user"></i>My profile</a></li>--}}
                                {{--<li><a href="#"><i class="icon-envelop"></i>My inbox</a></li>--}}
                                {{--<li><a href="#"><i class="icon-calendar-check"></i>Calendar</a></li>--}}
                                {{--<li role="separator" class="divider"></li>--}}
                                {{--<li><a href="#"><i class="icon-lock"></i>Lock screen</a></li>--}}
                                <li><a href="{{route('logout')}}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="icon-download-left"></i>Log out</a>

                                    <div class="hidden">
                                        {!! Form::open([ 'url'=> '/logout', 'method'=>'POST', 'id'=>'logout-form']) !!}
                                            {{csrf_field()}}
                                        {!! Form::close() !!}
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> <!-- end .AN-PROFILE-SETTINGS -->
            </div>

    @endif

    <!-- end .AN-TOPBAR-RIGHT-PART -->
    </header> <!-- end .AN-HEADER -->

    <div class="an-page-content m10top">

        {{--<div class="an-content-body home-body-content">--}}
        @yield('content')

        <div class="">

        </div> <!-- end .AN-PAGE-CONTENT-BODY -->
    </div> <!-- end .AN-PAGE-CONTENT -->

    <footer class="an-footer">
        <p>COPYRIGHT 2017 © APTITUDE. ALL RIGHTS RESERVED</p>
    </footer> <!-- end an-footer -->
</div> <!-- end .MAIN-WRAPPER -->


@include('layouts.footer')
</body>


</html>
