@foreach($userList as $k=>$e)
<tr>
    <th scope="row">{{$k+1}}</th>
    <td class="text-capitalize">{{$e['name']}}</td>
    <td >
        {{$e['email'] ?? 'N/A'}}
    </td>
    <td>{{$e['mobile_number']}}</td>
    <td>Rs. 100</td>
</tr>
@endforeach