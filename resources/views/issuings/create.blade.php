@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-md-8">
            <h3 class="display-6">Create New Issuing Transaction</h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('issuings.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-chevron-left"></i>
                Back to Transaction List
            </a>
        </div>
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {!! session('error') !!}
        </div>
    @endif

    <form action="{{ route('issuings.store') }}" method="POST" id="issuing-form">
        @csrf

        {{-- User and Transaction Date --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="transaction_date" class="form-label">Transaction Date</label>
                <input type="datetime-local" name="transaction_date" id="transaction_date" class="form-control" value="{{ old('transaction_date', now()->format('Y-m-d\TH:i')) }}" required>
            </div>
        </div>

        {{-- Remarks --}}
        <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
        </div>

        {{-- Item Details --}}
        <div class="d-flex justify-content-between align-items-center">
            <h3>Item Details</h3>
            <button type="button" id="add-item" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Add Item
            </button>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="items-container">
                    <tr id="empty-row">
                        <td colspan="4" class="text-center">No items added</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Total Quantity --}}
        <div class="mb-3">
            <label for="total_quantity" class="form-label">Total Quantity</label>
            <input type="number" name="total_quantity" id="total_quantity" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-success">Save Issuing</button>
    </form>
</div>

<script>
    let itemIndex = 0;

    // Add Item
    document.getElementById('add-item').addEventListener('click', () => {
        const container = document.getElementById('items-container');
        const emptyRow = document.getElementById('empty-row');
        if (emptyRow) emptyRow.remove();

        const newRow = document.createElement('tr');
        newRow.setAttribute('data-index', itemIndex);

        newRow.innerHTML = `
            <td>${itemIndex + 1}</td>
            <td>
                <select name="items[${itemIndex}][item_id]" class="form-control item-select" required>
                    <option value="">Select Item</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control item-quantity" value="1" min="1" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        container.appendChild(newRow);
        itemIndex++;
        updateTotalQuantity();
    });

    // Remove Item
    document.getElementById('items-container').addEventListener('click', (e) => {
        if (e.target.closest('.remove-item')) {
            const row = e.target.closest('tr');
            row.remove();
            updateTotalQuantity();

            if (document.querySelectorAll('#items-container tr').length === 0) {
                document.getElementById('items-container').innerHTML = `
                    <tr id="empty-row">
                        <td colspan="4" class="text-center">No items added</td>
                    </tr>
                `;
            }
        }
    });

    // Update Total Quantity
    document.getElementById('items-container').addEventListener('input', updateTotalQuantity);

    function updateTotalQuantity() {
        let totalQuantity = 0;

        document.querySelectorAll('#items-container tr').forEach(row => {
            const quantityInput = row.querySelector('.item-quantity');
            const quantity = parseInt(quantityInput.value || 1);

            totalQuantity += quantity;
        });

        document.getElementById('total_quantity').value = totalQuantity;
    }
</script>

@endsection
