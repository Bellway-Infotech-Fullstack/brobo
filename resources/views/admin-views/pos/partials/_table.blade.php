@foreach($customers as $k=>$e)
<tr>
    <th scope="row">{{$k+1}}</th>
    <td class="text-capitalize">{{$e['name']}}</td>
    <td >
        {{$e['email']}}
    </td>
    <td>{{$e['mobile_number']}}</td>
    <td>
        <a class="btn btn-sm btn-white"
            href="{{route('admin.customer.edit',[$e['id']])}}" title="{{__('messages.edit')}} {{__('messages.customer')}}"><i class="tio-edit"></i>
        </a>
        <a class="btn btn-sm btn-danger" href="javascript:"
            onclick="form_alert('employee-{{$e['id']}}','{{__('messages.Want_to_delete_this_role')}}')" title="{{__('messages.delete')}} {{__('messages.customer')}}"><i class="tio-delete-outlined"></i>
        </a>
        <form action="{{route('admin.customer.delete',[$e['id']])}}"
                method="post" id="employee-{{$e['id']}}">
            @csrf @method('delete')
        </form>
    </td>
</tr>
@endforeach