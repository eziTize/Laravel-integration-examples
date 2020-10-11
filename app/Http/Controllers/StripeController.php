<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;

class StripeController extends Controller
{
    public function index()
	{
	    return view('stripe.index');
	}

    public function store(Request $request)
    {
        try {
            $charge = Stripe::charges()->create([
                'amount' => '1',
                'currency' => 'USD',
                'source' => $request->stripeToken,
                'description' => 'Demo Integration Order',
                //'receipt_email' => $request->email,
                'metadata' => [
                    //change to Order ID after we start using DB
                  //  'contents' => $contents,
                   // 'quantity' => '10',
                ],
            ]);
            // SUCCESSFUL
             return back()->with('success_message', 'Thank you! Your payment was successful!');
           } catch (CardErrorException $e) {

           }
    }
}