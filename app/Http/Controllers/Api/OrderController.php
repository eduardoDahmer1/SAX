<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $ordersQuery = Order::query();

        if ($request->has('date')) {
            $date = $request->input('date');
            $ordersQuery->where(function ($query) use ($date) {
                $query->where('created_at', '>=', $date)
                      ->orWhere('updated_at', '>=', $date);
            });
        }

        $orders = $ordersQuery->get();

        return OrderResource::collection($orders);
    }

    public function store(Request $request)
    {
        return response()->json(['errors' => ['Not found']], Response::HTTP_NOT_FOUND);
    }

    public function show($id)
    {
        $order = Order::find($id);

        if ($order) {
            return new OrderResource($order);
        }

        return response()->json(['errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['errors' => ['Not found']], Response::HTTP_NOT_FOUND);
    }

    public function destroy($id)
    {
        return response()->json(['errors' => ['Not found']], Response::HTTP_NOT_FOUND);
    }
}
