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
            'review_user' => 'required',
            'review_book' => 'required',
            'review_date' => 'required',
            'review_rating' => 'required',
            'review_comment' => 'required',
        ], [
            'review_user.required' => 'User must be selected',
            'review_book.required' => 'Book must be selected',
            'review_date.required' => 'Date must be selected',
            'review_rating.required' => 'Rating cannot be empty',
            'review_comment.required' => 'Comment cannot be empty',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $review = Reviews::create([
            'review_user' => $request->review_user,
            'review_book' => $request->review_book,
            'review_date' => $request->review_date,
            'review_rating' => $request->review_rating,
            'review_comment' => $request->review_comment,
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
        $review = Reviews::find($id);
        if (is_null($review)) {
            return new ReviewResource(false, 'Cannot find the Review', null);
            }
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
            'review_user' => 'required',
            'review_book' => 'required',
            'review_date' => 'required',
            'review_rating' => 'required',
            'review_comment' => 'required',
        ], [
            'review_user.required' => 'User must be selected',
            'review_book.required' => 'Book must be selected',
            'review_date.required' => 'Date must be selected',
            'review_rating.required' => 'Rating cannot be empty',
            'review_comment.required' => 'Comment cannot be empty',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $review = Reviews::find($id);
        if (is_null($review)) {
            return new ReviewResource(false, 'Cannot find the Review', null);
            }
        $review->update([
            'review_user' => $request->review_user,
            'review_book' => $request->review_book,
            'review_date' => $request->review_date,
            'review_rating' => $request->review_rating,
            'review_comment' => $request->review_comment,
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
        $review = Reviews::find($id);
        if (is_null($review)) {
            return new ReviewResource(false, 'Cannot find the Review', null);
            }
        return new ReviewResource(true, 'Success Delete Review', $review);
    }
}
