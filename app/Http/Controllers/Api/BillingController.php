<?php

namespace App\Http\Controllers\Api;

use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BillingController extends ApiController
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
        // $billings = Billing::all();
        // return $this->respondWithSuccess(
        //     message: 'model_all_data_retreived_successfully',
        //     data: ['billings' => $billings]
        // );
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
        // $request->validate([
        //     'rue' => 'required|string',
        //     'ville' => 'required|string',
        //     'region' => 'required|string',
        //     'code_postal' => 'required|number',
        //     'pays' => 'required|string',
        //     'photo' => 'required|string',
        //     'is_green' => 'required|boolean'
        // ]);

        // $billing = Billing::create($request->all());

        // return $this->respondWithSuccess(
        //     message: 'model_created_successfully',
        //     data: ['billing' => $billing]
        // );

        // $user = auth()->user();
        // $user->createOrGetStripeCustomer();
        // if ($user->hasDefaultPaymentMethod()) {
        //     $paymentMethod = $user->defaultPaymentMethod();
        //     $user->charge(
        //         100,
        //         $paymentMethod
        //     );
        // } else {
        //     //ask for payment method
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function show($id): Response
    {
        $billing = Billing::find($id);
        if (is_null($billing)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError(
                    message: 'not_found'
                );
        }

        return $this->respondWithSuccess(
            message: 'model_retrieved_successfully',
            data: ['billing' => $billing]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Billing $billing): Response
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
        $billing->update($request->all());

        return $this->respondWithSuccess(
            message: 'model_updated_successfully',
            data: ['billing' => $billing]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billing $billing): Response
    {
        $billing->delete();
        return $this->respondWithSuccess(
            message: 'model_deleted_successfully',
            data: ['billing' => $billing]
        );
    }
}
