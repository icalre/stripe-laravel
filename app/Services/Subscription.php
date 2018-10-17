<?php
/**
 * Created by PhpStorm.
 * User: icalvay
 * Date: 10/15/18
 * Time: 11:46 PM
 */

namespace App\Services;


use App\Plan;
use App\User;
use Carbon\Carbon;
use Stripe\Customer;
use Stripe\Subscription as StripeSubscription;

class Subscription
{

    protected $user;

    protected $coupon;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(Plan $plan, $token)
    {
        $customer = Customer::create([
            'email' => $this->user->email,
            'source' => $token,
            'plan' => $plan->stripe_id,
            'coupon'=>$this->coupon
        ]);


        $subscriptionId = $customer->subscriptions->data[0]->id;


        $this->user->activate($customer->id, $subscriptionId);
    }


    public function retrieveStripeSubscription()
    {
        return StripeSubscription::retrieve($this->user->stripe_subscription);
    }

    public function cancel($atPeriodEnd = true)
    {
        $customer = Customer::retrieve($this->user->stripe_id);

        $subscription = $customer->cancelSubscription(['at_period_end'=>$atPeriodEnd]);

        $endDate = Carbon::createFromTimestamp($subscription->current_period_end);

        $this->user->deactivate($endDate);

    }

    public function cancelImmediately()
    {
        return $this->cancel(false);
    }

    public function usingCoupon($coupon)
    {
        if($coupon)
        {
            $this->coupon = '0xGaXdXC';
        }

        return $this;

    }

    public function retrieveStripeCustomer()
    {
        return Customer::retrieve($this->user->stripe_id);
    }

    public function resume()
    {
        $subscription = $this->retrieveStripeSubscription();

        $subscription->plan = 'plan_Dn1cIigmWOIG36';
        $subscription->save();

        $this->user->activate();


    }

}