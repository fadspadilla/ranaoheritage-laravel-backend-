<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        return Category::all();
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

            $category = Category::create($request->all()); //by traversy
        
            return response()->json([
                'status' => 200,
                'category' => $category,
                'message' => 'Category Added Successfully',
            ]);
        }        
    }

    public function show ($id)
    {
        $category = Category::find($id);

        if($category)
        {
            return response()->json([
                'status' => 200,
                'category' => $category,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Category Not Found',
            ]);
        }
    }

    public function update (Request $request, $id)
    {
        $category = Category::find($id);

        if($category){
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
            else
            {
                $category->update($request->all()); //by traversy

                return response()->json([
                    'status' => 200,
                    'category' => $category,
                    'message' => 'Category Added Successfully',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Category Not Found',
            ]);
        }
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if($category){
            Category::destroy($id);

            return response()->json([
                'status' => 200,
                'message' => 'Category Deleted Successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Category Not Found',
            ]);
        }
    }
}
