<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClinicResource;
use App\Models\Clinic;
use App\Http\Requests\Clinics\ClinicViewAllRequest as ViewAll;
use App\Http\Requests\Clinics\ClinicViewRequest as View;
use App\Http\Requests\Clinics\ClinicStoreRequest as Store;
use App\Http\Requests\Clinics\ClinicUpdateRequest as Update;
use App\Http\Requests\Clinics\ClinicDeleteRequest as Delete;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'index',
                'show'
            ]
        ]);
    }

    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @param ViewAll $request
     *
     * @return JsonResponse
     */
    public function index(ViewAll $request)
    {
        $search = $request->query('search');
        $perPage = $request->input('per_page', 20);

        $clinics = Clinic::query();

        if ($search) {
            $clinics->orWhere('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
                ->orWhere('postcode', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%");

            $clinics->orWhereHas('country', function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });

            $clinics->orWhereHas('categories', function($query) use ($search) {
                $query->where('en', 'like', "%{$search}%")
                    ->orWhere('hr', 'like', "%{$search}%");
            });
        }

        $clinics = $clinics->simplePaginate($perPage);

        return response()->json(array_merge([
            'status' => 'Success',
            'message' => 'All clinics fetched successfully!',
        ], ClinicResource::collection($clinics)->toArray()), 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Display a specified resource.
     *
     * @param View $request
     * @param Clinic $clinic
     *
     * @return JsonResponse
     */
    public function show(View $request, Clinic $clinic)
    {
        return $this->success(new ClinicResource($clinic), 'Clinic fetched successfully!');
    }

    /**
     * Store a newly created clinic resource and return $clinic to UserController.
     *
     * @param Store $request
     *
     * @return JsonResponse
     */
    public function store(Store $request)
    {
        $clinic = Clinic::create([
            'name' => $request->name,
            'address' => $request->address,
            'postcode' => $request->postcode,
            'city' => $request->city,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'country_id' => $request->country_id,
            'created_by' => Auth::user()->id
        ]);

        $clinic->categories()->sync($request->validated('category_ids', []));

        $clinic->refresh();

        return $this->success(new ClinicResource($clinic), 'Clinic created successfully!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Update $request
     * @param Clinic $clinic
     *
     * @return JsonResponse
     */
    public function update(Update $request, Clinic $clinic)
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
     * @param Delete $request
     * @param Clinic $clinic
     *
     * @return JsonResponse
     */
    public function destroy(Delete $request, Clinic $clinic)
    {
        // delete media related to this clinic, in public folder and media_files table
        if ($clinic->media) {
            app(MediaController::class)->destroy($clinic);
        }

        $clinic->delete();

        return $this->success('', 'Clinic deleted successfully!');
    }
}
