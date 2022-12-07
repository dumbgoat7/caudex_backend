<?php

namespace App\Http\Controllers\Api;

use App\Models\Authors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AuthorResource;
use App\Http\Controllers\Controller;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $author = Authors::latest()->get();
        return new AuthorResource(true, 'All Authors', $author);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $author = Authors::get();
        return new AuthorResource(true, 'All Authors', $author);
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
            'author_name' => 'required',
        ], [
            'author_name.required' => 'Author name must be filled',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $author = Authors::create([
            'author_name' => $request->author_name,
        ]);
        return new AuthorResource(true, 'Success Add Author', $author);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $author = Authors::find($id);
        if ($author) {
            return new AuthorResource(true, 'Author Found', $author);
        } else {
            return new AuthorResource(false, 'Cannot find the Author', null);
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
            'author_name' => 'required',
        ], [
            'author_name.required' => 'Author name must be filled',
        ]);
        $author = Authors::find($id);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $author->update([
            'author_name' => $request->author_name,
        ]);
        return new AuthorResource(true, 'Success Update Author', $author);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $author = Authors::find($id);
        $author->delete();
        return new AuthorResource(true, 'Success Delete Author', null);
    }
}
