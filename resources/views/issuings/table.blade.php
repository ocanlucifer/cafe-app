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
                    <a href="javascript:void(0);" class="sortable nav-link" data-sort-by="user_name" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                       User Name
                        @if ($sortBy === 'user_name')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-2 text-center">
                    <a href="javascript:void(0);" class="sortable nav-link" data-sort-by="created_at" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Date
                        @if ($sortBy === 'created_at')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-2 text-center">Remarks</th>
                <th class="col-2 text-center">Actions</th>
            </tr>
        </thead>
        <tbody id="purchase-table-body">
            @foreach ($result as $issuing)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><a href="{{ route('issuings.show', $issuing->id) }}" class="">{{ $issuing->transaction_number }}</a></td>
                    <td>{{ $issuing->user->name ?? 'N/A' }}</td>
                    <td>{{ $issuing->created_at->format('d-m-Y') }}</td>
                    <td>{{ $issuing->remarks }}</td>
                    <td class="text-center">
                        {{-- Edit Button --}}
                        <a href="{{ route('issuings.edit', $issuing->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit Purchase">
                            <i class="bi bi-pencil"></i>
                        </a>

                        {{-- Delete Button --}}
                        <form action="{{ route('issuings.destroy', $issuing->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this purchase?')">
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
