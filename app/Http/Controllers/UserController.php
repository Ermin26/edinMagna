<?php

namespace App\Http\Controllers;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller{
    public function Login(Request $request){
        try{
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            $user = User::where('name', $request->username)->first();
            if($user && Hash::check($request->password, $user->password)){
                Auth::login($user);
                $request->session()->regenerate();
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'message' => 'Login successful',
                    'user' => Auth::user(),
                    'token' => $token
                ], 200);
            }else{
                return response()->json(['message' => 'Invalid credentials. Do you have profile?'], 401);
            }
        }catch(ValidationException $e){
            $errors = $e->validator->errors()->all();
            return response()->json([
                'message' => $errors,
                'errors' => implode(',', $errors)
            ], 422);
        }

    }

    public function logout(Request $request){
        if(Auth::check()){
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json('success', 'Loged out.');
        }else{
            return response()->json('error', 'You are not logged in.');
        }
    }

    public function role(Request $request){
        try{
            return User::where('id', $request->id)->value('role');
        }catch(ValidationException $e){
            return response()->json([
                'message' => "Can't find user.",
                'errors' => $e->errors()
            ]);
        }
    }

    public function createUser(Request $request){
        if(!$this->checkRole()){
            try{
                $request->validate([
                    'username' => 'required|string',
                    'role' => 'required|string',
                    'password' => 'required|string|min:4|max:255'
                ]);
                User::create([
                    'name' => $request->username,
                    'role' => $request->role,
                    'added' => Auth::user()->name,
                    'password' =>  Hash::make($request->password)
                ]);
                return response()->json([
                    'message' => 'User created successfully, backend message'
                ], 200);
            }catch(ValidationException $e){
                $error = $e->validator->errors()->all();
                $errors = implode('<br>', $error);
                return response()->json([
                    'message' => $errors,
                    'errors' => $errors
                ]);
            }
        }else{
            return response()->json([
                'message' => 'You are not authorized to create users'
            ], 403);
        }
    }

    public function users() {
        try{
            return User::all()->pluck('name')->toArray();
        }catch(ValidationException $e){
            $errors = $e->validator->errors()->all();
            return response()->json([
                'message' => 'Failed retrieving all users '.$errors,
                'errors' => $errors
            ], 400);
        }
    }

    public function updateUser(Request $request){
        if(!$this->checkRole()){
            Log::info('User Data:', $request->all());
            $user = User::where('name', $request->name)->first();

            try{
                $request->validate([
                    'username' => 'string|required',
                    'role' => 'string|required',
                    'password' => 'string|nullable|min:4|confirmed'
                ]);
                    $pass = $request->input('password') ? Hash::make($request->input('password')) : $user->password;

                    $user->name= $request->username ? $request->username : $user->name;
                    $user->role= $request->role;
                    $user->password= $pass;
                    $user->updated_by = Auth::user()->name;
                    $user->save();
                    return response()->json([
                        'message' => "Successfully updated user data."
                    ]);
                    return response()->json([
                        'message' => 'User '.$request->name. ' does not exist2'
                    ],200);
            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return response()->json([
                    'message' => "Error updating data.",
                    "errors" => $errors,
                    'user' => $user
                ],400);
            }
        }else{
            return response()->json(['message' => "You are not allowed to edit users data."],403);
        }
    }

    public function profile(Request $request, $id){
        $user = User::find($id);
        if($user){
            return response()->json([
                'user' => $user
            ]);
        }else{
            return response()->json(['error' => 'User not found.']);
        }
    }
    public function updateProfile(Request $request, $id){
        try{
            $user = User::find($id);
            $request->validate([
                'username' => 'string|',
                'password' => 'string|nullable|min:4|confirmed'
            ]);
            $password = $request->input('password') ? Hash::make($request->input('password')) : $user->password;
            $user->name = $request->input('username');
            $user->password = $password;
            $user->save();
            return response()->json(['message' => 'Successfully updated profile.'], 200);

        }catch(ValidationException $e){
            $errors = $e->validator->errors()->all();
            return response()->json([
                'message' => $errors,
                'errors' =>  $errors
            ], 422);
        }
    }

    private function checkRole(){
        return Auth::user()->role == 'visitor';
    }
}