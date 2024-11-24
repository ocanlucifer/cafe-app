<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm">
        <thead class="table-dark">
            <tr>
                <th class="col-0">No.</th>
                <th class="col-2">
                    <a href="javascript:void(0);" class="sortable nav-link" data-sort-by="transaction_number" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Transaction Number
                        @if ($sortBy === 'transaction_number')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-3">
                    <a href="javascript:void(0);" class="sortable nav-link" data-sort-by="customer_name" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Customer Name
                        @if ($sortBy === 'customer_name')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-2">Total Before Discount</th>
                <th class="col-1">Total Discount</th>
                <th class="col-2">Total After Discount</th>
                <th class="col-1 text-center">
                    <a href="javascript:void(0);" class="sortable nav-link" data-sort-by="created_at" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Date
                        @if ($sortBy === 'created_at')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-2 text-center">Actions</th>
            </tr>
        </thead>
        <tbody id="sales-table-body">
            @foreach ($result as $sale)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><a href="{{ route('sales.show', $sale->id) }}" class="">{{ $sale->transaction_number }}</a></td>
                    <td>{{ $sale->customer->name }}</td>
                    <td>Rp {{ number_format($sale->total_before_discount, 2) }}</td>
                    <td>Rp {{ number_format($sale->total_item_discount + $sale->discount, 2) }}</td>
                    <td>Rp {{ number_format($sale->total_after_discount, 2) }}</td>
                    <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                    <td class="text-center">
                        {{-- Edit Button --}}
                        <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit Transaction">
                            <i class="bi bi-pencil"></i>
                        </a>

                        {{-- Delete Button --}}
                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this transaction?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Delete Transaction">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<div class="d-flex justify-content-between align-items-center mt-3">
    <span class="text-muted">
        Showing {{ $result->firstItem() }} to {{ $result->lastItem() }} of {{ $result->total() }} Sales
    </span>

    <div>
        {!! $result->links('pagination::bootstrap-5') !!}
    </div>
</div>
