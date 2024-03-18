<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['username'] = strstr($data['email'], '@', true);
        $data['is_admin'] = $request->is_admin ?? 0;

        $user = User::create($data);
        $token = $user->createToken(User::USER_TOKEN);

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken,
            'message' => 'User has been register successfully!'
        ]);

        // return $this->success([
        //     'user' => $user,
        //     'token' => $token->plainTextToken
        //     ], 'User has been register successfully');
    }   

    public function login(LoginRequest $request) {
        $isValid  = $this->isValidCredential($request);
        if(!$isValid['success']) {
            if($request->type && $request->type == "web") {
                return to_route('login_page')->with('message', 'Credentials does not match');
            }
            return $this->error($isValid['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $isValid['user'];
        $token = $user->createToken(USER::USER_TOKEN);


        if($request->type && $request->type == "web") {
            return redirect()->route('home')->with(['token' => $token->plainTextToken, 'user' => $user]);
        }

        return response()->json([
                'user' => $user,
                'token' => $token->plainTextToken,
                'message' => 'Login successfully'
        ]);
        
        // return $this->success([
        //     'user' => $user,
        //     'token' => $token->plainTextToken
        // ], 'Login successfully!');
    }

    private function isValidCredential(Request $request) 
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        if($user === null) {
            return [
                'success' => false,
                'message' => 'Invalid credential'
            ];
        }

        if(Hash::check($data['password'], $user->password)) {
            return [
                'success' => true,
                'user' => $user
            ];
        }

        return [
            'success' => false,
            'message' => 'Password does not match'
        ];

        
    }

    public function loginWithToken() {
        return response()->json([
            'user' => auth()->user(),
            'message' => 'Login Successfully'
        ]);
        // return $this->success(auth()->user(), 'Login Successfully');
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();   
        return response()->json([
            'user' => null,
            'message' => 'Login Successfully'
        ]);
        // return $this->success(null, 'Logout Successfully');
    }
}
