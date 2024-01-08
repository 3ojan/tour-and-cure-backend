<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeesUsersClinics;
use App\Http\Requests\EmployeesUsers;
use App\Http\Requests\Users;
use App\Http\Requests\Users\UserViewAllRequest;
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
     * @param UserViewAllRequest $request
     * @return JsonResponse
     */
    public function index(Users\UserViewAllRequest $request)
    {
        $perPage = $request->input('per_page', 20);

        $users = User::paginate($perPage);

        return response()->json(array_merge([
            'status' => 'Success',
            'message' => 'All users fetched successfully!',
        ], UserResource::collection($users)->toArray()), 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Store clinic_owner and clinic and return $user to EmployeeController.
     *
     * @param EmployeesUsersClinics\EmployeeUserClinicStoreRequest $request
     *
     * @return JsonResponse
     */
    public function storeClinicOwner(EmployeesUsersClinics\EmployeeUserClinicStoreRequest $request)
    {
        $clinic = app(ClinicController::class)->store($request);

        $clinicOwner = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'clinic_owner',
            'clinic_id' => $clinic->id
        ]);

        $clinicOwner->refresh();

        return $clinicOwner;
    }

    /**
     * Store clinic_user and return $user to EmployeeController.
     *
     * @param EmployeesUsersClinics\EmployeeUserClinicStoreRequest $request
     *
     * @return JsonResponse
     */
    public function storeClinicUser(EmployeesUsersClinics\EmployeeUserClinicStoreRequest $request)
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
     */
    public function show(Users\UserViewRequest $request, User $user)
    {
        return $this->success(new UserResource($user), 'User fetched successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeesUsers\EmployeeUserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return $this->success(new UserResource($user), 'User successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Users\UserDeleteRequest $request, User $user)
    {
        // delete media related to this user, in public folder and media_files table
        if ($user->employee->media) {
            app(MediaController::class)->destroy($user->employee);
        }

        //delete user
        $user->delete();

        return $this->success('', 'User successfully deleted!');
    }
}
