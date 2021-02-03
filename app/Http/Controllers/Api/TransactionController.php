<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends ApiController
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
        $transactions = Transaction::all();
        return $this->respondWithSuccess(
            message: 'model_all_data_retreived_successfully',
            data: ['transactions' => $transactions]
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

        $transaction = Transaction::create($request->all());

        return $this->respondWithSuccess(
            message: 'model_created_successfully',
            data: ['transaction' => $transaction]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show($id): Response
    {
        $transaction = Transaction::find($id);
        if (is_null($transaction)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError(
                    message: 'not_found'
                );
        }

        return $this->respondWithSuccess(
            message: 'model_retrieved_successfully',
            data: ['transaction' => $transaction]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction): Response
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
        $transaction->update($request->all());

        return $this->respondWithSuccess(
            message: 'model_updated_successfully',
            data: ['transaction' => $transaction]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction): Response
    {
        $transaction->delete();
        return $this->respondWithSuccess(
            message: 'model_deleted_successfully',
            data: ['transaction' => $transaction]
        );
    }
}
