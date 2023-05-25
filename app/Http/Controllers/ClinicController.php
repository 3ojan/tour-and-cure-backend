<?php

namespace App\Http\Controllers;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //        
        $clinics = Clinic::all();
        return response()->json($clinics);
    }

    /**
     * Show the form for creating a new resource.
     */
    ////TODOOO
    public function create( Clinic $clinic)
    {
        //
        try {
            error_log("request".$request);
            $creatorId = Auth::id();
            // Get the current user's ID
            // Add the current user's ID to the data
            $validatedData['creator_id'] = $creatorId;
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string',
                'activity_id' => 'required|exists:activities,id',
                'creator_id' => $creatorId,
            ]);
            // Create a new clinic record
            Clinic::create($validatedData);
            // Return success message
            return response()->json(['message' => 'Clinic created successfully'], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['error' => $e->errors()], 422);
        } catch (QueryException $e) {
            // Handle database query errors
            return response()->json(['error' => 'Failed to create clinic'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, Clinic $clinic)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string',
                'activity_id' => 'required|exists:activities,id',
                'creator_id' => 'required|exists:users,id',
            ]);

            // Update the clinic record
            $clinic->update($validatedData);

            // Return success message
            return response()->json(['message' => 'Clinic updated successfully'], 200);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Handle other errors
            return response()->json(['error' => 'Failed to update clinic'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clinic $clinic)
    {
        try {
            // Delete the clinic record
            $clinic->delete();
            // Return success message
            return response()->json(['message' => 'Clinic deleted successfully'], 200);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => 'Failed to delete clinic'], 500);
        }
    }
}
