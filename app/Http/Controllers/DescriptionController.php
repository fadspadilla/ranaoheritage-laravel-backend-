<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Description;

class DescriptionController extends Controller
{
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
            $des = Description::create($request->all());
            
            return response()->json([
                'status' => 200,
                'message' => 'Description Added Successfully',
            ]);
        }

    }
/**
 * id param from description id
 */
    public function show($id)// where id = description_id
    {
        //
    }

    public function update(Request $request, $id)
    {
        $query = Description::find($id);

        if($query){
            
            $query->update($request->all()); //by traversy
            
            return response()->json([
                'status' => 200,
                'message' => 'Description Updated Successfully',
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

    // ****************************************
    public function descriptionHeritage($id)
    {
        $query = DB::table('descriptions as des')         
                    ->select('des.title', 'des.content')
                    ->where('des.heritage_id', '=', $id)
                    ->get();

        if($query){
            return response()->json([
                'status' => 200,
                'des' => $query,
            ]);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Heritage Not Found',
            ]);
        }
    }
}