<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File; 
use App\Models\Icon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class IconsController extends Controller
{
    public function index()
    {
        $icons = Icon::all();
        return response()->json([
            'status' => 200,
            'icons' => $icons,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'link' => 'image|mimes:png,svg|max:2048',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        }
        else{
            $icon = new Icon;
            $icon->name = $request->input('name');
            if($request->hasFile('link')){
                $result = $request->file('link')->storeOnCloudinary();

                $icon->link = $result->getSecurePath();
                $icon->cloud_id = $result->getPublicId();
            }
            $icon->save();

            return response()->json([
                'status' => 200,
                'message' => 'Icon Added Successfully',
            ]);
        }
    }

    public function show($id)
    {
        $icon = Icon::find($id);

        if($icon)
        {
            return response()->json([
                'status' => 200,
                'icon' => $icon,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Icon Not Found',
            ]);
        }
    }

    public function updateIcon(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'max:191',
            'link' => 'image|mimes:png,svg|max:2048',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 422,
                //'errors' => $validator->messages(),
            ]);
        }
        else{
            $icon = Icon::find($id); //search icon

            if($icon){      
                $icon->name = $request->input('name');
                if($request->hasFile('link')){
                    //delete Old icon
                    Cloudinary::destroy($icon->cloud_id); //delete image in cloudinary
                    
                    //update link and cloud_id
                    $result = $request->file('link')->storeOnCloudinary();
                    //save new link and cloud_id
                    $icon->link = $result->getSecurePath();
                    $icon->cloud_id = $result->getPublicId();
                }
                $icon->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Icon Updated Successfully',
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

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $icon = Icon::find($id);

        if($icon){
            Cloudinary::destroy($icon->cloud_id); //delete image in cloudinary
            Icon::destroy($id); //delete image data in DB

            return response()->json([
                'status' => 200,
                'message' => 'Icon Deleted Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Icon Not Found',
            ]);
        }
    }
}
