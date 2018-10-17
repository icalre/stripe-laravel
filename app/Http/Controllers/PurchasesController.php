<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Stripe\{Charge, Customer};

class PurchasesController extends Controller
{
    public function store(Request $request)
    {

        $product = Product::findOrFail($request->get('product'));

        $customer = Customer::create([
            'email' => $request->get('stripeEmail'),
            'source' => $request->get('stripeToken')
        ]);

        Charge::create([
            'customer' => $customer->id,
            'amount' => $product->price,
            'currency' => 'usd'
        ]);

        return 'All done';
    }
}
