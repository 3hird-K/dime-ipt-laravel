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
        ]);

        if ($validator->fails()) {
            return (new ErrorHandlerController)->handleError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('CuteKoYaa')->plainTextToken;
        $success['name'] =  $user->name;

        return (new ErrorHandlerController)->handleSendResponse($success, 'User registered successfully.');
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('CuteKoYaa')->plainTextToken;
            $success['name'] =  $user->name;
            return (new ErrorHandlerController)->handleSendResponse($success, 'User logged in successfully.');
        }else{ 
            return (new ErrorHandlerController)->handleError('Unauthorized.');
        }
    }
}
