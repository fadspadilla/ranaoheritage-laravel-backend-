<?php

namespace App\Http\Controllers;

use App\Models\Conservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ConservationController extends Controller
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
            $con = Conservation::create($request->all());
            
            return response()->json([
                'status' => 200,
                'message' => 'Conservation Added Successfully',
            ]);
        }
    }
    
    public function show($id)
    {
        return $query = DB::table('conservations as con')       
                    ->where('con.heritage_id', '=', $id)
                    ->get();

    }

    public function update(Request $request, $id)
    {
        $query = DB::table('conservations as con')       
                    ->where('con.heritage_id', '=', $id)
                    ->select('con.title');

        if($query){
            if($title = $request->input('title')){
                $query->whereRaw("con.title = '".$title."'");
                
                $query->update($request->all()); //by traversy
            
                return response()->json([
                    'status' => 200,
                    'message' => 'Conservation Updated Successfully',
                ]);
            }else
            {
                $con = Conservation::create($request->all());
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Conservation Added Successfully',
                ]);
            }
            
        }
        else
        {
            $con = Conservation::create($request->all());
            
            return response()->json([
                'status' => 200,
                'message' => 'Conservation Added Successfully',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conservation  $conservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conservation $conservation)
    {
        //
    }

    // ****************************************
    public function conservationHeritage($id)
    {
        $query = DB::table('conservations as con')         
                    ->select('con.title', 'con.content')
                    ->where('con.heritage_id', '=', $id)
                    ->get();

        if($query){
            return response()->json([
                'status' => 200,
                'con' => $query,
            ]);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Heritage Not Found',
            ]);
        }
    }
}
