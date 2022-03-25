<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Location;

class LocationsController extends Controller
{
    public function index()
    {
        return Location::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'longitude' => 'required',            
            'latitude' => 'required',            
            'icon_id' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        else{

            $location = Location::create($request->all()); //by traversy
        
            return response()->json([
                'status' => 200,
                'location' => $location,
                'message' => 'Location Added Successfully',
            ]);
        }  
    }

    public function show($id)
    {
        $location = Location::find($id);

        if($location)
        {
            return response()->json([
                'status' => 200,
                'location' => $location,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Location Not Found',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $location = Location::find($id);

        if($location){
            $location->update($request->all()); //by traversy

                return response()->json([
                    'status' => 200,
                    'message' => 'Location Updated Successfully',
                ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Location Not Found',
            ]);
        }
    }

    public function destroy($id)
    {
        $location = Location::find($id);

        if($location){
            Location::destroy($id);

            return response()->json([
                'status' => 200,
                'message' => 'Location Deleted Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Location Not Found',
            ]);
        }
    }
}
