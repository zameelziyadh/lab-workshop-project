<?php

namespace App\Http\Controllers\Department;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Department\Department;

class DepartmentController extends Controller
{
    private $locations = [
        ''=>'',
        'NEW YORK'=>'NEW YORK',
        'DALLAS'=>'DALLAS',
        'CHICAGO'=>'CHICAGO',
        'BOSTON'=>'BOSTON',
        'TEXAS'=>'TEXAS',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::all();
        return view('department.index', ['dept'=>$departments]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('department.create',['locations' => $this->locations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, $this->rules());

            $objDepartment = new Department();
            $objDepartment->dname = $request->input('dname');
            $objDepartment->loc = $request->input('loc');

            if ($objDepartment->save()) {
                session()->flash('status', 'The department has been successfully saved');
                return redirect(route('department.index'));
            }

        }catch(\Exception $ex){
            $error = $ex->getMessage();
            error_log($error, 3, "../logs/php_errors.log");
            session()->flash('status', 'OOPS! An unexpected error occurred');
            return back()->withInput();
        }

        //print("came here..."); exit;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        return view('department.show',
            ['department'=>$department]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        //print ($department->deptno); exit;

        return view('department.edit',
            ['locations' => $this->locations,
                'department'=>$department]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        try {
            $this->validate($request, $this->rules());

            $department->dname = $request->input('dname');
            $department->loc = $request->input('loc');
            $department->save();

            if ($department->save()) {
                session()->flash('status', 'The department has been successfully updated');
                return redirect(route('department.index'));
            }

        }catch(\Exception $ex){
            $error = $ex->getMessage();
            error_log($error, 3, "../logs/php_errors.log");
            session()->flash('status', 'OOPS! An unexpected error occurred');
            return back()->withInput();
        }

        //print( $department->dname);
        //print("Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $department->delete();
        session()->flash('status', 'The department has been successfully Deleted');
        return redirect(route('department.index'));

        //print("Deleted");
    }


    private function rules() {
        $rules = [
            'dname'     => 'required|min:6',
            'loc'       => 'required|min:4'
        ];

        return $rules;
    }
}
