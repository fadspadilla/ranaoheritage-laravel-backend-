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
            'username'  =>  'required|max:15|unique:users,username',
            'password'  => 'required|min:8|max:20|confirmed',
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
            'username'  =>  'required|max:15',
            'password'  => 'required|min:8|max:20',
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
        $authUser = Auth::user();

        if($authUser){
            return response()->json([
                'status' => 200,
                'auth_user' => $authUser,
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Not Found',
            ]);
        }
    }


    public function updateUser(Request $request, $id){
        $user = User::find($id);

        if($user){
            $user->update($request->all()); //by traversy

            return response()->json([
                'status' => 200,
                'message' => 'outside else',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Address Not Found',
            ]);
        }
    }


    public function updateName(Request $request, $id)
    {
        //validate the data received from request
        $validator = Validator::make($request->all(), [
            'firstname'  =>  'required',
            'lastname'  =>  'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'validation_errors' => $validator->messages(),
            ]);
        }else{
            $user = User::find($id);
            if($user){
                $user->firstname = $request->firstname;
                $user->lastname = $request->lastname;            
                
                $user->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Update Successfully'
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Not Found'
                ]);
            }    
        }
    }

    //naka separate and username sa pag-update ky dapat unique siya
    public function updateUsername(Request $request, $id)
    {
        //validate the data received from request
        $validator = Validator::make($request->all(), [
            'username'  =>  'required|unique:users,username',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'validation_errors' => $validator->messages(),
            ]);
        }else{
            $user = User::find($id);
            if($user){
                $user->username = $request->username;           
                
                $user->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Update Successfully'
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Not Found'
                ]);
            }    
        }
    }

    public function updatePassword(Request $request, $id){
        //validate the data received from request
        $validator = Validator::make($request->all(), [
            'password'  => 'required|min:8|max:20|confirmed',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'validation_errors' => $validator->messages(),
            ]);
        }else{
            $user = User::find($id);
            if($user){
                $user->password = bcrypt($request->password);               
                
                $user->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Update Successfully'
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Not Found'
                ]);
            }    
        }
    }
}
