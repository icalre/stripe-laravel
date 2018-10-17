<?php
/**
 * Created by PhpStorm.
 * User: icalvay
 * Date: 10/16/18
 * Time: 3:06 AM
 */

namespace App\Billing;

use App\Services\Subscription;
use Carbon\Carbon;


trait Billable{

    public function activate($customerId = null , $subscriptionId = null )
    {
        return $this->update([
            'stripe_id'=> $customerId ?? $this->stripe_id,
            'stripe_active'=>true,
            'stripe_subscription'=>$subscriptionId ?? $this->stripe_subscription,
            'subscription_end_at'=>null
        ]);
    }

    public function deactivate($endDate = null)
    {

        $endDate = $endDate ?? Carbon::now();

        return $this->update([
            'stripe_active'=>false,
            'subscription_end_at'=>$endDate
        ]);
    }

    public function isActive()
    {
        return $this->isSubscribed() || $this->isOnGracePeriod();
    }

    public function isOnGracePeriod()
    {

        if(!$endsAt = $this->subscription_end_at)
        {
            return false;
        }

        return Carbon::now()->lt(Carbon::instance($endsAt));
    }

    public function isSubscribed()
    {
        return !! $this->stripe_active;
    }

    public function subscription()
    {
        return new Subscription($this);
    }

    public static function byStripeId($stripeId)
    {
        return static::where('stripe_id', $stripeId)->firstOrFail();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}