<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $products = Product::when($query, function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%");
        })->get();

        return view('transactions.index', compact('products'));
    }

    public function cancelTransaction(Transaction $transaction)
    {
        foreach ($transaction->items as $item) {
            $product = $item->product;
            $product->increment('stock', $item->quantity);
        }
    
        $transaction->update([
            'status' => 'canceled',
        ]);
    
        return redirect()->route('transactions.history')->with('success', 'Transaksi berhasil dibatalkan.');
    }
    



    public function complete(Transaction $transaction)
    {
        $transaction->update(['status' => 'completed']);
        return redirect()->route('transactions.index');
    }

    public function history()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
        return view('transactions.history', compact('transactions'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $cart = session()->get('cart', []);
        $product = Product::findOrFail($request->input('product_id'));
        $quantity = $request->input('quantity');
    
        // Hitung stok yang tersedia dengan mempertimbangkan jumlah di keranjang
        $currentCartQuantity = isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0;
        $availableStock = $product->stock + $currentCartQuantity;
    
        // Periksa apakah jumlah baru melebihi stok yang tersedia
        if ($quantity + $currentCartQuantity > $availableStock) {
            return redirect()->route('transactions.index')->with('error', 'Jumlah total melebihi stok yang tersedia.');
        }
    
        // Jika produk sudah ada di keranjang, perbarui jumlahnya
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            // Tambahkan produk baru ke keranjang
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'discount' => $product->discount,
                'quantity' => $quantity,
            ];
        }
    
        session()->put('cart', $cart);
    
        // Kurangi stok produk di database
        $product->decrement('stock', $quantity);
    
        return redirect()->route('transactions.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }
    


    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $product = Product::findOrFail($productId);
            $product->increment('stock', $cart[$productId]['quantity']);
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('transactions.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    

    public function showCart()
    {
        $cart = session()->get('cart', []);
        return view('transactions.cart', compact('cart'));
    }

    public function completeTransaction(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('transactions.index')->with('error', 'Keranjang kosong.');
        }

        $transaction = Transaction::create([
            'status' => 'completed',
            'total_amount' => collect($cart)->sum(function ($item) {
                $discountedPrice = $item['price'] * (1 - $item['discount'] / 100);
                return $discountedPrice * $item['quantity'];
            }),
        ]);

        foreach ($cart as $productId => $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Bersihkan keranjang
        session()->forget('cart');

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diselesaikan.');
    }


}

