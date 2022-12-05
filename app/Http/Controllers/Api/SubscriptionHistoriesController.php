<?php

namespace App\Http\Controllers\Api;
use App\Models\SubscriptionHistories;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionHistoriesController extends Controller
{
    //
    public function index()
    {
        $subscription_histories = SubscriptionHistories::with(['User','Subscription'])->latest()->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subscription_histories = SubscriptionHistories::get();
    }
}
