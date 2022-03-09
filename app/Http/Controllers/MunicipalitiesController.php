<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; 
use App\Models\Municipality;


class MunicipalitiesController extends Controller
{
    public function index()
    {
        $municipality = Municipality::all();
        return response()->json([
            'status' => 200,
            'municipality' => $municipality,
        ]);
    }

    public function munInProv($id)
    {
        $municipality = Municipality::where('prov_id', $id)->get();        

        if($municipality)
        {
            return response()->json([
                'status' => 200,
                'municipality' => $municipality,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Municipality Not Found',
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'prov_id' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        else{
            $mun = new Municipality;

            $mun->name = $request->input('name');
            $mun->description = $request->input('description');
            $mun->prov_id = $request->input('prov_id');            
            if($request->hasFile('seal')){
                $file = $request->file('seal');
                $extension = $file->getClientOriginalExtension();
                $filename = rand().'_'.time() .'.'.$extension;
                $file->move('uploads/seals/', $filename);
                $mun->seal = 'uploads/seals/'.$filename;
            }
            $mun->save();

            
            return response()->json([
                'status' => 200,
                'municipality' => $mun,
                'message' => 'Municipality Added Successfully',
            ]);
        }   
    }

    public function show($id)
    {
        $municipality = Municipality::find($id);

        if($municipality)
        {
            return response()->json([
                'status' => 200,
                'municipality' => $municipality,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Municipality Not Found',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $municipality = Municipality::find($id);

        if($municipality){
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'prov_id' => 'required',
            ]);
    
            if($validator->fails())
            {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages(),
                ]);
            }
            else
            {
                $municipality->update($request->all()); //by traversy

                return response()->json([
                    'status' => 200,
                    'municipality' => $municipality,
                    'message' => 'Municipality Updated Successfully',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Municipality Not Found',
            ]);
        }
    }

    public function destroy($id)
    {
        $municipality = Municipality::find($id);

        if($municipality){
            File::delete($municipality->seal);
            Municipality::destroy($id);

            return response()->json([
                'status' => 200,
                'message' => 'Municipality Deleted Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Municipality Not Found',
            ]);
        }
    }
}
