@extends('nav.navmin')

@section('content')
    <style>
        form {
            max-width: 600px;
            margin: 0 auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input[type="text"], form input[type="number"], form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        form button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        form button:hover {
            background-color: #218838;
        }

        .btn-back {
            margin-left: 30px;
            display: inline-block;
            padding: 8px 12px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }

        img {
            margin-top: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>

    <a href="{{ route('products.index') }}" class="btn-back">&larr; Kembali</a>

    <h1 style="text-align: center; color: #1d2b64;">Edit Produk</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(function() {
                window.location.href = "{{ session('redirect') }}";
            }, 2000);
        </script>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Nama:</label>
        <input type="text" name="name" value="{{ $product->name }}" required>

        <label>Harga:</label>
        <input type="number" name="price" step="0.01" value="{{ $product->price }}" required>

        <label>Stok:</label>
        <input type="number" name="stock" value="{{ $product->stock }}" required>

        <label>Diskon (%):</label>
        <input type="number" name="discount" min="0" max="100" step="1" value="{{ $product->discount ?? 0 }}">

        @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk" width="100">
        @endif
        <label>Gambar:</label>
        <input type="file" name="image">

        <button type="submit">Simpan</button>
    </form>
@endsection
