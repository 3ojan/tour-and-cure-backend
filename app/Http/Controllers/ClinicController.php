<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClinicResource;
use App\Models\Clinic;
use App\Http\Requests\Clinics;
use App\Http\Requests\EmployeesUsersClinics;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @param Clinics\ClinicViewAllRequest $request
     *
     * @return JsonResponse
     */
    public function index(Clinics\ClinicViewAllRequest $request)
    {
        $perPage = $request->input('per_page', 20);

        $clinics = Clinic::paginate($perPage);

        // Return only the 'data' part of the paginated response
        return response()->json($clinics->items(), 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Display a specified resource.
     *
     * @param Clinics\ClinicViewRequest $request
     * @param Clinic $clinic
     *
     * @return JsonResponse
     */
    public function show(Clinics\ClinicViewRequest $request, Clinic $clinic)
    {
        return $this->success(new ClinicResource($clinic), 'Clinic fetched successfully!');
    }

    /**
     * Store a newly created clinic resource and return $clinic to UserController.
     *
     * @param EmployeesUsersClinics\EmployeeUserClinicStoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(EmployeesUsersClinics\EmployeeUserClinicStoreRequest $request)
    {
        $clinic = Clinic::create([
            'name' => $request->clinic_name,
            'address' => $request->clinic_address,
            'postcode' => $request->clinic_postcode,
            'city' => $request->clinic_city,
            'latitude' => $request->clinic_latitude,
            'longitude' => $request->clinic_longitude,
            'country_id' => $request->clinic_country_id,
            'created_by' => Auth::user()->id
        ]);

        $clinic->categories()->sync($request->validated('category_ids', []));

        $clinic->refresh();

        return $clinic;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Clinics\ClinicUpdateRequest $request
     * @param Clinic $clinic
     *
     * @return JsonResponse
     */
    public function update(Clinics\ClinicUpdateRequest $request, Clinic $clinic)
    {
        $validatedData = $request->validated();
        $validatedData['updated_by'] = Auth::user()->id;

        $clinic->update($validatedData);

        $clinic->categories()->sync($validatedData['category_ids']);

        $clinic->refresh();

        return $this->success(new ClinicResource($clinic), 'Clinic updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Clinics\ClinicDeleteRequest $request
     * @param Clinic $clinic
     *
     * @return JsonResponse
     */
    public function destroy(Clinics\ClinicDeleteRequest $request, Clinic $clinic)
    {
        // delete media related to this user, in public folder and media_files table
        if ($clinic->media) {
            app(MediaController::class)->destroy($clinic);
        }

        $clinic->delete();

        return $this->success('', 'Clinic deleted successfully!');
    }
}
