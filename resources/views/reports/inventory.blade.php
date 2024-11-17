<!-- resources/views/reports/inventory.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Inventory Report</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventories as $inventory)
                <tr>
                    <td>{{ $inventory->item->name }}</td>
                    <td>{{ $inventory->quantity }}</td>
                    <td>{{ $inventory->item->category->name }}</td>
                    <td>{{ $inventory->item->type->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
