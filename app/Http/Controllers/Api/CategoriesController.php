<?php

namespace App\Http\Controllers\Api;

use App\Models\Categories;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoriesResource;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = DB::table('categories')
            ->select(DB::raw('category_name as text'), DB::raw('id as value'))
            ->get();
        return new CategoriesResource(true, 'All Categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Categories::get();
        return new CategoriesResource(true, 'All Categories', $categories);
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
            'category_name' => 'required',
        ], [
            'category_name.required' => 'Nama Kategori tidak boleh kosong',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $categories = Categories::create([
            'category_name' => $request->category_name,
        ]);
        return new CategoriesResource(true, 'Success Add Categories', $categories);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categories = Categories::find($id);
        if (is_null($categories)) {
            return new CategoriesResource(false, 'Cannot find the Category', null);
        }
        return new CategoriesResource(true, 'Detail Categories', $categories);
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
            'category_name' => 'required',
        ], [
            'category_name.required' => 'Category name must be filled',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $categories = Categories::find($id);
        if (is_null($categories)) {
            return new CategoriesResource(false, 'Cannot find the Category', null);
        }
        $categories->update([
            'category_name' => $request->category_name,
        ]);
        return new CategoriesResource(true, 'Success Update Categories', $categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categories = Categories::find($id);
        if (is_null($categories)) {
            return new CategoriesResource(false, 'Cannot find the Category', null);
        }
        $categories->delete();
        return new CategoriesResource(true, 'Success Delete Categories', $categories);
    }
}
