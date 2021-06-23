<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CartController extends Controller {

    public function index() {
        return view("order.cart");
    }

    public function api_index(Request $request) {

        if($request->session()->has('WMI'))
        {
            $data = session()->get('data');
            return response()->json(["WMI" => session()->get("WMI"),
                "data" => $data,
                "count" => count($data)]);
        }

        $items = $request->session()->get("cart", new Collection());
        $info = $request->session()->get("info", new Collection());

        return response()->json([
            "data" => $items,
            "info" => $info,
            "count" => count($items),
        ]);
    }

    public function add(Request $request) {
        $request->validate([
            "id" => "required",
            "quantity" => "required",
        ]);

        $product = Product::find($request->get("id"));
        if (!isset($product))
            return;

        $id = $product->id;
        $price = $product->price;
        $quantity = $request->get("quantity", 1);
        $title = $product->name;
        $image_url = $product->image_url;

        // Если корзина существует, берем. Иначе создаем.
        $array = session()->get("cart", new Collection());

        // Если уже есть в корзине, берем. Иначе создаем.
        $item = $array->get($id, collect(["price" => $price,
            "quantity" => 0,
            "title" => $title,
            "image_url" => $image_url,
        ]));
        $item["quantity"] += $quantity;
        $array->put($id, $item);

        session()->put("cart", $array);
        return response()->json(['status' => 'ok', 'data' => $array, 'count' => count($array), 'info' => []]);
    }

    public function api_update(Request $request) {
        $request->validate([
            "id" => "required",
            "quantity" => "required",
        ]);

        $id = $request->input("id");
        $quantity = $request->input("quantity");

        $array = session()->get("cart", new Collection());
        $item = $array->get($id);
        // TODO: Проверять наличие нужного количества товара
        $item["quantity"] = max(1, $quantity); // Больше нуля
        $array->put($id, $item);

        session()->put("cart", $array);
        return response()->json([
            'status' => 'ok',
            "data" => $array,
            "count" => count($array),
        ]);
    }

    public function remove(Request $request) {
        $request->validate([
            "id" => "required",
        ]);

        $id = $request->input("id");

        $array = session()->get("cart", new Collection());
        $array->forget($id);
        session()->put("cart", $array);
        return response()->json([
            'status' => 'ok',
            "data" => $array,
            "count" => count($array),
        ]);
    }

    public function create_order(Request $request) {
        $request->validate([
            "order.contact.email" => "required|email",
            "order.contact.fname" => "required",
            "order.contact.lname" => "required",
            "order.contact.phone" => "required",

            "order.address.country" => "required",
            "order.address.city" => "required",
            "order.address.zip" => "required|numeric",
            "order.address.address" => "required",
            "order.address.home" => "required",

            "order.payment_method" => "required",
            "order.shipment_method" => "required",
        ]);

        $array = session()->get("cart");
        if (empty($array))
            return;

        $order = new Order();
        $order->first_name = $request->input("order.contact.fname");
        $order->last_name = $request->input("order.contact.lname");
        $order->middle_name = $request->input("order.contact.mname");
        $order->phone = $request->input("order.contact.phone");
        $order->email = $request->input("order.contact.email");

        $order->country = $request->input("order.address.country");
        $order->city = $request->input("order.address.city");
        $order->zip = $request->input("order.address.zip");
        $order->address = $request->input("order.address.address");
        $order->home = $request->input("order.address.home");

        $order->payment_method = $request->input("order.payment_method");
        $order->shipment_method = $request->input("order.shipment_method");
        $order->status_code = ORDER_OPENED;

        $order->comment = $request->input("order.comment");

        $items = new Collection();
        $total = 0;

        foreach ($array as $id => $item) {
            $order_item = new OrderItem();
            $order_item->title = $item["title"];
            $order_item->quantity = $item["quantity"];
            $order_item->price = $item["price"];
            $order_item->product_id = $id;
            $total += $order_item->quantity * $order_item->price;

            $items->push($order_item);
        }

        session()->forget("cart");
        $order->total = $total;
        $order->status = "pay_awaiting";
        $order->save();

        $status = $order->items()->saveMany($items) ? 'ok' : 'error';
        $data = $array;
        $WMI = [];
        $WMI["WMI_PAYMENT_AMOUNT"] = $total;
        $WMI["WMI_MERCHANT_ID"] = '146_this_is_wrong_merchant_id_15805';
        $WMI["WMI_CURRENCY_ID"] = '643';
        $WMI["WMI_DESCRIPTION"] = 'BASE64:'.base64_encode('Оплата заказа #'.$order->id);
        $WMI["WMI_SUCCESS_URL"] = route('order_success');
        $WMI["WMI_FAIL_URL"] = route('cart');
        $WMI["order_id"] = $order->id;


        $vals = '';
        $key = '553264715756__this_is_wrong_key5c4b485b453547331325b';
        uksort($WMI, "strcasecmp");
        foreach ($WMI as $name => $field) {
            $vals .= iconv('utf-8', 'windows-1251', strval($field));
        }
        $signature = base64_encode(pack("H*", md5($vals . $key)));

        $WMI["WMI_SIGNATURE"] = $signature;

        session()->put('WMI', $WMI);
        session()->put('data', $data);

        return response()->json(['status' => $status, "data" => $data, "WMI" => $WMI, "count" => count($data)]);
    }

    public function save_order(Request $request) {
        $request->validate([
            "order" => "required",
            "order.contact.email" => "email|nullable",
            "order.address.zip" => "numeric|nullable",
        ]);

        session()->put("info", $request->input("order"));
    }

    public function payment_show() {

        $data = [];
        $data["total"] = session()->get('cart')["total"];

        return view('cart.payment', $data);
    }
}
