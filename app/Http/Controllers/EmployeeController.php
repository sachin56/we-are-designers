<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $Companys = Company::all();

        return view('emplyee')->with(['Companys'=>$Companys]);
    }

    public function create(){
        $result = Employee::all();

        return DataTables($result)->make(true);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'first_name'=>'required',
            'last_name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                if($request->file('profile_image')) {
                    $file = $request->file('profile_image');
                    $file_name =$request->first_name.'-'.$request->last_name.'-'.date('m-d-Y_H-i-s').'.'.$file->getClientOriginalExtension();
                    $file->move(public_path('uploads\employee_attachment'), $file_name);

                    $employee = new Employee;
                    $employee->first_name = $request->first_name;
                    $employee->last_name = $request->last_name;
                    $employee->company = $request->company;
                    $employee->email = $request->employee_email;
                    $employee->phone_no = $request->phone_no;
                    $employee->profile_image = public_path("uploads\employee_attachment/".$file_name);

                    $employee->save();
                }

                DB::commit();
                return response()->json(['db_success' => 'Added New Employee']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
    }


    public function show($id){

        $result = Employee::find($id);

        return response()->json($result);

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'first_name'=>'required',
            'last_name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $employee = Employee::find($request->id);
                $employee->first_name = $request->first_name;
                $employee->last_name = $request->last_name;
                $employee->company = $request->company;
                $employee->email = $request->employee_email;
                $employee->phone_no = $request->phone_no;
                $employee->profile_image = $request->profile_image;

                $employee->save();

                DB::commit();
                return response()->json(['db_success' => 'Employee Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }
    public function destroy($id){

        $result = Employee::destroy($id);

        return response()->json($result);

    }
}
