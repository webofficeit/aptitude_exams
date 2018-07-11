@extends('backpack::layout')

@section('header')
    <style>
        @media print{
            .table-bordered td, .table-bordered th {
                border: 1px solid #9e9e9e!important;
            }
        }
    </style>
    <section class="content-header">
        <h1>
            {{ trans('backpack::crud.edit') }} <span>{{ $crud->entity_name }}</span>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url(config('backpack.base.route_prefix'),'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
            <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
            <li class="active">{{ trans('backpack::crud.edit') }}</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <!-- Default box -->
            @if ($crud->hasAccess('list'))
                <a href="{{ url($crud->route) }}"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br>
            @endif

            @include('crud::inc.grouped_errors')

            {!! Form::open(array('url' => $crud->route.'/'.$entry->getKey(), 'method' => 'put', 'files'=>$crud->hasUploadFields('update', $entry->getKey()))) !!}
            <div class="box">
                <div class="box-header with-border">
                @if ($crud->model->translationEnabled())
                    <!-- Single button -->
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[$crud->request->input('locale')?$crud->request->input('locale'):App::getLocale()] }} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                                    <li><a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <h3 class="box-title" style="line-height: 30px;">
                            {{trans('backpack::crud.edit')}}
                        </h3>
                    @else
                        <h3 class="box-title">Leaderboard for {{$entry->name}}</h3>
                        <div>
                            @if(count($sectionCutoffs))
                                <h5><u>SECTION CUT OFF MARKS</u></h5>

                                @foreach($sectionCutoffs as $cutOff)
                                    <i>
                                        {{$cutOff['name']}} : <strong>{{round($cutOff['marks'], 2)}}</strong>
                                    </i> <br>
                                @endforeach

                            @else
                                @if($calculatedCutOff)
                                    Cut-off marks:  {{$calculatedCutOff}}
                                @else
                                    <span class="danger">Cut-off not set in "Exams" Dashboard</span>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
                <div class="box-body row">
                    {{--{{$entry}}--}}
                    <table id="crudTable" class="table table-bordered table-striped display">
                        <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Student Name</th>
                            @foreach($sectionCutoffs as $cutoff)
                                <th><i>{{$cutoff['name']}}</i> Marks</th>
                            @endforeach
                            <th><i>(1st attempt)</i> TOTAL Marks</th>
                            <th>Correct answers</th>
                            <th>Incorrect answers</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($answers as $answer)
                            <tr>
                                <td>{{$loop->index +1 }}</td>
                                <td>
                                    {{$answer->user? $answer->user->name: 'INCORRECT USER REGISTERED : USER ID : '.$answer->user_id }}
                                    {{--<br>--}}
                                    {{--Mobile: {{$answer->user? $answer->user->mobile : 'INCORRECT USER REGISTERED : USER ID : '.$answer->user_id }}--}}
                                </td>
                                <? $i=0;?>
                                @foreach($sectionCutoffs as $sectionCategoryId=>$cutoff)
                                    <? $sectionScores = json_decode($answer->sectional_marks, true) ?>
                                    <? $sectionCategoryIndex = array_search($sectionCategoryId, array_column($sectionScores, 'section_category_id') ) ?>
                                    <td><i>{{ isset($sectionScores[$sectionCategoryIndex])? $sectionScores[$sectionCategoryIndex]['marks']: '0' }}</i>  </td>
                                    <? $i++;?>
                                @endforeach
                                <td>{{$answer->first_attempt_marks? : $answer->total_marks}}</td>
                                <td>{{$answer->correct_answers}}</td>
                                <td>{{$answer->wrong_answers}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>


                    <!-- load the view from the application if it exists, otherwise load the one in the package -->
                    @if(view()->exists('vendor.backpack.crud.form_content'))
                        @include('vendor.backpack.crud.form_content', ['fields' => $fields, 'action' => 'edit'])
                    @else
                        @include('crud::form_content', ['fields' => $fields, 'action' => 'edit'])
                    @endif
                </div><!-- /.box-body -->

                <div class="box-footer">

                    {{--@include('crud::inc.form_save_buttons')--}}

                </div><!-- /.box-footer-->
            </div><!-- /.box -->
            {!! Form::close() !!}
        </div>
    </div>
@endsection


@section('after_styles')
    <!-- DATA TABLES -->
    <link href="{{ asset('vendor/adminlte/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

    <!-- CRUD LIST CONTENT - crud_list_styles stack -->
    @stack('crud_list_styles')
@endsection


@section('after_scripts')

    <script src="{{ asset('vendor/adminlte/plugins/datatables/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("#crudTable").DataTable();
        });
    </script>
    @stack('crud_list_scripts')
@endsection
