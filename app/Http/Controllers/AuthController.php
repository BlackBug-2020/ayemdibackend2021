<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string', 'min:11', 'unique:users', 'max:11'],
            'barangay' => ['required', 'string'],
            'purok' => ['required', 'string'],
        ]);

        if($validator->fails())
        {
            $messages = $validator->messages();
            if ($messages->has('name'))
            {
                return response()->json(['status_code'=>400, 'message'=>'Please fill up all fields']);
            }
            elseif ($messages->has('email'))
            {
                return response()->json(['status_code'=>400, 'message'=>'Email is already taken!']);
            }
            elseif ($messages->has('password'))
            {
                return response()->json(['status_code'=>400, 'message'=>'The password must be at least 8 characters.']);
            }
            elseif ($messages->has('phone'))
            {
                return response()->json(['status_code'=>400, 'message'=>'The phone number is invalid / phone number is already taken.']);
            }
            elseif ($messages->has('barangay'))
            {
                return response()->json(['status_code'=>400, 'message'=>'Location is needed.']);
            }
            elseif ($messages->has('purok'))
            {
                return response()->json(['status_code'=>400, 'message'=>'Specific location is needed.']);
            }
            
            // return response()->json(['status_code'=>400, 'message'=>'Email is already taken!']);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->barangay = $request->barangay;
        $user->purok = $request->purok;
        $user->save();

        return response()->json(['status_code'=>200, 'message'=>'Successfully Registered!']);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60 * 24); // 1 day

        return response([
            'message' => $token
        ])->withCookie($cookie);
    }

    public function user()
    {
        return Auth::user();
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'Successfully Logout!'
        ])->withCookie($cookie);
    }

    public function edit(Request $request)
    {
        $id = Auth::id();
        $name = $request->name;
        $password = Hash::make($request->password);
        $phone = $request->phone;
        $barangay = $request->barangay;
        $purok = $request->purok;
        // $request['name'] = $request->name;
        // $request['password'] =  Hash::make($request->password);
        // $request['phone'] = $request->phone;
        // $request['barangay'] = $request->barangay;
        // $request['purok'] = $request->barangay;
        $user = UserModel::findOrFail($id);
        // $id->edit($request->all());
        // return $request->all();
        $user->name = $name;
        $user->password = $password;
        $user->barangay = $barangay;
        $user->phone = $phone;
        $user->purok = $purok;
        $user->save();
        $result = [
            'data' => $user,
            'code' => 200,
            'message' => 'updated user successfully!',
        ];
        return response()->json($result);
    }
}
