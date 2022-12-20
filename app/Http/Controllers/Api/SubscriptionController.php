<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscriptions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SubscriptionResource;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = DB::table('subscriptions')
            ->join('users', 'users.id', '=', 'subscriptions.subscription_user')
            ->select('subscriptions.*', 'users.user_name')
            ->get();
        return new SubscriptionResource(true, 'All Subscriptions', $subscriptions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subscriptions = Subscriptions::get();
        return new SubscriptionResource(true, 'All Subscriptions', $subscriptions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subscription_user' => 'required',
            'subscription_type' => 'required',
            'subscription_start' => 'required|date',
            'subscription_expired' => 'required|date|after:subscription_start',
            'subscription_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $subscriptions = Subscriptions::create($request->all());
        return new SubscriptionResource(true, 'Success Add Subscription', $subscriptions);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subscriptions = Subscriptions::find($id);
        if (!$subscriptions) {
            return new SubscriptionResource(false, 'Subscription not found', null);
        }
        return new SubscriptionResource(true, 'Detail Subscription', $subscriptions);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'subscription_start' => 'required|date|after:yesterday',
            'subscription_expired' => 'required|date|after:subscription_start',
            'subscription_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $subscriptions = Subscriptions::find($id);
        if (!$subscriptions) {
            return new SubscriptionResource(false, 'Subscription not found', null);
        }
        $subscriptions->update([
            'subscription_start' => $request->subscription_start,
            'subscription_expired' => $request->subscription_expired,
            'subscription_price' => $request->subscription_price,
        ]);
        return new SubscriptionResource(true, 'Success Update Subscription', $subscriptions);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subscriptions = Subscriptions::find($id);
        if (!$subscriptions) {
            return new SubscriptionResource(false, 'Subscription not found', null);
        }
        $subscriptions->delete();
        return new SubscriptionResource(true, 'Success Delete Subscription', $subscriptions);
    }
}
