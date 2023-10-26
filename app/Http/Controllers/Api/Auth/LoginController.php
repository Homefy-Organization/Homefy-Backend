<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\ApiTrait;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
         
    
            $user = User::where('email', $request->email)->first();
    
            if (!$user) {
                throw new \Exception('User not found');
            } 
            if (!Hash::check($request->password, $user->password)) {
                throw new \Exception('Invalid credentials');
            }

            $token = $user->createToken('auth')->plainTextToken;
            $user->token = $token;
            return ApiTrait::data(compact('user'), 'Login Successful');
    
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['message' => $e->getMessage()]);
        }
    }
    

   public function logout(){
       $user =auth()->user();
       $user->currentAccessToken()->delete();
       return ApiTrait::successMessage(__('Logout successfully'));
   }
}