<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_published($product_id) {
        return Review::where("product_id", "=", $product_id)->where("is_published", "=", "1")->limit(10)->get();
    }

    public function index_unpublished() {
        return Review::where("is_published", "=", "0")->simplePaginate(15);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            "text" => "required",
            "product_id" => "required|numeric|min:1",
        ]);

        $review = new Review;
        $review->product_id = $request->input("product_id");
        $review->text = $request->input("text");
        return response()->json(["status" => $review->save() ? "ok" : "error" ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        return response()->json(['status' => Review::destroy($id) ? 'ok' : 'error']);
    }

    public function publish($id) {
        //$review = Review::find($id);
        //$review->is_published = true;
        $updateData = ['is_published' => true];
        $resp = Review::where('id', '=', $id)->update($updateData);
        return response()->json(['status' => $resp ? 'ok' : 'error']);
    }
}
