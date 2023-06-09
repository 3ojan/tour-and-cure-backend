<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Http\Requests\ClinicRequest;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function index()
    {
        return Clinic::with('serviceTypes')->get();
    }

    public function show(Clinic $clinic)
    {
        return $clinic;
    }

    public function store(ClinicRequest $request)
    {
        return Clinic::create($request->all());
    }

    public function update(ClinicRequest $request, Clinic $clinic)
    {
        $clinic->update($request->all());

        return $clinic;
    }

    public function delete(Request $request, Clinic $clinic)
    {
        $clinic->delete();

        return 204;
    }
}
