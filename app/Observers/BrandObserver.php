<?php

namespace App\Observers;
use App\Models\Brand;
use App\Models\Product;

class BrandObserver
{
    public function saved(Brand $brand)
    {
        // disable all products from specific brand
        if ($brand->status == 0) {
            Product::where('brand_id', $brand->id)->update(['status' => 0]);
        }
    }
}
