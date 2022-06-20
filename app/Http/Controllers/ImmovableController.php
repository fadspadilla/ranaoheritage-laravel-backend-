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

    public function counter() {
        return Immovable::all()->count();
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
        $query = DB::table('heritages as her')  
                    ->leftJoin('immovables as immov', 'her.id', '=', 'immov.heritage_id')
                    ->leftJoin('addresses as add', 'her.address_id', '=', 'add.id')
                    ->leftJoin('municipalities as mun', 'add.mun_id', '=', 'mun.id')
                    ->leftJoin('locations as loc', 'add.loc_id', '=', 'loc.id')
                    ->leftJoin('icons', 'loc.icon_id', '=', 'icons.id')
                    ->select('immov.*', 'her.user_id', 'her.address_id', 'her.name as heritage_name', 
                    'her.heritage_type', 'her.stories', 'add.mun_id', 'add.loc_id', 'add.address as address_name', 
                    'loc.icon_id', 'loc.longitude', 'loc.latitude', 'mun.name as mun_name', )
                    ->where('her.id', '=', $id)
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
        $query = DB::table('immovables as imm')       
                    ->where('imm.id', '=', $id);
                    
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
