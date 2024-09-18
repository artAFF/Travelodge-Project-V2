{{-- <!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login to MIS</title>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        #background-video {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 100px auto;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #dddfe2;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <video autoplay muted loop id="background-video">
        <source src="{{ asset('videos/travelodge-background.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="overlay"></div>
    <div class="login-container">
        <h2>Login to MIS</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="text" name="name" placeholder="Username" required value="{{ old('name') }}">
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
            <input type="password" name="password" placeholder="Password" required>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
            <div>
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember Me</label>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html> --}}

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login to MIS</title>
    <style>
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #dddfe2;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login to MIS</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="text" name="name" placeholder="Username" required value="{{ old('name') }}">
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
            <input type="password" name="password" placeholder="Password" required>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
            <div>
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember Me</label>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>
