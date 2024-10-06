<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OtpMail;
use App\Helper\JwtToken;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Type\TrueType;


class UserController extends Controller
{


    function registrationPage()
    {
        return view('pages.auth.registration-page');
    }
    function loginPage()
    {
        return view('pages.auth.login-page');
    }
    function userProfilePage()
    {
        return view('pages.auth.user-profile');
    }


    function sendOtpPage()
    {
        return view('pages.auth.send-otp-page');
    }
    function verifyOtpPage()
    {
        return view('pages.auth.verify-otp-page');
    }
    function resetPasswordPage()
    {
        return view('pages.auth.reset-password-page');
    }


    public function register(Request $request)
    {
        // Validation for required fields and format
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15',
            'password' => 'required|min:6'
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            // Create a new user with hashed password
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password')), // Hashing the password
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registration Successful',
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Registration Failed: ' . $exception->getMessage(),
            ], 500);
        }
    }


    public function login(Request $request)
    {
        // Validate email and password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email
        $user = User::where('email', $request->input('email'))->first(); // Fixed here

        if ($user !== null && Hash::check($request->input('password'), $user->password)) { // Check hashed password
            $token = JwtToken::createToken($user->email, $user->id); // Pass the user ID

            return response()->json([
                'status' => true,
                'message' => 'Login Successful',
            ], 200)->cookie('token', $token, 60 * 24 * 30);
        } else {
            // Invalid credentials
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }
    }
    function userProfile(Request $request)
    {
        $email = $request->header('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'email' => $user->email,
                'firstName' => $user->firstName,
                'lastName' => $user->lastName,
                'phone' => $user->phone
            ]
        ], 200);
    }

    function updateProfile(Request $request)
    {
        try {
            $email = $request->header('email');
            $firstName = $request->input('firstName');
            $lastName = $request->input('lastName');
            $phone = $request->input('phone');
            $password = $request->input('password');

            // Find the user by email
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'User not found',
                ], 404);
            }

            // Update user data
            $user->update([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'phone' => $phone,
                'password' => Hash::make($password), // Hash the password before saving
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong: ' . $exception->getMessage(),
            ], 500);
        }
    }
    public function otpSend(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(100000, 999999);
        $count = User::where('email', '=', '$email')->count();

        if ($count == 1) {
            Mail::to($email)->send(new OtpMail($otp));
            User::where('email', '=', $email)->update(['otp' => $otp]);

            return response()->json([
                'status' => true,
                'message' => '6 digit otp code send to your email',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'unauthorized',
            ], 401);
        }
    }
    public function otpVerify(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)
            ->where('otp', '=', $otp) // Add 'otp' condition here
            ->count();

        if ($count == 1) {
            User::where('email', '=', $email)->update([
                'otp' => '0',
            ]);
            $token = JwtToken::setPasswordToken($request->input('email'));
            return response()->json([
                'status' => true,
                'message' => 'Otp Verification Successful',
                'token' => $token
            ], 200);
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Verification failed'
                ],
                401
            );
        }
    }
    public function resetPassword(Request $request)
    {
        try {
            $email = $request->header('email');
            $password = $request->input('password');

            User::where('email', '=', $email)->update([
                'password' => Hash::make($password), // Hash the new password
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Password Reset Successful'
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Password Reset Failed'
            ], 401);
        }
    }
    public function logout(Request $request)
    {
        return redirect('userLogin')->cookie('token', '', '-1');

    }

}