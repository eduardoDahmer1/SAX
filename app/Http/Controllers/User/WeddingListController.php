<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WeddingProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WeddingListController extends Controller
{
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
        $this->authorize('view', [WeddingProduct::class, $user]);
        return view('front.wedding.show', [
            'products' => $user->weddingProducts,
            'owner' => $user,
        ]);
    }

    public function buyProduct(User $user, $product_id)
    {
        $this->authorize('buy', [WeddingProduct::class, $user]);

        $data = [
            'id' => $user->weddingProducts()->where('product_id', $product_id)->first()->pivot->id,
            'product_id' => $user->weddingProducts()->where('product_id', $product_id)->first()->id,
        ];

        if (!in_array($data, session('weddings') ?? [])) {
            session([
                'weddings' => array_merge(
                    (session('weddings') ?? []),
                    [$data]
                )
            ]);
        }

        return redirect()->route('product.cart.redirect.wedding', [$user->id, $product_id]);
    }

    public function privacy()
    {
        auth()->user()->is_wedding = !auth()->user()->is_wedding;
        auth()->user()->save();

        return response()->json([
            "success" => __('Success')
        ]);
    }
}
