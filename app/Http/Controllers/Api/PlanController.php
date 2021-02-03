<?php

namespace App\Http\Controllers\Api;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlanController extends ApiController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $plans = Plan::all();
        return $this->respondWithSuccess(
            message: 'model_all_data_retreived_successfully',
            data: ['plans' => $plans]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        //
        $request->validate([
            'rue' => 'required|string',
            'ville' => 'required|string',
            'region' => 'required|string',
            'code_postal' => 'required|number',
            'pays' => 'required|string',
            'photo' => 'required|string',
            'is_green' => 'required|boolean'
        ]);

        $plan = Plan::create($request->all());

        return $this->respondWithSuccess(
            message: 'model_created_successfully',
            data: ['plan' => $plan]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show($id): Response
    {
        $plan = Plan::find($id);
        if (is_null($plan)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError(
                    message: 'not_found'
                );
        }

        return $this->respondWithSuccess(
            message: 'model_retrieved_successfully',
            data: ['plan' => $plan]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan): Response
    {
        //
        $request->validate([
            'rue' => 'required|string',
            'ville' => 'required|string',
            'region' => 'required|string',
            'code_postal' => 'required|number',
            'pays' => 'required|string',
            'photo' => 'required|string',
            'is_green' => 'required|boolean'
        ]);
        $plan->update($request->all());

        return $this->respondWithSuccess(
            message: 'model_updated_successfully',
            data: ['plan' => $plan]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan): Response
    {
        $plan->delete();
        return $this->respondWithSuccess(
            message: 'model_deleted_successfully',
            data: ['plan' => $plan]
        );
    }
}
