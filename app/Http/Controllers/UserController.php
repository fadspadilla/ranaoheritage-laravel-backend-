<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => 200,
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        //done in AUthController - register
    }

    public function show($id)
    {
        $user = User::find($id);
        if($user){
            return response()->json([
                'status' => 200,
                'user' => $user
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Not Found'
            ]);
        }
    }

    public function destroy($id)
    {
        //not yet sure if pwede
    }
}
