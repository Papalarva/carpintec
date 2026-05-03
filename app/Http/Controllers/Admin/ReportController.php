<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Ventas por mes (Corregido para usar tu Enum en lugar del JOIN)
        $salesByMonth = DB::table('orders')
            ->whereNotIn('status_id', [
                OrderStatus::CANCELLED->value,
                OrderStatus::RETURNED->value
            ])
            ->selectRaw("DATE_TRUNC('month', created_at) as month, SUM(total) as revenue, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn ($row) => [
                'month'   => Carbon::parse($row->month)->format('M Y'),
                'revenue' => $row->revenue,
                'count'   => $row->count,
            ]);

        return view('admin.reports.index', compact('salesByMonth'));
    }
}