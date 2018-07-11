@extends('layouts.app')

@section('content')


    <div class="col-md-12">

     <exam-window examobject="{{ json_encode($exam) }}" instructions="{{ $instructions  }}" now="{{ $now  }}" sections="{{$sections}}" user="{{Auth::user()}}" />

        {{--<div class="hidden">--}}

            {{--<form id="logoutForm" action="/logout" method="POST">--}}
                {{--{{csrf_field()}}--}}
            {{--</form>--}}

        {{--</div>--}}

    </div>


@endsection
