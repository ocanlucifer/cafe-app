<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm">
        <thead class="table-dark">
            <tr>
                <th class="col-0">No.</th>
                <th class="col-3">
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
                <th class="col-2">
                    <a href="#" class="sortable nav-link" data-sort-by="price" data-order="{{ $order === 'asc' ? 'desc' : 'asc' }}">
                        Price
                        @if ($sortBy === 'price')
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
                <th>Registered By</th>
                <th class="col-2 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menus as $menu)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $menu->name }}</td>
                    <td>{{ $menu->category->name }}</td>
                    <td>{{ $menu->type->name }}</td>
                    <td>{{ number_format($menu->price, 2) }}</td>
                    <td class="text-center">
                        <span class="badge {{ $menu->active ? 'bg-success' : 'bg-danger' }}">
                            {{ $menu->active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $menu->user->name }}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning edit-menu" data-id="{{ $menu->id }}" data-name="{{ $menu->name }}" data-category_id="{{ $menu->category_id }}" data-type_id="{{ $menu->type_id }}"  data-price="{{ $menu->price }}" data-active="{{ $menu->active }}" data-bs-toggle="tooltip" title="Edit Menu">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-menu" data-id="{{ $menu->id }}" data-bs-toggle="tooltip" title="Delete Menu">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button class="btn btn-sm {{ $menu->active ? 'btn-secondary' : 'btn-success' }} toggle-status" data-id="{{ $menu->id }}" data-bs-toggle="tooltip" title="{{ $menu->active ? 'Deactivate Menu' : 'Activate Menu' }}">
                            <i class="bi bi-toggle-{{ $menu->active ? 'on' : 'off' }}"></i>
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
        Showing {{ $menus->firstItem() }} to {{ $menus->lastItem() }} of {{ $menus->total() }} items
    </span>

    <!-- Pagination links on the right -->
    <div>
        {!! $menus->links('pagination::bootstrap-5') !!}
    </div>
</div>
