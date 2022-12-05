<?php

namespace App\Http\Controllers\Api;
use App\Models\Publishers;
use App\Models\Authors;
use App\Models\Books;
use App\Models\Categories;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Books::with(['Publisher','Author','Category'])->latest()->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $book = Book::get();
        return view('book.create',compact('publisher','author','categories'));
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
            'book_title' => 'required',
            'book_year' => 'required',
            'book_publisher' => 'required',
            'book_author' => 'required',
            'book_file' => 'required',
            'book_category' => 'required',
            'book_cover' => 'required',
            
        ], [
            'book_title.required' => 'Judul Buku tidak boleh kosong',
            'book_year.required' => 'Tahun Buku tidak boleh kosong',
            'book_author.required' => 'Penulis Buku tidak boleh kosong',
            'book_file.required' => 'File Buku tidak boleh kosong',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $book = Book::create([
            'book_title' => $request->book_title,
            'book_year' => $request->book_year,
            'book_publisher' => $request->book_publisher,
            'book_author' => $request->book_author,
            'book_file' => $request->book_file,
            'book_category' => $request->book_category,
            'book_cover' => $request->book_cover,

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);
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
            'book_title' => 'required',
            'book_year' => 'required',
            'book_publisher' => 'required',
            'book_author' => 'required',
            'book_file' => 'required',
            'book_category' => 'required',
            'book_cover' => 'required',
            
        ], [
            'book_title.required' => 'Judul Buku tidak boleh kosong',
            'book_year.required' => 'Tahun Buku tidak boleh kosong',
            'book_file.required' => 'File Buku tidak boleh kosong',
        ]);
        $book = Book::find($id);
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $book->update([
            'book_title' => $request->book_title,
            'book_year' => $request->book_year,
            'book_publisher' => $request->book_publisher,
            'book_author' => $request->book_author,
            'book_file' => $request->book_file,
            'book_category' => $request->book_category,
            'book_cover' => $request->book_cover,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        $book->delete(); 
    }
}
