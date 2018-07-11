
<!-- field_type_name -->

<div @include('crud::inc.field_wrapper_attributes') >

    <label>{!! $field['label'] !!}</label>
    {{--<input--}}
    {{--type="text"--}}
    {{--name="{{ $field['name'] }}"--}}
    {{--value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"--}}
    {{--@include('crud::inc.field_attributes')--}}
    {{-->--}}

    <div class="col-sm-12">
        <input type="hidden"
               name="{{ $field['name'] }}"
               value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
                @include('crud::inc.field_attributes')
        />

        <input type="hidden" id="{{ $field['name'] }}" role="uploadcare-uploader"
               data-images-only="true"
               data-multiple="true" />
    </div>
    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>



@if ( (!isset($field['noJsLoad']) or $field['noJsLoad']== false ) and $crud->checkIfFieldIsFirstOfItsType($field, $fields))
    {{-- FIELD EXTRA CSS  --}}
    {{-- push things in the after_styles section --}}

    @push('crud_fields_styles')
    <!-- no styles -->
    @endpush


    {{-- FIELD EXTRA JS --}}
    {{-- push things in the after_scripts section --}}

    @push('crud_fields_scripts')
    <!-- no scripts -->
    {{--<script src="https://code.jquery.com/jquery-2.2.4.min.js" charset="utf-8"></script>--}}

    <script type="text/javascript">

        window.UPLOADCARE_LOCALE = "en";
        window.UPLOADCARE_TABS = "file url facebook gdrive";
        window.UPLOADCARE_PUBLIC_KEY = "9349b3737eeccfec1b46";
        {{--window.doc = '{{ isset($entry)? $entry : '' }}';--}}
{{--//        if(doc)--}}
            {{--doc= doc.replace(/&quot;/g,'"').replace(/&lt;/g, '<').replace(/&gt;/g,  '>').replace(/"{"/g,'{"').replace(/"}"/g,'"}').replace(/\\t/g, '').replace(/\n\r/g,'');--}}

        {{--doc = doc? JSON.parse(doc) : {} ;--}}
    </script>
    <script charset="utf-8" src="https://ucarecdn.com/libs/widget/3.0.1/uploadcare.full.min.js"></script>


    <script type="text/javascript">
        setTimeout(function(){
            $.each( $('[role=uploadcare-uploader]'), function(index, el) {
                var id = $(el).attr('id');
                var widget = uploadcare.Widget( '#'+ id );
                if(doc[id])
                    widget.value(doc[id])

                widget.onUploadComplete(function(file) {
                    var ele = $('input[name= '+$(el).attr('id')+']')
                    ele.val(file.uuid);
                    ele.trigger('input')
                    ele.trigger('change')
                    if( ['project_images_uploadcare_id', 'uploadcare_id'].indexOf(id) > -1 ){
                        $('input[name=images_count]').val(file.count)
                    }
                });
            } )

        },1500)
    </script>

    @endpush
@endif

