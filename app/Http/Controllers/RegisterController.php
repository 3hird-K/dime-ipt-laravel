<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use App\Http\Controllers\ErrorHandlerController;
use Illuminate\Support\Facades\Auth;


class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'role' => 'required|in:' . User::ROLE_ADMIN . ',' . User::ROLE_CHAIRMAN,
        ]);

        if ($validator->fails()) {
            return (new ErrorHandlerController)->handleError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = \Illuminate\Support\Facades\Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('CuteKoYaa')->plainTextToken;
        $success['name'] = $user->name;

        return (new ErrorHandlerController)->handleSendResponse($success, 'User registered successfully.');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('CuteKoYaa')->plainTextToken;
            $success['name'] = $user->name;
            $success['email'] = $user->email;

            return (new ErrorHandlerController)->handleSendResponse($success, 'User logged in successfully.');
        }
        else {
            return (new ErrorHandlerController)->handleError('Unauthorized.');
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return (new ErrorHandlerController)->handleSendResponse([], 'User logged out successfully.');
    }
}
