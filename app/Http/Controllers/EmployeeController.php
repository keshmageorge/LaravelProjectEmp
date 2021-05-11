<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['employee'] = Employee::orderBy('id','desc')->paginate(5);

        return view('employee.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employee.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required',
            'email'          => 'required|email',
            'jdate'        => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $joiningDate = Carbon::parse($request->jdate)->format('Y-m-d');
        $leavingDate = Carbon::parse($request->ldate)->format('Y-m-d');

        // die($request->working);

        if($request->hasFile('image')) {
            $file = $request->file('image');
 
            //you also need to keep file extension as well
            $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();
            $imageName = $file->getClientOriginalName();
            //using the array instead of object
            $image['filePath'] = $name;
            $file->move(public_path().'/uploads/', $imageName);
            $path = public_path().'/uploads/'. $imageName;
            // $user->save();
         }

       Employee::create([
           'name'          => $request->name,
           'email'         => $request->email,
           'joining_date'       => $joiningDate,
           'leaving_date' => $leavingDate,
           'is_working'       => $request->working,
           'image'       => $imageName,
       ]);

       return response()->json([ 'success'=> 'Form is successfully submitted!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $employee = Employee::find($id)->delete();
        return redirect()->route('employee.index')
            ->with('success', 'Employee deleted successfully');
        // return response()->json(['success'=>"Employee Deleted successfully.", 'tr'=>'tr_'.$id]);
    }

    public function delete($id)
{
    $employee = Employee::find($id);

    return view('employee.delete', compact('employee'));
}
}
