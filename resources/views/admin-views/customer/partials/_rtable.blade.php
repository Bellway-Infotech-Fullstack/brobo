  @foreach($user_list as $k=>$e)
                            
                            @php
                                   $referrer = \APP\Models\User::select('id', 'name')
        ->where('referral_code', $e->referred_code)
        ->first();
                            @endphp
                                <tr>
                                    <th scope="row">{{$k+$user_list->firstItem()}}</th>
                                    <td class="text-capitalize">{{$referrer->name}}</td>
                                    <td >{{$e['name'] ?? 'N/A'}}</td>
                                </tr>
                            @endforeach