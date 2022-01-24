<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => 200,
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //done in AUthController - register
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if($user){
            return response()->json([
                'status' => 200,
                'category' => $user
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Not Found'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validate the data received from request
        $validator = Validator::make($request->all(), [
            'firstname'  =>  'required',
            'lastname'  =>  'required',
            'password'  => 'required|min:8|confirmed',
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



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //not yet sure if pwede
    }
}
