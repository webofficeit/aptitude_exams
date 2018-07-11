<!-- array input -->

<?php
$max = isset($field['max']) && (int) $field['max'] > 0 ? $field['max'] : -1;
$min = isset($field['min']) && (int) $field['min'] > 0 ? $field['min'] : -1;


$maxSections = isset($field['maxSections']) && (int) $field['maxSections'] > 0 ? $field['maxSections'] : -1;
$minSections = isset($field['minSections']) && (int) $field['minSections'] > 0 ? $field['minSections'] : -1;

$item_name = strtolower(isset($field['entity_singular']) && !empty($field['entity_singular']) ? $field['entity_singular'] : $field['label']);
$section_name = strtolower(isset($field['section_entity_singular']) && !empty($field['section_entity_singular']) ? $field['section_entity_singular'] : $field['label']);

$items = old($field['name']) ? (old($field['name'])) : (isset($field['value']) ? ($field['value']) : (isset($field['default']) ? ($field['default']) : '' ));

// make sure not matter the attribute casting
// the $items variable contains a properly defined JSON
if (is_array($items)) {
    if (count($items)) {
        $items = json_encode($items);
    } else {
        $items = '[]';
    }
} elseif (is_string($items) && !is_array(json_decode($items))) {
    $items = '[]';
}

?>



