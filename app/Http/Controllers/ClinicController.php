<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClinicResource;
use App\Models\Clinic;
use App\Http\Requests\Clinics;
use App\Http\Requests\Users;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
    use HttpResponses;
    public function index(Clinics\ViewAll $request)
    {
        $clinics = Clinic::all();

        return $this->success(ClinicResource::collection($clinics), 'Clinics fetched successfully') ;
    }

    public function show(Clinics\View $request, Clinic $clinic)
    {
        return $this->success(new ClinicResource($clinic), 'Clinic fetched successfully!');
    }

    public function store(Users\StoreClinicOwner $request)
    {
        $clinic = Clinic::create([
            'name' => $request->clinic_name,
            'address' => $request->clinic_address,
            'postcode' => $request->clinic_postcode,
            'city' => $request->clinic_city,
            'country_id' => $request->clinic_country_id,
            'created_by' => Auth::user()->id
        ]);

        $clinic->categories()->sync($request->validated('category_ids', []));

        return $clinic;
    }

    public function update(Clinics\Update $request, Clinic $clinic)
    {
        $validatedData = $request->validated();
        $validatedData['updated_by'] = Auth::user()->id;

        $clinic->update($validatedData);

        $clinic->categories()->sync($validatedData['category_ids']);

        $clinic->refresh();

        return $this->success(new ClinicResource($clinic), 'Clinic successfully updated!');
    }

    public function destroy(Clinics\Delete $request, Clinic $clinic)
    {
        $clinic->delete();

        return $this->success('', 'Clinic successfully deleted!');
    }
}
