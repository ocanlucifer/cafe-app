<table class="table table-bordered table-striped table-hover table-sm">
    <thead class="table-dark">
        <tr>
            <th>No.</th>
            <th>Item</th>
            <th>Total Quantity</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->total_quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<!-- Pagination Links -->
<div class="d-flex justify-content-between align-items-center mt-3">
    <span class="text-muted">
        Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} Items
    </span>

    <div>
        {!! $items->links('pagination::bootstrap-5') !!}
    </div>
</div>
