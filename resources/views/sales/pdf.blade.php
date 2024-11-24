<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div style="font-family: Arial, sans-serif; font-size: 10px; width: 250px; margin: 0 auto; padding: 10px;">
        <h3 style="text-align: center; font-size: 16px;">Transaction Receipt</h3>
        <p style="text-align: center; font-size: 12px; margin: 0;"><strong>Transaction #${{ $sale->transaction_number }}</strong></p>
        <p style="text-align: center; font-size: 12px;">Date: {{ $sale->created_at->format('d M Y, H:i') }}</p>
        <hr style="margin: 5px 0;">

        <strong style="font-size: 12px;">Customer:</strong> <span style="font-size: 12px;">{{ $sale->customer->name }}</span><br>
        <strong style="font-size: 12px;">Address:</strong> <span style="font-size: 12px;">{{ $sale->customer->address ?? 'No address provided' }}</span><br>
        <strong style="font-size: 12px;">Contact:</strong> <span style="font-size: 12px;">{{ $sale->customer->contact ?? 'No contact provided' }}</span><br><br>

        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 3px; border-bottom: 1px solid #000;">No</th>
                    <th style="text-align: left; padding: 3px; border-bottom: 1px solid #000;">Item</th>
                    <th style="text-align: right; padding: 3px; border-bottom: 1px solid #000;">Price</th>
                    <th style="text-align: center; padding: 3px; border-bottom: 1px solid #000;">Qty</th>
                    <th style="text-align: right; padding: 3px; border-bottom: 1px solid #000;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->details as $detail)
                <tr>
                    <td style="text-align: left; padding: 3px;">{{ $loop->iteration }}</td>
                    <td style="text-align: left; padding: 3px;">{{ $detail->MenuItem->name }}</td>
                    <td style="text-align: right; padding: 3px;">Rp {{ number_format($detail->price, 2) }}</td>
                    <td style="text-align: center; padding: 3px;">{{ $detail->quantity }}</td>
                    <td style="text-align: right; padding: 3px;">Rp {{ number_format($detail->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <hr style="margin: 5px 0;">
        <div style="display: flex; justify-content: space-between; font-size: 12px; padding: 3px;">
            <span><strong>Total Before Discount:</strong></span>
            <span>Rp {{ number_format($totalBeforeDiscount, 2) }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 12px; padding: 3px;">
            <span><strong>Total Discount (Items):</strong></span>
            <span>Rp {{ number_format($totalItemDiscount, 2) }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 12px; padding: 3px;">
            <span><strong>Transaction Discount:</strong></span>
            <span>Rp {{ number_format($sale->discount, 2) }}</span>
        </div>
        <hr style="margin: 5px 0;">
        <div style="display: flex; justify-content: space-between; font-size: 14px; font-weight: bold; padding: 3px;">
            <span>Total After Discount:</span>
            <span>Rp {{ number_format($totalPriceAfterDiscount, 2) }}</span>
        </div>
        <hr style="margin: 5px 0;">
        <div style="text-align: center; font-size: 10px; margin-top: 10px;">
            <small>Thank you for shopping with us!</small>
        </div>
    </div>
</body>
</html>
