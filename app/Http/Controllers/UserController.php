<?php

namespace App\Http\Controllers;

use App\Helper\JwtToken;
use App\Mail\OtpMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            User::create([
                "firstName" => $request->input("firstName"),
                "lastName" => $request->input("lastName"),
                "email" => $request->input("email"),
                "phone" => $request->input('phone'),
                "password" => ($request->input("password")),
            ]);
            return response()->json([
                "status" => "success",
                "message" => "Registration Successful",
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                "status" => "failed",
                "message" => "Registration Failed",
            ], 401);
        }

    }
    public function login(Request $request)
    {
        $count = User::where("email", $request->input("email"))->where("password", "=", $request->input("password"))->count();

        if ($count == 1) {
            $token = JwtToken::createToken($request->input("email"));

            return response()->json([
                "status" => "success",
                "message" => "Login Successful",
                "token" => $token,
            ], 200);
        } else {
            return response()->json([
                "status" => "Login Failed",
                "message" => "Unauthorized",
            ], 401);
        }
    }

    public function otpSend(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(100000, 999999);
        $count = User::where('email', '=', '$email')->count();

        if ($count == 1) {
            Mail::to($email)->send(new OtpMail($otp));
            User::where('email', '=', '$email')->update(['otp' => $otp]);

            return response()->json([
                'status' => 'success',
                'message' => '6 digit otp code send to your email',
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ], 401);
        }
    }
    public function otpVerify(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)
            ->where('', '=', $otp)->count();
        if ($count == 1) {
            User::where('email', '=', $email)->update([
                'otp' => '0',
            ]);
            $token = JwtToken::createToken($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'Otp Verification Successful',
                'token' => $token
            ], 200);
        } else {
            return response()->json(
                [
                    'status' => 'Failed',
                    'message' => 'Verification failed'
                ],
                401
            );
        }
    }
    public function resetPassword(Request $request)
    {
        try {
            $email = $request->header("email");
            $password = $request->input("password");

            User::where("email", "=", $email)->update([
                "password" => $password
            ]);

            return response()->json([
                "status" => "Success",
                "message" => "Password Reset Successful"
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                "status" => "Failed",
                "message" => "Password Reset Failed"
            ], 401);
        }
    }
}
