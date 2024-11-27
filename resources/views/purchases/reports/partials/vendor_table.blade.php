<table class="table table-bordered table-striped table-hover table-sm">
    <thead class="table-dark">
        <tr>
            <th>No.</th>
            <th>Vendors</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($purchases as $purchase)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $purchase->vendor->name }}</td>
            <td>Rp {{ number_format($purchase->total_amount, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<!-- Pagination Links -->
<div class="d-flex justify-content-between align-items-center mt-3">
    <span class="text-muted">
        Showing {{ $purchases->firstItem() }} to {{ $purchases->lastItem() }} of {{ $purchases->total() }} Vendors
    </span>

    <div>
        {!! $purchases->links('pagination::bootstrap-5') !!}
    </div>
</div>
