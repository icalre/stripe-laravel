<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class WebhooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function handle(Request $request)
    {
        $payload = $request->all();
        $method = $this->eventToMethod($payload['type']);

        if (method_exists($this, $method)) {
            $this->$method($payload);
        }

        return response()->json('Webhook Received', 200);

    }

    public function whenCustomerSubscriptionDeleted($payload)
    {

        $user = $this->retrieveUser($payload);

        $user->deactivate();

    }

    public function whenChargeSucceeded($payload)
    {
        $user = $this->retrieveUser($payload);

        $user->payments()->create([
            'amount'=>$payload['data']['object']['amount'],
            'charge_id'=>$payload['data']['object']['id']
        ]);

    }

    public function eventToMethod($event)
    {

        return 'when' . studly_case(str_replace('.', '_', $event));
    }


    public function retrieveUser($payload)
    {
        return User::byStripeId($payload['data']['object']['customer']);

    }
}
