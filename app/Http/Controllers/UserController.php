<?php

namespace App\Http\Controllers;

use App\Models\User;
use Facade\FlareClient\Http\Response;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name'=>'required',
            'email' => 'required',
            'password' => 'required'
        ]);
    
        if($validate->fails()){
            return response()->json(['error'=>$validate->errors()],400);
        }
        // Check email
        // $user = User::where('email', $validate['email'])->first();

        $user = User::create([
            'name'=> $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);


    }
    public function login(Request $request){

        $validate = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        //Check email
        $user = User::where('email', $validate['email'])->first();

        if(!Auth::attempt($request->only('email','password'))){
            return response([
                'message'=>'Invalid Credentials'
            ],HttpFoundationResponse::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $request->session()->put('name', $user->id);
        $token =$user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt',$token,60*24); //1 day

        return response(["message"=>$token], 200)->withCookie($cookie);
    }
    public function user(Request $request)
    {

        return response(["message"=>"done","data"=>Auth::user()]); 
    }
    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        $cookie = Cookie::forget('jwt');
        $request->session()->forget('my_name');
        return [
            'message'=>'Logged out'
        ];
    }
}
