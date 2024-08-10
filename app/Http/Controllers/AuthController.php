<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function register()
    {
        $validator = Validator::make(request()->all(),[
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->messages());
        }
        $user = User::create([
            'email' => request('email'),
            'password' => Hash::make(request('password')),            
        ]);
        $credentials = request(['email', 'password']);
        $token = auth() -> attempt($credentials);
        if ($user) {
            return response()->json([
                'access_token' => $token,
                'email' => $user->email,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                ]);
        } else {
            return response()->json(['message' => 'Register failed']);
        }
        
    }   

    public function login(Request $request)
    {
        $validator = Validator::make(request()->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], 422);
        }
        $credentials = $request->only('email', 'password');
        
        if (!$token = auth() -> attempt($credentials)) {
            return response() -> json(['error' => 'Unauthorized', 401]);
        }
        return $this -> respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        $user = auth()->user();
        return response()->json([
            'access_token' => $token,
            'email' => $user->email,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            ]
        );
    }
}

