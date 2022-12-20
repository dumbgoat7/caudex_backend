<?php

namespace App\Http\Controllers\Api;

use App\Models\Publishers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PublisherResource;
use Illuminate\Support\Facades\DB;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $publisher = DB::table('publishers')
            ->select(DB::raw('publisher_name as text'), DB::raw('id as value'))
            ->get();
        return new PublisherResource(true, 'All Publishers', $publisher);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $publisher = Publishers::get();
        return new PublisherResource(true, 'All Publishers', $publisher);
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
            'publisher_name' => 'required',
            'publisher_country' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $publisher = Publishers::create($request->all());
        return new PublisherResource(true, 'Success Add Publisher', $publisher);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $publisher = Publishers::find($id);
        if (is_null($publisher)) {
            return new PublisherResource(false, 'Cannot find the Publisher', null);
        }
        return new PublisherResource(true, 'Detail Publisher', $publisher);
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
        $publish = Publishers::find($id);
        $validator = Validator::make($request->all(), [
            'publisher_name' => 'required',
            'publisher_country' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $publisher = Publishers::find($id);
        if (is_null($publisher)) {
            return new PublisherResource(false, 'Cannot find the Publisher', null);
        }
        $publisher->update($publish);
        return new PublisherResource(true, 'Success Update Publisher', $publisher);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $publisher = Publishers::find($id);
        if (is_null($publisher)) {
            return new PublisherResource(false, 'Cannot find the Publisher', null);
        }
        $publisher->delete();
        return new PublisherResource(true, 'Success Delete Publisher', $publisher);
    }
}
