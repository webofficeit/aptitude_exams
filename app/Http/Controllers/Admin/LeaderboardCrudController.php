<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExamRequest as StoreRequest;
use App\Http\Requests\ExamRequest as UpdateRequest;
use App\Models\SectionCategory;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation

class LeaderboardCrudController extends CrudController
{
    public function setup(){
        $this->crud->denyAccess(['create', 'delete']);

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Exam');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/leaderboards');
        $this->crud->setEntityNameStrings('Leader Board', 'Leader Boards');
        $this->crud->orderBy('created_at', 'DESC');
//        $this->crud->with('answers');
//        $this->crud->setFromDb();

//         $this->crud->removeColumns(['cut_off_marks']);
        $this->crud->addColumns([
            ['name'=>'name'],
            ['name'=>'cut_off_marks', 'label'=>'Cut off marks (Admin)', 'type'=> 'text'],
            ['name'=>'start_date_time', 'type'=> 'datetime'],
            ['name'=>'end_date_time', 'type'=> 'datetime'],
        ]); // add multiple columns, at the end of the stack


        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

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

    public function edit($id){

        $this->crud->hasAccessOrFail('update');

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        $totalMarksOfStudents = 0;
        $noOfStudents= 0;
        $calculatedCutOff = 0;
        $sectionTotalMarks = [];
        $sectionCutoffs = [];

        // check if cut-off is set by admin for this exam
        if(!$this->data['entry']->cut_off_marks){
            foreach($this->data['entry']->answers as $answer){

                if($answer->answers){
                    $sectionalMarks = $answer->sectional_marks? json_decode($answer->sectional_marks, true): [];
                    $noOfStudents++;

//                    dd($sectionalMarks, $answer->sectional_marks);
                    foreach ($sectionalMarks as $index=>$section){
                        if(!isset($sectionTotalMarks[$section['section_category_id']])){
                            $sectionTotalMarks[$section['section_category_id']] = 0;
                        }

                        $sectionTotalMarks[$section['section_category_id']] += $section['marks'];
                    }
                }

            }

            foreach($sectionTotalMarks as $sectionCategoryId=>$mark){
                $sectionCutoffs[$sectionCategoryId] = ['name'=> SectionCategory::find($sectionCategoryId)->name, 'marks'=>$mark/$noOfStudents ];
            }

        }


//        if($this->data['entry']->cut_off_marks){
//            foreach($this->data['entry']->answers as $answer){
//                $totalMarksOfStudents =+ $answer->first_attempt_marks ? : $answer->total_marks;
//                $noOfStudents++;
//            }
//        }
//        if($noOfStudents)
//            $calculatedCutOff = $totalMarksOfStudents/$noOfStudents;
//        else
        $calculatedCutOff = $this->data['entry']->cut_off_marks;

        $this->data['calculatedCutOff'] = $calculatedCutOff;
        $this->data['sectionCutoffs'] = $sectionCutoffs;

        if($calculatedCutOff)
            $this->data['answers'] = $this->data['entry']->answers()->where(function($query) use ($calculatedCutOff, $sectionCutoffs){
                $query->where('first_attempt_marks', '>=', $calculatedCutOff)->orWhere('total_marks', '>=', $calculatedCutOff);
            })->orderBy('first_attempt_marks','DESC')->orderBy('total_marks','DESC')->get();
        else
            $this->data['answers'] = $this->data['entry']->answers()->orderBy('total_marks','DESC')->get()->filter(function ($ans) use ($sectionCutoffs) {
                $sectionalScores = json_decode($ans->sectional_marks? : "{}", true);
                $pass=false;
                foreach ($sectionalScores as $i=>$score){
//                if($ans->user_id == 3212 and $i==1)
//                var_dump($i, $score, $sectionCutoffs );
                    if($score['marks'] < $sectionCutoffs[$score['section_category_id']]['marks']){
                        $pass=false;
                        break;
                    }
                    else $pass=true;
                }
                return $pass;
            });

//                            dd($sectionCutoffs);
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
//        return view($this->crud->getEditView(), $this->data);
        return view('admin.leaderboards.edit', $this->data);
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
