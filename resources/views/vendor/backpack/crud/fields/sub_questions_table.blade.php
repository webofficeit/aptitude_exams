<!-- array input -->

<?php
$max = isset($field['max']) && (int) $field['max'] > 0 ? $field['max'] : -1;
$min = isset($field['min']) && (int) $field['min'] > 0 ? $field['min'] : -1;
$item_name = strtolower(isset($field['entity_singular']) && !empty($field['entity_singular']) ? $field['entity_singular'] : $field['label']);

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

    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    <input class="array-json" type="hidden" id="{{ $field['name'] }}" name="{{ $field['name'] }}">

    <div class="array-container form-group">

        <table class="table table-bordered table-striped m-b-0" ng-init="field = '#{{ $field['name'] }}'; items = {{ $items }}; max = {{$max}}; min = {{$min}}; maxErrorTitle = '{{trans('backpack::crud.table_cant_add', ['entity' => $item_name])}}'; maxErrorMessage = '{{trans('backpack::crud.table_max_reached', ['max' => $max])}}'">

            {{--<thead>--}}
            {{--<tr>--}}

            {{--<th class="text-center" style="width: 10%" ng-if="max == -1 || max > 1"> --}}{{-- <i class="fa fa-trash"></i> --}}{{-- </th>--}}

            {{--<th style="font-weight: 600!important;">--}}
            {{--Question--}}
            {{--</th>--}}

            {{--<th style="font-weight: 600!important;">--}}
            {{--Options--}}
            {{--</th>--}}

            {{--<th class="text-center" style="width: 10%" ng-if="max == -1 || max > 1"> --}}{{----}}{{-- <i class="fa fa-sort"></i> --}}{{----}}{{-- </th>--}}
            {{--</tr>--}}
            {{--</thead>--}}

            <tbody ui-sortable="sortableOptions" ng-model="items" class="table-striped">

            <tr ng-repeat="(questionIndex, question) in items" class="array-row">

                <td style="width: 14px" ng-if="max == -1 || max > 1">

                    <span style="margin-bottom: 12px" class="btn btn-sm btn-default sort-handle"><span class="sr-only">sort question</span><i class="fa fa-sort" role="presentation" aria-hidden="true"></i></span>

                    <button  ng-hide="min > -1 && $index < min" class="btn btn-sm btn-danger" type="button" ng-click="removeItem(question);"><span class="sr-only">delete question</span><i class="fa fa-trash" role="presentation" aria-hidden="true"></i></button>

                </td>


                <td>

                    {{--<input class="form-control input-sm summernote" type="text" ng-model="question.text" placeholder="Type your question here">--}}
                    {{--<div summernote ng-model="?question.text"></div>--}}
                    <label>Enter your question in the box below.</label> <br>
                    <span>For typing math equations, visit <a href="http://www.sciweavers.org/free-online-latex-equation-editor" target="_blank">sciweavers.org/free-online-latex-equation-editor</a> and type your equation, and copy-paste the equation (or the image) in below box</span>
                    <div angular-trix ng-model="question.text" trix-initialize="trixInitialize(e, editor);" trix-id="<% $index %>" ></div>

                    <label>If your question has image, upload it here</label>
                    <input type="hidden" data-questionindex="<% questionIndex %>" id="uploadcare-field-<% questionIndex %>" role="uploadcare-uploader"  data-images-only="true" />

                    <input type="hidden" ng-model="question.image" id="question-<% $index %>" />

                    {{--<text-angular ng-model="question.htmlVariable"></text-angular>--}}

                    <input class="form-control input-sm" type="hidden" ng-model="question.questionId">


                    <h5>Options</h5>


                    <div class="col-sm-12">

                        <span class="col-sm-6" ng-repeat="(optionIndex, option ) in question.options">


                            <button style="margin-left: 22px" class="btn btn-sm btn-danger" type="button" ng-click="removeOption(questionIndex, optionIndex);"><span class="sr-only">delete question</span><i class="fa fa-trash" aria-hidden="true"></i></button>

                         <input class="form-control input-sm col-sm-6" type="text" ng-model="option.text" style="width: 80%" placeholder="Option <% optionIndex+1 %>" />

                             {{--<input type="hidden" name="option-image-<% questionIndex %>-<% optionIndex %>" ng-model="option.image" />--}}

                            <input type="hidden"
                                   id="option-image-<% questionIndex %>-<% optionIndex %>"
                                   data-questionindex="<% questionIndex %>"
                                   data-optionindex="<% optionIndex %>"
                                   role="uploadcare-uploader"
                                   data-images-only="true"
                            />
                            <input type="hidden" ng-model="option.imageType" id="option-image-type-<% questionIndex %>-<% optionIndex %>" />



                        <span class="row">
                            <input class="input-sm" type="radio" name="option-<% questionIndex %>" ng-model="option.answer" ng-value="true"/>
                            <label for="option-<% questionIndex %>">Mark above option as the answer</label>


                        </span>
                    </span>

                    </div>


                    <button ng-if="!question.options || (question.options.length < 6)" class="btn btn-sm btn-info" type="button" ng-click="addOption(questionIndex)"><i class="fa fa-plus"></i> Add Option </button>

                </td>

            </tr>

            </tbody>

        </table>

        <div class="array-controls btn-group m-t-10">
            <button ng-if="max == -1 || items.length < max" class="btn btn-sm btn-success" type="button" ng-click="addItem()"><i class="fa fa-plus"></i> {{trans('backpack::crud.add')}} Question </button>
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

    <script>

        window.UPLOADCARE_LOCALE = "en";
        window.UPLOADCARE_TABS = "file url facebook gdrive";
        window.UPLOADCARE_PUBLIC_KEY = "9349b3737eeccfec1b46";
        {{--window.doc = '{{ isset($entry)? json_encode($entry) : '' }}';--}}
        //        var doc = window.doc;
        //        //        if(doc)
        //        //            doc= doc.replace(/&quot;/g,'"').replace(/&lt;/g, '<').replace(/&gt;/g,  '>').replace(/"{"/g,'{"').replace(/"}"/g,'"}').replace(/\\t/g, '').replace(/\n\r/g,'');
        //
        //        if(doc)
        //            doc = doc.replace(/&quot;/g,'"').replace(/\"\[/g, '[' ).replace(/\]\"/g, ']' ).replace(/questions\]/g, "questions]\"").replace(/\r\n/g, " ")
        //
        //
        //        doc = doc? JSON.parse(doc) : {} ;
    </script>

    <link rel='stylesheet' href='/assets/css/textAngular.css'>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-sortable/0.14.3/sortable.min.js"></script>

    <script charset="utf-8" src="https://ucarecdn.com/libs/widget/3.0.1/uploadcare.full.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.7/angular-resource.min.js"></script>


    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.9.2/trix.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/trix/0.9.2/trix.js"></script>

    <script charset="utf-8" src="/assets/js/angular-trix.min.js"></script>
    <script charset="utf-8" src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.js"></script>

    <script>

        window.angularApp = window.angularApp || angular.module('backPackTableApp', ['ui.sortable'], function($interpolateProvider){
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        window.angularApp.controller('tableController', function($scope){


            $scope.sortableOptions = {
                handle: '.sort-handle'
            };

            $scope.addItem = function(){

                if( $scope.max > -1 ){
                    if( $scope.items.length < $scope.max ){
                        var item = { text: '', image:'', options: [] };
                        $scope.items.push(item);
                    } else {
                        new PNotify({
                            title: $scope.maxErrorTitle,
                            text: $scope.maxErrorMessage,
                            type: 'error'
                        });
                    }
                }
                else {
                    var item = { text: '', image:'', options: [] };
                    $scope.items.push(item);
                }
            }

            $scope.removeItem = function(item){

                if(confirm("Are you sure you want to remove this question?")){
                    var index = $scope.items.indexOf(item);
                    $scope.items.splice(index, 1);
                }

            }

            $scope.addOption = function(questionIndex){

                var option = {};
                $scope.items[questionIndex].options = $scope.items[questionIndex].options || []
                $scope.items[questionIndex].options.push(option);
            }

            $scope.removeOption = function(questionIndex, optionIndex){

                if(confirm("Are you sure you want to remove this option?")){
                    $scope.items[questionIndex].options.splice(optionIndex, 1);
                }
            }

            $scope.$watch('items', function(a, b){


                if( $scope.min > -1 ){
                    while($scope.items.length < $scope.min){
                        $scope.addItem();
                    }
                }

                if( typeof $scope.items != 'undefined' && $scope.items.length ){

                    if( typeof $scope.field != 'undefined'){
                        if( typeof $scope.field == 'string' ){
                            $scope.field = $($scope.field);
                        }
                        console.log('watch',$scope.items[0].options[0])
                        $scope.field.val( angular.toJson($scope.items) );
                    }
                }
            }, true);

            $scope.trixInitialize = function(e, editor) {
//                console.log(editor)
            }
//            $scope.$watch('items[0]["options"]', function (a, b) {
//
//
//            })

            $scope.addImage = function (questionIndex, optionIndex, value) {

                if(optionIndex)
                    _.set($scope.items[questionIndex].options[optionIndex], 'image', value)

                if(questionIndex && !optionIndex)
                    _.set($scope.items[questionIndex], 'image', value)

                $scope.$apply()
                $scope.$digest()
                setTimeout(()=>{
                    console.log($scope.items[questionIndex].options[optionIndex], value )
                },500)

            }

            if( $scope.min > -1 ){
                for(var i = 0; i < $scope.min; i++){
                    $scope.addItem();
                }
            }


            setTimeout(function(){

                $.each( $('[role=uploadcare-uploader]'), function(index, el) {
                    var id = $(el).attr('id');
                    var questionIndex = $(el).data('questionindex')
                    var optionIndex = $(el).data('optionindex')
                    var questions = _.get(window,'doc.question') ? JSON.parse(window.doc.question): [];
                    var widget = uploadcare.Widget( '#'+ id );
                    var ele = $('input[name="'+$(el).attr('id')+'"]')

                    if(questions.length>0 && questions[questionIndex]){
                        if(questionIndex!== undefined && questions[questionIndex].image)
                            widget.value(questions[questionIndex].image)
                        else  if(optionIndex !== undefined && _.get(questions[questionIndex].options[optionIndex], 'image'))
                            widget.value(questions[questionIndex].options[optionIndex].image)

                    }
                    else
                        widget.value( ele.val() )

                    widget.onUploadComplete(function(file) {

                        if( optionIndex!=undefined && questionIndex!==undefined && $scope.items[questionIndex].options[optionIndex] ){
                            console.log(file)
                            $scope.items[questionIndex].options[optionIndex]['image'] = file.uuid
                            $scope.$apply();
                        }
                        else if( questionIndex!==undefined && optionIndex== undefined && !$scope.items[questionIndex].options[optionIndex] )
                            $scope.items[questionIndex]['image'] = file.uuid
                        else if( ele.length>0 ){
                            ele.val(file.uuid);
                            ele.trigger('input')
                            ele.trigger('change')
                        }

                        $scope.$apply();

                        setTimeout(()=>{
                            setTimeout(()=>{
                                console.log(questionIndex, optionIndex, $scope.items[questionIndex].options[optionIndex] )
                            })
                        },1000)

                        if( ['project_images_uploadcare_id', 'uploadcare_id'].indexOf(id) > -1 ){
                            $('input[name=images_count]').val(file.count)
                        }
                    });
                } )

//                angular.forEach( $scope.items, (question, qIndex) => {
//
//                    // Initialize images for each question
//                    var widget = uploadcare.Widget( '#uploadcare-field-'+qIndex );
//
//                    console.log(widget)
//                    if(question.image)
//                        widget.value(question.image.uuid)
//
//                    widget.onUploadComplete(function(file){
//
////                        console.log(qIndex, file)
//                        $scope.addImage(qIndex, null, file )
//
//                    })
//
//                    // Initialize images for the options of each question
//                    angular.forEach( question.options, (option, oIndex) => {
//
//                        var widget = uploadcare.Widget( '#uploadcare-field-'+qIndex+'-'+oIndex );
//
//                        if(option.image)
//                            widget.value(option.image.uuid)
//
//                        widget.onUploadComplete(function(file){
//                            $scope.addImage(qIndex, oIndex, file )
//
//                        })
//
//                    })
//
//                })


            },1500)

            window.$scope = $scope
        });

        angular.element(document).ready(function(){
            angular.forEach(angular.element('[ng-app]'), function(ctrl){
                var ctrlDom = angular.element(ctrl);
                if( !ctrlDom.hasClass('ng-scope') ){
                    angular.bootstrap(ctrl, [ctrlDom.attr('ng-app')]);
                }
            });
        })


        window.angularApp.directive('angularTrix', angularTrix);

        function angularTrix() {
            return {
                restrict: 'A',
                require: 'ngModel',
                scope: {
                    trixInitialize: '&',
                    trixChange: '&',
                    trixSelectionChange: '&',
                    trixFocus: '&',
                    trixBlur: '&',
                    trixFileAccept: '&',
                    trixAttachmentAdd: '&',
                    trixAttachmentRemove: '&'
                },
                link: function(scope, element, attrs, ngModel) {
                    var trixElement = createTrixEditor(attrs);
                    element.append(trixElement);

                    trixElement.on('trix-initialize', function() {
                        if (ngModel.$modelValue) {
                            trixElement[0].editor.loadHTML(ngModel.$modelValue);
                        }
                    });

                    ngModel.$render = function() {
                        if (trixElement[0].editor) {
                            trixElement[0].editor.loadHTML(ngModel.$modelValue);
                        }

                        trixElement.on('trix-change', function() {
                            ngModel.$setViewValue(trixElement[0].inputElement.value);
                        });
                    };

                    var registerEvents = function(type, method) {
                        trixElement[0].addEventListener(type, function(e) {
                            if (type === 'trix-file-accept' && attrs.preventTrixFileAccept === 'true') {
                                e.preventDefault();
                            }

                            scope[method]({
                                e: e,
                                editor: trixElement[0].editor
                            });
                        });
                    };

                    registerEvents('trix-initialize', 'trixInitialize');
                    registerEvents('trix-change', 'trixChange');
                    registerEvents('trix-selection-change', 'trixSelectionChange');
                    registerEvents('trix-focus', 'trixFocus');
                    registerEvents('trix-blur', 'trixBlur');
                    registerEvents('trix-file-accept', 'trixFileAccept');
                    registerEvents('trix-attachment-add', 'trixAttachmentAdd');
                    registerEvents('trix-attachment-remove', 'trixAttachmentRemove');

                }
            };
        };

        /**
         * Convert string from camelCase to kebab-case
         * @param str {string} string in camelCase format
         * @return {string} string in kebab-case format
         */
        function kebabCased(str) {
            return str.replace(/[A-Z]/g, function(g) {
                return '-' + g.toLowerCase();
            });
        }

        /**
         * Create trix-editor element with the passed attributes
         * @param tAttrs {object} directive's attrs object
         * @return {HTMLElement} angular wrapped trix-editor element with the copied attributes
         */
        function createTrixEditor(tAttrs) {
            var attrsToCopy = [];

            //Copy all attributes except for angular attrs, ngModel and angularTrix
            for (var attr in tAttrs) {
                if (attr.indexOf('$') !== 0 && tAttrs.hasOwnProperty(attr) && attr !== 'angularTrix' && attr !== 'ngModel')
                    attrsToCopy.push({
                        name: kebabCased(attr),
                        value: tAttrs[attr]
                    });
            }

            //Create trix editor element with the copied attributes
            var trixElement = angular.element('<trix-editor></trix-editor>');
            for (var i = 0; i < attrsToCopy.length; i++) {
                trixElement.attr(attrsToCopy[i].name, attrsToCopy[i].value);
            }

            return trixElement;
        }

    </script>

    @endpush
@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
