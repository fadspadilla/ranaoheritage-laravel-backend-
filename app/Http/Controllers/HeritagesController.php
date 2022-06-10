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

    public function commonDetail($id){
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
                ->leftJoin('addresses', 'heritages.address_id', '=', 'addresses.id')
                ->leftJoin('municipalities', 'addresses.mun_id', '=', 'municipalities.id')
                ->select('heritages.id', 'heritages.name', 'heritages.heritage_type', 'heritages.address_id', 'municipalities.name as mun', 'addresses.address', 'heritages.created_at');
        
        if($search = $request->input('search')){
            $query->whereRaw("heritages.name ILIKE'%". $search . "%'")
                  ->orWhereRaw("municipalities.name ILIKE'%". $search . "%'");                  
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
                ->leftJoin('addresses', 'heritages.address_id', '=', 'addresses.id')
                ->leftJoin('municipalities', 'addresses.mun_id', '=', 'municipalities.id')
                ->select('heritages.id', 'heritages.name', 'heritages.heritage_type', 'heritages.address_id', 'municipalities.name as mun', 'addresses.address', 'heritages.created_at');
        
        if($search = $request->input('search')){
            $query->whereRaw("heritages.name ILIKE'%". $search . "%'");
        }

        if($filter = $request->input('filter')){
            $query->where('heritages.heritage_type', $filter);
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
                ->leftJoin('addresses', 'heritages.address_id', '=', 'addresses.id')
                ->leftJoin('municipalities', 'addresses.mun_id', '=', 'municipalities.id')
                ->select('heritages.id', 'heritages.name', 'municipalities.name as mun',  'heritages.created_at');
        
        return $query->orderBy('heritages.name', 'DESC')->take(2)->get();
    }

    public function editHeritage($id){
        $query = DB::table('heritages as her')   
                    ->leftJoin('addresses as add', 'her.address_id', '=', 'add.id')
                    ->leftJoin('municipalities as mun', 'add.mun_id', '=', 'mun.id')
                    ->leftJoin('locations as loc', 'add.loc_id', '=', 'loc.id')
                    ->leftJoin('icons', 'loc.icon_id', '=', 'icons.id')
                    ->select('her.user_id', 'her.address_id', 'her.name as heritage_name', 
                    'her.heritage_type', 'her.stories', 'add.mun_id', 'add.loc_id', 'add.address as address_name', 
                    'loc.icon_id', 'loc.longitude', 'loc.latitude', 'mun.name as mun_name', )
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
                    ->leftJoin('addresses as add', 'her.address_id', '=', 'add.id')
                    ->leftJoin('municipalities as mun', 'add.mun_id', '=', 'mun.id')
                    ->leftJoin('locations as loc', 'add.loc_id', '=', 'loc.id')
                    ->select('her.id', 'her.name as heritage_name', 'her.description',  'add.address', 'add.id as address_id',
                     'mun.name as municipality', 'loc.longitude', 'loc.latitude')
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
            'heritage_type' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                // 'errors' => $validator->messages(),
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
