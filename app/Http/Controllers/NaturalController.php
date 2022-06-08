<?php

namespace App\Http\Controllers;

use App\Models\Natural;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NaturalController extends Controller
{
    
    public function index()
    {
        //
    }

    public function counter() {
        return Natural::all()->count();
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
            $sig = Natural::create($request->all());
            
            return response()->json([
                'status' => 200,
                'message' => 'Natural Heritage Added Successfully',
            ]);
        }
    }

    public function show($id)
    {
        $query = DB::table('naturals as nat') 
                    ->where('nat.heritage_id', '=', $id)
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
        $query = Natural::find($id);

        if($query){
            
            $query->update($request->all()); //by traversy
            
            return response()->json([
                'status' => 200,
                'message' => 'Natural Heritage Updated Successfully',
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

    public function destroy(Natural $natural)
    {
        //
    }
}
