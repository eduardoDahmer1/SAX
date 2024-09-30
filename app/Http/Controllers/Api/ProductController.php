<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return ProductResource::collection(
            Product::when($request->query('q'), function ($query, $search) {
                $query->whereHas('translations', function (Builder $query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
            })->paginate()
        );
    }

    public function store(Request $request)
    {
        $request->merge(['api' => true, 'type' => 'Physical']);
        $adminProduct = new AdminProductController();
        return $adminProduct->store($request);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if ($product) {
            return new ProductResource($product);
        }
        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }

    public function update(Request $request, $id)
    {
        $request->merge(['api' => true, 'type' => 'Physical']);
        $adminProduct = new AdminProductController();
        return $adminProduct->update($request, $id);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product) {
            $adminProduct = new AdminProductController();
            if ($adminProduct->destroy($id)) {
                return response()->json(['status' => 'ok']);
            }
        }
        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }
}
