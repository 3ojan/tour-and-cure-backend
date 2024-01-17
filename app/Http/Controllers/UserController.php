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
     *
     * @return JsonResponse
     */
    public function index(ViewAll $request)
    {
        $perPage = $request->input('per_page', 20);

        $users = User::paginate($perPage);

        return response()->json(array_merge([
            'status' => 'Success',
            'message' => 'All users fetched successfully!',
        ], UserResource::collection($users)->toArray()), 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Store clinic_user and return $user to EmployeeController.
     *
     * @param Store $request
     *
     * @return JsonResponse
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
