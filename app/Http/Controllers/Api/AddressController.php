<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AddressController extends ApiController
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
        $addresses = Address::all();
        return $this->respondWithSuccess(
            message: 'model_all_data_retreived_successfully',
            data: ['addresses' => $addresses]
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
            'description' => 'required|string',
            'photo' => 'required|string',
            'is_green' => 'required|boolean'
        ]);

        $address = Address::create($request->all());

        return $this->respondWithSuccess(
            message: 'model_created_successfully',
            data: ['address' => $address]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show($id): Response
    {
        $address = Address::find($id);
        if (is_null($address)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError(
                    message: 'not_found'
                );
        }

        return $this->respondWithSuccess(
            message: 'model_retrieved_successfully',
            data: ['address' => $address]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address): Response
    {
        //
        $request->validate([
            'description' => 'string',
            'photo' => 'string',
            'is_green' => 'boolean'
        ]);
        $address->update($request->all());

        return $this->respondWithSuccess(
            message: 'model_updated_successfully',
            data: ['address' => $address]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address): Response
    {
        $address->delete();
        return $this->respondWithSuccess(
            message: 'model_deleted_successfully',
            data: ['address' => $address]
        );
    }
}
