@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Edit Purchase</h2>
    <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="vendor_id" class="form-label">Vendor</label>
            <select class="form-control" name="vendor_id" required>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ $vendor->id == $purchase->vendor_id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="item_id" class="form-label">Item</label>
            <select class="form-control" name="item_id" required>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" {{ $item->id == $purchase->item_id ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" name="quantity" value="{{ $purchase->quantity }}" required>
        </div>
        <div class="mb-3">
            <label for="total_price" class="form-label">Total Price</label>
            <input type="number" class="form-control" name="total_price" step="0.01" value="{{ $purchase->total_price }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
