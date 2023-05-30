<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Requests\ServiceRequest;

class ServiceController extends Controller
{
    public function index()
    {
        return Service::all();
    }

    public function show(Service $service)
    {
        return $service;
    }

    public function store(ServiceRequest $request)
    {
        return Service::create($request->all());
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->all());

        return $service;
    }

    public function delete(Request $request, Service $service)
    {
        $service->delete();

        return 204;
    }
}
