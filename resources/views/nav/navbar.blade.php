<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyPOS')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(90deg, #1d2b64 5%, #f8cdda);
            color: #333;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar a {
            text-decoration: none;
            color: #fff;
            margin: 0 15px;
            font-weight: bold;
            transition: color 0.3s ease, text-decoration 0.3s ease;
        }

        .navbar a:hover {
            color: #9e9e9e;
        }

        .content {
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
            color: #333;
        }

        .admin-button {
            background-color: #28a745;
            color: #fff;
            padding: 10px 15px;
            border-radius: 4px;
            border: none;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .admin-button:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                text-align: center;
            }

            .navbar a {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <a href="#">MyPOS</a>
        </div>
        <div class="links">
            <a href="{{ route('transactions.index') }}">Transaksi</a>
            <a href="{{ route('transactions.history') }}">Riwayat</a>
            <a href="{{ route('admin.login') }}" class="admin-button">Halaman Admin</a>
        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>
</body>
</html>
