<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReservationController extends ApiController
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
        $reservations = Reservation::all();
        return $this->respondWithSuccess(
            message: 'model_all_data_retreived_successfully',
            data: ['reservations' => $reservations]
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

        $reservation = Reservation::create($request->all());

        return $this->respondWithSuccess(
            message: 'model_created_successfully',
            data: ['reservation' => $reservation]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show($id): Response
    {
        $reservation = Reservation::find($id);
        if (is_null($reservation)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError(
                    message: 'not_found'
                );
        }

        return $this->respondWithSuccess(
            message: 'model_retrieved_successfully',
            data: ['reservation' => $reservation]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation): Response
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
        $reservation->update($request->all());

        return $this->respondWithSuccess(
            message: 'model_updated_successfully',
            data: ['reservation' => $reservation]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation): Response
    {
        $reservation->delete();
        return $this->respondWithSuccess(
            message: 'model_deleted_successfully',
            data: ['reservation' => $reservation]
        );
    }
}
