
<style>
    body {
        font-family: Arial, sans-serif;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
</style>
</head>
<body>
<h1>Referred Customer List</h1>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Refer By</th>
            <th>Refer To</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $k=>$e)
                            
        @php
               $referrer = \APP\Models\User::select('id', 'name','mobile_number')
            ->where('referral_code', $e->referred_code)
            ->first();
        @endphp
            <tr>
                <td>{{ $k+1 }}</td>
                   <td style="text-transform:capitalize">{{$referrer->name}} ({{$referrer->mobile_number}})</td>
                <td >{{$e['name'] ?? 'N/A'}} ({{$e['mobile_number'] ?? 'N/A'}})</td>
            </tr>
        @endforeach
    </tbody>
</table>
