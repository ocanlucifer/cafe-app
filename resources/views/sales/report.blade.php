@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Sales Report</h1>
        <div>
            <form action="{{ route('penjualan.reports') }}" method="GET">
                @csrf
                <div class="input-group input-group-sm">
                    <input type="date" name="from_date" value="{{ $fromDate->toDateString() }}" class="form-control form-control-sm">
                    <span class="input-group-text">to</span>
                    <input type="date" name="to_date" value="{{ $toDate->toDateString() }}" class="form-control form-control-sm">
                    <button type="submit" class="btn btn-primary btn-sm ms-2">Filter</button>
                    <!-- Tombol export dengan metode POST -->
                    <button type="submit" name="export" value="excel" class="btn btn-success btn-sm ms-2">Export Excel</button>
                    <a href="{{ route('penjualan.printReportPDF', ['group' => $group, 'from_date' => $fromDate->toDateString(), 'to_date' => $toDate->toDateString()]) }}"
                        class="btn btn-danger btn-sm ms-2"
                        target="_blank">
                        Print PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Dropdown to select report grouping -->
        <form method="GET" class="d-flex ms-2">
            @csrf
            <select name="group" class="form-control form-control-sm" onchange="this.form.submit()">
                <option value="item" {{ $group == 'item' ? 'selected' : '' }}>Per Item</option>
                <option value="customer" {{ $group == 'customer' ? 'selected' : '' }}>Per Customer</option>
            </select>
        </form>
    </div>

    <!-- Laporan Berdasarkan Kategori (Item atau Customer) -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover table-sm">
            <thead class="table-dark">
                <tr>
                    @if($group == 'item')
                        <th>Item</th>
                        <th>Total Quantity</th>
                        <th>Total Sales</th>
                    @else
                        <th>Customer</th>
                        <th>Total Sales</th>
                        <th>Total Discount</th>
                        <th>Total After Discount</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($group == 'item')
                    @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->total_quantity }}</td>
                        <td>Rp {{ number_format($item->total_sales, 2) }}</td>
                    </tr>
                    @endforeach
                @else
                    @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $sale->customer->name }}</td>
                        <td>Rp {{ number_format($sale->total_price, 2) }}</td>
                        <td>Rp {{ number_format($sale->discount, 2) }}</td>
                        <td>Rp {{ number_format($sale->total_price - $sale->discount, 2) }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
