<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employees\EmployeeViewAllRequest as ViewAll;
use App\Http\Requests\Employees\EmployeeViewRequest as View;
use App\Http\Requests\Employees\EmployeeDeleteRequest as Delete;
use App\Http\Requests\EmployeesUsers\EmployeeUserStoreRequest as Store;
use App\Http\Requests\EmployeesUsers\EmployeeUserUpdateRequest as Update;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\Media;
use App\Traits\HttpResponses;


class EmployeeController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ViewAll $request)
    {
        $perPage = $request->input('per_page', 20);
        $user = auth()->user();

        $employees = Employee::when($user->role === 'clinic_owner', function ($query) use ($user) {
            return $query->whereHas('user', function ($subquery) use ($user) {
                $subquery->where('clinic_id', $user->clinic_id);
            });
        })
            ->paginate($perPage);

        return response()->json(array_merge([
            'status' => 'Success',
            'message' => 'All employees fetched successfully!',
        ], EmployeeResource::collection($employees)->toArray()), 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Store employee and clinic user at once.
     */
    public function store(Store $request)
    {
        $user = app(UserController::class)->storeClinicUser($request);

        $employee = Employee::create([
            'user_id' => $user->id,
            'description' => $request->description,
            'phone' => $request->phone,
            'type' => $request->type,
            'is_public' => $request->is_public
        ]);

        if ($request->has('profile_picture')) {
            $uuid = $request->profile_picture;

            $media = Media::where('id', $uuid)->first();

            if ($media) {
                $employee->media()->save($media);
            }
        }

        $employee->refresh();

        return $this->success(new EmployeeResource($employee), 'Employee created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(View $request, Employee $employee)
    {
        return $this->success(new EmployeeResource($employee), 'Employee fetched successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request, Employee $employee)
    {
        //If user data are present call user update method
        if ($request->hasAny(['name', 'email', 'password', 'role'])) {
            app(UserController::class)->update($request, $employee->user);
        }

        //Update employee data
        $employee->update([
            'description' => $request->description,
            'phone' => $request->phone,
            'type' => $request->type,
            'is_public' => $request->is_public
        ]);

        if ($request->has('profile_picture')) {
            $uuid = $request->profile_picture;

            $media = Media::where('id', $uuid)->first();

            if ($media) {
                $employee->media()->save($media);
            }
        }

        $employee->refresh();

        return $this->success(new EmployeeResource($employee), 'Employee successfully updated!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delete $request, Employee $employee)
    {
        // delete media related to this employee, in public folder and media_files table
        if ($employee->media) {
            app(MediaController::class)->delete($employee);
        }

        // delete employee
        $employee->delete();

        return $this->success('', 'Employee successfully deleted!');
    }
}
