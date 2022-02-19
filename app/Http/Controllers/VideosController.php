<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File; 
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
            'path.*' => 'required|mimes:mp4,3gp,ogx,oga,ogv,ogg,webm,ts,mkv',          
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
                $videos = $request->file('path');

                foreach($videos as $file){                    
                    $vidname = rand().'_'.time() .'.'.$file->getClientOriginalExtension();
                    $file->move('uploads/videos/', $vidname);

                    //store Video file into directory and database
                    $video = new Video();
                    $video->heritage_id = $request->input('heritage_id');
                    $video->path = 'uploads/videos/'.$vidname;
                    $video->save();
                }
            }
        
            return response()->json([
                'status' => 200,
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

    public function destroy($id)
    {
        $video = Video::find($id);

        if($video){
            File::delete($video->path);
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
