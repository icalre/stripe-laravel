<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationForm;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function store(RegistrationForm $request)
    {

        try {
            $request->save();
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getMessage()], 422);
        }


        return ['status' => 'Success'];
    }


    public function destroy()
    {
        auth()->user()->subscription()->cancel();

        return back();
    }

    public function update(Request $request)
    {
        if($request->get('resume'))
        {
            auth()->user()->subscription()->resume();
        }

        return back();
    }
}
