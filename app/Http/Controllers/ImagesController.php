<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; 
use App\Models\Image;

class ImagesController extends Controller
{
    public function index()
    {
        // $heritage = DB::table('heritages')
        //         ->join('categories', 'categories.id', '=', 'heritages.category_id')
        //         ->selectRaw('heritages.name as heritage_name, categories.name as category_name')
        //         ->get();        
        return Image::all();
    }

    public function heritageImages($id)
    {
        //SELECT images.path FROM images WHERE images.heritage_id = 1;
        $images = DB::table('images')
                    ->select('path')
                    ->where('images.heritage_id', '=', $id)
                    ->get();

        return response()->json([
            'status' => 200,
            'images' => $images,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path.*' => 'required|image|mimes:jpeg,jpg,bmp,gif,png,svg',            
            'heritage_id' => 'required',   
        ]);        

        if($validator->fails())
        {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        }
        else{
            if($request->hasFile('path')){   
                $images = $request->file('path');

                foreach($images as $file){                    
                    $imgname = rand().'_'.time() .'.'.$file->getClientOriginalExtension();
                    $file->move('uploads/images/', $imgname);

                    //store image file into directory and database
                    $image = new Image();
                    $image->heritage_id = $request->input('heritage_id');
                    $image->path = 'uploads/images/'.$imgname;
                    $image->save();
                }
            }
        
            return response()->json([
                'status' => 200,
                'message' => 'Image Added Successfully',
            ]);
        } 
    }

    public function show($id)
    {
        $image = Image::find($id);

        if($image)
        {
            return response()->json([
                'status' => 200,
                'image' => $image,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Image Not Found',
            ]);
        }
    }    

    public function destroy($id)
    {
        $image = Image::find($id);

        if($image){
            File::delete($image->path);
            Image::destroy($id);

            return response()->json([
                'status' => 200,
                'message' => 'Image Deleted Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Image Not Found',
            ]);
        }
    }
}
