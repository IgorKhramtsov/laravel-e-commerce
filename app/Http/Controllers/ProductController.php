<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $sort_by = "name";
        $sort_type = "ASC";
        $per_page = 16;
        if ($request->has("per_page"))
            $per_page = $request->input("per_page");
        if ($request->has("sort_type"))
            if (strtoupper($request->input("sort_type")) === "DESC")
                $sort_type = "DESC";
        if ($request->has("sort_by")) {
            switch ($request->input("sort_by")) {
                case "price":
                    $sort_by = "price";
                    break;
                case "reviews_count":
                    $sort_by = "reviews_count";
                    break;
                default:
                    $sort_by = "name";
            }
        }
        $products = Product::withCount('reviews')->orderBy($sort_by, $sort_type);
        $paginator = $products->simplePaginate(min($per_page, 60));
        $paginator = $paginator->toArray();
        $data =  [$paginator, 'sort_by' => $sort_by, 'sort_type' => $sort_type];

        return view('products', ['data' => $data]);

        //return view('products');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            "name" => "required",
            "price" => "required|numeric|min:1",
            "description" => "required",
            "image" => "required|image|mimes:jpeg,jpg,png",
        ]);

        $product = new Product;
        $product->price = $request["price"];
        $product->name = $request["name"];
        $product->description = $request["description"];
        $product->image_url = Storage::url($request->file('image')->store('products', 'public'));

        return response()->json(['status' => $product->save() ? "ok" : "error"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product) {

        return view('product', ["product" => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $request->validate([
            "name" => "required",
            "price" => "required|numeric|min:1",
            "description" => "required",
        ]);

        $updateData = ['name' => $request->get('name'),
            'description' => $request->get('description'),
            'price' => $request->get('price'),
        ];
        if ($request->hasFile('image')) {
            $request->validate([
                "image" => "image|mimes:jpeg,jpg,png",
            ]);
            $updateData['image_url'] = Storage::url($request->file('image')->store('products', 'public'));
        }

        if ($id > 0) {
            $product = Product::where('id', '=', $id)->update($updateData);
            return response()->json(['status' => $product ? 'ok' : 'error']);
        }
        return response()->json(['status' => 'error', 'message' => 'Некорректный товар']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product) {
        //
    }

    public function json_index(Request $request) {
        $sort_by = "name";
        $sort_type = "ASC";
        $per_page = 16;
        if ($request->has("per_page"))
            $per_page = $request->input("per_page");
        if ($request->has("sort_type"))
            if (strtoupper($request->input("sort_type")) === "DESC")
                $sort_type = "DESC";
        if ($request->has("sort_by")) {
            switch ($request->input("sort_by")) {
                case "price":
                    $sort_by = "price";
                    break;
                case "reviews_count":
                    $sort_by = "reviews_count";
                    break;
                default:
                    $sort_by = "name";
            }
        }
        $products = Product::withCount('reviews')->orderBy($sort_by, $sort_type);
        $paginator = $products->simplePaginate(min($per_page, 60));
        $paginator = $paginator->toArray();

        return response()->json([$paginator, 'sort_by' => $sort_by, 'sort_type' => $sort_type]);
    }
}
