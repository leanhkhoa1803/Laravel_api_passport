<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
class AuthController extends Controller
{
    public function register(Request $request){
        $validate = Validator::make($request->all(),[
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|string|confirmed',
        ]);
        if($validate->fails()){
            return response()->json([
                'status'=>'fails',
                'message'=>$validate->errors()->first(),
                'errors'=>$validate->errors()->toArray(),

            ]);
        }

        $user = new User([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);

        $user->save();
        return response()->json([
            'status'=>'Success',
        ]);
    }

    public function login(Request $request){
        $validate = Validator::make($request->all(),[
            'email'=>'required|string|email',
            'password'=>'required|string',
        ]);
        if($validate->fails()){
            return response()->json([
                'status'=>'fails',
                'message'=>$validate->errors()->first(),
                'errors'=>$validate->errors()->toArray(),

            ]);
        }

        $data = request(['email','password']);
        if(!Auth::attempt($data)){
            return response()->json([
                'status' => 'fails',
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $token = $user->createToken('api_access_token')->accessToken;

        return \response()->json([
            'status'=>'success',
            'user'=>$user,
            'token'=>$token,
        ],200);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function getMe(Request $request){
        return \response()->json([
            'data'=>$request->user(),
        ]);
    }
}
