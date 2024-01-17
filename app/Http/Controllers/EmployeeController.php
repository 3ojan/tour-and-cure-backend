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
use Illuminate\Http\JsonResponse;


class EmployeeController extends Controller
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
     *
     * @OA\Get(
     *     path="/api/employees",
     *     summary="Get a listing of employees",
     *     operationId="getEmployeeList",
     *     tags={"Employees"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", format="int32", minimum=1, maximum=100),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="All employees fetched successfully!"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/EmployeeResource")),
     *         ),
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
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
     *
     * @param Store $request
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/employees",
     *      operationId="storeEmployee",
     *      tags={"Employees"},
     *      summary="Store a new employee",
     *      description="Stores a new employee along with clinic user details.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Employee details",
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"email", "password", "role", "clinic_id"},
     *                  @OA\Property(property="name", type="string", example="John Doe"),
     *                  @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                  @OA\Property(property="password", type="string", format="password", example="secret123"),
     *                  @OA\Property(property="clinic_id", type="integer", example=1),
     *                  @OA\Property(property="role", type="string", example="employee_role"),
     *                  @OA\Property(property="description", type="string", example="Employee description"),
     *                  @OA\Property(property="phone", type="string", example="123456789"),
     *                  @OA\Property(property="type", type="string", example="employee_type"),
     *                  @OA\Property(property="is_public", type="boolean", example=true),
     *                  @OA\Property(property="profile_picture", type="string", example="uuid_of_media_file"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employee created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="Employee created successfully!"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/EmployeeResource"),
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
     *      security={
     *         {"bearerAuth": {}}
     *     }
     * )
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
     *
     * @param View $request
     * @param Employee $employee
     * @return JsonResponse
     *
     * @OA\Get(
     *      path="/api/employees/{employee}",
     *      operationId="showEmployee",
     *      tags={"Employees"},
     *      summary="Get a Specific Employee",
     *      description="Displays information about a specific employee.",
     *      @OA\Parameter(
     *          name="employee",
     *          in="path",
     *          required=true,
     *          description="ID of the employee",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employee fetched successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="Employee fetched successfully!"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/EmployeeResource"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found - Employee not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Employee not found"),
     *          ),
     *      ),
     *      security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show(View $request, Employee $employee)
    {
        return $this->success(new EmployeeResource($employee), 'Employee fetched successfully!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Update $request
     * @param Employee $employee
     * @return JsonResponse
     *
     * @OA\Patch(
     *      path="/api/employees/{employee}",
     *      operationId="updateEmployee",
     *      tags={"Employees"},
     *      summary="Update an Employee",
     *      description="Updates information about a specific employee.",
     *      @OA\Parameter(
     *          name="employee",
     *          in="path",
     *          required=true,
     *          description="ID of the employee",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Employee details",
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="description", type="string", example="Updated description"),
     *                  @OA\Property(property="phone", type="string", example="987654321"),
     *                  @OA\Property(property="type", type="string", example="updated_employee_type"),
     *                  @OA\Property(property="is_public", type="boolean", example=false),
     *                  @OA\Property(property="profile_picture", type="string", example="uuid_of_media_file"),
     *                  @OA\Property(property="name", type="string", example="Updated John Doe"),
     *                  @OA\Property(property="email", type="string", format="email", example="updated_john@example.com"),
     *                  @OA\Property(property="password", type="string", format="password", example="updated_secret123"),
     *                  @OA\Property(property="role", type="string", example="updated_role"),
     *                  @OA\Property(property="clinic_id", type="integer", example=1),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employee successfully updated",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="Employee successfully updated!"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/EmployeeResource"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found - Employee not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Employee not found"),
     *          ),
     *      ),
     *      security={
     *         {"bearerAuth": {}}
     *     }
     * )
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
     *
     * @param Delete $request
     * @param Employee $employee
     * @return JsonResponse
     *
     * @OA\Delete(
     *      path="/api/employees/{employee}",
     *      operationId="deleteEmployee",
     *      tags={"Employees"},
     *      summary="Delete an Employee",
     *      description="Deletes a specific employee and associated media files.",
     *      @OA\Parameter(
     *          name="employee",
     *          in="path",
     *          required=true,
     *          description="ID of the employee",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employee successfully deleted",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="Employee successfully deleted!"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found - Employee not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Employee not found"),
     *          ),
     *      ),
     *      security={
     *         {"bearerAuth": {}}
     *     }
     * )
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
