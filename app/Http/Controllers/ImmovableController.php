<?php

namespace App\Http\Controllers;

use App\Models\Immovable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ImmovableController extends Controller
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
            'category' => 'required',   
        ]);
        
        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        else{
            $sig = Immovable::create($request->all());
            
            return response()->json([
                'status' => 200,
                'message' => 'Cultural Tangible Immovable Heritage Added Successfully',
            ]);
        }
    }

    public function show($id)
    {
        $query = DB::table('immovables as imm') 
                    ->where('imm.heritage_id', '=', $id)
                    ->get();

        if($query){
            return response()->json([
                'status' => 200,
                'data' => $query,
            ]);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Heritage Not Found',
            ]);
        }
    }

    public function update(Request $request, immovable $immovable)
    {
        $query = Immovable::find($id);

        if($query){
            
            $query->update($request->all()); //by traversy
            
            return response()->json([
                'status' => 200,
                'message' => 'Cultural Tangible Immovable Heritage Updated Successfully',
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

    public function destroy(immovable $immovable)
    {
        //
    }
}
