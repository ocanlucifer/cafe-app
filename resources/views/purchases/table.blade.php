<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm">
        <thead class="table-dark">
            <tr>
                <th class="col-0">No.</th>
                <th class="col-3">
                    <a href="javascript:void(0);" class="sortable nav-link" data-sort-by="transaction_number" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Transaction Number
                        @if ($sortBy === 'transaction_number')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-3">
                    <a href="javascript:void(0);" class="sortable nav-link" data-sort-by="vendor_name" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Vendor Name
                        @if ($sortBy === 'vendor_name')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-3">Purchase Amount</th>
                <th class="col-2 text-center">
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
        <tbody id="purchase-table-body">
            @foreach ($result as $purchase)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><a href="{{ route('purchases.show', $purchase->id) }}" class="">{{ $purchase->transaction_number }}</a></td>
                    <td>{{ $purchase->vendor->name }}</td>
                    <td>Rp {{ number_format($purchase->details->sum('total_price'), 2) }}</td>
                    <td>{{ $purchase->created_at->format('d-m-Y') }}</td>
                    <td class="text-center">
                        {{-- Edit Button --}}
                        <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit Purchase">
                            <i class="bi bi-pencil"></i>
                        </a>

                        {{-- Delete Button --}}
                        <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this purchase?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Delete Purchase">
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
        Showing {{ $result->firstItem() }} to {{ $result->lastItem() }} of {{ $result->total() }} Purchases
    </span>

    <div>
        {!! $result->links('pagination::bootstrap-5') !!}
    </div>
</div>
