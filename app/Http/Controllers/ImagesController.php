<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Image;

class ImagesController extends Controller
{
    public function index()
    {
        return Image::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required',            
            'heritage_id' => 'required',   
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        else{

            $image = Image::create($request->all()); //by traversy
        
            return response()->json([
                'status' => 200,
                'image' => $image,
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

    public function update(Request $request, $id)
    {
        $image = Image::find($id);

        if($image){
            $validator = Validator::make($request->all(), [
                'path' => 'required',            
                'heritage_id' => 'required',
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
                $image->update($request->all()); //by traversy

                return response()->json([
                    'status' => 200,
                    'image' => $image,
                    'message' => 'Image Updated Successfully',
                ]);
            }
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
