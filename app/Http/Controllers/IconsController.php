<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Icon;

class IconsController extends Controller
{
    public function index()
    {
        return Icon::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'link' => 'required|image|mimes:png,svg|max:2048',
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
                $file = $request->file('link');
                $extension = $file->getClientOriginalExtension();
                $filename = time() .'.'.$extension;
                $file->move('uploads/icons/', $filename);
                $icon->link = 'uploads/icons/'.$filename;
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

    public function update(Request $request, $id)
    {
        $icon = Icon::find($id);

        if($icon){
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'link' => 'required',
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
                $icon->update($request->all()); //by traversy

                return response()->json([
                    'status' => 200,
                    'icon' => $icon,
                    'message' => 'Icon Updated Successfully',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Icon Not Found',
            ]);
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
            Icon::destroy($id);

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
