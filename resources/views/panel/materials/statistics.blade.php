<div class="my-5">
    <label>Orders by Manufacture</label>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Manufacture</th>
            <th>Order Count</th>
            <th>Packages Amount</th>
            <th>Pieces Amount</th>
{{--            <th style="width: 40px">Percentage</th>--}}
        </tr>
        </thead>
        <tbody>
        @foreach($manufacturers_statistics as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>{{$item->total_count}}</td>
                <td>{{$item->total_packages_amount}}</td>
                <td>{{$item->total_pieces_amount}}</td>
{{--                <td><span class="badge bg-primary">{{ number_format((($item->total_count / $manufacturers_statistics->count('total_count')) * 100), 2) }}%</span></td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="">
    <label>Orders by Suppliers</label>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Supplier</th>
            <th>Order Count</th>
            <th>Packages Amount</th>
            <th>Pieces Amount</th>
            <th style="width: 40px">Percentage</th>
        </tr>
        </thead>
        <tbody>
        @foreach($suppliers_statistics as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>{{$item->total_count}}</td>
                <td>{{$item->total_packages_amount}}</td>
                <td>{{$item->total_pieces_amount}}</td>
{{--                <td><span class="badge bg-primary">{{ number_format((($item->total_count / $suppliers_statistics->count('total_count')) * 100), 2) }}%</span></td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
