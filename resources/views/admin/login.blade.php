<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1d2b64, #f8cdda);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h1 {
            text-align: center;
            color: #1d2b64;
            margin-bottom: 20px;
        }

        .login-container label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #1d2b64;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: #d484ec;
            color: #333;
        }

        .error-message {
            color: #ff4d4d;
            text-align: center;
            margin-top: -15px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #1d2b64;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .back-button:hover {
            color: #c44ae9;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login Admin</h1>
        <form action="{{ route('admin.authenticate') }}" method="POST">
            @csrf
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>

            <a href="{{ url()->previous() }}" class="back-button">Kembali</a>
        </form>

        @if ($errors->any())
            <p class="error-message">{{ $errors->first() }}</p>
        @endif
    </div>
</body>
</html>
