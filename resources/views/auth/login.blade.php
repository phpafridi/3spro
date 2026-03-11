{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Software - MG Khyber Motors</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <style>
        .wrapper.fadeInDown {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f5f5f5;
        }
        #formContent {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .fadeIn {
            text-align: center;
            margin-bottom: 20px;
        }
        .fadeIn img {
            max-width: 150px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #c00;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background: #900;
        }
        #formFooter {
            text-align: center;
            margin-top: 20px;
        }
        .underlineHover {
            color: #666;
            text-decoration: none;
        }
        .underlineHover:hover {
            color: #c00;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<div class="wrapper fadeInDown">
    <div id="formContent">


        {{-- Display Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        {{-- Display Session Error --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Icon --}}
        <div class="fadeIn first">
            <img src="{{ asset(env('APP_LOGO', 'src/3spro.png')) }}" alt="Logo">
        </div>

        {{-- Login Form --}}
        <form method="POST" action="{{ route('p_login') }}">
            @csrf
            <input type="hidden" name="myname" value="1">
            <input type="text" class="fadeIn second" name="user_name" placeholder="Username" value="{{ old('user_name') }}" required>
            <input type="password" class="fadeIn third" name="password" placeholder="Password" required>
            <input type="submit" class="fadeIn fourth" value="Log In">
        </form>

        {{-- Footer --}}
        <div id="formFooter">
            <a class="underlineHover" href="#">© {{ date('Y') }} 3spro. All Rights Reserved.</a>
        </div>

    </div>
</div>

</body>
</html>
