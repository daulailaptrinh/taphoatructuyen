<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldCart)
    {
        if ($oldCart) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
    }

    public function add($item, $id, $qty)
    {
        $Qty = $qty > 0 ? $qty : 1;
        $giohang = ['qty' => $Qty, 'price' => 0, 'price2' => 0, 'unit_price' => $item->unit_price, 'promotion_price' => $item->promotion_price, 'item' => $item];
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {
                $giohang = $this->items[$id];
                $giohang['qty'] += $Qty;
            }

        }
        // $giohang['qty']++;
        if ($item->promotion_price == 0) {
            $unit_or_promotion_price = $item->unit_price;
        } else {
            $unit_or_promotion_price = $item->promotion_price;
        }
        $giohang['price'] = $unit_or_promotion_price * $giohang['qty'];
        $giohang['price2'] = $unit_or_promotion_price;
        // dd($giohang['price']);
        $this->items[$id] = $giohang;
        $total = 0;
        foreach ($this->items as $value) {
            $total += ($value['price2'] * $value['qty']);
        }
        $this->totalPrice = $total;
        $this->totalQty = count($this->items);
    }

    //xóa 1
    public function reduceByOne($id)
    {
        $this->items[$id]['qty']--;
        $this->items[$id]['price'] -= $this->items[$id]['item']['price'];
        $this->totalQty--;
        $this->totalPrice -= $this->items[$id]['item']['price'];
        if ($this->items[$id]['qty'] <= 0) {
            unset($this->items[$id]);
        }
    }
    //xóa nhiều
    public function removeItem($id)
    {
        $this->totalQty -= $this->items[$id]['qty'];
        $this->totalPrice -= $this->items[$id]['price'];
        unset($this->items[$id]);
    }
}