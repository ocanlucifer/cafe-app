@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-md-8">
            <h3 class="display-6">Transaction Details #{{ $purchase->transaction_number }}</h3>
            <p class="lead">Date: {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y, H:i') }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-chevron-left"></i>
                <i class="fas fa-chevron-left"></i>
                Back to Transaction List
            </a>
            {{-- Print Button --}}
            {{-- <button class="btn btn-success btn-sm" onclick="openPopup('{{ route('purchases.print-pdf', $purchase->id) }}')">
                <i class="bi bi-printer"></i> Print Receipt
            </button> --}}
            {{-- Edit Button --}}
            <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit Transaction">
                <i class="bi bi-pencil"></i>
            </a>

            {{-- Delete Button --}}
            <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this transaction?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Delete Transaction">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Transaction Overview --}}
    <div class="row g-4">
        {{-- Vendor Info --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0 text-primary">Vendor Info</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Name:</strong> {{ $purchase->vendor->name }}</p>
                    <p class="mb-2"><strong>Address:</strong> {{ $purchase->vendor->address ?? 'No address provided' }}</p>
                    <p class="mb-0"><strong>Contact:</strong> {{ $purchase->vendor->contact ?? 'No contact provided' }}</p>
                </div>
            </div>
        </div>

        {{-- Transaction Summary --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Transaction Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>Total Amount:</strong></span>
                        <span class="text-end">Rp {{ number_format($purchase->total_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>Purchase Date:</strong></span>
                        <span class="text-end">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Item Details --}}
    <div class="mb-4">
        <h3 class="mb-3">Item Details</h3>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->details as $detail)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $detail->item->name }}</td>
                            <td class="text-end">Rp {{ number_format($detail->price, 2) }}</td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($detail->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function openPopup(url) {
        // Open print popup for receipt
        var printWindow = window.open(url, '_blank', 'width=400,height=600,scrollbars=yes,resizable=no');
        printWindow.document.write(printContent);
        printWindow.document.close();
    }
</script>

@endsection
