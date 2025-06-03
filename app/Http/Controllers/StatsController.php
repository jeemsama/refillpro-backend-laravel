<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatsController extends Controller
{
    /**
     * Return per‐month aggregation of earnings and order‐counts
     * for the authenticated owner’s shop.
     *
     * GET /api/owner/stats?year=2025
     */
    public function monthlyStats(Request $request)
    {
        // 1) Fetch the authenticated owner via the "sanctum" guard
        /** @var \App\Models\RefillingStationOwner|null $owner */
        $owner = Auth::guard('sanctum')->user();

        if (! $owner) {
            return response()->json([
                'message' => 'Unauthenticated or invalid token.'
            ], 401);
        }

        // 2) Use the owner's own ID as the shop_id
        $shopId = $owner->id;
        Log::debug("StatsController::monthlyStats → Authenticated owner ID={$owner->id}, using shop_id={$shopId}");

        // 3) Validate 'year' parameter
        $year = $request->query('year');
        if (! $year || ! preg_match('/^\d{4}$/', $year)) {
            return response()->json([
                'message' => 'Invalid or missing year parameter.'
            ], 422);
        }

        // 4) Run the MySQL query you tested manually:
        $results = DB::table('orders')
            ->select(
                DB::raw('MONTH(created_at) AS month'),
                DB::raw('SUM(total) AS earnings'),
                DB::raw('COUNT(*) AS orders_count')
            )
            ->where('shop_id', $shopId)
            ->whereRaw("LOWER(status) = 'completed'")
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        Log::debug("StatsController::monthlyStats → Raw DB results for year {$year}: " . $results->toJson());

        // 5) Build a default January→December array, all zeros
        $monthly = [];
        for ($m = 1; $m <= 12; ++$m) {
            $monthly[$m] = [
                'earnings' => 0.00,
                'orders'   => 0,
            ];
        }

        // 6) Overwrite any month that actually has data
        foreach ($results as $row) {
            $mon = intval($row->month);
            $monthly[$mon] = [
                'earnings' => floatval($row->earnings),
                'orders'   => intval($row->orders_count),
            ];
        }

        // 7) Return the payload
        return response()->json([
            'year'    => intval($year),
            'monthly' => $monthly,
        ]);
    }
}
