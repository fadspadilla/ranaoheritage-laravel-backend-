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

    public function counter(){
        return Heritage::all()->count();
    }

    public function show($id){
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

    public function search(Request $request){
        $query = DB::table('heritages')  
                ->leftJoin('categories', 'heritages.category_id', '=', 'categories.id')          
                ->leftJoin('addresses', 'heritages.address_id', '=', 'addresses.id')
                ->leftJoin('municipalities', 'addresses.mun_id', '=', 'municipalities.id')
                ->leftJoin('provinces', 'municipalities.prov_id', '=', 'provinces.id')
                ->select('heritages.id', 'heritages.name', 'municipalities.name as mun', 'provinces.name as prov', 'heritages.created_at', 'categories.id as categoryID');
        
        if($search = $request->input('search')){
            $query->whereRaw("heritages.name LIKE '%". $search . "%'")
                  ->orWhereRaw("municipalities.name LIKE '%". $search . "%'")
                  ->orWhereRaw("provinces.name LIKE '%". $search . "%'");
        }

        if($sort = $request->input('sort')){
            $query->orderBy('heritages.name', $sort);
        }else{
            $query->orderBy('heritages.name', 'ASC');
        }

        return $query->paginate(12);                
    }

    public function catalog(Request $request)
    {
        $query = DB::table('heritages')  
                ->leftJoin('categories', 'heritages.category_id', '=', 'categories.id')          
                ->leftJoin('addresses', 'heritages.address_id', '=', 'addresses.id')
                ->leftJoin('municipalities', 'addresses.mun_id', '=', 'municipalities.id')
                ->leftJoin('provinces', 'municipalities.prov_id', '=', 'provinces.id')
                ->select('heritages.id', 'heritages.name', 'municipalities.name as mun', 'provinces.name as prov', 'heritages.created_at', 'categories.id as categoryID');
        
        if($search = $request->input('search')){
            $query->whereRaw("heritages.name LIKE '%". $search . "%'");
        }

        if($filter = $request->input('filter')){
            $query->where('categories.id', $filter);
        }

        if($sort = $request->input('sort')){
            $query->orderBy('heritages.name', $sort);
        }else{
            $query->orderBy('heritages.name', 'ASC');
        }

        return $query->paginate(12);
    }

    public function dashboard() {
        $query = DB::table('heritages')  
                ->leftJoin('categories', 'heritages.category_id', '=', 'categories.id')          
                ->leftJoin('addresses', 'heritages.address_id', '=', 'addresses.id')
                ->leftJoin('municipalities', 'addresses.mun_id', '=', 'municipalities.id')
                ->leftJoin('provinces', 'municipalities.prov_id', '=', 'provinces.id')
                ->select('heritages.id', 'heritages.name', 'municipalities.name as mun', 'provinces.name as prov', 'heritages.created_at', 'categories.id as categoryID');
        
        return $query->orderBy('heritages.name', 'DESC')->take(2)->get();
    }

    public function editHeritage($id){
        $query = DB::table('heritages as her')
                    ->leftJoin('categories as cat', 'her.category_id', '=', 'cat.id')          
                    ->leftJoin('addresses as add', 'her.address_id', '=', 'add.id')
                    ->leftJoin('municipalities as mun', 'add.mun_id', '=', 'mun.id')
                    ->leftJoin('provinces as prov', 'mun.prov_id', '=', 'prov.id')
                    ->leftJoin('locations as loc', 'add.loc_id', '=', 'loc.id')
                    ->leftJoin('icons', 'loc.icon_id', '=', 'icons.id')
                    ->select('her.id as her_id', 'add.id as add_id', 'prov.id as prov_id',  'loc.id as loc_id')
                    ->where('her.id', '=', $id)
                    ->get();

        if($query){
            return response()->json([
                'status' => 200,
                'details' => $query,
            ]);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Heritage Not Found',
            ]);
        }
    }

    public function catalogDetails(Request $request, $id){
        $query = DB::table('heritages as her')
                    ->leftJoin('categories as cat', 'her.category_id', '=', 'cat.id')          
                    ->leftJoin('addresses as add', 'her.address_id', '=', 'add.id')
                    ->leftJoin('municipalities as mun', 'add.mun_id', '=', 'mun.id')
                    ->leftJoin('provinces as prov', 'mun.prov_id', '=', 'prov.id')
                    ->leftJoin('locations as loc', 'add.loc_id', '=', 'loc.id')
                    ->select('her.id', 'her.name as heritage_name', 'her.description', 'cat.name as category', 'add.address', 'mun.name as municipality', 'prov.name as province', 'loc.longitude', 'loc.latitude')
                    ->where('her.id', '=', $id)
                    ->get();

        if($query){
            return response()->json([
                'status' => 200,
                'details' => $query,
            ]);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Heritage Not Found',
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',            
            'user_id' => 'required',     
            'address_id' => 'required',
            'heritage_type' => 'required',
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

    public function update(Request $request, $id)
    {
        $heritage = Heritage::find($id);

        if($heritage){
            $heritage->update($request->all()); //by traversy
            
            return response()->json([
                'status' => 200,
                'message' => 'Heritage Updated Successfully',
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
