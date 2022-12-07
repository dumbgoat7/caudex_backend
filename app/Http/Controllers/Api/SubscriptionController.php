<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscriptions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SubscriptionResource;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = Subscriptions::with(['User'])->latest()->get();
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
        ], [
            'subscription_user.required' => 'User must be filled',
            'subscription_type.required' => 'Subscription type must be filled',
            'subscription_expired.after' => 'Expired date must be a date after start date',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $subscriptions = Subscriptions::create([
            'subscription_user' => $request->user_id,
            'subscription_type' => $request->subscription_type,
            'subscription_start' => $request->subscription_start,
            'subscription_expired' => $request->subscription_expired,
            'subscription_price' => $request->subscription_price,
        ]);
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
            'subscription_user' => 'required',
            'subscription_type' => 'required',
            'subscription_start' => 'required|date',
            'subscription_expired' => 'required|date|after:subscription_start',
            'subscription_price' => 'required|numeric',
        ], [
            'subscription_user.required' => 'User must be filled',
            'subscription_type.required' => 'Subscription type must be filled',
            'subscription_expired.after' => 'Expired date must be a date after start date',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $subscriptions = Subscriptions::find($id)->update([
            'subscription_user' => $request->user_id,
            'subscription_type' => $request->subscription_type,
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
        $subscriptions = Subscriptions::find($id)->delete();
        return new SubscriptionResource(true, 'Success Delete Subscription', $subscriptions);
    }
}
