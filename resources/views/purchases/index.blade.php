@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Purchase List</h2>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary mb-3">Create New Purchase</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vendor</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->id }}</td>
                    <td>{{ $purchase->vendor->name }}</td>
                    <td>{{ $purchase->item->name }}</td>
                    <td>{{ $purchase->quantity }}</td>
                    <td>${{ number_format($purchase->total_price, 2) }}</td>
                    <td>
                        <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
