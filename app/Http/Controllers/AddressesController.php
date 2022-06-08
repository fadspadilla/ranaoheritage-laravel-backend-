<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Address;

class AddressesController extends Controller
{
    public function index()
    {
        return Address::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required',            
            'mun_id' => 'required',            
            'loc_id' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        else{

            $address = Address::create($request->all()); //by traversy
        
            return response()->json([
                'status' => 200,
                'address' => $address,
                'message' => 'Address Added Successfully',
            ]);
        } 
    }

    public function show($id)
    {
        $address = DB::table('addresses as add')
                        ->rightJoin('municipalities as mun', 'add.mun_id', '=', 'mun.id')
                        ->select('add.address', 'add.mun_id', 'mun.prov_id as prov_id')
                        ->where('add.id', '=', $id)
                        ->get();

        if($address)
        {
            return response()->json([
                'status' => 200,
                'address' => $address,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Address Not Found',
            ]);
        }
    }

    public function updateAddress(Request $request, $id)
    {
        $query = DB::table('addresses as add')       
                    ->where('add.id', '=', $id);
                    

        if($query){
            $query->update($request->all()); //by traversy

            return response()->json([
                'status' => 200,
                'message' => 'Address Updated Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Address Not Found',
            ]);
        }
    }

    public function destroy($id)
    {
        $address = Address::find($id);

        if($address){
            Address::destroy($id);

            return response()->json([
                'status' => 200,
                'message' => 'Address Deleted Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Address Not Found',
            ]);
        }
    }

    //**************************************** */
    public function getAddress($id){
        $query = DB::table('addresses as add')       
                    ->leftJoin('municipalities as mun', 'add.mun_id', '=', 'mun.id')
                    ->leftJoin('locations as loc', 'add.loc_id', '=', 'loc.id')
                    ->select( 'add.address', 'mun.name as municipality', 'loc.longitude', 'loc.latitude')
                    ->where('add.id', '=', $id)
                    ->get();

        if($query){
            return response()->json([
                'status' => 200,
                'address' => $query,
            ]);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Heritage Not Found',
            ]);
        }
    }
}
