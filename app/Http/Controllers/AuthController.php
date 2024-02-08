<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Http\Requests\Authentications;
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
        $this->middleware('auth:api', [
            'except' => [
                'login',
                'refresh',
                'register',
                'refresh',
                'forgotPassword',
                'resetPassword',
                'social/*',
                'social/*/callback',
            ],
        ]);
    }

    /**
     * * Get a JWT token via given credentials.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/login",
     *      operationId="loginUser",
     *      tags={"Authentication"},
     *      summary="User login",
     *      description="Logs in a user with email and password",
     *      @OA\RequestBody(
     *          required=true,
     *          description="User credentials",
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                  @OA\Property(property="password", type="string", format="password", example="secret123"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User successfully logged in",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="user", type="object"),
     *              @OA\Property(
     *                  property="authorisation",
     *                  type="object",
     *                  @OA\Property(property="access_token", type="string", example="your_access_token"),
     *                  @OA\Property(property="token_type", type="string", example="bearer"),
     *                  @OA\Property(property="expires_in", type="integer", example=3600),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized - Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Invalid credentials"),
     *          ),
     *      ),
     * )
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

        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'user' => new UserResource($user),
            'authorisation' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60
            ]
        ]);
    }


    /**
     * Register a new user.
     *
     * @param Authentications\AuthenticationRegisterNewUserRequest $request
     *
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/register",
     *      operationId="registerUser",
     *      tags={"Authentication"},
     *      summary="Register a new user",
     *      description="Registers a new user with the provided information",
     *     @OA\RequestBody(
     *          required=true,
     *          description="User registration details",
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="name", type="string", example="John Doe"),
     *                  @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                  @OA\Property(property="password", type="string", format="password", example="secret123"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User successfully registered",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="user", type="object"),
     *              @OA\Property(
     *                  property="authorisation",
     *                  type="object",
     *                  @OA\Property(property="access_token", type="string", example="your_access_token"),
     *                  @OA\Property(property="token_type", type="string", example="bearer"),
     *                  @OA\Property(property="expires_in", type="integer", example=3600),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity - Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Validation error"),
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}}),
     *          ),
     *      ),
     * )
     */
    public function register(Authentications\AuthenticationRegisterNewUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        $user->refresh();

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
     * Get the authenticated user information.
     *
     * @return JsonResponse
     *
     * @OA\Get(
     *      path="/api/me",
     *      operationId="getUserProfile",
     *      tags={"Authentication"},
     *      summary="Get user profile",
     *      description="Get information about the authenticated user",
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", type="object"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized - Invalid or missing token",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Unauthorized"),
     *          ),
     *      ),
     * )
     */

    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Logout the authenticated user.
     *
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/logout",
     *      operationId="logoutUser",
     *      tags={"Authentication"},
     *      summary="Logout user",
     *      description="Logs out the authenticated user",
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successfully logged out",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Successfully logged out"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized - Invalid or missing token",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Unauthorized"),
     *          ),
     *      ),
     * )
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh the user's JWT token.
     *
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/refresh",
     *      operationId="refreshToken",
     *      tags={"Authentication"},
     *      summary="Refresh User Token",
     *      description="Refreshes the user's JWT token",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Token successfully refreshed",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="user", type="object"),
     *              @OA\Property(
     *                  property="authorisation",
     *                  type="object",
     *                  @OA\Property(property="access_token", type="string", example="your_refreshed_access_token"),
     *                  @OA\Property(property="token_type", type="string", example="bearer"),
     *                  @OA\Property(property="expires_in", type="integer", example=3600),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized - Token refresh failed",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Token refresh failed"),
     *          ),
     *      ),
     * )
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Initiate the forgot password process.
     *
     * @param Authentications\AuthenticationForgotPasswordRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/password/forgot",
     *      operationId="forgotPassword",
     *      tags={"Authentication"},
     *      summary="Forgot Password",
     *      description="Initiates the forgot password process and sends a reset link to the user's email address.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="User email",
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Reset link sent successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="A reset link has been sent to your email address."),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found - User with provided email not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="User not found"),
     *          ),
     *      ),
     * )
     */
    public function forgotPassword(Authentications\AuthenticationForgotPasswordRequest $request)
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
     * Reset user's password based on the provided token.
     *
     * @param Authentications\AuthenticationResetPasswordRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/reset-password",
     *      operationId="resetPassword",
     *      tags={"Authentication"},
     *      summary="Reset Password",
     *      description="Resets user's password based on the provided token.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Reset password details",
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="token", type="string", example="reset_token_here"),
     *                  @OA\Property(property="password", type="string", format="password", example="new_password"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Password reset successful",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="user", type="object"),
     *              @OA\Property(
     *                  property="authorisation",
     *                  type="object",
     *                  @OA\Property(property="access_token", type="string", example="new_access_token"),
     *                  @OA\Property(property="token_type", type="string", example="bearer"),
     *                  @OA\Property(property="expires_in", type="integer", example=3600),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized - Incorrect token, please try again",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Incorrect token, please try again"),
     *          ),
     *      ),
     * )
     */
    public function resetPassword(Authentications\AuthenticationResetPasswordRequest $request)
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
     * * Redirect the user to the Provider authentication page.
     *
     * @param $provider
     *
     * @return JsonResponse
     *
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }


    /**
     * Social login response and register/login logic.
     *
     * @param $provider
     *
     * @return JsonResponse
     *
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
