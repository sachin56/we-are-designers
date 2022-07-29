<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('companies');
    }

    public function create(){
        $result = Company::all();

        return DataTables($result)->make(true);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email' => 'required',
            'telephone'=>'required',
            'logo' => 'required',
            'cover_images'=>'required',
            'website' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                if($request->file('logo')) {

                    $file_logo = $request->file('logo');
                    $file_name_logo =$request->id.'-'.$request->name.'-'.date('m-d-Y_H-i-s').'.'.$file_logo->getClientOriginalExtension();
                    $file_logo->move(public_path('uploads\companies_attachment_logo'), $file_name_logo);

                    $file_cover = $request->file('cover_images');
                    $file_name_cover =$request->id.'-'.$request->name.'-'.date('m-d-Y_H-i-s').'.'.$file_cover->getClientOriginalExtension();
                    $file_cover->move(public_path('uploads\companies_attachment_cover_img'), $file_name_cover);

                    $company = new Company;
                    $company->name = $request->name;
                    $company->email = $request->email;
                    $company->telephone = $request->telephone;
                    $company->logo = public_path("uploads\companies_attachment_logo/".$file_name_logo);;
                    $company->cover_images = public_path("uploads\companies_attachment_cover_img/".$file_name_cover);
                    $company->website = $request->website;

                    $company->save();
                }
                DB::commit();
                return response()->json(['db_success' => 'Added New Companies']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
    }


    public function show($id){

        $result = Company::find($id);

        return response()->json($result);

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email' => 'required',
            'telephone'=>'required',
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $company = Company::find($request->id);
                $company->name = $request->name;
                $company->email = $request->email;
                $company->telephone = $request->telephone;
                $company->logo = $request->logo;
                $company->cover_images = $request->cover_images;
                $company->website = $request->website;

                $company->save();

                DB::commit();
                return response()->json(['db_success' => 'Companies Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }

    public function destroy($id){

        $result = Company::destroy($id);

        return response()->json($result);

    }
}
