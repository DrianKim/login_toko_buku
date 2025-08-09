<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h1 style="text-align: center;">Login Page</h1>
        @if(session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif
        <form action="" method="POST">
            @csrf
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
            </div>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
