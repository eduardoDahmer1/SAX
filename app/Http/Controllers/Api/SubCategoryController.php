<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\SubCategoryController as AdminSubCategoryController;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Resources\SubCategoryResource;
use Symfony\Component\HttpFoundation\Response;

class SubCategoryController extends Controller
{
    public function index()
    {
        $sub_categories = SubCategory::all();
        return SubCategoryResource::collection($sub_categories);
    }

    public function store(Request $request)
    {
        $request->api = true;
        $adminSubCategory = new AdminSubCategoryController();
        return $adminSubCategory->store($request);
    }

    public function show($id)
    {
        $sub_category = SubCategory::find($id);
        if ($sub_category) {
            return new SubCategoryResource($sub_category);
        }
        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }

    public function update(Request $request, $id)
    {
        $request->api = true;
        $sub_category = SubCategory::find($id);
        if ($sub_category) {
            $adminSubCategory = new AdminSubCategoryController();
            $msg = $adminSubCategory->update($request, $id);
            if ($msg) {
                return response()->json(['status' => 'ok']);
            }
            return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
        }
        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }

    public function destroy($id)
    {
        $sub_category = SubCategory::find($id);
        if ($sub_category) {
            $adminSubCategory = new AdminSubCategoryController();
            $msg = $adminSubCategory->destroy($id);
            if ($msg) {
                return response()->json(['status' => 'ok']);
            }
            return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
        }
        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }
}
