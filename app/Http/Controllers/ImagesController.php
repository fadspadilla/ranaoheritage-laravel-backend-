<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; 
use App\Models\Image;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ImagesController extends Controller
{
    public function index()
    {      
        $images = Image::select("*")
                    ->orderBy("created_at", "desc")
                    ->take(10)
                    ->get();

        return response()->json([
            'status' => 200,
            'images' => $images,
        ]);
    }
    
    public function counter()
    {      
        return Image::all()->count();
    }

    public function heritageImages($id)
    {
        //SELECT images.path FROM images WHERE images.heritage_id = 1;
        $images = DB::table('images')
                    ->select('*')
                    ->where('images.heritage_id', '=', $id)
                    ->get();

        return response()->json([
            'status' => 200,
            'images' => $images,
            'message' => 'Image Added Successfully',
        ]);
    }

    public function singleImage($id)
    {
        $image = DB::table('images')
                    ->select('path')
                    ->where('images.heritage_id', '=', $id)
                    ->first();

        return response()->json([
            'status' => 200,
            'image' => $image,
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

                foreach($images as $img){ 
                    //upload file in cloudinary                   
                    $result = $img->storeOnCloudinary();

                    //store image file into directory and database
                    $image = new Image(); //create image
                    $image->heritage_id = $request->input('heritage_id');
                    $image->path = $result->getSecurePath();
                    $image->cloud_id = $result->getPublicId();
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
            Cloudinary::destroy($image->cloud_id); //delete image in cloudinary
            Image::destroy($id); //delete image data in DB

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
