<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm">
        <thead class="table-dark">
            <tr>
                <th class="col-0">No.</th>
                <th class="col-8">
                    <a href="#" class="sort-link nav-link" data-sort-by="name" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Name
                        @if ($sortBy === 'name')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($types as $type)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $type->name }}</td>
                    <td class="text-center">
                        <!-- Edit Button with Tooltip -->
                        <button class="btn btn-warning btn-sm edit-type" data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-bs-toggle="tooltip" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- Delete Button with Tooltip -->
                        <button class="btn btn-danger btn-sm delete-type"" data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-bs-toggle="tooltip" title="Delete">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-3">
    <!-- Showing results text on the left -->
    <span class="text-muted">
        Showing {{ $types->firstItem() }} to {{ $types->lastItem() }} of {{ $types->total() }} types
    </span>

    <!-- Pagination links on the right -->
    <div>
        {!! $types->links('pagination::bootstrap-5') !!}
    </div>
</div>
