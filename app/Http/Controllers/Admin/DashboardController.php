<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\DeliveryMan;
use App\Models\Service;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Vendor;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $params = [
            'zone_id' => $request['zone_id'] ?? 'all',
            'statistics_type' => $request['statistics_type'] ?? 'overall',
            'user_overview' => $request['user_overview'] ?? 'overall',
            'business_overview' => $request['business_overview'] ?? 'overall',
        ];
        session()->put('dash_params', $params);
        $data = self::dashboard_data();

        $total_users = User::where('role_id',2)->count();
        $total_orders = Order::All()->count();
        $total_sales = Order::whereIn('status',['ongoing','completed'])->sum('paid_amount');

        return view('admin-views.dashboard', compact('data', 'total_users', 'total_orders', 'total_sales'));
    }



    public function order_stats_calc()
    {


            $ongoing = Order::ServiceOngoing()->count();
            $completed = Order::Completed()->count();
            $cancelled = Order::Cancelled()->count();
            $refund_requested = Order::failed()->count();
            $refunded = Order::Refunded()->count();
        


        $data = [
            'ongoing' => $ongoing,
            'cancelled' => $cancelled,
            'completed' => $completed,            
            'refund_requested' => $refund_requested,
            'refunded' => $refunded
        ];
        return $data;
    }



    public function dashboard_data()
    {
  

        $data_os = self::order_stats_calc();
        $dash_data = array_merge($data_os);
        return $dash_data;
    }
}
