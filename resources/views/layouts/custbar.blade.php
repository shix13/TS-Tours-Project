<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TS Tours</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/ts_logo.jpg') }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">

    <link href="{{ asset('css/customer.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/TS TOURS.jpg') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <header>
        <div class="logo">
            
            <a href="/"><img src="{{ asset('images/TS Tours.jpg') }}" alt="TS logo"></a>
            <h1>TS Tours</h1>
        </div>
        <nav>
            @guest
                <button class="gbtnHollow" data-href="aboutus">About Us</button>
                <button class="gbtnHollow" data-href="fleet">Vehicles</button>
                <button class="gbtnHollow" data-href="contactus">Contact Us</button>

            @if (Route::has('login'))
                <button class="gbtnHollow" data-href="login">Login</button>
            @endif

            @if (Route::has('register'))
                <button class="gbtnHollow" data-href="register">Register</button>
            @endif
            
            @else
                <button class="gbtnHollow" data-href="aboutus">About Us</button>
                <button class="gbtnHollow" data-href="bookvehicle">Browse Vehicles</button>
                <button class="gbtnHollow" data-href="contactus">Contact Us</button>
                
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->firstName }}                           
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('bookingdashboard') }}">
                            {{ __('My Bookings') }}
                        </a>

                        <a class="dropdown-item" href="{{ route('user.logout') }}"
                            onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </nav>
    </header>
    <br>

    @yield('content')

    <script>
    // Add click event listeners to navigation buttons
    const buttons = document.querySelectorAll('.gbtnHollow');

    buttons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default link behavior

            // Add a class to trigger the transition
            document.body.classList.add('page-leave');

            // Delay the actual page redirection by a short time
            setTimeout(() => {
                window.location.href = event.target.getAttribute('data-href');
            }, 300); // Adjust the delay time (in milliseconds) as needed
        });
    });
    </script>

</body>
</html>
