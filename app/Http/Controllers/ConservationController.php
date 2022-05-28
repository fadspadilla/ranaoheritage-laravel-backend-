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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conservation  $conservation
     * @return \Illuminate\Http\Response
     */
    public function show(Conservation $conservation)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $query = Conservation::find($id);

        if($query){
            
            $query->update($request->all()); //by traversy
            
            return response()->json([
                'status' => 200,
                'message' => 'Conservation Updated Successfully',
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
}
