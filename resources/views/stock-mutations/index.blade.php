@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Stock Mutation Report</h3>

    {{-- Filters --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="date_from" class="form-label">From Date</label>
            <input type="date" id="date_from" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="date_to" class="form-label">To Date</label>
            <input type="date" id="date_to" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="item_name" class="form-label">Item Name</label>
            <input type="text" id="item_name" class="form-control" placeholder="Search by item name">
        </div>
        <div class="col-md-3">
            <label for="sort_by" class="form-label">Sort By</label>
            <select id="sort_by" class="form-select">
                <option value="item_name">Item Name</option>
                <option value="qty_begin">Quantity Begin</option>
                <option value="qty_end">Quantity End</option>
                <option value="created_at">Date</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="sort_direction" class="form-label">Sort Direction</label>
            <select id="sort_direction" class="form-select">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="table-dark">
                    <th>Item Name</th>
                    <th>Qty Begin</th>
                    <th>Qty In</th>
                    <th>Qty Out</th>
                    <th>Qty End</th>
                </tr>
            </thead>
            <tbody id="report-table">
                <tr>
                    <td colspan="7" class="text-center">No data available</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateFrom = document.getElementById('date_from');
        const dateTo = document.getElementById('date_to');
        const itemName = document.getElementById('item_name');
        const sortBy = document.getElementById('sort_by');
        const sortDirection = document.getElementById('sort_direction');
        const reportTable = document.getElementById('report-table');

        // Fetch data with AJAX
        function fetchData() {
            const params = {
                date_from: dateFrom.value,
                date_to: dateTo.value,
                item_name: itemName.value,
                sort_by: sortBy.value,
                sort_direction: sortDirection.value,
            };

            fetch(`{{ route('stock-mutations.fetch') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify(params),
            })
            .then(response => response.json())
            .then(data => {
                reportTable.innerHTML = '';
                if (data.length === 0) {
                    reportTable.innerHTML = '<tr><td colspan="7" class="text-center">No data found</td></tr>';
                } else {
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${row.name}</td>
                            <td>${row.qty_begin}</td>
                            <td>${row.qty_in}</td>
                            <td>${row.qty_out}</td>
                            <td>${row.qty_end}</td>
                        `;
                        reportTable.appendChild(tr);
                    });
                }
            });
        }

        // Attach event listeners
        [dateFrom, dateTo, itemName, sortBy, sortDirection].forEach(input => {
            input.addEventListener('input', fetchData);
        });

        // Initial fetch
        fetchData();
    });
</script>
@endsection
