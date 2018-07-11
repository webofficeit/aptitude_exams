@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="https://placehold.it/160x160/00a65a/ffffff/&text={{ mb_substr(Auth::user()->name, 0, 1) }}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">{{ trans('backpack::base.administration') }}</li>
                <!-- ================================================ -->
                <!-- ==== Recommended place for admin menu items ==== -->
                <!-- ================================================ -->
                <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>



                <li class="header" style="padding: 10px 12px; background: #327198; color: #fff;" ><h5>MASTER SETTINGS <i class="fa fa-chevron-down"></i> </h5></li>


                <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/examtypes') }}"><i class="fa fa-edit"></i> <span>
                Exam Types
              </span></a>
                </li>


                <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/section-category') }}"><i class="fa fa-edit"></i> <span>
                Section Categories
              </span></a>
                </li>


                <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/examcategory') }}"><i class="fa fa-edit"></i> <span>
                Exam Categories
              </span></a>
                </li>



                <li class="header" style="padding: 10px 12px; background: #9a7319; color: #fff"><h5>
                        FREQUENTLY  ACCESSED <i class="fa fa-chevron-down"></i></h5>
                </li>

                <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/question-paper') }}"><i class="fa fa-folder"></i> <span>
                    Set Question Papers
              </span></a>
                </li>

                <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/question') }}"><i class="fa fa-tag"></i> <span>
                    Set Questions
              </span></a>
                </li>


                <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/exam') }}"><i class="fa fa-edit"></i> <span>
                    Schedule Exams
              </span></a>
                </li>

                <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/leaderboards') }}"><i class="fa fa-trophy"></i> <span>
                    Leaderboards
              </span></a>
                </li>

                <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/users') }}"><i class="fa fa-users"></i> <span>
                    All Users
              </span></a>
                </li>


                <!-- ======================================= -->
                <li class="header">{{ trans('backpack::base.user') }}</li>
                <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/logout') }}"><i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span></a></li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
@endif
