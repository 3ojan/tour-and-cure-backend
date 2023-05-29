<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class ClinicController extends Controller
{
    public function index()
    {
        return Clinic::all();
    }

    public function show($id)
    {
        return Clinic::find($id);
    }

    public function store(Request $request)
    {
        return Clinic::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinic->update($request->all());

        return $clinic;
    }

    public function delete(Request $request, $id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinic->delete();

        return 204;
    }
}
