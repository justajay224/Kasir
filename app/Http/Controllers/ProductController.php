<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all(); 
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:products,name',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'discount' => 'nullable|numeric|min:0|max:100',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images/products', 'public');
            }

            Product::create($data);

            return redirect()
                ->route('products.create')
                ->with('success', 'Produk berhasil ditambahkan!')
                ->with('redirect', route('products.index'));
        } catch (\Exception) {
            return redirect()
                ->route('products.create')
                ->withErrors(['error' => 'Gagal Menambahkan Produk']);
        }
    }


    

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:products,name,' . $product->id,
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'discount' => 'nullable|numeric|min:0|max:100',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images/products', 'public');
            }

            $product->update($data);

            return redirect()
                ->route('products.edit', $product->id)
                ->with('success', 'Produk berhasil diperbarui!')
                ->with('redirect', route('products.index'));
        } catch (\Exception $e) {
            return redirect()
                ->route('products.edit', $product->id)
                ->withErrors(['error' => 'Gagal memperbarui produk: ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }


    public function salesReport(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;
    
        $query = Transaction::with('items.product');
    
        // Terapkan filter tanggal jika disediakan
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        $transactions = $query->get();
    
        $totalRevenue = $transactions->where('status', 'completed')->sum('total_amount');
    
        // Group data untuk grafik penjualan per bulan
        $salesByMonthCompleted = collect(range(1, 12))->mapWithKeys(function ($month) use ($transactions) {
            $monthTransactions = $transactions->where('status', 'completed')->filter(function ($transaction) use ($month) {
                return $transaction->created_at->month == $month;
            });
            return [$month => $monthTransactions->sum('total_amount')];
        });
        
        $salesByMonthCanceled = collect(range(1, 12))->mapWithKeys(function ($month) use ($transactions) {
            $monthTransactions = $transactions->where('status', 'canceled')->filter(function ($transaction) use ($month) {
                return $transaction->created_at->month == $month;
            });
            return [$month => $monthTransactions->sum('total_amount')];
        });

        $salesByMonthCompleted = collect(range(1, 12))->map(function ($month) use ($transactions) {
            return $transactions->where('status', 'completed')->whereBetween('created_at', [
                Carbon::create(now()->year, $month, 1)->startOfMonth(),
                Carbon::create(now()->year, $month, 1)->endOfMonth(),
            ])->sum('total_amount');
        });
        
        $salesByMonthCanceled = collect(range(1, 12))->map(function ($month) use ($transactions) {
            return $transactions->where('status', 'canceled')->whereBetween('created_at', [
                Carbon::create(now()->year, $month, 1)->startOfMonth(),
                Carbon::create(now()->year, $month, 1)->endOfMonth(),
            ])->sum('total_amount');
        });
        
        
    
        // Hitung status transaksi
        $transactionStatus = $transactions->groupBy('status')->map(function ($statusTransactions) {
            return $statusTransactions->count();
        });
    
        return view('reports.sales', compact(
            'transactions',
            'totalRevenue',
            'salesByMonthCompleted',
            'salesByMonthCanceled',
            'transactionStatus',
            'startDate',
            'endDate'
        ));
    }


}

