<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; 
use App\Models\Municipality;


class MunicipalitiesController extends Controller
{
    public function index()
    {  
        return Municipality::all();
    }

    public function munDetails($id) {
        return $query = DB::table('municipalities as mun')
                        ->leftJoin('provinces as prov', 'mun.prov_id', '=', 'prov.id')
                        ->select('mun.id', 'mun.name as municipality', 'mun.seal', 'mun.description', 'prov.name as province')          
                        ->where('mun.id', '=', $id)              
                        ->get();
    }

    public function munLIst(Request $request)
    {
        $query = DB::table('municipalities');

        if($search = $request->input('search')){
            $query->whereRaw("name LIKE '%". $search . "%'");
        }

        if($filter = $request->input('filter')){
            $query->where('prov_id', $filter);
        }

        if($sort = $request->input('sort')){
            $query->orderBy('name', $sort);
        }else{
            $query->orderBy('name', 'ASC');
        }

        return $query->paginate(12);        
    }

    // returns municipalities in a province
    public function munInProv($id)
    {
        $municipality = Municipality::where('prov_id', $id)->get();        

        if($municipality)
        {
            return response()->json([
                'status' => 200,
                'municipality' => $municipality,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Municipality Not Found',
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'prov_id' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        else{
            $mun = new Municipality;

            $mun->name = $request->input('name');
            $mun->description = $request->input('description');
            $mun->prov_id = $request->input('prov_id');            
            if($request->hasFile('seal')){
                $file = $request->file('seal');
                $extension = $file->getClientOriginalExtension();
                $filename = rand().'_'.time() .'.'.$extension;
                $file->move('uploads/seals/', $filename);
                $mun->seal = 'uploads/seals/'.$filename;
            }
            $mun->save();

            
            return response()->json([
                'status' => 200,
                'municipality' => $mun,
                'message' => 'Municipality Added Successfully',
            ]);
        }   
    }

    public function show($id)
    {
        $municipality = Municipality::find($id);

        if($municipality)
        {
            return response()->json([
                'status' => 200,
                'municipality' => $municipality,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Municipality Not Found',
            ]);
        }
    }

    public function updateMunicipality(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'max:191',
            'seal' => 'image'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]); 
        }
        else{

            $mun = Municipality::find($id);

            if($mun){
                $mun->name = $request->input('name');
                $mun->description = $request->input('description');
                $mun->prov_id = $request->input('prov_id');

                if($request->hasFile('seal')){
                    //delete old seal
                    $path = $mun->seal;
                    if(File::exists($path))
                    {
                        File::delete($path);
                    }

                    $file = $request->file('seal');
                    $extension = $file->getClientOriginalExtension();
                    $filename = rand().'_'.time() .'.'.$extension;
                    $file->move('uploads/seals/', $filename);
                    $mun->seal = 'uploads/seals/'.$filename;
                }

                $mun->save();
            
                return response()->json([
                    'status' => 200,
                    'message' => 'Municipality Updated Successfully',
                ]);
            }
            else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Not Found',
                ]); 
            }            
        } 
    }

    public function destroy($id)
    {
        $municipality = Municipality::find($id);

        if($municipality){
            File::delete($municipality->seal);
            Municipality::destroy($id);

            return response()->json([
                'status' => 200,
                'message' => 'Municipality Deleted Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Municipality Not Found',
            ]);
        }
    }
}
