<?php

namespace App\Http\Controllers;

use App\Models\Movable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MovableController extends Controller
{

    public function index()
    {
        //
    }

    public function counter() {
        return Movable::all()->count();
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
            $sig = Movable::create($request->all());
            
            return response()->json([
                'status' => 200,
                'message' => 'Cultural Tangible Movable Heritage Added Successfully',
            ]);
        }
    }

    public function show($id)
    {
        $query = DB::table('movables as mov') 
                    ->where('mov.heritage_id', '=', $id)
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

    public function update(Request $request, $id)
    {
        $query = DB::table('movables as mov')       
                    ->where('mov.id', '=', $id);

        if($query){
            
            $query->update($request->all()); //by traversy
            
            return response()->json([
                'status' => 200,
                'message' => 'Cultural Tangible Movable Heritage Updated Successfully',
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

    public function destroy($id)
    {
        //
    }
}
