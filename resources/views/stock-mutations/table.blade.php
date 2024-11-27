<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm">
        <thead class="table-dark">
            <tr>
                <th>No.</th>
                <th>Item Name</th>
                <th>Begin Qty</th>
                <th>In Qty</th>
                <th>Out Qty</th>
                <th>End Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stockMutations as $stockMutation)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $stockMutation->name }}</td>
                <td>{{ number_format($stockMutation->qty_begin) }}</td>
                <td>{{ number_format($stockMutation->qty_in) }}</td>
                <td>{{ number_format($stockMutation->qty_out) }}</td>
                <td>{{ number_format($stockMutation->qty_end) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<div class="d-flex justify-content-between align-items-center mt-3">
    <span class="text-muted">
        Showing {{ $stockMutations->firstItem() }} to {{ $stockMutations->lastItem() }} of {{ $stockMutations->total() }} Items
    </span>

    <div>
        {!! $stockMutations->links('pagination::bootstrap-5') !!}
    </div>
</div>
