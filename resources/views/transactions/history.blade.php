@extends('nav.navbar')

@section('content')
    <div style="background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h1 style="text-align: center; color: #1d2b64; margin-bottom: 20px;">Riwayat Transaksi</h1>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <thead>
                    <tr style="background-color: #1d2b64; color: #fff;">
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">ID Transaksi</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Waktu Pembelian</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Produk</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Harga Jual</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Total</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Status</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $transaction->id }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <ul style="list-style-type: none; padding: 0; margin: 0;">
                                    @foreach ($transaction->items as $item)
                                        <li>{{ $item->product->name }} ({{ $item->quantity }} x Rp {{ number_format($item->price, 2, ',', '.') }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                @foreach ($transaction->items as $item)
                                    Rp {{ number_format($item->price, 2, ',', '.') }}<br>
                                @endforeach
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;">Rp {{ number_format($transaction->total_amount, 2, ',', '.') }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd; text-transform: capitalize;">{{ $transaction->status }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                @if ($transaction->status == 'canceled')
                                    <span style="color: #888;">Tidak ada aksi</span>
                                @else
                                    <form action="{{ route('transactions.cancel', $transaction->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?');">
                                        @csrf
                                        <button type="submit" style="background-color: #ff4d4d; color: #fff; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">Batalkan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
