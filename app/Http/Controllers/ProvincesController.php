<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Province;

class ProvincesController extends Controller
{
    public function index()
    {
        return Province::all();
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

            $province = Province::create($request->all()); //by traversy
        
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
                    'pro$province' => $province,
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
