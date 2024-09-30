<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    public function store(Request $request)
    {
        $request->merge(['api' => true]);
        $adminCategory = new AdminCategoryController();
        return $adminCategory->store($request);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            return new CategoryResource($category);
        }
        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }

    public function update(Request $request, $id)
    {
        $request->merge(['api' => true]);
        $category = Category::find($id);

        if ($category) {
            $adminCategory = new AdminCategoryController();
            if ($adminCategory->update($request, $id)) {
                return response()->json(['status' => 'ok']);
            }
        }

        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            $adminCategory = new AdminCategoryController();
            if ($adminCategory->destroy($id)) {
                return response()->json(['status' => 'ok']);
            }
        }

        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }
}
