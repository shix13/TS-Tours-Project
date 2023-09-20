<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TS Tours</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/ts_logo.jpg') }}">

    <!-- Add Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Add Font Awesome CSS -->
    <link rel="stylesheet" href="/css/fontawesome-free-6.4.2-web/css/all.min.css">
   
    <link rel="icon" href="{{ asset('images/TS TOURS.jpg') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Add Bootstrap 5 JavaScript (including Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/customer1.css') }}" rel="stylesheet">
    
</head>

<body style="font-family: 'Montserrat', sans-serif;" >
    <header>
        
        <!-- Bootstrap 5 Navbar -->
        <nav class="navbar navbar-expand-lg" >
            <div class="container-fluid" >
                <a class="navbar-brand logo" href="{{ route('home') }}"><img src="{{ asset('images/TS Tours.jpg') }}" alt="TS logo">TS Tours Services</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown" >
                    <ul class="navbar-nav ms-auto " >
                        @guest 
                        <li class="gbtnHollow nav-item {{ Request::is('/') ? 'active' : '' }}" ><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                        <li class="gbtnHollow  nav-item {{ Request::is('aboutus') ? 'active' : '' }}"><a class="nav-link" href="{{ route('aboutus') }}">About Us</a></li>
                        <li class="gbtnHollow nav-item {{ Request::is('fleet') ? 'active' : '' }}"><a class="nav-link" href="{{ route('fleet') }}">Vehicles</a></li>
                        <li class="gbtnHollow nav-item {{ Request::is('contactus') ? 'active' : '' }}"><a class="nav-link" href="{{ route('contactus') }}">Contact Us</a></li>
                        @if (Route::has('login'))
                            <li class="gbtnHollow nav-item {{ Request::is('login') ? 'active' : '' }}"><a class="nav-link" href="login">Login</a></li>
                        @endif
                        @else
                        <li class="nav-item {{ Request::is('home') ? 'active' : '' }}"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item {{ Request::is('aboutus') ? 'active' : '' }}"><a class="nav-link" href="{{ route('aboutus') }}">About Us</a></li>
                        <li class="nav-item {{ Request::is('fleet') ? 'active' : '' }}"><a class="nav-link" href="{{ route('fleet') }}">Vehicles</a></li>
                        <li class="nav-item {{ Request::is('contactus') ? 'active' : '' }}"><a class="nav-link" href="{{ route('contactus') }}">Contact Us</a></li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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
                    </ul>
                </div>
            </div>
        </nav>
        
    </header>
    <br>

    @yield('content')

    <script>
        new bootstrap.Collapse(document.querySelector('.navbar-collapse'));
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const navbarCollapse = document.querySelector(".navbar-collapse");
        const body = document.body;

        // Function to toggle the menu
        function toggleMenu() {
            if (navbarCollapse.classList.contains("collapsed")) {
                navbarCollapse.classList.remove("collapsed");
                body.classList.add("menu-open");
            } else {
                navbarCollapse.classList.add("collapsed");
                body.classList.remove("menu-open");
            }
        }

        // Add a click event listener to the navbar-toggler button
        document.querySelector(".navbar-toggler").addEventListener("click", toggleMenu);
    });
</script>


</body>
</html>
