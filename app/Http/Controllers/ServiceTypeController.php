<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceType;
use App\Http\Requests\ServiceTypeRequest;

class ServiceTypeController extends Controller
{
    public function index()
    {
        return ServiceType::all();
    }

    public function show(ServiceType $serviceType)
    {
        return $serviceType;
    }

    public function store(ServiceTypeRequest $request)
    {
        return ServiceType::create($request->all());
    }

    public function update(ServiceTypeRequest $request, ServiceType $serviceType)
    {
        $serviceType->update($request->all());

        return $serviceType;
    }

    public function delete(Request $request, ServiceType $serviceType)
    {
        $serviceType->delete();

        return 204;
    }
}
