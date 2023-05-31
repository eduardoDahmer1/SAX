<?php

namespace App\Observers;

use App\Models\Brand;
use App\Models\Product;

class BrandObserver
{
    /**
     * Handle the Brand "saved" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function saved(Brand $brand)
    {
        // enable all products from specific brand
        if ($brand->status) {
            Product::where('brand_id', $brand->id)->update(['status' => 1]);
            return;
        }

        // disable all products from specific brand
        Product::where('brand_id', $brand->id)->update(['status' => 0]);
    }
}
