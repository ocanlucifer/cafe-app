@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-md-8">
            <h3 class="display-6">Transaction Details #{{ $issuing->transaction_number }}</h3>
            <p class="lead">Date: {{ $issuing->transaction_date->format('d M Y, H:i') }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('issuings.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-chevron-left"></i>
                Back to Transaction List
            </a>
            {{-- Edit Button --}}
            <a href="{{ route('issuings.edit', $issuing->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit Transaction">
                <i class="bi bi-pencil"></i>
            </a>
            {{-- Delete Button --}}
            <form action="{{ route('issuings.destroy', $issuing->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this transaction?')">
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
        {{-- User Info --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0 text-primary">User Info</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Name:</strong> {{ $issuing->user->name }}</p>
                    <p class="mb-2"><strong>Email:</strong> {{ $issuing->user->email }}</p>
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
                        <span><strong>Remarks:</strong></span>
                        <span class="text-end">{{ $issuing->remarks ?? 'No remarks provided' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><strong>Transaction Date:</strong></span>
                        <span class="text-end">{{ $issuing->transaction_date->format('d M Y') }}</span>
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
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($issuing->issuingDetails as $detail)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $detail->item->name }}</td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
