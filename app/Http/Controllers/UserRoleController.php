<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRole;
use App\Http\Requests\UserRoleRequest;

class UserRoleController extends Controller
{
    public function index()
    {
        return UserRole::all();
    }

    public function show(UserRole $userRole)
    {
        return $userRole;
    }

    public function store(UserRoleRequest $request)
    {
        return UserRole::create($request->all());
    }

    public function update(UserRoleRequest $request, UserRole $userRole)
    {
        $userRole->update($request->all());

        return $userRole;
    }

    public function delete(Request $request, UserRole $userRole)
    {
        $userRole->delete();

        return 204;
    }
}