<div ng-app="backPackTableApp" ng-controller="tableController" @include('crud::inc.field_wrapper_attributes') >

    <label>{!! $field['label'] !!} -  { Total <% totalQuestions || 0 %> questions } </label>
    @include('crud::inc.field_translatable_icon')

    <input class="array-json" type="hidden" id="{{ $field['name'] }}" name="{{ $field['name'] }}">

    <div class="array-container form-group" ng-init="field = '#{{ $field['name'] }}'; sections = {{ $items }}; max = {{$max}}; min = {{$min}}; maxSections = {{$maxSections}}; minSections = {{$minSections}}; maxErrorTitle = '{{trans('backpack::crud.table_cant_add', ['entity' => $item_name])}}'; maxErrorMessage = '{{trans('backpack::crud.table_max_reached', ['max' => $max])}}'"   >



        {{--<div class="panel-group" id="accordion2">--}}
        {{--<div class="panel panel-default">--}}
        {{--<div class="panel-heading">--}}
        {{--<h4 class="panel-title">--}}
        {{--<a data-toggle="collapse" data-parent="#accordion2" href="#collapse1">--}}
        {{--Collapsible Group 1</a>--}}
        {{--</h4>--}}
        {{--</div>--}}
        {{--<div id="collapse1" class="panel-collapse collapse in">--}}
        {{--<div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,--}}
        {{--sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad--}}
        {{--minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea--}}
        {{--commodo consequat.</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="panel panel-default">--}}
        {{--<div class="panel-heading">--}}
        {{--<h4 class="panel-title">--}}
        {{--<a data-toggle="collapse" data-parent="#accordion2" href="#collapse2">--}}
        {{--Collapsible Group 2</a>--}}
        {{--</h4>--}}
        {{--</div>--}}
        {{--<div id="collapse2" class="panel-collapse collapse">--}}
        {{--<div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,--}}
        {{--sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad--}}
        {{--minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea--}}
        {{--commodo consequat.</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}

        {{-- Begin Looping Each Section--}}
        <div class="panel-group" id="accordion" ui-sortable="sortableOptions" ng-model="sections" ng-repeat="(sectionIndex ,section) in sections" >
            {{--<span ng-init="questionNumbers=[]"></span>--}}

            <div class="panel panel-success">
                <div class="panel-heading col-sm-12 m-t-10">
                    <div class="col-sm-11">
                        <h4 class="panel-title">

                            <div class="col-sm-4">

                                <a class="text-left" data-toggle="collapse" data-parent="#accordion" href="#collapse<% sectionIndex+1 %>">

                                    Section <% sectionIndex+1 %> - <% section.section_name %>  [<% questionsInSection(sectionIndex) %> questions]

                                </a>

                            </div>


                            <div class="col-sm-3">

                                {{--<label>Time allotted</label>--}}
                                <input ng-model="section.time" class="form-control" placeholder="eg: 60" />
                                <span class="help-block text-sm">Time in Minutes allotted for this section. </span>

                            </div>



                            <div class="col-sm-5">

                                <select ng-model="section.section_id"  @include('crud::inc.field_attributes') >

                                    <option value="">Select a section</option>

                                    @if (isset($field['section_model']))
                                        @foreach ($field['section_model']::all() as $connected_entity_entry)

                                            <option value="{{ $connected_entity_entry->getKey() }}"

                                                    @if ( ( old($field['name']) && old($field['name']) == $connected_entity_entry->getKey() ) || (!old($field['name']) && isset($field['value']) && $connected_entity_entry->getKey()==$field['value']))

                                                    selected

                                                    @endif
                                            >
                                                {{ $connected_entity_entry->{$field['section_attribute']} }}

                                            </option>

                                        @endforeach
                                    @endif

                                </select>

                                <span class="help-block text-sm">To create a section, go to Master-Settings -> Section Categories </span>

                            </div>
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            &nbsp

                        </h4>
                    </div>

                    <div class="col-sm-1">
                        {{--<span class="btn btn-sm btn-info sort-handle"><span class="sr-only">sort section</span><i class="fa fa-sort" role="presentation" aria-hidden="true"></i></span>--}}

                        <button ng-hide="min > -1 && $index < min" class="btn btn-sm btn-danger" type="button" ng-click="removeSection(section);"><span class="sr-only">delete section</span><i class="fa fa-trash" role="presentation" aria-hidden="true"></i></button>

                    </div>

                </div>



                <div id="collapse<% sectionIndex+1 %>" class="panel-collapse collapse">

                    <div class="panel-body" style="padding: 0;">

                        {{--<div class="">--}}
                        {{--<label>Section Instructions (Optional)</label>--}}
                        {{--<textarea rows="2" class="form-control input-sm" type="text" ng-model="section.section_instructions" >--}}
                        {{--</textarea>--}}
                        {{--</div>--}}

                        <table class="table table-bordered table-striped m-b-0 m-t-20" ng-if="section.questions.length > 0" >

                            <thead>
                            <tr>

                                {{--<th style="font-weight: 600!important;">--}}
                                {{--Question Instruction (Optional)--}}
                                {{--</th>--}}
                                <th style="width:10%">
                                    Question No.
                                </th>
                                <th style="font-weight: 600!important;">
                                    Question
                                </th>

                                <th style="width: 10%">

                                </th>
                                {{--<th>--}}
                                {{--Options--}}
                                {{--</th>--}}
                                {{--<th class="text-center" ng-if="max == -1 || max > 1"> --}}{{-- <i class="fa fa-sort"></i> --}}{{-- </th>--}}
                                {{--<th class="text-center" ng-if="max == -1 || max > 1"> --}}{{-- <i class="fa fa-trash"></i> --}}{{-- </th>--}}
                            </tr>
                            </thead>

                            <tbody ui-sortable="sortableOptions" ng-model="section.questions" class="table-striped">

                            <tr ng-repeat="(questionIndex, question) in section.questions" class="array-row">

                                {{--<td style="width:40%">--}}
                                {{--<textarea class="form-control" ng-model="question.instructions" ></textarea>--}}
                                {{--</td>--}}

                                <td style="width:3%">

                                    <span class="row">
                                        <span ng-if="questionNumbers[sectionIndex][questionIndex].length>1">

                                        <% questionNumbers[sectionIndex][questionIndex][0] %> - <% questionNumbers[sectionIndex][questionIndex][questionNumbers[sectionIndex][questionIndex].length-1]  %>

                                    </span>
                                    <span ng-if="questionNumbers[sectionIndex][questionIndex].length==1">
                                        <% questionNumbers[sectionIndex][questionIndex][0] %>
                                    </span>
                                    </span>


                                </td>




                                <td style="width:50%">

                                    {{--<div ng-if="questionIndex >0 " class="">--}}
                                    {{--<input type="checkbox" ng-model="question.sub_question" rows="2" />--}}
                                    {{--<label>Group this question with the above question</label>--}}
                                    {{--</div>--}}

                                    {{--<label>Instruction (Optional)</label>--}}
                                    {{--<textarea class="form-control" ng-model="question.instructions" ></textarea>--}}


                                    <?php $entity_model = $crud->model; ?>

                                    <label>Select Question from the Database</label>
                                    <select ng-model="question.question_id"  @include('crud::inc.field_attributes') name="questionId"  >

                                        <option value="">Click here to select a question</option>
                                        <option value="<% question.id %>" ng-repeat="question in allQuestionsInSection(section)"><% question.question_summary %></option>

                                    </select>
                                    {{--<select ng-model="question.question_id"  @include('crud::inc.field_attributes') name="questionId"  >--}}

                                        {{--<option value="">Click here to select a question</option>--}}

                                        {{--@if (isset($field['model']) and isset($crud->entry))--}}
                                            {{--@foreach ($field['model']::where('question_paper_id', $crud->entry->id )->orderBy('created_at', 'DESC')->get() as $connected_entity_entry)--}}
                                                {{--<option value="{{ $connected_entity_entry->getKey() }}"--}}
                                                        {{--@if ( ( old($field['name']) && old($field['name']) == $connected_entity_entry->getKey() ) || ( !old($field['name']) && isset($field['value']) && $connected_entity_entry->getKey()==$field['value'] ) )--}}
                                                        {{--selected--}}
                                                        {{--@endif >--}}
                                                    {{--{{ $connected_entity_entry->{$field['attribute']} }}--}}
                                                {{--</option>--}}
                                            {{--@endforeach--}}
                                        {{--@endif--}}

                                    {{--</select>--}}

                                    {{--<label>Or enter a new question below</label>--}}
                                    {{--<textarea class="form-control" ng-model="question.question" rows="2" ></textarea>--}}


                                </td>

                                <td>
                                    <span ng-if="max == -1 || max > 1">
                                    <span class="btn btn-sm btn-info sort-handle m-r-10"><span class="sr-only">sort question</span><i class="fa fa-sort" role="presentation" aria-hidden="true"></i>
                                    </span>
                                    </span>

                                    <span ng-if="max == -1 || max > 1">
                                    <button ng-hide="min > -1 && $index < min" class="btn btn-sm btn-danger" type="button" ng-click="removeItem(sectionIndex, question);">
                                    <span class="sr-only">delete item</span><i class="fa fa-trash" role="presentation" aria-hidden="true"></i>
                                    </button>
                                    </span>
                                </td>

                                {{--<td style="width:50%">--}}

                                {{--<div class="col-sm-6">--}}
                                {{--<input class="form-control" ng-model="question.options[0]" placeholder="Option 1" />--}}
                                {{--</div>--}}

                                {{--<div class="col-sm-6">--}}
                                {{--<input class="form-control" ng-model="question.options[1]" placeholder="Option 2"/>--}}
                                {{--</div>--}}

                                {{--<div class="col-sm-6">--}}
                                {{--<input class="form-control" ng-model="question.options[2]" placeholder="Option 3"/>--}}
                                {{--</div>--}}

                                {{--<div class="col-sm-6">--}}
                                {{--<input class="form-control" ng-model="question.options[3]" placeholder="Option 4"/>--}}
                                {{--</div>--}}
                                {{--<div class="col-sm-6">--}}
                                {{--<input class="form-control" ng-model="question.options[4]" placeholder="Option 5"/>--}}
                                {{--</div>--}}


                                {{--<div class="col-sm-12">--}}
                                {{--<label>Answer Option</label>--}}
                                {{--<input class="form-control" ng-model="question.answer_option" type="number" min="1" max="5"/>--}}
                                {{--</div>--}}


                                {{--</td>--}}



                            </tr>

                            </tbody>

                        </table>


                    </div>

                    <div class="panel-footer">
                        <div class="array-controls btn-group m-t-10">
                            <button class="btn btn-sm btn-info" type="button" ng-click="addQuestion(sectionIndex)"><i class="fa fa-plus"></i> {{trans('backpack::crud.add')}} {{ $item_name }}</button>
                        </div>
                    </div>

                </div>
            </div>





        </div>


        <div class="add-section-button col-sm-12">
            <div class="array-controls btn-group m-t-10">
                <button  class="btn btn-sm btn-info" type="button" ng-click="addSection()"><i class="fa fa-plus"></i> {{trans('backpack::crud.add')}} Section </button>
            </div>
        </div>


    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    {{-- @push('crud_fields_styles')
        {{-- YOUR CSS HERE --}}
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
    {{-- YOUR JS HERE --}}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-sortable/0.14.3/sortable.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.js"></script>


    <script type="text/javascript">
        {{--window.questions = '{{ isset($crud->entry)? $field['model']::where('question_paper_id', $crud->entry->id )->get(['id','question_summary','section_category_id'])  : null  }}';--}}
        {{--window.questions = window.questions.replace(/\"/g, "\\\"").replace(/&quot;/g, '"')--}}

        window.angularApp = window.angularApp || angular.module('backPackTableApp', ['ui.sortable'], function($interpolateProvider){
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        window.angularApp.controller('tableController', function($scope){

            $scope.sortableOptions = {
                handle: '.sort-handle'
            };
            window.$scope = $scope;

            $scope.questionsInSection = function(sectionIndex){

                return _.flattenDeep($scope.questionNumbers[sectionIndex]).length
            }

            $scope.allQuestionsInSection = function(section){
//                console.warn(JSON.stringify(section))
                return _.filter($scope.questions, q => +q.section_category_id == +section.section_id )
            }

            $scope.addSection = function(){

                if( $scope.maxSections > -1 ){
                    if( $scope.sections.length < $scope.maxSections ){
                        var section = { questions:[] };
                        $scope.sections.push(section);
                    } else {
                        new PNotify({
                            title: '{{trans('backpack::crud.table_cant_add', ['entity' => 'Section'])}}',
                            text: '{{trans('backpack::crud.table_max_reached', ['max' => $maxSections ])}}',
                            type: 'error'
                        });
                    }
                }
                else {
                    var section = { questions:[] };
                    $scope.sections.push(section);
                }
            }

            $scope.addQuestion = function(sectionIndex){
                var questions = $scope.sections[sectionIndex].questions || []
                if( $scope.max > -1 ){
                    if( questions.length < $scope.max ){
                        var question = {};
                        questions.push(question);
                    } else {
                        new PNotify({
                            title: $scope.maxErrorTitle,
                            text: $scope.maxErrorMessage,
                            type: 'error'
                        });
                    }
                }
                else {
                    var question = {};
                    questions.push(question);
                }
            }

            $scope.removeItem = function(sectionIndex, item){

                if(confirm("Are you sure you want to remove this question?")){
                    var index = $scope.sections[sectionIndex].questions.indexOf(item);
                    $scope.sections[sectionIndex].questions.splice(index, 1);
                }

            }

            $scope.removeSection = function(section){
                if(confirm("Are you sure you want to remove this section?")){
                    var index = $scope.sections.indexOf(section);
                    $scope.sections.splice(index, 1);
                }
            }

            let questions = window.questions

//            if(questions)
//            questions = questions.replace(/&quot;\[/g, '[' ).replace(/\]&quot;/g, ']' ).replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g,'"').replace(/questions\]/g, 'questions]"').replace('/\n\r/g', '');

//            if(questions)
//            questions = questions.replace(/&nbsp;/g, '' ).replace(/\r\n/g, " ").replace(/\r/g, " ")

//            $scope.questions = questions? JSON.parse(questions): [] ;
            $scope.questions= window.questions
//            $scope.$watch('items', function(a, b){
//
//                if( $scope.min > -1 ){
//                    while($scope.items.length < $scope.min){
//                        $scope.addItem();
//                    }
//                }
//
//                if( typeof $scope.items != 'undefined' && $scope.items.length ){
//
//                    if( typeof $scope.field != 'undefined'){
//                        if( typeof $scope.field == 'string' ){
//                            $scope.field = $($scope.field);
//                        }
//                        $scope.field.val( angular.toJson($scope.items) );
//                    }
//                }
//            }, true);
//
//            if( $scope.min > -1 ){
//                for(var i = 0; i < $scope.min; i++){
//                    $scope.addItem();
//                }
//            }
            $scope.$watch('sections', function(a, b){

                if( $scope.minSection > -1 ){
                    while($scope.sections.length < $scope.minSections ){
                        $scope.addSection();
                    }
                }

                if( typeof $scope.sections != 'undefined' && $scope.sections.length ){

                    if( typeof $scope.field != 'undefined'){
                        if( typeof $scope.field == 'string' ){
                            $scope.field = $($scope.field);
                        }
                        $scope.field.val( angular.toJson($scope.sections) );
                    }
                }

                var serialNo = 1;
                $scope.questionNumbers= [];


                setTimeout(() => {
                    $scope.questionNumbers = $scope.sections.map( function(section){

                        var arr = section.questions.map(function(q){

                            var defaultNoOfQuestions =1,
                                slNoArray = [],
                                label = $('select[ng-model="question.question_id"]').find('option[value='+q.question_id+']').text().trim(),
                                startIndex = label.indexOf('['),
                                endIndex = label.indexOf(' questions]'),
                                actualNoOfQuestions = label.substring(startIndex+1, endIndex);

                            defaultNoOfQuestions = endIndex > -1 ? actualNoOfQuestions : defaultNoOfQuestions;

                            if(defaultNoOfQuestions>1)
                                for(var i =0; i< defaultNoOfQuestions; i++){
                                    slNoArray.push(serialNo)
                                    serialNo++;
                                }
                            else{
                                slNoArray.push(serialNo)
                                serialNo++;
                            }

                            return slNoArray

                        });

                        return arr

                    })
                    $scope.totalQuestions = _.flattenDeep($scope.questionNumbers).length

                    $scope.$apply();
                },1000)


            }, true);


            if( $scope.minSections > -1 ){
                for(var i = 0; i < $scope.minSections ; i++){
                    $scope.addSection();
                }
            }



        });

        angular.element(document).ready(function(){
            angular.forEach(angular.element('[ng-app]'), function(ctrl){
                var ctrlDom = angular.element(ctrl);
                if( !ctrlDom.hasClass('ng-scope') ){
                    angular.bootstrap(ctrl, [ctrlDom.attr('ng-app')]);
                }
            });
        })

    </script>



    <script src="{{ asset('vendor/backpack/summernote/summernote.min.js') }}"></script>
    <script>
        jQuery(document).ready(function($) {
            $('.summernote').summernote({
                onChange: function(e) {

                },
            });


        });
    </script>

    @endpush
@endif


{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
