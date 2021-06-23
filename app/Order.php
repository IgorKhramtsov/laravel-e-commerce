<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

define('ORDER_OPENED', 1 << 0);
define('ORDER_CANCELED', 1 << 1);
define('ORDER_PAID', 1 << 2);
define('ORDER_SHIPMENT_PACKED', 1 << 3);
define('ORDER_SHIPMENT_SHIPPED', 1 << 4);
define('ODER_SHIPMENT_DELIVERED', 1 << 5);

class Order extends Model
{
    public function items()
    {
        return $this->hasMany('App\OrderItem');
    }
}
