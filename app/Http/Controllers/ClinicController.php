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
     *
     * @OA\Get(
     *      path="/api/clinics",
     *      operationId="index",
     *      tags={"Clinics"},
     *      summary="Get All Clinics",
     *      description="Displays a listing of all clinics.",
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Number of items per page",
     *          @OA\Schema(type="integer", default=20)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="All clinics fetched successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="All clinics fetched successfully!"),
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ClinicResource")),
     *              @OA\Property(property="links", type="object", @OA\Property(property="pagination", type="object")),
     *          ),
     *      ),
     * )
     */
    public function index(ViewAll $request)
    {
        $perPage = $request->input('per_page', 20);

        $clinics = Clinic::paginate($perPage);

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
     *
     * @OA\Get(
     *      path="/api/clinics/{clinic}",
     *      operationId="show",
     *      tags={"Clinics"},
     *      summary="Get a Specific Clinic",
     *      description="Displays information about a specific clinic.",
     *      @OA\Parameter(
     *          name="clinic",
     *          in="path",
     *          required=true,
     *          description="ID of the clinic",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Clinic fetched successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="Clinic fetched successfully!"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/ClinicResource"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found - Clinic not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Clinic not found"),
     *          ),
     *      ),
     * )
     */
    public function show(View $request, Clinic $clinic)
    {
        return $this->success(new ClinicResource($clinic), 'Clinic fetched successfully!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/clinics",
     *      operationId="store",
     *      tags={"Clinics"},
     *      summary="Create a Clinic",
     *      description="Stores a newly created clinic.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Clinic details",
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"name", "city", "country_id", "category_ids"},
     *                  @OA\Property(property="name", type="string", example="Clinic Name"),
     *                  @OA\Property(property="address", type="string", example="Clinic Address"),
     *                  @OA\Property(property="postcode", type="string", example="12345"),
     *                  @OA\Property(property="city", type="string", example="Clinic City"),
     *                  @OA\Property(property="latitude", type="string", example="Clinic Latitude"),
     *                  @OA\Property(property="longitude", type="string", example="Clinic Longitude"),
     *                  @OA\Property(property="country_id", type="integer", example="1"),
     *                  @OA\Property(property="category_ids", type="array", @OA\Items(type="integer", example=1)),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Clinic created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="Clinic created successfully!"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/ClinicResource"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity - Validation errors",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Validation failed"),
     *              @OA\Property(property="errors", type="object"),
     *          ),
     *      ),
     * )
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
     * @return JsonResponse
     *
     * @OA\Put(
     *      path="/api/clinics/{clinic}",
     *      operationId="update",
     *      tags={"Clinics"},
     *      summary="Update a Clinic",
     *      description="Updates the specified clinic.",
     *      @OA\Parameter(
     *          name="clinic",
     *          in="path",
     *          required=true,
     *          description="ID of the clinic",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Updated clinic details",
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(property="name", type="string", example="Updated Clinic Name"),
     *                  @OA\Property(property="address", type="string", example="Updated Clinic Address"),
     *                  @OA\Property(property="postcode", type="string", example="54321"),
     *                  @OA\Property(property="city", type="string", example="Updated Clinic City"),
     *                  @OA\Property(property="latitude", type="string", example="Updated Clinic Latitude"),
     *                  @OA\Property(property="longitude", type="string", example="Updated Clinic Longitude"),
     *                  @OA\Property(property="country_id", type="integer", example=1),
     *                  @OA\Property(property="category_ids", type="array", @OA\Items(type="integer", example=1)),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Clinic updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="Clinic updated successfully!"),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/ClinicResource"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found - Clinic not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Clinic not found"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity - Validation errors",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Validation failed"),
     *              @OA\Property(property="errors", type="object"),
     *          ),
     *      ),
     * )
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
     * @return JsonResponse
     *
     * @OA\Delete(
     *      path="/api/clinics/{clinic}",
     *      operationId="destroy",
     *      tags={"Clinics"},
     *      summary="Delete a Clinic",
     *      description="Removes the specified clinic.",
     *      @OA\Parameter(
     *          name="clinic",
     *          in="path",
     *          required=true,
     *          description="ID of the clinic",
     *          @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Clinic deleted successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="Success"),
     *              @OA\Property(property="message", type="string", example="Clinic deleted successfully!"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found - Clinic not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Clinic not found"),
     *          ),
     *      ),
     * )
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
