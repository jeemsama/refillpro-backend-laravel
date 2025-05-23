<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Customer places a new order.
     */
public function store(Request $request)
{
    $data = $request->validate([
        'customer_id'     => 'required|exists:customers,id',
        // 'shop_name'       => 'required|string',
        'shop_id'         => 'required|exists:owner_shop_details,id',
        'ordered_by'      => 'required|string',
        'phone'           => 'required|string',
        'time_slot'       => 'required|string',
        'message'         => 'nullable|string',
        'regular_count'   => 'required|integer|min:0',
        'dispenser_count' => 'required|integer|min:0',
        'borrow'          => 'required|boolean',
        'swap'            => 'required|boolean',
        'total'           => 'required|numeric',
    ]);

    $order = Order::create([
        'customer_id'     => $data['customer_id'],
        // 'shop_name'       => $data['shop_name'],
        'shop_id'         => $data['shop_id'],
        'ordered_by'      => $data['ordered_by'],
        'phone'           => $data['phone'],
        'time_slot'       => $data['time_slot'],
        'message'         => $data['message'] ?? null,
        'regular_count'   => $data['regular_count'],
        'dispenser_count' => $data['dispenser_count'],
        'borrow'          => $data['borrow'],
        'swap'            => $data['swap'],
        'total'           => $data['total'],
        'status'          => 'pending',
    ]);

    return response()->json(['data' => $order], 201);
}


    /**
     * Customer’s own order list (protected by sanctum).
     */
    public function index(Request $request)
    {
        $user   = $request->user();
        $orders = Order::with('shopDetails')
                       ->where('customer_id', $user->id)
                       ->orderByDesc('created_at')
                       ->get()
                       ->map(fn(Order $o): array => $this->formatOrderPayload($o));

        return response()->json(['data' => $orders], 200);
    }

    /**
     * All orders for a given customer (public).
     */
    public function getOrdersByCustomers(Request $request)
    {
        $customerId = $request->query('customer_id');
        $orders = Order::with('shopDetails')
                       ->where('customer_id', $customerId)
                       ->orderByDesc('created_at')
                       ->get()
                       ->map(fn(Order $o): array => $this->formatOrderPayload($o));

        return response()->json(['data' => $orders], 200);
    }

    /**
     * All orders for a shop owner.
     */
    public function getOrdersByOwner(Request $request)
    {
        $ownerId = $request->query('owner_id');
        $orders = Order::with('shopDetails')
                       ->whereHas('shopDetails', fn($q) => $q->where('owner_id', $ownerId))
                       ->orderByDesc('created_at')
                       ->get()
                       ->map(fn(Order $o): array => $this->formatOrderPayload($o));

        return response()->json(['data' => $orders], 200);
    }

    /**
     * Customer cancels their own order.
     */
    public function cancel(Request $request, $id)
    {
        $data = $request->validate([
            'reason' => 'required|string',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status'        => 'cancelled',
            'cancel_reason' => $data['reason'],
        ]);

        return response()->json(['data' => $this->formatOrderPayload($order->refresh())], 200);
    }

    /**
     * Owner approves and assigns a rider.
     */
    public function accept(Request $request, $id)
    {
        $data = $request->validate([
            'rider_id' => 'required|exists:riders,id',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status'   => 'accepted',
            'rider_id' => $data['rider_id'],
        ]);

        return response()->json(['data' => $this->formatOrderPayload($order->refresh())], 200);
    }

    /**
     * Owner declines an order.
     */
    public function decline(Request $request, $id)
    {
        $data = $request->validate([
            'reason' => 'required|string',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status'         => 'declined',
            'decline_reason' => $data['reason'],
        ]);

        return response()->json(['data' => $this->formatOrderPayload($order->refresh())], 200);
    }

    /**
     * Format a single Order into the exact JSON shape Flutter expects.
     */
    protected function formatOrderPayload(Order $order): array
    {
        return [
            'id'               => $order->id,
            'shop_id'          => $order->shop_id,
            'customer_id'      => $order->customer_id,
            // 'shop_name'        => $order->shopDetails->shop_name,
            'owner_name'       => $order->shopDetails->owner_name,
            'ordered_by'       => $order->ordered_by,
            'phone'            => $order->phone,
            'time_slot'        => $order->time_slot,
            'message'          => $order->message,
            'regular_count'    => $order->regular_count,
            'dispenser_count'  => $order->dispenser_count,
            'borrow'           => (bool)$order->borrow,
            'swap'             => (bool)$order->swap,
            'total'            => $order->total,
            'created_at'       => $order->created_at->toDateTimeString(),
            'status'           => $order->status,
            'cancel_reason'    => $order->cancel_reason,
            'decline_reason'   => $order->decline_reason,
            'rider_id'         => $order->rider_id,
        ];
    }

    public function destroy(Request $request, $id)
  {
      // Only allow the owner of the order (the customer) to delete it:
      $order = Order::where('id', $id)
                    ->where('customer_id', $request->user()->id)
                    ->firstOrFail();
  
      $order->delete();
  
      return response()->json(null, 204);
  }

}
