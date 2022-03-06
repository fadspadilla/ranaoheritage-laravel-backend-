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
                ->join('categories', 'categories.id', '=', 'heritages.category_id')
                ->selectRaw('heritages.name as heritage_name, categories.name as category_name')
                ->get();
                
        return response()->json([
            'status' => 200,
            'heritage' => $heritage,
        ]);
    }

    public function catalog(Request $request)
    {
        $query = DB::table(DB::raw('heritages', 'images'))
                    ->join('heritages.id', '=', 'images.heritage_id')
                    ->select('images.path', 'heritages.name');
                    // ->join('categories', 'categories.id', '=', 'heritages.category_id')
                    // ->join('addresses', 'addresses.id', '=', 'heritages.address_id')
                    // ->join('municipalities', 'municipalities.id', '=', 'addresses.mun_id')
                    // ->join('provinces', 'provinces.id', '=', 'municipalities.id')
                    // ->selectRaw('heritages.id, heritages.name as heritage_name, categories.name as category_name, images.path, addresses.address, municipalities.name as municipality, provinces.name as province');


        if($filterBy = $request->input('filterBy')){
            $query->where('categories.id', $filterBy);
        }

        if($sortedBy = $request->input('sortedBy')){
            $query->orderBy('heritages.updated_at', $sortedBy);
        }else{
            $query->orderBy('heritages.updated_at', 'ASC');
        }

        return $query->paginate(12);
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
