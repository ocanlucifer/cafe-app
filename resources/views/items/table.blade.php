<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm">
        <thead class="table-dark">
            <tr>
                <th class="col-0">No.</th>
                <th class="col-2">
                    <a href="#" class="sortable nav-link" data-sort-by="name" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Name
                        @if ($sortBy === 'name')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-2">
                    <a href="#" class="sortable nav-link" data-sort-by="category_name" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Category
                        @if ($sortBy === 'category_name')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-2">
                    <a href="#" class="sortable nav-link" data-sort-by="type_name" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Type
                        @if ($sortBy === 'type_name')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-1">
                    <a href="#" class="sortable nav-link" data-sort-by="price" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Price
                        @if ($sortBy === 'price')
                            <i class="fas fa-sort-{{ $order === 'asc' ? 'down' : 'up' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="col-2">
                    <a href="#" class="sortable nav-link" data-sort-by="stock" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Stock
                        @if ($sortBy === 'stock')
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
                <th>registered By</th>
                <th class="col-2 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name }}</td>
                    <td>{{ $item->type->name }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->stock }}</td>
                    <td class="text-center">
                        <span class="badge {{ $item->active ? 'bg-success' : 'bg-danger' }}">
                            {{ $item->active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $item->user->name }}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning edit-item" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-category_id="{{ $item->category_id }}" data-type_id="{{ $item->type_id }}" data-price="{{ $item->price }}" data-stock="{{ $item->stock }}" data-active="{{ $item->active }}" data-bs-toggle="tooltip" title="Edit Item">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-item" data-id="{{ $item->id }}" data-bs-toggle="tooltip" title="Delete Item">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button class="btn btn-sm {{ $item->active ? 'btn-secondary' : 'btn-success' }} toggle-status" data-id="{{ $item->id }}" data-bs-toggle="tooltip" title="{{ $item->active ? 'Deactivate Item' : 'Activate Item' }}">
                            <i class="bi bi-toggle-{{ $item->active ? 'on' : 'off' }}"></i>
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
        Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} items
    </span>

    <!-- Pagination links on the right -->
    <div>
        {!! $items->links('pagination::bootstrap-5') !!}
    </div>
</div>
