<?php

namespace App\Http\Controllers\Api;

use App\Models\Reads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReadResource;
use Illuminate\Support\Facades\DB;

class ReadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $read = DB::table('reads')
            ->join('users', 'reads.read_user', '=', 'users.id')
            ->join('books', 'reads.read_book', '=', 'books.id')
            ->select('reads.*', 'users.name', 'books.book_title')
            ->get();
        return new ReadResource(true, 'All Reads', $read);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $read = Reads::get();
        return new ReadResource(true, 'All Reads', $read);
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
            'read_user' => 'required',
            'read_book' => 'required',
            'read_date' => 'required',
        ], [
            'read_user.required' => 'User tidak boleh kosong',
            'read_book.required' => 'Buku tidak boleh kosong',
            'read_date.required' => 'Tanggal tidak boleh kosong',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $read = Reads::create([
            'read_user' => $request->user_id,
            'read_book' => $request->book_id,
            'read_date' => $request->read_date,
        ]);
        return new ReadResource(true, 'Success Add Read', $read);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return berdasarkan read_user
        $read = DB::table('reads')
            ->join('users', 'reads.read_user', '=', 'users.id')
            ->join('books', 'reads.read_book', '=', 'books.id')
            ->select('reads.*', 'users.user_name', 'books.book_title')
            ->where('reads.read_user', $id)
            ->get();
        if (is_null($read)) {
            return new ReadResource(false, 'Cannot find the Read Log', null);
        }
        return response()->json([
            'success' => true,
            'message' => 'Read Log',
            'data' => $read
        ], 200);
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
            'read_user' => 'required',
            'read_book' => 'required',
            'read_date' => 'required',
        ], [
            'read_user.required' => 'User tidak boleh kosong',
            'read_book.required' => 'Buku tidak boleh kosong',
            'read_date.required' => 'Tanggal tidak boleh kosong',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $read = Reads::find($id);
        if (is_null($read)) {
            return new ReadResource(false, 'Cannot find the Read Log', null);
        }
        $read->update([
            'read_user' => $request->user_id,
            'read_book' => $request->book_id,
            'read_date' => $request->read_date,
        ]);
        return new ReadResource(true, 'Success Update Read', $read);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $read = $read = Reads::find($id);
        if (is_null($read)) {
            return new ReadResource(false, 'Cannot find the Read Log', null);
        }
        $read->delete();
        return new ReadResource(true, 'Success Delete Read', $read);
    }
}
