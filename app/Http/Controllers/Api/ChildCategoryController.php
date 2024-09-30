<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\ChildCategoryController as AdminChildCategoryController;
use App\Http\Controllers\Controller;
use App\Models\ChildCategory;
use Illuminate\Http\Request;
use App\Http\Resources\ChildCategoryResource;
use Symfony\Component\HttpFoundation\Response;

class ChildCategoryController extends Controller
{
    public function index()
    {
        return ChildCategoryResource::collection(ChildCategory::all());
    }

    public function store(Request $request)
    {
        $request->merge(['api' => true]);
        $adminChildCategory = new AdminChildCategoryController();
        return $adminChildCategory->store($request);
    }

    public function show($id)
    {
        $childCategory = ChildCategory::find($id);
        if ($childCategory) {
            return new ChildCategoryResource($childCategory);
        }
        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }

    public function update(Request $request, $id)
    {
        $request->merge(['api' => true]);
        $childCategory = ChildCategory::find($id);

        if ($childCategory) {
            $adminChildCategory = new AdminChildCategoryController();
            if ($adminChildCategory->update($request, $id)) {
                return response()->json(['status' => 'ok']);
            }
        }

        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }

    public function destroy($id)
    {
        $childCategory = ChildCategory::find($id);
        if ($childCategory) {
            $adminChildCategory = new AdminChildCategoryController();
            if ($adminChildCategory->destroy($id)) {
                return response()->json(['status' => 'ok']);
            }
        }

        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }
}
