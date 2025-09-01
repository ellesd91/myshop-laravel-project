<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariation;

class ProductVariationController extends Controller
{
     public function store($variations, $attributeId, $product): void
    {
        if (!$attributeId || empty($variations['value'])) return;

        $count = count($variations['value']);

        for ($i = 0; $i < $count; $i++) {
            $value = $variations['value'][$i] ?? '';
            if ($value === '') continue;

            ProductVariation::create([
                'attribute_id' => $attributeId,
                'product_id'   => $product->id,
                'value'        => $value,
                'price'        => (int)($variations['price'][$i] ?? 0),
                'quantity'     => (int)($variations['quantity'][$i] ?? 0),
                'sku'          => $variations['sku'][$i] ?? null,
            ]);
        }
    }
}
