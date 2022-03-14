<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\DB;
use App\Models\Province;

class ProvincesController extends Controller
{
    public function index()
    {
        $provinces = DB::table('provinces')->select('id', 'name')->get();

        return response()->json([
            'status' => 200,
            'provinces' => $provinces,
        ]);
    }

    public function provinceList(Request $request)
    {
        $query = DB::table('provinces');

        if($search = $request->input('search')){
            $query->whereRaw("name LIKE '%". $search . "%'");
        }

        if($sort = $request->input('sort')){
            $query->orderBy('name', $sort);
        }else{
            $query->orderBy('name', 'ASC');
        }

        return $query->paginate(12);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]); 
        }
        else{

            $province = new Province;

            $province->name = $request->input('name');
            $province->description = $request->input('description');
            if($request->hasFile('seal')){
                $file = $request->file('seal');
                $extension = $file->getClientOriginalExtension();
                $filename = rand().'_'.time() .'.'.$extension;
                $file->move('uploads/seals/', $filename);
                $province->seal = 'uploads/seals/'.$filename;
            }
            $province->save();
        
            return response()->json([
                'status' => 200,
                'province' => $province,
                'message' => 'Province Added Successfully',
            ]);
        }    
    }

    public function show($id)
    {
        $province = Province::find($id);

        if($province)
        {
            return response()->json([
                'status' => 200,
                'province' => $province,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Province Not Found',
            ]);
        }
    }

    public function updateProvince(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'max:191',
            'seal' => 'image|max:2048'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]); 
        }
        else{

            $province = Province::find($id);

            if($province){
                $province->name = $request->input('name');
                $province->description = $request->input('description');

                if($request->hasFile('seal')){
                    //delete old seal
                    $path = $province->link;
                    if(File::exists($path))
                    {
                        File::delete($path);
                    }

                    $file = $request->file('seal');
                    $extension = $file->getClientOriginalExtension();
                    $filename = rand().'_'.time() .'.'.$extension;
                    $file->move('uploads/seals/', $filename);
                    $province->seal = 'uploads/seals/'.$filename;
                }

                $province->save();
            
                return response()->json([
                    'status' => 200,
                    'message' => 'Province Updated Successfully',
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
        $province = Province::find($id);

        if($province){
            File::delete($province->seal);
            Province::destroy($id);

            return response()->json([
                'status' => 200,
                'message' => 'Province Deleted Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Province Not Found',
            ]);
        }
    }
}
