<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $address = Address::find($id);

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

    public function update(Request $request, $id)
    {
        $address = Address::find($id);

        if($address){
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
            else
            {
                $address->update($request->all()); //by traversy

                return response()->json([
                    'status' => 200,
                    'address' => $address,
                    'message' => 'Address Updated Successfully',
                ]);
            }
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
}
