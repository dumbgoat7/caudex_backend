<?php

namespace App\Http\Controllers\Api;

use App\Models\Reviews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReviewResource;
use Illuminate\Support\Facades\DB;
use App\Models\Books;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = DB::table('reviews')
            ->join('users', 'reviews.reviews_user', '=', 'users.id')
            ->join('books', 'reviews.reviews_book', '=', 'books.id')
            ->select('reviews.*', 'users.user_name', 'books.book_title')
            ->get();
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
            'reviews_user' => 'required',
            'reviews_book' => 'required',
            'reviews_date' => 'required',
            'reviews_rating' => 'required',
            'reviews_comment' => 'required',
        ], [
            'reviews_user.required' => 'User must be selected',
            'reviews_book.required' => 'Book must be selected',
            'reviews_date.required' => 'Date must be selected',
            'reviews_rating.required' => 'Rating cannot be empty',
            'reviews_comment.required' => 'Comment cannot be empty',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $input = $request->all();
        $review = Reviews::create($input);
        //jalankan method public function show($id)
        return $this->show($review->reviews_book);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //cari data reviews_rating berdasarkan id
        $review = DB::table('reviews')
            ->join('users', 'reviews.reviews_user', '=', 'users.id')
            ->join('books', 'reviews.reviews_book', '=', 'books.id')
            ->select('reviews.*', 'users.user_name', 'books.book_title')
            ->where('reviews.reviews_book', $id)
            ->get();
        //cari rata-rata reviews_rating
        if (count($review) > 0) {
            $rating = DB::table('reviews')
                ->select(DB::raw('AVG(reviews_rating) as rating'))
                ->where('reviews_book', $review[0]->reviews_book)
                ->get();
            $books = Books::find($id);
            $books->book_rating = $rating[0]->rating;
            $books->save();
            return response()->json([
                'success' => true,
                'message' => 'Review Detail',
                'data' => $review,
                'rating' => $rating,
            ], 200);
        } else {
            $rating = DB::table('reviews')
                ->select(DB::raw('AVG(reviews_rating) as rating'))
                ->where('reviews_book', $id)
                ->get();
            $rating[0]->rating = 0;
            $books = Books::find($id);
            $books->book_rating = 0;
            $books->save();
            return response()->json([
                'success' => true,
                'message' => 'Review Detail',
                'data' => $review,
                'rating' => $rating,
            ], 200);
        }
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
