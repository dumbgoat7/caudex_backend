<?php

namespace App\Http\Controllers\Api;

use App\Models\Reviews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = Reviews::with(['User', 'Book'])->latest()->get();
        return new ReviewResource(true, 'All Reviews', $reviews);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $reviews = Reviews::get();
        return new ReviewResource(true, 'All Reviews', $reviews);
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
            'user_id' => 'required',
            'book_id' => 'required',
            'review' => 'required',
        ], [
            'user_id.required' => 'User tidak boleh kosong',
            'book_id.required' => 'Buku tidak boleh kosong',
            'review.required' => 'Review tidak boleh kosong',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $review = Reviews::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'review' => $request->review,
        ]);
        return new ReviewResource(true, 'Success Add Review', $review);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $review = Reviews::with(['User', 'Book'])->where('id', $id)->first();
        return new ReviewResource(true, 'Detail Review', $review);
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
            'user_id' => 'required',
            'book_id' => 'required',
            'review' => 'required',
        ], [
            'user_id.required' => 'User tidak boleh kosong',
            'book_id.required' => 'Buku tidak boleh kosong',
            'review.required' => 'Review tidak boleh kosong',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $review = Reviews::where('id', $id)->update([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'review' => $request->review,
        ]);
        return new ReviewResource(true, 'Success Update Review', $review);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Reviews::where('id', $id)->delete();
        return new ReviewResource(true, 'Success Delete Review', $review);
    }
}
