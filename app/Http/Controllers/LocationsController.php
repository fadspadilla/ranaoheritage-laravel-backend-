<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Location;

class LocationsController extends Controller
{
    public function index()
    {
        return Location::all();
    }

    public function location(Request $request)
    {
        $query = DB::table('heritages as her')
                    ->join('addresses as add', 'her.address_id', '=', 'add.id')
                    ->leftJoin('municipalities as mun', 'add.mun_id', '=', 'mun.id')
                    ->leftJoin('locations as loc', 'add.loc_id', '=', 'loc.id')
                    ->leftJoin('icons', 'loc.icon_id', '=', 'icons.id')
                    ->select('her.id', 'her.name as heritage_name', 'her.heritage_type', 'add.address', 'mun.name as municipality' ,'loc.longitude', 'loc.latitude', 'icons.link');

        if($search = $request->input('search')){
            $query->whereRaw("her.name LIKE '%". $search . "%'")
                    ->orWhereRaw("mun.name LIKE '%". $search . "%'");
        }

        return $query->get();
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

    public function updateLocation(Request $request, $id)
    {
        $location = DB::table('locations as loc')
                        ->where('loc.id', '=', $id);

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
