<?php

namespace App\Http\Controllers\Api;

use App\Models\Trottinete;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TrottineteController extends ApiController
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
        $trottinetes = Trottinete::all();
        return $this->respondWithSuccess(
            message: 'model_all_data_retreived_successfully',
            data: ['trottinetes' => $trottinetes]
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

        $trottinet = Trottinete::create($request->all());

        return $this->respondWithSuccess(
            message: 'model_created_successfully',
            data: ['trottinet' => $trottinet]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trottinete  $trottinet
     * @return \Illuminate\Http\Response
     */
    public function show($id): Response
    {
        $trottinet = Trottinete::find($id);
        if (is_null($trottinet)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError(
                    message: 'not_found'
                );
        }

        return $this->respondWithSuccess(
            message: 'model_retrieved_successfully',
            data: ['trottinet' => $trottinet]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Trottinete  $trottinet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trottinete $trottinet): Response
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
        $trottinet->update($request->all());

        return $this->respondWithSuccess(
            message: 'model_updated_successfully',
            data: ['trottinet' => $trottinet]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trottinete  $trottinet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trottinete $trottinet): Response
    {
        $trottinet->delete();
        return $this->respondWithSuccess(
            message: 'model_deleted_successfully',
            data: ['trottinet' => $trottinet]
        );
    }
}
