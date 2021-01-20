<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Returns the existing usernames on the db
     */
    public function getUsernames()
    {
        $users = DB::table('users')->select('email')->get();
        return $users;
    }

    /**
     * Logins the user generating token
     * Inspiration -> https://dev.to/romanpaprotsky/vue-js-token-based-authentication-with-laravel-sanctum-3a84
     */
    public function login(Request $request)
    {
        /* Validate the data */
        $v = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        /* Get the user */
        $user = User::where('email', $request->email)->first();
        /* Confirm if the user exists and its credentials are correct */
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['No hay ningún usuario registrado con los datos introducidos']
            ], 401);
        }
        /* If everything is correct store a token */
        $token = $user->createToken('my-app-token')->plainTextToken;
        /* Confirm the successful login */
        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Registers a new user
     */
    public function register(Request $request)
    {
        $data = $request->all();
        $v = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password'  => 'required',
            'role' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        try {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->role = $data['role'];
            $user->save();
            return response()->json([
                'email' => $request->email,
                'password' =>$request->password
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al registrar el usuario: ' . $e->getMessage(),
            ], 500);
        }
    }
}
