<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        //validate the data received from request
        $validator = Validator::make($request->all(), [
            'firstname'  =>  'required',
            'lastname'  =>  'required',
            'username'  =>  'required|unique:users,username',
            'password'  => 'required|min:8|confirmed',
        ]);

        if($validator->fails()){
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        }else{
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'password' => bcrypt($request->password),
            ]);

            $token = $user->createToken('token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'user' => $user,
                'token' => $token,
                'message' => 'Registered Successfully',
            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  =>  'required|max:191',
            'password'  => 'required|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        }else{
            $user = User::where('username', $request->username)->first();

            if(! $user || ! Hash::check($request->password, $user->password)){
                return response()->json([
                    'status' => 401,
                    'message' => "Invalid Credentials",
                ]);
            }else{
                $token = $user->createToken('token')->plainTextToken;

                return response()->json([
                    'status' => 200,
                    'user' => $user,
                    'token' => $token,
                    'message' => 'Logged in Successfully',
                ]);
            }
        }
    }

    public function logout()
    {
        //delete token in DB
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Logged out'
        ]);
    }

    public function user()
    {
        return Auth::user();
    }
}
