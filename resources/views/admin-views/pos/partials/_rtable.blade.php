@foreach($user_list as $k=>$e)
                            
@php
       $referrer = \APP\Models\User::select('id', 'name','mobile_number')
->where('referral_code', $e->referred_code)
->first();
@endphp
    <tr>
        <th scope="row">{{$k+$user_list->firstItem()}}</th>
         <td class="text-capitalize">{{$referrer->name}} ({{$referrer->mobile_number}})</td>
        <td >{{$e['name'] ?? 'N/A'}} ({{$e['mobile_number'] ?? 'N/A'}})</td>
    </tr>
@endforeach