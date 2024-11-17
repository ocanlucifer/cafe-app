<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm">
        <thead class="table-dark">
            <tr>
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
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td class="text-center">
                        <!-- Edit Button with Tooltip -->
                        <button class="btn btn-warning btn-sm edit-category" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-bs-toggle="tooltip" title="Edit Category">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- Delete Button with Tooltip -->
                        <button class="btn btn-danger btn-sm delete-category" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-bs-toggle="tooltip" title="Delete Category">
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
        Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} categories
    </span>

    <!-- Pagination links on the right -->
    <div>
        {!! $categories->links('pagination::bootstrap-5') !!}
    </div>
</div>
