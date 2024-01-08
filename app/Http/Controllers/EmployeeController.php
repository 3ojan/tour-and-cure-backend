<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employees;
use App\Http\Requests\EmployeesUsers;
use App\Http\Requests\EmployeesUsersClinics;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\Media;
use App\Models\User;
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
    public function index(Employees\EmployeeViewAllRequest $request)
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
    public function store(EmployeesUsersClinics\EmployeeUserClinicStoreRequest $request)
    {
        if($request->has('clinic_id')) {
            $user = app(UserController::class)->storeClinicUser($request);
        } else {
            $user = app(UserController::class)->storeClinicOwner($request);
        }

        $employee = Employee::create([
            'user_id' => $user->id,
            'description' => $request->employee_description ?? null,
            'phone' => $request->employee_phone ?? null,
            'type' => $request->employee->type ?? null
        ]);

        if ($request->has('employee_picture')) {
            $uuid = $request->employee_picture;

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
    public function show(Employees\EmployeeViewRequest $request, Employee $employee)
    {
        return $this->success(new EmployeeResource($employee), 'Employee fetched successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeesUsers\EmployeeUserUpdateRequest $request, Employee $employee)
    {
        //If user data are present call user update method
        if ($request->hasAny(['name', 'email', 'password', 'role'])) {
            app(UserController::class)->update($request, $employee->user);
        }

        //Update employee data
        $employee->update([
            'description' => $request->employee_description,
            'phone' => $request->employee_phone,
            'type' => $request->employee_type,
        ]);

        $employee->refresh();

        return $this->success(new EmployeeResource($employee), 'Employee successfully updated!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employees\EmployeeDeleteRequest $request, Employee $employee)
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
