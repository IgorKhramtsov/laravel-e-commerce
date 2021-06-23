<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use Illuminate\Http\Request;


class OrderController extends Controller {
    public function index(Request $request) {
        $sort_by = "name";
        $sort_type = "ASC";
        $per_page = 5;
        if ($request->has("per_page"))
            $per_page = $request->input("per_page");
        if ($request->has("sort_type"))
            if (strtoupper($request->input("sort_type")) === "DESC")
                $sort_type = "DESC";
        if ($request->has("sort_by")) {
            switch ($request->input("sort_by")) {
                case "created_at":
                    $sort_by = "created_at";
                    break;
                case "total":
                    $sort_by = "total";
                    break;
                default:
                    $sort_by = "status";
            }
        }
        $products = Order::orderBy($sort_by, $sort_type);
        $paginator = $products->simplePaginate(min($per_page, 30));

        $paginator_data = $paginator->groupBy(function ($item, $key) {
            $item->time = $item->created_at->format("H:i");
            return $item->created_at->format("M d, Y");
        })->toArray();
        $paginator = $paginator->toArray();
        $paginator["data"] = $paginator_data;

        return response()->json([$paginator, 'sort_by' => $sort_by, 'sort_type' => $sort_type]);
    }

    public function get_items(Request $request, $id) {
        return Order::find($id)->items()->with("product")->get();
    }

    public function success(Request $request) {
        $order_id = $request->session()->get('WMI')["order_id"];
        $request->session()->forget('WMI');
        $request->session()->forget('data');

        $order = Order::find($order_id);
        $order->status_code |= ORDER_PAID;
        $order->save();

        return redirect(route("products"));
    }
}
