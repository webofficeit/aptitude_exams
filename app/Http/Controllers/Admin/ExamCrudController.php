<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ExamRequest as StoreRequest;
use App\Http\Requests\ExamRequest as UpdateRequest;

class ExamCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Exam');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/exam');
        $this->crud->setEntityNameStrings('Exam', 'Exams');
        $this->crud->orderBy('created_at', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

//        $this->crud->setFromDb();

        $this->crud->addFields([
            [
                'name'=>'name',
                'label'=>'Name',
            ],
            [
                'label'=> 'Exam Type',
                'type'=> 'checklist',
                'name'=> 'examTypes',
                'entity'=> 'examTypes',
                'attribute'=> 'name',
                'model'=> 'App\Models\ExamType',
                'pivot'=> true
            ],
            [
              'name'=> 'cut_off_marks',
                'label'=> 'Overall Cut-off Marks (Optional)',
                'type'=>'number',
                'attributes'=> ['min'=>1],
                'hint'=> 'If you leave this field bank, then the section-wise cut-off(average marks of students in each section) will be applied automatically'
            ],
            [
                'name'=>'start_date_time',
                'label'=>'Start Date/Time',
                'type' => 'datetime_picker'
            ],
            [
                'name'=>'end_date_time',
                'label'=>'End Date/Time',
                'type' => 'datetime_picker'
            ],
            [
                'name'=>'duration',
                'label'=>'Duration (in minutes)',
                'attributes' => ['placeholder'=> 'Leave this field blank, if you need session-based time for this exam'],
                'type' => 'number',
                'hint'=> 'If you enter the above field, then this value will be regarded as the overall duration of the exam, and this will be an open-session exam, which means no individual time-limits will be set for each section'
            ],

            [
                'name'=>'marks_per_question',
                'label'=>'Marks per Question',
                'default' => '1',
                'type' => 'number'
            ],
            [
                'name'=>'negative_marks',
                'label'=>'Negative marks for each wrong answer',
                'default' => '1',
                'type' => 'number',
                'hint'=> 'Leave blank or enter 0, if no negative marking is required',
                'attributes'=>['step'=>'0.05']
            ],
            [
                'name'=>'max_entries',
                'label'=>'Total No. of Students permitted to attend this exam',
                'type' => 'number',
                'hint'=> 'Leave blank for allowing unlimited students'
            ],
            [
                'name'=>'exam_category_id',
                'label'=>'Exam Category',
                'type' => 'select',
                'entity'=> 'examCategory',
                'model'=> 'App\Models\ExamCategory',
                'placeholder'=> 'Select One Category',
                'attribute'=> 'name'
            ],

        ], 'update/create/both');

        $this->crud->addFields([
            [
                'name'=>'sms_notifications',
                'label'=>'Send SMS Notifications to attendees after exam',
                'type' => 'checkbox',
                'default'=> true
            ],
            [
                'name'=>'display_answer_key',
                'label'=>'Display answer key to students after exam',
                'type' => 'checkbox',
                'default'=> true

            ],
            [
                'name'=>'accept_instructions',
                'label'=>'Make Students mandatorily accept the Instructions before proceeding',
                'type' => 'checkbox',
                'default'=> true
            ],
        ], 'update/create/both');

        $this->crud->addField([ // Table
            'name' => 'question_paper_id',
            'label' => 'Question Paper',
            'type' => 'select',
            'entity_singular' => 'Question Paper', // used on the "Add X" button
            'model' => 'App\Models\QuestionPaper',
            'attribute'=> 'name'
        ]);


        $this->crud->addColumns([

            [
                'name'=> 'name',
                'label'=> 'Name'
            ],
            [
                'name'=> 'duration',
                'label'=> 'Duration (min)'
            ],
//            [
//                'name'=> 'cut_off_marks',
//                'label'=> 'Cut-Off'
//            ],
            [
                'name'=> 'start_date_time',
                'label'=> 'Start date/time',
                'type'=> 'date'
            ],
            [
                'name'=> 'end_date_time',
                'label'=> 'End date/time',
                'type'=> 'date'
            ]

        ]);


//
//         $this->crud->addField([
//             'name' => 'custom-ajax-button',
//             'type' => 'view',
//             'view' => 'auth.login'
//         ], 'update/create/both');





        // ------ CRUD FIELDS
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);

        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);
        // $this->crud->removeAllButtons();
        // $this->crud->removeAllButtonsFromStack('line');

        // ------ CRUD ACCESS
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('reorder');

        // ------ CRUD DETAILS ROW
        // $this->crud->enableDetailsRow();
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('details_row');
        // NOTE: you also need to do overwrite the showDetailsRow($id) method in your EntityCrudController to show whatever you'd like in the details row OR overwrite the views/backpack/crud/details_row.blade.php

        // ------ REVISIONS
        // You also need to use \Venturecraft\Revisionable\RevisionableTrait;
        // Please check out: https://laravel-backpack.readme.io/docs/crud#revisions
        // $this->crud->allowAccess('revisions');

        // ------ AJAX TABLE VIEW
        // Please note the drawbacks of this though:
        // - 1-n and n-n columns are not searchable
        // - date and datetime columns won't be sortable anymore
        // $this->crud->enableAjaxTable();

        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
        // $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        // $this->crud->addClause('where', 'name', '==', 'car');
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });
        // $this->crud->addClause('withoutGlobalScopes');
        // $this->crud->addClause('withoutGlobalScope', VisibleScope::class);
        // $this->crud->with(); // eager load relationships
        // $this->crud->orderBy();
        // $this->crud->groupBy();
        // $this->crud->limit();
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud();
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud();
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
