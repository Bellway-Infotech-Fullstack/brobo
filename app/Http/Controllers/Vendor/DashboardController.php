<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $params = [
            'statistics_type' => $request['statistics_type'] ?? 'today'
        ];
        session()->put('dash_params', $params);

        $data = self::dashboard_order_stats_data();
        $earning = [];
        $commission = [];
        $from = Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');
        $restaurant_earnings = OrderTransaction::where(['vendor_id' => Helpers::get_vendor_id()])->select(
            DB::raw('IFNULL(sum(restaurant_amount),0) as earning'),
            DB::raw('IFNULL(sum(admin_commission),0) as commission'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; $inc++) {
            $earning[$inc] = 0;
            $commission[$inc] = 0;
            foreach ($restaurant_earnings as $match) {
                if ($match['month'] == $inc) {
                    $earning[$inc] = $match['earning'];
                    $commission[$inc] = $match['commission'];
                }
            }
        }


        $service_ids = Service::where(['vendor_id' => Helpers::get_restaurant_id()])->pluck('id')->toArray();
        $top_sell = OrderDetail::with(['service'])->whereIn('service_id', $service_ids)
            ->select('service_id', DB::raw('COUNT(service_id) as count'))
            ->groupBy('service_id')
            ->orderBy("count", 'desc')
            ->take(6)
            ->get();

        $most_rated_foods = Service::rightJoin('reviews', 'reviews.service_id', '=', 'services.id')
            ->whereIn('service_id', $service_ids)
            ->groupBy('service_id')
            ->select(['service_id',
                DB::raw('AVG(reviews.rating) as ratings_average'),
                DB::raw('count(*) as total')
            ])
            ->orderBy('total', 'desc')
            ->take(6)
            ->get();
        $data['top_sell'] = $top_sell;
        $data['most_rated_foods'] = $most_rated_foods;

        return view('vendor-views.dashboard', compact('data', 'earning', 'commission', 'params'));
    }

    public function restaurant_data()
    {
    
        $new_pending_order = DB::table('orders')->where(['checked' => 0])->where('vendor_id', Helpers::get_restaurant_id())->where('order_status','pending');

        // if(config('order_confirmation_model') != 'vendor')
        // {
        //     $new_pending_order = $new_pending_order->where('order_type', 'take_away');
        // }
        $new_pending_order = $new_pending_order->count();
        $new_confirmed_order = DB::table('orders')->where(['checked' => 0])->where('vendor_id', Helpers::get_restaurant_id())->whereIn('order_status',['confirmed', 'accepted'])->whereNotNull('confirmed')->count();
        
        return response()->json([
            'success' => 1,
            'data' => ['new_pending_order' => $new_pending_order, 'new_confirmed_order' => $new_confirmed_order]
        ]);
    }

    public function order_stats(Request $request)
    {
        $params = session('dash_params');
        foreach ($params as $key => $value) {
            if ($key == 'statistics_type') {
                $params['statistics_type'] = $request['statistics_type'];
            }
        }
        session()->put('dash_params', $params);

        $data = self::dashboard_order_stats_data();
        return response()->json([
            'view' => view('vendor-views.partials._dashboard-order-stats', compact('data'))->render()
        ], 200);
    }

    public function dashboard_order_stats_data()
    {
        $params = session('dash_params');
        $today = $params['statistics_type'] == 'today' ? 1 : 0;
        $this_month = $params['statistics_type'] == 'this_month' ? 1 : 0;

        $completed = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->where(['vendor_id' => Helpers::get_restaurant_id()])->Delivered()->count();

        $processing = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->where(['vendor_id' => Helpers::get_restaurant_id()])->Preparing()->count();

        $services_ongoing = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->where(['vendor_id' => Helpers::get_restaurant_id()])->ServiceOngoing()->count();


        $accepted = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->where(['vendor_id' => Helpers::get_restaurant_id()])->Accepted()->count();

        $refunded = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->where(['vendor_id' => Helpers::get_restaurant_id()])->Refunded()->count();

        $scheduled = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());

        })->where(['vendor_id' => Helpers::get_restaurant_id()])->Scheduled()->count();

        $all = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->where(['vendor_id' => Helpers::get_restaurant_id()])->whereIn('order_status',['pending', 'canceled', 'services_ongoing', 'picked_up', 'service_ongoing', 'processing', 'accepted', 'delivered', 'completed', 'refunded'])->count();

        $data = [
            'completed' => $completed,
            'processing' => $processing,
            'accepted' => $accepted,
            'services_ongoing' => $services_ongoing,
            'refunded' => $refunded,
            'scheduled' => $scheduled,
            'all' => $all,
        ];

        return $data;
    }
}
