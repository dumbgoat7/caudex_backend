<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription_Histories;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SubscriptionHistoriesResource;

class SubscriptionHistoriesController extends Controller
{
        //
        public function index()
        {
                $subscription_histories = Subscription_Histories::with(['User', 'Subscription'])->latest()->get();
                return new SubscriptionHistoriesResource(true, 'All Subscription Histories', $subscription_histories);
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
                $subscription_histories = Subscription_Histories::get();
                return new SubscriptionHistoriesResource(true, 'All Subscription Histories', $subscription_histories);
        }

        public function store(Request $request)
        {

                $subscription_histories = Subscription_Histories::create([
                        'subscription' => $request->subscription,
                        'subscription_start' => $request->subscription->subscription_start,
                        'subscription_expired' => $request->subscription->subscription_expired,
                ]);
                return new SubscriptionHistoriesResource(true, 'Subscription History Added', $subscription_histories);
        }

        public function show($id)
        {
                $subscription_histories = Subscription_Histories::find($id);
                return new SubscriptionHistoriesResource(true, 'Subscription History Found', $subscription_histories);
        }
}
