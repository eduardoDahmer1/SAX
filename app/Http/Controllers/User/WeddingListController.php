<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
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

    /**
     * Display a resource.
     */
    public function show(User $user)
    {
        return view('front.wedding.show', [
            'products' => $user->weddingProducts,
            'owner' => $user,
        ]);
    }

    public function buyProduct(User $user, $product_id)
    {
        $user->weddingProducts()->updateExistingPivot($product_id, [
            'buyer_id' => auth()->user()->id,
            'buyed_at' => now(),
        ]);

        return redirect()->route('product.cart.quickadd', $product_id);
    }
}
