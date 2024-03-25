<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\UserRegisterRequest;

class AuthController extends Controller
{
    /**
     * Create user with basic details
     *
     * @param  [string] firstname
     * @param  [string] lastname
     * @param  [string] email
     * @param  [string] mobile
     * @param  [string] gender
     * @param  [string] address
     * @param  [string] city
     * @param  [string] state
     * @param  [integer] pincode
     * @param  [date] birthdate
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(UserRegisterRequest $request)
    {
        try {
            $user = User::firstOrCreate([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'gender' => $request->gender,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'pincode' => $request->pincode,
                'birthdate' => date('Y-m-d',strtotime($request->birthdate)),
                'password' => Hash::make($request->password)
            ]);

            if ($user) {
                $tokenResult = $user->createToken(config('sanctum.token_sanctum'));
                $token = $tokenResult->plainTextToken;
                return response()->json([
                    'status' => true,
                    'data' => $user,
                    'message' => 'User created Successfully...!',
                    'accessToken' => $token,
                ], 201);
            } 
            
            throw new \Exception('fails not created...!', 403);
            
        } catch (\Illuminate\Database\QueryException $ex) { // Handle query exception
            return response()->json(['status' => false, 'data' => [], 'error' => "Error Query inserting data : " . $ex->getMessage()],400);
        } catch (\Exception $ex) { // general exception
            return response()->json(['status' => false, 'data' => [], 'error' => $ex->getMessage()],404);
        }
    }
}
