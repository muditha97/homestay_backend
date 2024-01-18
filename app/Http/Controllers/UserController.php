<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\country;
use App\Models\city;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    function index(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 201);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'success' => true,
            'user' => $user,
            'token' => $token
        ];

        $errRes = [
            'message' => 'Invalid credentials'
        ];

        if ($token) {
            return response($response, 201);
        } else {
            return response($errRes, 201);
        }
    }

    function register(Request $request)
    {
        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->telephone = $request->telephone;
        $user->user_type = $request->user_type;
        $user->country = $request->country;

        $result = $user->save();

        if ($result) {
            return ["Success" => true, 'message' => 'Successfully registered to the system!'];
        } else {
            return ["Success" => false, 'message' => 'Something went wrong!'];
        }
    }

    function getCountries()
    {
        return country::all();
    }

    function getCities()
    {
        return city::all();
    }

    function getCityById(Request $request)
    {
        $id = $request->id;
        $location = city::where('id', $id)->first();
        //print_r($location);die();
        return ["Data" => $location];
    }

    function logout(Request $request)
    {
        $token = $request->token;

        $delete = DB::table('personal_access_tokens')->where('tokenable_id', '=', $token)->delete();

        if ($delete) {
            return ["success" => true, 'message' => 'User logged out successfully!'];
        }
    }

    function resetPassword(Request $request)
    {
        $password = $request->password;
        $user_id = $request->user_id;
        $encrypted = Hash::make($password);

        $affected = DB::table('users')
            ->where('id', $user_id)
            ->update(['password' => $encrypted]);

        if ($affected) {
            return ["Success" => true, 'message' => 'Password updated successfully!'];
        } else {
            return ["Success" => false, 'message' => 'Something went wrong!'];
        }
    }
}
