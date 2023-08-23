<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WeddingListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.wedding.index', [
            'products' => auth()->user()->weddingProducts,
        ]);
    }

    public function store(Request $request, $product)
    {
        $message = __("Successfully Product Added To Wedding List");
        if ($request->user()->weddingProducts()->where('product_id', $product)->count()) {
            $message = __("Successfully Product Removed From Wedding List");
        }

        $request->user()->weddingProducts()->toggle($product);

        return response()->json([
            "success" => $message,
        ]);
    }
}
