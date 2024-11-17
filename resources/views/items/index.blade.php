@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Item Management</h1>
        <button class="btn btn-primary btn-sm" id="open-create-form">
            <i class="bi bi-plus-lg" style="font-size: 1rem;"></i> Add Item
        </button>
    </div>

    <!-- Success Message -->
    <div id="success-message" class="alert alert-success d-none" role="alert">
        Item saved successfully!
    </div>

    <!-- Filter, Sort, and Search Form -->
    <form id="filter-form" class="mb-4">
        <div class="row g-2 justify-content-end">
            <div class="col-md-3 col-sm-12">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, category, or price" id="search" value="{{ $search }}">
            </div>
            {{-- <div class="col-md-2 col-sm-6"> --}}
                <select name="sort_by" id="sort_by" class="form-select form-select-sm" hidden>
                    <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="category_name" {{ $sortBy == 'category_name' ? 'selected' : '' }}>Category</option>
                    <option value="price" {{ $sortBy == 'price' ? 'selected' : '' }}>Price</option>
                    <option value="stock" {{ $sortBy == 'stock' ? 'selected' : '' }}>Stock</option>
                    <option value="active" {{ $sortBy == 'active' ? 'selected' : '' }}>Status</option>
                </select>
            {{-- </div>
            <div class="col-md-2 col-sm-6"> --}}
                <select name="order" id="order" class="form-select form-select-sm" hidden>
                    <option value="asc" {{ $order == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ $order == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            {{-- </div> --}}
            <div class="col-md-2 col-sm-6">
                <select name="per_page" id="per_page" class="form-select form-select-sm">
                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 per page</option>
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 per page</option>
                    <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 per page</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Table Container for AJAX -->
    <div id="table-container">
        @include('items.table')
    </div>

    <!-- Modal for Create/Edit Item Form -->
    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalLabel">Item Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="item-form">
                        @csrf
                        <input type="hidden" name="id" id="item-id">
                        <div class="mb-3">
                            <label for="item-name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="item-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="item-category_id" class="form-label">Category</label>
                            <select name="category_id" id="item-category_id" class="form-select" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="item-price" class="form-label">Price</label>
                            <input type="text" class="form-control" id="item-price" name="price" required>
                        </div>
                        {{-- <div class="mb-3"> --}}
                            <label for="item-stock" class="form-label" hidden>Stock</label>
                            <input type="number" class="form-control" id="item-stock" name="stock" hidden>
                        {{-- </div> --}}
                        <div class="mb-3">
                            <label for="item-status" class="form-label">Status</label>
                            <select id="item-status" name="active" class="form-select" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i>
                    </button>
                    <button type="button" class="btn btn-primary" id="save-item">
                        <i class="bi bi-save"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        function fetchItems() {
            const formData = $('#filter-form').serialize();

            $.ajax({
                url: "{{ route('items.index') }}",
                method: "GET",
                data: formData,
                success: function(response) {
                    $('#table-container').html(response);
                }
            });
        }

        //Event handler for sort links
        $(document).on('click', '.sortable', function(e) {
            e.preventDefault();
            $('#sort_by').val($(this).data('sort-by'));
            $('#order').val($(this).data('order'));
            fetchItems();
        });

        // Event listener for changes in form inputs
        $('#filter-form').on('change', 'select', function() {
            fetchItems();
        });

        // Trigger search when typing in search input
        $('#filter-form').on('keyup', '#search', function() {
            fetchItems();
        });

        // Event handler for pagination links
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const formData = $('#filter-form').serialize();

            $.ajax({
                url: url,
                method: "GET",
                data: formData,
                success: function(response) {
                    $('#table-container').html(response);
                }
            });
        });

        // Open the Create item form
        $('#open-create-form').on('click', function() {
            $('#itemModalLabel').text('Add Item');
            $('#item-form')[0].reset();
            $('#item-id').val('');
            $('#itemModal').modal('show');
        });

        // Open the Edit item form
        $(document).on('click', '.edit-item', function() {
            const item = $(this).data();
            $('#itemModalLabel').text('Edit Item');
            $('#item-id').val(item.id);
            $('#item-name').val(item.name);
            $('#item-category_id').val(item.category_id);
            $('#item-price').val(item.price);
            $('#item-stock').val(item.stock);
            $('#item-status').val(item.active);
            $('#itemModal').modal('show');
        });

        // Save or update item
        $('#save-item').on('click', function() {
            const formData = $('#item-form').serialize();
            const itemId = $('#item-id').val();
            const url = itemId ? `/items/${itemId}` : '/items';
            const method = itemId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: formData,
                success: function() {
                    $('#success-message').removeClass('d-none').text('Item saved successfully!');
                    setTimeout(() => { $('#success-message').addClass('d-none'); }, 3000);
                    $('#itemModal').modal('hide');
                    fetchItems();
                },
                error: function() { alert('Error saving item.'); }
            });
        });

        // Delete item
        $(document).on('click', '.delete-item', function() {
            if (confirm('Are you sure you want to delete this item?')) {
                const itemId = $(this).data('id');
                $.ajax({
                    url: `/items/${itemId}`,
                    method: 'DELETE',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function() { fetchItems(); },
                    error: function() { alert('Error deleting item.'); }
                });
            }
        });

        // Toggle active status
        $(document).on('click', '.toggle-status', function() {
            const itemId = $(this).data('id');
            $.ajax({
                url: `/items/${itemId}/toggle-active`,
                method: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function() { fetchItems(); },
                error: function() { alert('Error updating item status.'); }
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
