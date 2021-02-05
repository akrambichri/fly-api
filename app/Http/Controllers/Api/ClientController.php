<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\Trottinete;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends ApiController
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
        $clients = Client::all();
        return $this->respondWithSuccess(
            message: 'model_all_data_retreived_successfully',
            data: ['clients' => $clients]
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
            'name' => 'required|string',
            'email' => 'required|string',
            'mobile' => 'required|string',
            'password' => 'required|number'
        ]);

        $client = Client::create($request->all());

        return $this->respondWithSuccess(
            message: 'model_created_successfully',
            data: ['client' => $client]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id): Response
    {
        $client = Client::find($id);
        if (is_null($client)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError(
                    message: 'not_found'
                );
        }

        return $this->respondWithSuccess(
            message: 'model_retrieved_successfully',
            data: ['client' => $client]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client): Response
    {
        //
        $request->validate([
            'name' => 'string',
            'email' => 'string',
            'mobile' => 'string',
            'password' => 'number'
        ]);
        $client->update($request->all());

        return $this->respondWithSuccess(
            message: 'model_updated_successfully',
            data: ['client' => $client]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client): Response
    {
        $client->delete();

        return $this->respondWithSuccess(
            message: 'model_deleted_successfully',
            data: ['client' => $client]
        );
    }


    public function addCard(Request $request): Response
    {
        $client = Client::find(auth()->user()->id);
        $request->validate([
            'card_token' => 'required|string',
            'trottinete_token' => 'required|string',

        ]);
        $stripeCustomer = $client->createOrGetStripeCustomer();



        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $card = $stripe->customers->createSource(
            $client->stripe_id,
            ['source' => $request->card_token]
        );

        $client->card_token = $card->id;
        $client->save();

        $trottinete = Trottinete::where('uuid', $request->trottinete_token)->first();
        $reservation = Reservation::firstOrCreate(
            ['client_id' =>  $client->id, 'trottinete_id' => $trottinete->id],
            ['date_debut' => Carbon::now(), 'is_enabled' => 1]
        );
        if ($reservation->is_enabled === 0) {
            $reservation->date_debut = Carbon::now();
            $reservation->date_retour = null;
            $reservation->is_enabled = 1;
            $reservation->save();
        }
        $client->trottinetes;
        return $this->respondWithSuccess(
            message: 'model_updated_successfully',
            data: ['client' => $client]
        );
    }


    public function chargeCard(Request $request): Response
    {
        $request->validate([
            'trottinete_token' => 'required|string',
            'user_address' => 'required|string',
        ]);
        $client = auth()->user();
        $trottinete = Trottinete::where('uuid', $request->trottinete_token)->first();
        $address = Address::where('description', 'LIKE', '%' . $request->user_address . '%')->first();
        $reservation = Reservation::where(
            ['client_id' =>  $client->id, 'trottinete_id' => $trottinete->id]
        )->first();
        if (is_null($reservation)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError(
                    message: 'not_found'
                );
        }
        if ($reservation->is_enabled === 0) {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
                ->respondWithError(
                    message: 'not_open'
                );
        }
        if ($address->is_green === 0) {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
                ->respondWithError(
                    message: 'address_not_allowed'
                );
        }
        $reservation->date_retour = Carbon::now();
        $reservation->is_enabled = 0;
        $reservation->save();

        $trottinete->address_id =   $address->id;
        $trottinete->save();

        $to = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->date_debut);
        $from = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->date_retour);
        $diff_in_minutes = $to->diffInMinutes($from);


        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $stripe->charges->create([
            'customer' => $client->stripe_id,
            'amount' => $diff_in_minutes * $trottinete->price_per_minute * 100,
            'currency' => 'eur',
            'source' => $client->card_token,
            'description' => 'My First Test Charge (created for API docs)',
        ]);

        return $this->respondWithSuccess(
            message: 'charge_successfully',
            data: ['client' => $client]
        );
    }

    public function getAllInvoices()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $client = Client::find(auth()->user()->id);
        $invoices = $stripe->charges->all(['customer' => $client->stripe_id, 'limit' => 3]);
        return $this->respondWithSuccess(
            message: 'charge_successfully',
            data: ['invoices' => $invoices->data]
        );
    }
}
