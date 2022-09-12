<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users',
            'phone' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $data = [
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ];

        // create user
        $user = User::create($data);

        $user->token = $user->createToken($request->email)->plainTextToken;

        return response()->json($user);
    }

    public function getProfile(Request $request)
    {
        $user = Auth::user();

        $data = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'role' => $user->role
        ];

        return response()->json($data);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'in:writer,editor',
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = Auth::user();

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'role' => $request->role,
        ];

        $user->update($data);

        return response()->json(['status' => true]);
    }
}
