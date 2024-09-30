<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Resources\BrandResource;
use Symfony\Component\HttpFoundation\Response;

class BrandController extends Controller
{
    public function index()
    {
        return BrandResource::collection(Brand::all());
    }

    public function store(Request $request)
    {
        $request->merge(['api' => true]);
        $adminBrand = new AdminBrandController();
        return $adminBrand->store($request);
    }

    public function show($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            return new BrandResource($brand);
        }
        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }

    public function update(Request $request, $id)
    {
        $request->merge(['api' => true]);
        $brand = Brand::find($id);

        if ($brand) {
            $adminBrand = new AdminBrandController();
            if ($adminBrand->update($request, $id)) {
                return response()->json(['status' => 'ok']);
            }
        }

        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            $adminBrand = new AdminBrandController();
            if ($adminBrand->destroy($id)) {
                return response()->json(['status' => 'ok']);
            }
        }

        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }
}
