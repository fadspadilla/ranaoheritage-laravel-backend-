<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; 
use App\Models\Video;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class VideosController extends Controller
{
    public function index()
    {
        $videos = Video::select("*")
                    ->orderBy("created_at", "desc")
                    ->take(1)
                    ->get();

        return response()->json([
            'status' => 200,
            'videos' => $videos,
        ]);
    }

    public function counter()
    {
        return Video::all()->count();
    }

    public function heritageVideos($id)
    {
        //SELECT images.path FROM images WHERE images.heritage_id = 1;
        $videos = DB::table('videos')
                    ->select('*')
                    ->where('videos.heritage_id', '=', $id)
                    ->get();

        return response()->json([
            'status' => 200,
            'videos' => $videos,
            'message' => 'Video Added Successfully',
        ]);
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         //'path.*' => 'required|mimes:mp4,3gp,ogx,oga,ogv,ogg,webm,ts,mkv',          
    //         'heritage_id' => 'required',   
    //     ]);

    //     if($validator->fails())
    //     {
    //         return response()->json([
    //             'status' => 422,
    //             'errors' => $validator->messages(),
    //         ]);
    //     }
    //     else{

    //         if($request->hasFile('path')){   
    //             $videos = $request->file('path');

    //             foreach($videos as $vid){                    
    //                 //upload file in cloudinary                   
    //                 $result = $vid->storeOnCloudinary();

    //                 //store Video file into directory and database
    //                 $video = new Video();
    //                 $video->heritage_id = $request->input('heritage_id');
    //                 $video->path = $result->getSecurePath();
    //                 $video->cloud_id = $result->getPublicId();
    //                 $video->save();
    //             }
    //         }
        
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Video Added Successfully',
    //         ]);
    //     } 
    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [        
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

            Video::create($request->all()); //by traversy
        
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
           Video::destroy($id); //delete video data in DB

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
