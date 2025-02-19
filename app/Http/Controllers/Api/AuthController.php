<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mfa_token' => null
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate MFA Token
        $mfaToken = Str::random(6);
        $user->update(['mfa_token' => $mfaToken]);

        // Send MFA Token via Email
        Mail::raw("Your MFA Token is: $mfaToken", function ($message) use ($user) {
            $message->to($user->email)->subject("MFA Verification Code");
        });

        return response()->json(['message' => 'MFA token sent to email', 'user_id' => $user->id]);
    }

    public function verifyMfa(Request $request)
    {
        try{

            $request->validate([
                'user_id' => 'required',
                'mfa_token' => 'required',
            ]);
    
            $user = User::find($request->user_id);
    
            if (!$user || $user->mfa_token !== $request->mfa_token) {
                return response()->json(['message' => 'Invalid MFA token'], 401);
            }
    
            // Clear MFA token
            $user->update(['mfa_token' => null]);
    
            // Generate Passport token
            $token = $user->createToken('authToken')->accessToken;
    
            return response()->json(['message' => 'Login successful', 'token' => $token]);
        }catch(\Exception $e){
            return $e;
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
