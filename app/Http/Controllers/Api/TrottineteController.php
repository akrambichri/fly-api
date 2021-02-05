<?php

namespace App\Http\Controllers\Api;

use App\Models\Trottinete;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

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
            'price_per_minute' => 'required|string',
            'address_id' => 'required|string'
        ]);

        $trottinet = Trottinete::create([
            'price_per_minute' => $request->get('price_per_minute'),
            'address_id'=> $request->get('address_id'),
            'uuid'=> Str::uuid(),
            ]);

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
        $request->validate([
            'price_per_minute' => 'string',
            'address_id' => 'string'
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


    public function verifyQr(Request $request) :Response
    {
        $request->validate([
            'qrcode' => 'required|string',
        ]);
        $trottinet= Trottinete::where('uuid',$request->get('qrcode'))->first();
        if (is_null($trottinet)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError(
                    message: 'not_found'
                );
            }
        return $this->respondWithSuccess(
            message: 'model_found_successfully'
        );
    }
}
