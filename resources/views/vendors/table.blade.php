<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm">
        <thead class="table-dark">
            <tr>
                <th class="col-2">
                    <a href="#" class="sortable nav-link" data-sort-by="name" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Name
                        @if ($sortBy === 'name')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-2">
                    <a href="#" class="sortable nav-link" data-sort-by="contact" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Contact
                        @if ($sortBy === 'contact')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-4">
                    <a href="#" class="sortable nav-link" data-sort-by="address" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Address
                        @if ($sortBy === 'address')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-1 text-center">
                    <a href="#" class="sortable nav-link" data-sort-by="active" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Status
                        @if ($sortBy === 'active')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-1 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vendors as $vendor)
            <tr>
                <td>{{ $vendor->name }}</td>
                <td>{{ $vendor->contact }}</td>
                <td>{{ $vendor->address }}</td>
                <td class="text-center">
                    <span class="badge {{ $vendor->active ? 'bg-success' : 'bg-danger' }}">
                        {{ $vendor->active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-warning edit-vendor" data-id="{{ $vendor->id }}" data-name="{{ $vendor->name }}" data-contact="{{ $vendor->contact }}" data-address="{{ $vendor->address }}" data-active="{{ $vendor->active }}" data-bs-toggle="tooltip" title="Edit Vendor">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-vendor" data-id="{{ $vendor->id }}"  data-bs-toggle="tooltip" title="Delete Vendor">
                        <i class="bi bi-trash"></i>
                    </button>
                    <button class="btn btn-sm {{ $vendor->active ? 'btn-secondary' : 'btn-success' }} toggle-status" data-id="{{ $vendor->id }}" data-bs-toggle="tooltip" title="{{ $vendor->active ? 'Deactivate Vendor' : 'Activate Vendor' }}">
                        <i class="bi bi-toggle-{{ $vendor->active ? 'on' : 'off' }}"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No vendors found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-3">
    <!-- Showing results text on the left -->
    <span class="text-muted">
        Showing {{ $vendors->firstItem() }} to {{ $vendors->lastItem() }} of {{ $vendors->total() }} vendors
    </span>

    <!-- Pagination links on the right -->
    <div>
        {!! $vendors->links('pagination::bootstrap-5') !!}
    </div>
</div>