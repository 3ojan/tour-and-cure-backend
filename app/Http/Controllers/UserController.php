<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UserViewAllRequest as ViewAll;
use App\Http\Requests\EmployeesUsers\EmployeeUserStoreRequest as Store;
use App\Http\Requests\Users\UserViewRequest as View;
use App\Http\Requests\EmployeesUsers\EmployeeUserUpdateRequest as Update;
use App\Http\Requests\Users\UserDeleteRequest as Delete;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param ViewAll $request
     * @return JsonResponse
     *
     * @OA\Get(
     *      path="/api/users",
     *      operationId="indexUsers",
     *      tags={"Users"},
     *      summary="Get All Users",
     *      description="Displays a list of all users with pagination.",
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Number of items to show per page",
     *          required=false,
     *          @OA\Schema(type="integer", default=20),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Users fetched successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="All users fetched successfully!"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/UserResource"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity - Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Validation error"),
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"per_page": {"The per page must be an integer."}})
     *          ),
     *      ),
     *      security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index(ViewAll $request)
    {
        $search = $request->query('search');
        $perPage = $request->input('per_page', 20);

        $users = User::query();

        if ($search) {
            $users->orWhere('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");

            $users->orWhereHas('clinic', function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });

            $users->orWhereHas('employee', function($query) use ($search) {
                $query->where('phone', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $users = $users->simplePaginate($perPage);

        return response()->json(array_merge([
            'status' => 'Success',
            'message' => 'All users fetched successfully!',
        ], UserResource::collection($users)->toArray()), 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Store clinic_user and return $user to EmployeeController.
     *
     * @param Store $request
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/clinic-users",
     *      operationId="storeClinicUser",
     *      tags={"Users"},
     *      summary="Store Clinic User",
     *      description="Stores a new clinic user.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Clinic user details",
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"email", "password", "role", "clinic_id"},
     *                  @OA\Property(property="name", type="string", example="John Doe"),
     *                  @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                  @OA\Property(property="password", type="string", format="password", example="secret123"),
     *                  @OA\Property(property="role", type="string", example="clinic_owner"),
     *                  @OA\Property(property="clinic_id", type="integer", example="1"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Clinic user created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="string", example="1"),
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="john@example.com"),
     *              @OA\Property(property="role", type="string", example="clinic_owner"),
     *              @OA\Property(property="clinic_id", type="integer", example="1"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity - Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Validation error"),
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}})
     *          ),
     *      ),
     *      security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function storeClinicUser(Store $request)
    {
        $clinicUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'clinic_id' => $request->clinic_id
        ]);

        $clinicUser->refresh();

        return $clinicUser;
    }

    /**
     * Display the specified resource.
     *
     * @param View $request
     * @param User $user
     *
     * @return JsonResponse
     *
     * @OA\Get(
     *      path="/api/users/{user}",
     *      operationId="showUser",
     *      tags={"Users"},
     *      summary="Get a Specific User",
     *      description="Displays information about a specific user.",
     *      @OA\Parameter(
     *          name="user",
     *          in="path",
     *          required=true,
     *          description="ID of the user",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User fetched successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="User fetched successfully!"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/UserResource"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found - User not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="User not found"),
     *          ),
     *      ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show(View $request, User $user)
    {
        return $this->success(new UserResource($user), 'User fetched successfully!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Update $request
     * @param User $user
     *
     * @return JsonResponse
     *
     * @OA\Patch(
     *      path="/api/users/{user}",
     *      operationId="updateUser",
     *      tags={"Users"},
     *      summary="Update a User",
     *      description="Updates information about a user.",
     *      @OA\Parameter(
     *          name="user",
     *          in="path",
     *          required=true,
     *          description="ID of the user",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="User details",
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="name", type="string", example="John Doe"),
     *                  @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                  @OA\Property(property="password", type="string", format="password", example="newSecret123"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User successfully updated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="User successfully updated!"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/UserResource"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity - Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Validation error"),
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}})
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found - User not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="User not found"),
     *          ),
     *      ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function update(Update $request, User $user)
    {
        $user->update($request->validated());

        return $this->success(new UserResource($user), 'User successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Delete $request
     * @param User $user
     *
     * @return JsonResponse
     *
     * @OA\Delete(
     *      path="/api/users/{user}",
     *      operationId="destroyUser",
     *      tags={"Users"},
     *      summary="Delete a User",
     *      description="Deletes a user and related media.",
     *      @OA\Parameter(
     *          name="user",
     *          in="path",
     *          required=true,
     *          description="ID of the user",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User successfully deleted",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="User successfully deleted!"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found - User not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="User not found"),
     *          ),
     *      ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function destroy(Delete $request, User $user)
    {
        // delete media related to this user, in public folder and media_files table
        if ($user->employee->media) {
            app(MediaController::class)->destroy($user->employee);
        }

        $user->delete();

        return $this->success('', 'User successfully deleted!');
    }
}
