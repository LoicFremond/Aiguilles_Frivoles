<?php

namespace App\Service;

class OrderManager
{
    public function orderCartData(array $cart): array
    {
        $orders = [];
        foreach ($cart['data'] as $product) {
            $orders[] = [$product['product']];
        }
        return $orders;
    }
}
