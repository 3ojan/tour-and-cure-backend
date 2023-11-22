<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Http\Requests\Authentication;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'login',
            'register',
            'forgotPassword',
            'resetPassword',
            'social/{provider}',
            'social/{provider}/callback'
            ]]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = $this->guard()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60
            ]
        ]);
    }


    /**
     * Register and authenticate new User
     *
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60
            ]
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Creates token for password reset and sends password reset email.
     *
     * @param Authentication\ForgotPassword $request
     * @return JsonResponse
     * @throws Exception
     */
    public function forgotPassword(Authentication\ForgotPassword $request)
    {
        $user = ($query = User::query());

        $user = $user->where($query->qualifyColumn('email'), $request->input('email'))->first();

        $resetPasswordToken = str_pad(random_int(1,99999999), 8, '0', STR_PAD_LEFT);

        $resetPasswordToken = Hash::make($resetPasswordToken);

        $email = $user->email;

        if (!$userPasswordReset = PasswordReset::where('email', $user->email)->first()) {
            PasswordReset::create([
                'email' => $email,
                'token' => $resetPasswordToken
            ]);
        } else {
            $userPasswordReset->update([
                'email' => $email,
                'token' => $resetPasswordToken
            ]);
        }

        $user->notify(
            new PasswordResetNotification($resetPasswordToken)
        );

        return response()->json([
            'status' => 'success',
            'message' => 'A reset link has been sent to your email address.'
        ]);
    }

    /**
     * Resets password using token sent by forgotPassword method.
     *
     * @param Authentication\ResetPassword $request
     * @return JsonResponse
     */
    public function resetPassword(Authentication\ResetPassword $request)
    {
        $attributes = $request->validated();

        $resetRequest = PasswordReset::where('token', $attributes['token'])->first();

        $user = User::where('email', $resetRequest->email)->first();

        if (!$resetRequest || $resetRequest->token !== $request->token) {
            return response()->json(['error' => 'Incorrect token, please try again!'], 401);
        }

        $user->fill([
            'password' => Hash::make($attributes['password'])
        ]);

        $user->save();

        $user->tokens()->delete();
        $resetRequest->delete();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60
            ]
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Redirect the user to the Provider authentication page.
     *
     * @param $provider
     * @return JsonResponse
     */

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }


    /**
     * Social login response and register/login logic.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return response()->json(['error' => 'Social login failed.'], 400);
        }

        $user = User::firstOrCreate([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'role' => 'user'
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60
            ]
        ]);
    }


    /**
     * Get the guard to be used during authentication.
     *
     * @return Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
