@extends('nav.navbar')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 5px;">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="container" style="display: flex; justify-content: space-between; gap: 20px; margin-top: 20px; background: linear-gradient(135deg, #ffffff, #f3f3f3); border-radius: 8px; padding: 20px;">
        <div class="card" style="flex: 1; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <h3 style="color: #1d2b64;">Daftar Produk</h3>
            <form action="{{ route('transactions.index') }}" method="GET" style="margin-bottom: 20px; display: flex; gap: 10px;">
                <label for="search" style="font-weight: bold; align-self: center;">Cari Produk:</label>
                <input type="text" id="search" name="search" placeholder="Masukkan nama produk" value="{{ request('search') }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; flex: 1;">
                <button type="submit" style="background-color: #1d2b64; color: #fff; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">Cari</button>
            </form>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd; background: #ffffff;">
                <thead>
                    <tr style="background-color: #1d2b64; color: #fff;">
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Gambar</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Nama</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Harga</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Stok</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Diskon</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk" style="width: 50px; border-radius: 4px;">
                                @else
                                    Tidak ada gambar
                                @endif
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $product->name }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">Rp {{ number_format($product->price, 2, ',', '.') }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $product->stock }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $product->discount }}%</td>
                            <td style="padding: 10px; border: 1px solid #ddd; display: flex; align-items: center;">
                                @if ($product->stock > 0)
                                    <form action="{{ route('transactions.addToCart') }}" method="POST" style="display: flex; gap: 5px;">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="number" name="quantity" min="1" max="{{ $product->stock }}" required style="padding: 6px; width: 60px; border: 1px solid #ddd; border-radius: 4px;">
                                        <button type="submit" style="background-color: #1d2b64; color: #fff; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">+</button>
                                    </form>
                                @else
                                    <button type="button" disabled style="background-color: #ccc; color: #666; border: none; padding: 8px 12px; border-radius: 4px; cursor: not-allowed;">Habis</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 10px; text-align: center; border: 1px solid #ddd; background: #f9f9f9;">Produk tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card cart" style="flex: 0.5; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <h3 style="color: #1d2b64;">Keranjang</h3>
            @if (session('cart') && count(session('cart')) > 0)
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd; background: #ffffff;">
                    <thead>
                        <tr style="background-color: #1d2b64; color: #fff;">
                            <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Nama</th>
                            <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Harga</th>
                            <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Jumlah</th>
                            <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Total</th>
                            <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach (session('cart') as $productId => $item)
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px; border: 1px solid #ddd;">{{ $item['name'] }}</td>
                                <td style="padding: 10px; border: 1px solid #ddd;">Rp {{ number_format($item['price'], 2, ',', '.') }}</td>
                                <td style="padding: 10px; border: 1px solid #ddd;">{{ $item['quantity'] }}</td>
                                <td style="padding: 10px; border: 1px solid #ddd;">Rp {{ number_format($item['price'] * (1 - $item['discount'] / 100) * $item['quantity'], 2, ',', '.') }}</td>
                                <td style="padding: 10px; border: 1px solid #ddd;">
                                    <form action="{{ route('transactions.removeFromCart', $productId) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?');">
                                        @csrf
                                        <button type="submit" style="background-color: #ff4d4d; color: #fff; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @php $total += $item['price'] * (1 - $item['discount'] / 100) * $item['quantity']; @endphp
                        @endforeach
                    </tbody>
                </table>
                <div class="total" style="text-align: right; font-weight: bold; font-size: 18px; margin-top: 10px;">Total: Rp {{ number_format($total, 2, ',', '.') }}</div>
                <div class="checkout" style="text-align: center; margin-top: 20px;">
                    <form action="{{ route('transactions.complete') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan transaksi ini?');">
                        @csrf
                        <button type="submit" style="background-color: #28a745; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px; cursor: pointer;">Selesai</button>
                    </form>
                </div>
            @else
                <p style="text-align: center; color: #555;">Keranjang kosong.</p>
            @endif
        </div>        
    </div>
@endsection
