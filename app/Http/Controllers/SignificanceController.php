<?php

namespace App\Http\Controllers;

use App\Models\Significance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SignificanceController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //validate if my title
        $validator = Validator::make($request->all(), [
            'heritage_id' => 'required',   
            'title' => 'required',   
        ]);
        
        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        else{
            $sig = Significance::create($request->all());
            
            return response()->json([
                'status' => 200,
                'message' => 'Significance Added Successfully',
            ]);
        }
    }

    public function show(Significance $significance)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $query = Significance::find($id);

        if($query){
            
            $query->update($request->all()); //by traversy
            
            return response()->json([
                'status' => 200,
                'message' => 'Significance Updated Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Heritage Not Found',
            ]);
        }
    }

    public function destroy(Significance $significance)
    {
        //
    }
}
