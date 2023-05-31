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

        // activate all products from specific brand
        if ($brand->status) {
            Product::where('brand_id', $brand->id)->update(['status' => 1]);
            return;
        }

        // deactivate all products from specific brand
        Product::where('brand_id', $brand->id)->update(['status' => 0]);
    }
}
