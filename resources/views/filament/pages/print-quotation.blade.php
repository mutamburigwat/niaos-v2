<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation {{ $quotation->quote_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; font-size: 12px; line-height: 1.5; color: #1f2937; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 32px; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .header .status { font-size: 14px; padding: 4px 12px; border-radius: 9999px; text-transform: capitalize; }
        .status.draft { background: #f3f4f6; color: #6b7280; }
        .status.sent { background: #dbeafe; color: #1d4ed8; }
        .status.accepted { background: #dcfce7; color: #15803d; }
        .status.rejected { background: #fee2e2; color: #b91c1c; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 32px; }
        .info-grid dt { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; }
        .info-grid dd { font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th { text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; padding: 8px 12px; border-bottom: 2px solid #e5e7eb; }
        td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
        tbody tr:last-child td { border-bottom: none; }
        .amount { text-align: right; font-variant-numeric: tabular-nums; }
        .totals { margin-left: auto; width: 240px; }
        .totals tr:last-child td { font-weight: 700; font-size: 16px; border-top: 2px solid #1f2937; }
        .notes { margin-top: 32px; }
        .notes h3 { font-size: 14px; font-weight: 600; margin-bottom: 8px; }
        .notes p { color: #6b7280; font-size: 12px; }
        .footer { margin-top: 48px; text-align: center; font-size: 10px; color: #9ca3af; }
        @media print { body { padding: 20px; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:16px;">
        <button onclick="window.print()" style="padding:8px 16px;background:#1f2937;color:white;border:none;border-radius:6px;cursor:pointer;font-size:13px;">Print</button>
        <a href="{{ url('/admin/quotations') }}" style="margin-left:8px;padding:8px 16px;background:#e5e7eb;color:#1f2937;border:none;border-radius:6px;text-decoration:none;font-size:13px;">Back</a>
    </div>

    <div class="header">
        <div>
            <h1>Quotation</h1>
            <p style="color:#6b7280;font-size:14px;">{{ $quotation->quote_number }}</p>
        </div>
        <span class="status {{ $quotation->status }}">{{ $quotation->status }}</span>
    </div>

    <dl class="info-grid">
        <div>
            <dt>Customer</dt>
            <dd>{{ $quotation->customer?->name ?? '—' }}</dd>
            @if ($quotation->customer?->email)
                <dd style="font-size:12px;color:#6b7280;">{{ $quotation->customer->email }}</dd>
            @endif
        </div>
        <div>
            <dt>Valid Until</dt>
            <dd>{{ $quotation->valid_until?->format('M j, Y') ?? '—' }}</dd>
        </div>
        @if ($quotation->lead)
        <div>
            <dt>Related Lead</dt>
            <dd>{{ $quotation->lead->title }}</dd>
        </div>
        @endif
        <div>
            <dt>Currency</dt>
            <dd>{{ $quotation->currency ?? 'USD' }}</dd>
        </div>
    </dl>

    <table>
        <thead>
            <tr>
                <th style="width:50%;">Description</th>
                <th class="amount">Qty</th>
                <th class="amount">Unit Price</th>
                <th class="amount">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($quotation->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="amount">{{ $item->quantity }}</td>
                    <td class="amount">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="amount">{{ number_format($item->total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;color:#9ca3af;padding:24px;">No items</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="amount">{{ number_format($quotation->subtotal ?? 0, 2) }}</td>
        </tr>
        @if (($quotation->discount ?? 0) > 0)
            <tr>
                <td>Discount</td>
                <td class="amount">-{{ number_format($quotation->discount, 2) }}</td>
            </tr>
        @endif
        @if (($quotation->tax ?? 0) > 0)
            <tr>
                <td>Tax ({{ $quotation->tax }}%)</td>
                <td class="amount">{{ number_format(($quotation->subtotal - $quotation->discount) * $quotation->tax / 100, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td>Total</td>
            <td class="amount">{{ number_format($quotation->total ?? 0, 2) }}</td>
        </tr>
    </table>

    @if ($quotation->notes)
        <div class="notes">
            <h3>Notes</h3>
            <p>{{ $quotation->notes }}</p>
        </div>
    @endif

    <div class="footer">
        Generated on {{ now()->format('M j, Y \a\t g:i A') }}
    </div>
</body>
</html>
