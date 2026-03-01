<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Review;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::query()->where('role', 'user')->count(),
            'total_products' => Product::query()->count(),
            'total_orders' => Order::query()->count(),
            'revenue' => Order::query()->whereIn('status', ['paid', 'processing', 'shipped', 'done'])->sum('total'),
            'active_promotions' => Promotion::query()->where('is_active', true)->count(),
            'pending_reviews' => Review::query()->where('is_published', false)->count(),
            'unread_notifications' => UserNotification::query()->whereNull('read_at')->count(),
        ];

        $orderByStatus = Order::query()->select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status');
        $topProducts = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select('products.name', DB::raw('SUM(order_items.qty) as sold'))
            ->groupBy('products.name')
            ->orderByDesc('sold')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'orderByStatus', 'topProducts'));
    }
}
