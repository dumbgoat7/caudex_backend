<?php

namespace App\Http\Controllers\Api;

use App\Models\Publishers;
use App\Models\Authors;
use App\Models\Books;
use App\Models\Categories;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
                $books = DB::table('books')->select('books.id', 'books.book_title', 'books.book_cover', 'books.book_publisher', 'books.book_file', 'books.book_category', 'books.book_rating', 'books.book_author', 'authors.author_name', 'publishers.publisher_name', 'categories.category_name')
                        ->join('authors', 'authors.id', '=', 'books.book_author')
                        ->join('publishers', 'publishers.id', '=', 'books.book_publisher')
                        ->join('categories', 'categories.id', '=', 'books.book_category')
                        ->get();
                return new BookResource(true, 'All Books', $books);
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
                $book = Books::get();
                return new BookResource(true, 'All Books', $book);
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
                if ($request->id != null) {
                        return $this->update($request, $request->id);
                }
                $storeBook = $request->all();
                $validator = Validator::make($request->all(), [
                        'book_title' => 'required',
                        'book_year' => 'required',
                        'book_publisher' => 'required',
                        'book_author' => 'required',
                        'book_file' => 'required|mimes:pdf|min:10.240',
                        'book_category' => 'required',
                        'book_cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);

                if ($validator->fails()) {
                        return response()->json($validator->errors(), 422);
                }
                $imageName = $request->file('book_cover')->getClientOriginalName();
                $request->book_cover->move(public_path('images'), $imageName);
                $storeBook['book_cover'] = $imageName;
                $pdfName = $request->file('book_file')->getClientOriginalName();
                $request->book_file->move(public_path('pdf'), $pdfName);
                $storeBook['book_file'] = $pdfName;

                $book = Books::create($storeBook);
                return new BookResource(true, 'Success Add Book', $book);
        }

        /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
                $book = DB::table('books')
                        ->join('authors', 'books.book_author', '=', 'authors.id')
                        ->join('categories', 'books.book_category', '=', 'categories.id')
                        ->join('publishers', 'books.book_publisher', '=', 'publishers.id')
                        ->select('books.*', 'authors.author_name', 'categories.category_name', 'publishers.publisher_name')
                        ->where('books.id', $id)
                        ->first();
                if (is_null($book)) {
                        return new BookResource(false, 'Cannot find the Book', null);
                }
                return new BookResource(true, 'Detail Book', $book);
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
                $book = Books::find($id);
                if (is_null($book)) {
                        return new BookResource(false, 'Cannot find the Book', null);
                }
                if ($request->book_title != null) {
                        $book->book_title = $request->book_title;
                }
                if ($request->book_year != null) {
                        $book->book_year = $request->book_year;
                }
                if ($request->book_publisher != null) {
                        $book->book_publisher = $request->book_publisher;
                }

                if ($request->book_author != null) {
                        $book->book_author = $request->book_author;
                }
                if ($request->book_category != null) {
                        $book->book_category = $request->book_category;
                }
                if (is_file($request->book_cover)) {
                        $imageName = $request->book_cover->getClientOriginalName();
                        $request->book_cover->move(public_path('images'), $imageName);
                        $book->book_cover = $imageName;
                }
                if (is_file($request->book_file)) {
                        $pdfName = $request->file('book_file')->getClientOriginalName();
                        $request->book_file->move(public_path('pdf'), $pdfName);
                        $book->book_file = $pdfName;
                }
                $book->save();
                return new BookResource(true, 'Success Update Book', $book);
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
                $book = Books::find($id);
                if (is_null($book)) {
                        return new BookResource(false, 'Cannot find the Book', null);
                }
                $book->delete();
                return new BookResource(true, 'Success Delete Book', $book);
        }
}
