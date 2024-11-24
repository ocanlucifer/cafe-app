@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-md-8">
            <h3 class="display-6">Create New Transaction</h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-chevron-left"></i>
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

    <form action="{{ route('sales.store') }}" method="POST" id="transaction-form">
        @csrf

        {{-- Select Customer and Header Discount --}}
        <div class="row mb-3">
            {{-- Select Customer --}}
            <div class="col-md-6">
                <label for="customer_id" class="form-label">Customer</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Header Discount --}}
            <div class="col-md-6">
                <label for="discount" class="form-label">Transaction Discount (Header)</label>
                <input type="number" name="discount" id="discount" class="form-control" placeholder="Discount for the entire transaction" min="0" step="0.01" value="0">
            </div>
        </div>

        {{-- Transaction Summary --}}
        <div id="transaction-summary" class="mb-4 p-3 border rounded bg-light">
            <h3>Transaction Summary</h3>
            <div class="row">
                <div class="col-md-3">
                    <p><strong>Total Before Discount:</strong></p>
                    <h5 class="text-primary">Rp <span id="total-before-discount">0.00</span></h5>
                </div>
                <div class="col-md-3">
                    <p><strong>Total Item Discount:</strong></p>
                    <h5 class="text-danger">Rp <span id="total-discount-item">0.00</span></h5>
                </div>
                <div class="col-md-3">
                    <p><strong>Transaction Discount (Header):</strong></p>
                    <h5 class="text-danger">Rp <span id="total-header-discount">0.00</span></h5>
                </div>
                <div class="col-md-3">
                    <p><strong>Grand Total:</strong></p>
                    <h5 class="text-success">Rp <span id="grand-total">0.00</span></h5>
                </div>
            </div>
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
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>Discount</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="items-container">
                    <tr id="empty-row">
                        <td colspan="7" class="text-center">No items added</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-success">Save Transaction</button>
    </form>
</div>

{{-- Script --}}
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
            <td class="item-price">Rp 0.00</td>
            <td>
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control item-quantity" value="1" min="1" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][discount]" class="form-control item-discount" value="0" min="0" step="0.01">
            </td>
            <td class="item-subtotal">Rp 0.00</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        container.appendChild(newRow);
        itemIndex++;
        updateSummary();
    });

    // Remove Item
    document.getElementById('items-container').addEventListener('click', (e) => {
        if (e.target.closest('.remove-item')) {
            const row = e.target.closest('tr');
            row.remove();
            updateSummary();

            // Update indices in the table
            document.querySelectorAll('#items-container tr').forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
            });

            if (document.querySelectorAll('#items-container tr').length === 0) {
                document.getElementById('items-container').innerHTML = `
                    <tr id="empty-row">
                        <td colspan="7" class="text-center">No items added</td>
                    </tr>
                `;
            }
        }
    });

    // Update Summary
    document.getElementById('items-container').addEventListener('input', updateSummary);
    document.getElementById('discount').addEventListener('input', updateSummary);

    function updateSummary() {
        let totalBeforeDiscount = 0;
        let totalDiscountItem = 0;

        document.querySelectorAll('#items-container tr').forEach(row => {
            const price = parseFloat(row.querySelector('select option:checked')?.getAttribute('data-price') || 0);
            const quantity = parseInt(row.querySelector('.item-quantity')?.value || 0);
            const discount = parseFloat(row.querySelector('.item-discount')?.value || 0);

            totalBeforeDiscount += price * quantity;
            totalDiscountItem += discount;

            // Update Unit Price and Subtotal
            row.querySelector('.item-price').textContent = `Rp ${price.toLocaleString('id-ID', { minimumFractionDigits: 2 })}`;
            const subtotal = (price * quantity) - discount;
            row.querySelector('.item-subtotal').textContent = `Rp ${subtotal.toLocaleString('id-ID', { minimumFractionDigits: 2 })}`;
        });

        const headerDiscount = parseFloat(document.getElementById('discount').value || 0);
        const grandTotal = totalBeforeDiscount - totalDiscountItem - headerDiscount;

        document.getElementById('total-before-discount').textContent = totalBeforeDiscount.toLocaleString('id-ID', { minimumFractionDigits: 2 });
        document.getElementById('total-discount-item').textContent = totalDiscountItem.toLocaleString('id-ID', { minimumFractionDigits: 2 });
        document.getElementById('total-header-discount').textContent = headerDiscount.toLocaleString('id-ID', { minimumFractionDigits: 2 });
        document.getElementById('grand-total').textContent = grandTotal.toLocaleString('id-ID', { minimumFractionDigits: 2 });
    }
</script>
@endsection
