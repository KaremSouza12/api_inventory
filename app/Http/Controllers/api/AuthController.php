<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{   
    public function index(){
        try {
            $users = User::get();
            return response()->json(['sucess' => $users],200);

        } catch (Exception $e) {
            return response()->json(['error' => $e]);
        }
   

    }

    public function create(Request $request){

        try {
          $data =  $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required','max:8'],
        ]);
               
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);
            if ($user) {
                return response()->json(['sucess' => $user],200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e],400);
        }

    }

    public function login(LoginRequest $request){
        
        $input = $request->validated();
       
        $credentials = [
            'email' => $input['email'],
            'password' => $input['password']
        ];

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
  

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

}
