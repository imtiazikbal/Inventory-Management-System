<html>
<head>
    <link rel="stylesheet" href="{{ public_path('css/pdf.css') }}">
</head>
<body>
    <img src="{{ public_path('larg.jpg') }}" alt="" width="15%">
   
<h3>Summary</h3>

<table class="customers" >
    <thead>
    <tr>
        <th>Report</th>
        <th>Date</th>
        <th>Total</th>
        <th>Discount</th>
        <th>Vat</th>
        <th>Payable</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Sales Report</td>
        <td>{{$FormDate}} to {{$ToDate}}</td>
        <td>{{$total}}</td>
        <td>{{$discount}}</td>
        <td>{{$vat}}</td>
        <td>{{$payable}} </td>
    </tr>
    </tbody>
</table>


<h3>Details</h3>
<table class="customers" >
    <thead>
    <tr>
        <th>Customer</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Total</th>
        <th>Discount</th>
        <th>Vat</th>
        <th>Payable</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($list as $item)
        <tr>
            <td>{{$item->customer->name}}</td>
            <td>{{$item->customer->mobile}}</td>
            <td>{{$item->customer->email}}</td>
            <td>{{$item->total }}</td>
            <td>{{$item->discount }}</td>
            <td>{{$item->vat }}</td>
            <td>{{$item->payable }}</td>
            <td>{{$item->created_at }}</td>
        </tr>
    @endforeach

    </tbody>
</table>
</body>
</html>




