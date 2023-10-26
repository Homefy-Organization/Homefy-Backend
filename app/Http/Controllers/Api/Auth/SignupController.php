<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\Auth\SignupRequest;
use App\Traits\ApiTrait;

class SignupController extends Controller
{
    use ApiTrait;
    
    public function signup(SignupRequest $request)
    {
        $data = $request->safe()->except('password_confirmation', 'password');
        $data['password'] = Hash::make($request->password);

        try {
            $user = User::create($data);
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['message' => 'Something went wrong']);
        }
        
        $token = $user->createToken('auth')->plainTextToken;
        $user->token = $token;
        return ApiTrait::data(compact('user'), "Registration successful");
    }
}


