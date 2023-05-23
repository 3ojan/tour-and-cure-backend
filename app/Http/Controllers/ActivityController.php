<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Log;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
       
    }

    /**
     * Show all
     */
        public function all(){
        try{
           $worlds = Activity::all();
           return response()->json($worlds);
        }
        catch(Exception $e)
        {
            Log::error($e);
        }
    }

    /**
     * Show one
     */
        public function getOne(Request $request){
        try{
            $activity = Worlds::findOrFail($request->get("id"));
           return response()->json($activity);
        }
        catch(Exception $e)
        {
            Log::error($e);
        }
    }
    /**
     * Show one
     */
        public function upadte(Request $request){
        try{
            $activity = Worlds::findOrFail($request->get("id"));
           return response()->json($activity);
        }
        catch(Exception $e)
        {
            Log::error($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        Log::debug('Received POST request:', $request->all());
        //
        $name = $request->get("name");
        $description = $request->get("description");
         try{
            $model = new Activity([
            "name" => $name,
            "description" => $description || '',
            ]);
            $model->save();
            
           return response()->json([
            "success"=>true,
            ///TODO ::: add default value to descritpion null
            "message"=>$name." saved successfully",
           ]);
        }
        catch(Exception $e)
        {
            Log::error($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        //
        try{
            error_log($request);
            Log::debug('Received POST request:', $request->all());
            $id = $request->get("id");
            $name = $request->get("name"); ///table items except data field
            $description = $request->get("description"); ////data field in DB

           $model = Activity::findOrFail($id);
           Activity::where('id', $id)->update([
            // 'name'=> $name,
            'name'=> $name,
            'description'=> $description,
        ]);
           return response()->json([
            'id'=>$id,
            'message'=>$name.' saved successfully',
           ]);
        }
        catch(Exception $e)
        {
            error_log($e);
            Log::error($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        //
    }
}
