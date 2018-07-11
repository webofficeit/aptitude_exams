<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\QuestionPaperRequest as StoreRequest;
use App\Http\Requests\QuestionPaperRequest as UpdateRequest;
use App\Models\Question;

class QuestionPaperCrudController extends CrudController
{

    public function edit($id){
        $this->crud->hasAccessOrFail('update');

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id'] = $id;
        \JavaScript::put([
            'questions' => Question::where('question_paper_id', $this->crud->entry->id )->get(['id','question_summary','section_category_id'])->toArray(),
        ]);
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);

    }

    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\QuestionPaper');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/question-paper');
        $this->crud->setEntityNameStrings('Question Paper', 'Question Papers');
        $this->crud->orderBy('created_at', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        $this->crud->removeField('no_of_questions');
        $this->crud->removeColumns(['no_of_questions', 'questions']);

        $this->crud->addFields([
            [
                'name'=>'name',
                'label'=>'Name',
            ],
            [
                'name'=>'instructions',
                'label'=>'Instructions (Optional)',
                'type' => 'wysiwyg',
                'hint'=>'If you enter instructions in above area, it will be shown at the start of an exam, just after the general instructions of an exam is displayed'
            ],
//            [
//                'name'=>'no_of_questions',
//                'label'=>'Total no. of Questions in this Question paper',
//                'type' => 'number',
//                'attributes' => ['min'=>5, 'step'=>1 ]
//            ],

        ], 'update/create/both');

        $this->crud->addField([ // Table
            'name' => 'questions',
            'label' => 'Questions',
            'type' => 'questions_table',
            'entity_singular' => 'Question', // used on the "Add X" button
            'max' => 100, // maximum questions allowed in each section
            'min' => 0, // minimum questions allowed in each section
            'maxSections' => 5, // maximum rows allowed in the table
            'minSections' => 0 ,// minimum rows allowed in the table,
            'model' => 'App\Models\Question',
            'attribute'=> 'question_summary',

            'section_model' => 'App\Models\SectionCategory',
            'section_attribute'=> 'name',
        ]);


        $this->crud->addField([
            'name' => 'answer_key_url',
            'label' => 'Answer key Url (PDF/DOC/WORD file URL)',
            'hint'=> 'Paste the GOOGLE DRIVE url of the uploaded file to be viewed by the student',
            'type' => 'text',
        ]);
//
//        $this->crud->addField([
//            'name' => 'answer_key',
//            'label' => 'Answer key (Pure text form)',
//            'hint'=> 'Use this field if answer key is in pure Text Form',
//            'type' => 'wysiwyg',
//        ]);

        $this->crud->removeColumns(['instructions','answer_key','answer_key_url']);
        // ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
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
