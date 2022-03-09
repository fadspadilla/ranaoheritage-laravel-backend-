<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File; 
use App\Models\Province;

class ProvincesController extends Controller
{
    public function index()
    {
        $provinces = Province::all();
        return response()->json([
            'status' => 200,
            'provinces' => $provinces,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]); 
        }
        else{

            $province = new Province;

            $province->name = $request->input('name');
            $province->description = $request->input('description');
            if($request->hasFile('seal')){
                $file = $request->file('seal');
                $extension = $file->getClientOriginalExtension();
                $filename = rand().'_'.time() .'.'.$extension;
                $file->move('uploads/seals/', $filename);
                $province->seal = 'uploads/seals/'.$filename;
            }
            $province->save();
        
            return response()->json([
                'status' => 200,
                'province' => $province,
                'message' => 'Province Added Successfully',
            ]);
        }    
    }

    public function show($id)
    {
        $province = Province::find($id);

        if($province)
        {
            return response()->json([
                'status' => 200,
                'province' => $province,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Province Not Found',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $province = Province::find($id);

        if($province){
            $validator = Validator::make($request->all(), [
                'name' => 'required'
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
                $province->update($request->all()); //by traversy

                return response()->json([
                    'status' => 200,
                    'province' => $province,
                    'message' => 'Province Added Successfully',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Province Not Found',
            ]);
        }
    }

    public function destroy($id)
    {
        $province = Province::find($id);

        if($province){
            File::delete($province->seal);
            Province::destroy($id);

            return response()->json([
                'status' => 200,
                'message' => 'Province Deleted Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Province Not Found',
            ]);
        }
    }
}
