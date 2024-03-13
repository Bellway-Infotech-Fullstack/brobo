
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
<h1>Customer List</h1>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $k => $user) 
        <tr>
            <td>{{ $k+1 }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email ?? 'N/A' }}</td>
            <td>{{ $user->mobile_number }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
