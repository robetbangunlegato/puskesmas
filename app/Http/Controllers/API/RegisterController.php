<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller\API\BaseController as BaseController;


class RegisterController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'Register berhasil');
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            return $this->sendResponse($success, 'Login berhasil');
        }else{
            return $this>sendError('Unauthorised', ['error' => 'Unauthorised']);
        }
    }
}
