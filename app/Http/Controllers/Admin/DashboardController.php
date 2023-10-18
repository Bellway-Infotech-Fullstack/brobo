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

use App\Models\OrderTransaction;
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
        $data = array('total_sell' => 2,'commission' => 3,'params' => 3);
        $total_sell = $data['total_sell'];
        $commission = $data['commission'];
        return view('admin-views.dashboard', compact('total_sell', 'commission', 'params'));
    }

    public function order(Request $request)
    {
        $params = session('dash_params');
        foreach ($params as $key => $value) {
            if ($key == 'statistics_type') {
                $params['statistics_type'] = $request['statistics_type'];
            }
        }
        session()->put('dash_params', $params);

        if ($params['zone_id'] != 'all') {
            $vendor_ids = Vendor::where(['zone_id' => $params['zone_id']])->pluck('id')->toArray();
        } else {
            $vendor_ids = Vendor::pluck('id')->toArray();
        }
        $data = self::order_stats_calc(array_unique($vendor_ids));
        return response()->json([
            'view' => view('admin-views.partials._dashboard-order-stats', compact('data'))->render()
        ], 200);
    }

    public function zone(Request $request)
    {
        $params = session('dash_params');
        foreach ($params as $key => $value) {
            if ($key == 'zone_id') {
                $params['zone_id'] = $request['zone_id'];
            }
        }
        session()->put('dash_params', $params);

        $data = self::dashboard_data();
        $total_sell = $data['total_sell'];
        $commission = $data['commission'];
        $popular = $data['popular'];
        $top_customer = $data['top_customer'];
        // $top_deliveryman = $data['top_deliveryman'];
        $top_rated_services = $data['top_rated_services'];
        $top_restaurants = $data['top_restaurants'];
        $top_sell = $data['top_sell'];

        return response()->json([
            'popular_restaurants' => view('admin-views.partials._popular-restaurants', compact('popular'))->render(),
            'top_customer' => view('admin-views.partials._top-customer', compact('top_customer'))->render(),
            // 'top_deliveryman' => view('admin-views.partials._top-deliveryman', compact('top_deliveryman'))->render(),
            'top_rated_services' => view('admin-views.partials._top-rated-services', compact('top_rated_services'))->render(),
            'top_restaurants' => view('admin-views.partials._top-restaurants', compact('top_restaurants'))->render(),
            'top_selling_services' => view('admin-views.partials._top-selling-services', compact('top_sell'))->render(),

            'order_stats' => view('admin-views.partials._dashboard-order-stats', compact('data'))->render(),
            'user_overview' => view('admin-views.partials._user-overview-chart', compact('data'))->render(),
            'business_overview' => view('admin-views.partials._business-overview-chart', compact('data'))->render(),
            'monthly_graph' => view('admin-views.partials._monthly-earning-graph', compact('total_sell', 'commission'))->render(),
        ], 200);
    }

    public function user_overview(Request $request)
    {
        $params = session('dash_params');
        foreach ($params as $key => $value) {
            if ($key == 'user_overview') {
                $params['user_overview'] = $request['user_overview'];
            }
        }
        session()->put('dash_params', $params);
        //user overview
        if ($params['zone_id'] != 'all') {
            $vendor_ids = Vendor::where(['zone_id' => $params['zone_id']])->pluck('id')->toArray();
            $zone_ids = [$params['zone_id']];
            $user_ids = CustomerAddress::whereIn('zone_id', $zone_ids)->pluck('user_id')->toArray();
        } else {
            //$zone_ids = Zone::pluck('id')->toArray();
            $vendor_ids = Vendor::pluck('id')->toArray();
            $user_ids = CustomerAddress::whereIn('zone_id', $zone_ids)->pluck('user_id')->toArray();
        }

        $data = self::user_overview_calc(array_unique($vendor_ids), array_unique($zone_ids), array_unique($user_ids));

        return response()->json([
            'view' => view('admin-views.partials._user-overview-chart', compact('data'))->render()
        ], 200);
    }

    public function business_overview(Request $request)
    {
        $params = session('dash_params');
        foreach ($params as $key => $value) {
            if ($key == 'business_overview') {
                $params['business_overview'] = $request['business_overview'];
            }
        }
        session()->put('dash_params', $params);
        //business overview
        if ($params['zone_id'] != 'all') {
            $vendor_ids = Vendor::where(['zone_id' => $params['zone_id']])->pluck('id')->toArray();
        } else {
            $vendor_ids = Vendor::pluck('id')->toArray();
        }

        $data = self::business_overview_calc(array_unique($vendor_ids));

        return response()->json([
            'view' => view('admin-views.partials._business-overview-chart', compact('data'))->render()
        ], 200);
    }

    public function order_stats_calc($vendor_ids)
    {
        $params = session('dash_params');

        if ($params['statistics_type'] == 'today') {
            // $searching_for_dm = Order::SearchingForDeliveryman()->whereDate('created_at', Carbon::now());
            // $accepted_by_dm = Order::AccepteByDeliveryman()->whereDate('created_at', Carbon::now());
            $preparing_in_rs = Order::Preparing()->whereDate('created_at', Carbon::now());
            $picked_up = Order::ServiceOngoing()->whereDate('created_at', Carbon::now());
            $delivered = Order::Delivered()->whereDate('delivered', Carbon::now());
            $canceled = Order::where(['order_status' => 'canceled'])->whereDate('canceled', Carbon::now());
            $refund_requested = Order::where(['order_status' => 'refund_requested'])->whereDate('refund_requested', Carbon::now());
            $refunded = Order::where(['order_status' => 'refunded'])->whereDate('refunded', Carbon::now());
        } else {
            // $searching_for_dm = Order::SearchingForDeliveryman();
            // $accepted_by_dm = Order::AccepteByDeliveryman();
            $preparing_in_rs = Order::Preparing();
            $picked_up = Order::ServiceOngoing();
            $delivered = Order::Delivered();
            $canceled = Order::Canceled();
            $refund_requested = Order::failed();
            $refunded = Order::Refunded();
        }

        // $searching_for_dm = $searching_for_dm->OrderScheduledIn(30)->whereIn('vendor_id', $vendor_ids)->count();
        // $accepted_by_dm = $accepted_by_dm->whereIn('vendor_id', $vendor_ids)->count();
        // $preparing_in_rs = $preparing_in_rs->whereIn('vendor_id', $vendor_ids)->count();
        $picked_up = $picked_up->whereIn('vendor_id', $vendor_ids)->count();
        $delivered = $delivered->whereIn('vendor_id', $vendor_ids)->count();
        $canceled = $canceled->whereIn('vendor_id', $vendor_ids)->count();
        $refund_requested = $refund_requested->whereIn('vendor_id', $vendor_ids)->count();
        $refunded = $refunded->whereIn('vendor_id', $vendor_ids)->count();

        $data = [
            // 'searching_for_dm' => $searching_for_dm,
            // 'accepted_by_dm' => $accepted_by_dm,
            // 'preparing_in_rs' => $preparing_in_rs,
            'picked_up' => $picked_up,
            'delivered' => $delivered,
            'canceled' => $canceled,
            'refund_requested' => $refund_requested,
            'refunded' => $refunded
        ];
        return $data;
    }

    public function user_overview_calc($vendor_ids, $zone_ids, $user_ids)
    {
       

        return array();
    }
}
