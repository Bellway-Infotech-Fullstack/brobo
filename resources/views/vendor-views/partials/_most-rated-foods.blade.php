<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <i class="tio-star"></i> {{trans('messages.top_rated_Services')}}
    </h5>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="row">
        <div class="col-12">
            <table class="table">
                <tbody>
                @foreach($most_rated_foods as $key=>$item)
                    @php($service=\App\Models\Service::find($item['service_id']))
                    <tr onclick="location.href='{{route('vendor.service.view',[$item['service_id']])}}'"
                        style="cursor: pointer">
                        <td scope="row">
                            <img height="35" style="border-radius: 5px"
                                 src="{{asset('storage/app/public/product')}}/{{$service['image']}}"
                                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                                 alt="{{$service->name}} image">
                            <span class="ml-2">
                                                    {{$service->name??'Not exist!'}}
                                                </span>
                        </td>
                        <td>
                                                <span style="font-size: 18px">
                                                    {{round($item['ratings_average'],2)}} <i style="color: gold" class="tio-star"></i>
                                                </span>
                        </td>
                        <td>
                                                  <span style="font-size: 18px">
                                                    {{$item['total']}} <i class="tio-users-switch"></i>
                                                  </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- End Body -->
