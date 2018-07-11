<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\QuestionRequest as StoreRequest;
use App\Http\Requests\QuestionRequest as UpdateRequest;

class QuestionCrudController extends CrudController
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
            'doc' => $this->data['entry'],
        ]);
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);

    }

    public function setup()
    {


        $this->crud->setModel('App\Models\Question');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/question');
        $this->crud->setEntityNameStrings('Question', 'Questions');

        $this->crud->orderBy('created_at', 'DESC');

        $this->crud->enableAjaxTable();

//        dd($this->crud);


//        $this->crud->setFromDb();
//        $this->crud->removeFields(['question_id','']);

        $this->crud->addFields([
//            [
//                'label'=> 'Question Category',
//                'name'=> 'question_category_id',
//                'type' => 'select',
//                'entity'=> 'questionCategory',
//                'model'=> 'App\Models\QuestionCategory',
//                'attribute'=>'name'
//            ],
            [
                'name'=> 'instructions',
                'label'=> 'Question Instructions (optional)',
                'type'=> 'wysiwyg',
                'hint'=> 'This field can be used to give the instructions of a reasoning/aptitude type question'
            ],
            [
                'name'=> 'instructions_image',
                'label'=> 'Image field for instructions',
                'type'=> 'uploadcare_image',
                'hint'=> 'If you want to add an image with the instructions, you can do it here',
                'noJsLoad'=> true
            ],
            [
                'name'=> 'section_category_id',
                'label'=> 'Section Category',
                'type' => 'select',
                'entity'=> 'sectionCategory',
                'model'=> 'App\Models\SectionCategory',
                'attribute'=>'name',
                'hint'=> 'Select any Section category that you had previously entered in Master Settings. Eg: Reasoning, Aptitude'
            ],
            [
                'name'=> 'question_paper_id',
                'label'=> 'Question paper',
                'type' => 'select',
                'entity'=> 'questionPaper',
                'model'=> 'App\Models\QuestionPaper',
                'attribute'=>'name',
                'hint'=> 'Attach this question to a question paper (Mandatory). If you haven\'t created a question paper, click the QUESTION PAPERS link in the side menu.'
            ],
//            [
//                'name'=> 'question',
//                'label'=> 'Enter your Question',
//                'type'=> 'textarea'
//            ],
//            [
//                'name' => 'option1',
//                'label' => 'Option 1',
//                'fake' => true,
//                'store_in' => 'options'
//            ],
//            [
//                'name' => 'option1Image',
//                'label' => 'Option 1 Image (Optional)',
//                'type' => 'uploadcare_image',
//                'fake' => true,
//                'store_in' => 'options',
//                'hint'=> 'Use this if you want to use upload diagrams/figures'
//            ],
//            [
//                'name' => 'option2',
//                'label' => 'Option 2',
//                'fake' => true,
//                'store_in' => 'options'
//            ],
//            [
//                'name' => 'option2Image',
//                'label' => 'Option 2 Image (Optional)',
//                'type' => 'uploadcare_image',
//                'fake' => true,
//                'store_in' => 'options',
//                'hint'=> 'Use this if you want to use upload diagrams/figures'
//            ],
//            [
//                'name' => 'option3',
//                'label' => 'Option 3',
//                'fake' => true,
//                'store_in' => 'options'
//            ],
//            [
//                'name' => 'option3Image',
//                'label' => 'Option 3 Image (Optional)',
//                'type' => 'uploadcare_image',
//                'fake' => true,
//                'store_in' => 'options',
//                'hint'=> 'Use this if you want to use upload diagrams/figures'
//            ],
//            [
//                'name' => 'option4',
//                'label' => 'Option 4',
//                'fake' => true,
//                'store_in' => 'options'
//            ],
//            [
//                'name' => 'option4Image',
//                'label' => 'Option 4 Image (Optional)',
//                'type' => 'uploadcare_image',
//                'fake' => true,
//                'store_in' => 'options',
//                'hint'=> 'Use this if you want to use upload diagrams/figures'
//            ],
//            [
//                'name' => 'answer_option',
//                'label' => 'Enter Answer option (Option 1,2,3 or 4?)',
//                'type'=> 'number'
//            ],
            [
                'name'=> 'question',
                'label'=> false,
                'type'=> 'sub_questions_table',
                'columns' => [
                    'name' => 'Question',
                    'options' => 'Options'
                ],
                'min'=>1
            ],
        ]);



        $this->crud->addColumns([
            [
                'name'=> 'question_summary',
                'label'=> 'Question',
            ],
            [
                'label'=> 'Section',
                'name'=> 'section_category_id',
                'type' => 'select',
                'entity'=> 'sectionCategory',
                'model'=> 'App\Models\SectionCategory',
                'attribute'=>'name'
            ],
            [
                'label'=> 'Question paper',
                'name'=> 'question_paper_id',
                'type' => 'select',
                'entity'=> 'questionPaper',
                'model'=> 'App\Models\QuestionPaper',
                'attribute'=>'name'
            ],
        ]);
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

        $prefix = mb_substr($this->crud->entry->instructions , 0, 35).'... - ';
        $questions = json_decode($this->crud->entry->question);
        $this->crud->entry->question_summary = $this->crud->entry->instructions ? strip_tags($prefix.$questions[0]->text) : mb_substr(strip_tags($questions[0]->text), 0, 40) ;
        $this->crud->entry->question_summary.= ' ['.count($questions).' questions]';

        $this->crud->entry->save();

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {

        $redirect_location = parent::updateCrud();
        // use $this->data['entry'] or $this->crud->entry

        $prefix = mb_substr($this->crud->entry->instructions , 0, 35).'... - ';
        $questions = json_decode($this->crud->entry->question);
        $this->crud->entry->question_summary = $this->crud->entry->instructions ? strip_tags($prefix.$questions[0]->text) : mb_substr(strip_tags($questions[0]->text), 0, 40) ;
        $this->crud->entry->question_summary.= ' ['.count($questions).' questions]';

        $this->crud->entry->save();

        return $redirect_location;
    }
}
