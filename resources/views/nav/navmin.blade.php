<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1d2b64, #f8cdda);
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .sidebar {
            width: 200px;
            background: linear-gradient(190deg, #1d2b64 , #4459aa);
            color: #fff;
            height: 100vh;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background: #d943df;
            color: #1d2b64;
        }

        .logout {
            margin-bottom: 45px;
        }

        .logout a {
            background-color: #dc3545;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            display: block;
            transition: background 0.3s ease;
            cursor: pointer;
        }

        .logout a:hover {
            background-color: #c82333;
        }

        .logout a:active {
            transform: scale(0.95);
        }

        .main-content {
            margin-left: 220px;
            flex: 1;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            margin-top: 20px;
            margin-right: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 10px;
            }

            .sidebar h2 {
                font-size: 18px;
                margin-bottom: 10px;
            }

            .main-content {
                margin-left: 0;
                margin-top: 10px;
            }

            .logout {
                margin-top: 15px;
            }
        }
    </style>
    <script>
        function confirmLogout(event) {
            if (!confirm('Apakah Anda yakin ingin logout?')) {
                event.preventDefault();
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar">
        <div>
            <h2>Admin Panel</h2>
            <a href="{{ route('products.index') }}">Daftar Produk</a>
            <a href="{{ route('reports.sales') }}">Laporan</a>
        </div>
        <div class="logout">
            <a href="{{ route('logout') }}" onclick="confirmLogout(event)">Logout</a>
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>
</body>
</html>
