<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Heritage;

class HeritagesController extends Controller
{
    public function index()
    {        
        $heritage = DB::table('heritages')
                ->join('categories', 'categories.id', '=', 'heritages.id')
                ->join('images', 'images.id', '=', 'heritages.id')
                ->selectRaw('heritages.name as heritage_name, categories.name as category_name, path')
                ->paginate(12);
                
        return response()->json([
            'status' => 200,
            'heritage' => $heritage,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',            
            'user_id' => 'required',            
            'category_id' => 'required',
            'address_id' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        else{

            $heritage = Heritage::create($request->all()); //by traversy
        
            return response()->json([
                'status' => 200,
                'heritage' => $heritage,
                'message' => 'Heritage Added Successfully',
            ]);
        } 
    }

    public function show($id)
    {
        $heritage = Heritage::find($id);

        if($heritage)
        {
            return response()->json([
                'status' => 200,
                'heritage' => $heritage,
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

    public function update(Request $request, $id)
    {
        $heritage = Heritage::find($id);

        if($heritage){
            $validator = Validator::make($request->all(), [
                'name' => 'required',            
                'user_id' => 'required',            
                'category_id' => 'required',
                'address_id' => 'required',
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
                $heritage->update($request->all()); //by traversy

                return response()->json([
                    'status' => 200,
                    'heritage' => $heritage,
                    'message' => 'Heritage Updated Successfully',
                ]);
            }
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
        $heritage = Heritage::find($id);

        if($heritage){
            Heritage::destroy($id);

            return response()->json([
                'status' => 200,
                'message' => 'Heritage Deleted Successfully',
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
}
