<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreClinicOwner;
use App\Models\Clinic;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param StoreClinicOwner $request
     *
     * @return JsonResponse
     */
    public function storeClinicOwner(Users\StoreClinicOwner $request)
    {
        $clinicController = app(ClinicController::class);
        $clinic = $clinicController->store($request);

        $clinicOwner = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'clinic_owner',
            'clinic_id' => $clinic->id
        ]);

        $clinicOwner->refresh();

        return $this->success($clinicOwner,'Clinic owner and clinic successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
