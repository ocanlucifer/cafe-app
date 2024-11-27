<table class="table table-bordered table-striped table-hover table-sm">
    <thead class="table-dark">
        <tr>
            <th>No.</th>
            <th>Customer</th>
            <th>Total Sales</th>
            <th>Total Discount</th>
            <th>Total After Discount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sales as $sale)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $sale->customer->name }}</td>
            <td>Rp {{ number_format($sale->total_sales, 2) }}</td>
            <td>Rp {{ number_format($sale->total_discount + $sale->details->sum('discount'), 2) }}</td>
            <td>Rp {{ number_format($sale->total_sales - ($sale->total_discount + $sale->details->sum('discount')), 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<!-- Pagination Links -->
<div class="d-flex justify-content-between align-items-center mt-3">
    <span class="text-muted">
        Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} Customer
    </span>

    <div>
        {!! $sales->links('pagination::bootstrap-5') !!}
    </div>
</div>
