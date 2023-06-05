<?php

namespace App\Http\Controllers\Api\V1\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SignUpController extends Controller
{
    private $user, $defaultNumber;
    public function __construct(Customer $user)
    {
        $this->user = $user;
    }

    public function signUp(request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            $data = ['message' => $validator->errors(), 'sucess' => false];
            return response()->json($data);
        }
        $credentials = $request->all();

        $data = [];
        $userexit = Customer::where('email', $request->get('email'))->get();
        if (count($userexit)) {
            $data = array(
                'message' => 'User Already Exists.',
                'sucess' => false,
            );
        } else {
            $user = Customer::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                // 'password' => $request->get('password'),
            ]);
            $token = JWTAuth::fromUser($user);
            $data = array(
                'message' => 'User Register Successfully',
                'token' => $token,
                'result' => $user,
                'sucess' => true,
            );
        }

        // return response()->json(compact('user','token'),201);


        return response()->json($data, 201);
    }
    public function updateUser(Request $request, $id)
    {
        $request_data =  $this->validate($request, [
            "name" => "required|string",
            "email" => "required|string",
            "contactNo" => "required|string",
            "adharNo" => "required|string",
            "GSTNo" => "required|string",
            "PanNo" => "required|string",
            "address" => "required|string",
            "city" => "required|string",
            "state" => "required|string",
            "pincode" => "required|string",
            "compnyName" => "required|string",
            "gst_img" => "required|image",
            "pan_img" => "required|image",
            "aadhar_img" => "required|image",
        ]);

        $gst_imgname = time() . rand(1, 100) . '.' . $request->file('gst_img')->extension();
        $request->file('gst_img')->move(public_path('files'), $gst_imgname);
        $request_data['gst_img'] = strval($gst_imgname);

        $pan_imgname = time() . rand(1, 100) . '.' . $request->file('pan_img')->extension();
        $request->file('pan_img')->move(public_path('files'), $pan_imgname);
        $request_data['pan_img'] = strval($pan_imgname);

        $aadhar_imgname = time() . rand(1, 100) . '.' . $request->file('aadhar_img')->extension();
        $request->file('aadhar_img')->move(public_path('files'), $aadhar_imgname);
        $request_data['aadhar_img'] = strval($aadhar_imgname);

        // $request_data->update($request->all());

        Customer::where('id', $id)->update($request_data);
        $user = Customer::where('id', $id)->get();

        $data = array(
            'message' => 'User Updated Successfully',
            'result' => $user[0],
            'sucess' => true,

        );

        return response()->json($data, 201);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user =  Customer::where('email', $credentials['email'])->get();
        $data = null;
        if (count($user)) {
            try {
                if (!$token = JWTAuth::attempt($credentials)) {

                    return response()->json([
                        'error' => 'invalid_credentials',
                        'sucess' => false,
                    ], 400);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            $data = array(
                'message' => 'User Login Successfully',
                'token' => $token,
                'result' => $user,
                'sucess' => true,
            );
        } else {
            $data = array(
                'message' => 'User Not Foud.',
                'sucess' => false,
            );
        }
        return response()->json($data);
    }
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }

    public function loginWithGoolge(request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string',
            'googleId' => 'required|string'
        ]);
        if ($validator->fails()) {
            $data = ['message' => $validator->errors(), 'sucess' => false];
            return response()->json($data);
        }

        $data = [];
        $user = Customer::where('email', $request->get('email'))->get();
        // $user = Customer::where('googleId', $request->get('googleId'))->get();
        if (!count($user)) {
            $user = Customer::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'googleId' => $request->get('googleId'),
                'password' =>  Hash::make('123456'),
            ]);
            $user = Customer::where('email', $request->get('email'))->get();
        }

        $token = JWTAuth::attempt([
            "email" => $user[0]['email'],
            "password" => "123456"
        ]);
        $data = array(
            'message' => 'Login Sucess',
            'token' => $token,
            'result' => $user[0],
            'sucess' => true,
        );

        // return response()->json(compact('user','token'),201);


        return response()->json($data, 201);
    }
}
