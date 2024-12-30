@extends('nav.navmin')

@section('content')
    <style>
        .report-container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1, h3 {
            color: #1d2b64;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table thead {
            background: #1d2b64;
            color: #fff;
        }

        table tbody tr:hover {
            background: #f8f8f8;
        }

        .total-revenue {
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
            text-align: right;
        }

        .chart-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        canvas {
            max-width: 400px;
            max-height: 300px;
            margin: 0 auto;
            display: block;
        }
    </style>

    <div class="report-container">
        <h1>Laporan Penjualan</h1>

        <form action="{{ route('reports.sales') }}" method="GET" style="margin-bottom: 20px; text-align: center;">
            <label for="start_date">Tanggal Mulai:</label>
            <input type="date" name="start_date" id="start_date" value="{{ $startDate ?? '' }}" style="padding: 8px; margin-right: 10px;">

            <label for="end_date">Tanggal Akhir:</label>
            <input type="date" name="end_date" id="end_date" value="{{ $endDate ?? '' }}" style="padding: 8px; margin-right: 10px;">

            <button type="submit" style="background: #1d2b64; color: #fff; padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer;">Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>Produk (Jumlah)</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <ul>
                                @foreach ($transaction->items as $item)
                                    <li>{{ $item->product->name }} ({{ $item->quantity }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>Rp {{ number_format($transaction->total_amount, 2, ',', '.') }}</td>
                        <td>{{ ucfirst($transaction->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-revenue">
            Total Pendapatan (Completed): Rp {{ number_format($totalRevenue, 2, ',', '.') }}
        </div>

        <div class="chart-container">
            <div>
                <h3>Penjualan per Bulan</h3>
                <canvas id="salesByMonthChart" style="width: 560px; height: 420px;"></canvas>
            </div>
            <div>
                <h3>Status Transaksi</h3>
                <canvas id="transactionStatusChart" style="width: 240px; height: 180px;"></canvas> 
            </div>
        </div>
    </div>

    <script>
        const salesByMonthLabels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const salesByMonthCompletedData = {!! json_encode($salesByMonthCompleted->values()) !!};
        const salesByMonthCanceledData = {!! json_encode($salesByMonthCanceled->values()) !!};
    
        const salesByMonthChart = new Chart(document.getElementById('salesByMonthChart'), {
            type: 'bar',
            data: {
                labels: salesByMonthLabels,
                datasets: [
                    {
                        label: 'Completed',
                        data: salesByMonthCompletedData,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)', 
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Canceled',
                        data: salesByMonthCanceledData,
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    
        const transactionStatusLabels = {!! json_encode($transactionStatus->keys()) !!};
        const transactionStatusData = {!! json_encode($transactionStatus->values()) !!};
    
        const transactionStatusChart = new Chart(document.getElementById('transactionStatusChart'), {
            type: 'pie',
            data: {
                labels: transactionStatusLabels.map(status => status.charAt(0).toUpperCase() + status.slice(1)),
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: transactionStatusData,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
            }
        });
    </script>
@endsection
