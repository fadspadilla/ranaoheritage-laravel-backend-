<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Video;

class VideosController extends Controller
{
    public function index()
    {
        return Video::all();
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

            $video = Video::create($request->all()); //by traversy
        
            return response()->json([
                'status' => 200,
                'video' => $video,
                'message' => 'Video Added Successfully',
            ]);
        } 
    }

    public function show($id)
    {
        $video = Video::find($id);

        if($video)
        {
            return response()->json([
                'status' => 200,
                'video' => $video,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Video Not Found',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $video = Video::find($id);

        if($video){
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
                $video->update($request->all()); //by traversy

                return response()->json([
                    'status' => 200,
                    'video' => $video,
                    'message' => 'Video Updated Successfully',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Video Not Found',
            ]);
        }
    }

    public function destroy($id)
    {
        $video = Video::find($id);

        if($video){
            Video::destroy($id);

            return response()->json([
                'status' => 200,
                'message' => 'Video Deleted Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Video Not Found',
            ]);
        }
    }
}
