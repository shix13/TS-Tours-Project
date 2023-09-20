
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="{{ asset('assets/img/ts_logo.jpg') }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    TS Tours Services
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;500;700&family=Open+Sans:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- CSS Files -->
  <link href="./assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="./assets_emp/css/now-ui-kit.css?v=1.3.0" rel="stylesheet" />

  
</head>

<body class="index-page sidebar-collapse">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-white fixed-top " >
    <div class="container">
        <div class="navbar-translate">
          <a class="navbar-brand" href="#" rel="tooltip"  data-placement="bottom" target="_blank"><img src="{{ asset('images/TS Tours.jpg') }}" alt="TS logo" style="width: 60px;border-radius:20%">
            TS Tours Services
          </a>
            <button class="navbar-toggler navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navigation" aria-controls="navigation-index" aria-expanded="false"
                aria-label="Toggle navigation" >
                <span class="navbar-toggler-bar top-bar " ></span>
                <span class="navbar-toggler-bar middle-bar"></span>
                <span class="navbar-toggler-bar bottom-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navigation"
            data-nav-image="">
            <ul class="navbar-nav ms-auto">
              @guest
              <li class="nav-item custom-nav-item {{ Request::is('/') ? 'active' : '' }}"><a class="nav-link"
                      href="{{ route('home') }}">Home</a></li>
              <li class="nav-item custom-nav-item {{ Request::is('aboutus') ? 'active' : '' }}"><a class="nav-link"
                      href="{{ route('aboutus') }}">About Us</a></li>
              <li class="nav-item custom-nav-item {{ Request::is('fleet') ? 'active' : '' }}"><a class="nav-link"
                      href="{{ route('fleet') }}">Vehicles</a></li>
              <li class="nav-item custom-nav-item {{ Request::is('contactus') ? 'active' : '' }}"><a class="nav-link"
                      href="{{ route('contactus') }}">Contact Us</a></li>
              @if (Route::has('login'))
              <li class="nav-item custom-nav-item {{ Request::is('login') ? 'active' : '' }}"><a class="nav-link"
                      href="login">Login</a></li>
              @endif
              @else
              <li class="nav-item custom-nav-item {{ Request::is('home') ? 'active' : '' }}"><a class="nav-link"
                      href="{{ route('home') }}">Home</a></li>
              <li class="nav-item custom-nav-item {{ Request::is('aboutus') ? 'active' : '' }}"><a class="nav-link"
                      href="{{ route('aboutus') }}">About Us</a></li>
              <li class="nav-item custom-nav-item {{ Request::is('fleet') ? 'active' : '' }}"><a class="nav-link"
                      href="{{ route('fleet') }}">Vehicles</a></li>
              <li class="nav-item custom-nav-item {{ Request::is('contactus') ? 'active' : '' }}"><a class="nav-link"
                      href="{{ route('contactus') }}">Contact Us</a></li>
              <li class="nav-item dropdown custom-nav-item" >
                  <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink1" data-toggle="dropdown">
                     Account
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
  <!-- End Navbar -->
  <div class="wrapper">
    <div style="margin-top:110px">

    
    
    @yield('content')
  <br>
  </div>
    
  </div>
  <!--   Core JS Files   -->
  <script src="./assets/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="./assets/js/core/popper.min.js" type="text/javascript"></script>
  <script src="./assets/js/core/bootstrap.min.js" type="text/javascript"></script>
  <!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
  <script src="./assets/js/plugins/bootstrap-switch.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="./assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
  <!--  Plugin for the DatePicker, full documentation here: https://github.com/uxsolutions/bootstrap-datepicker -->
  <script src="./assets/js/plugins/bootstrap-datepicker.js" type="text/javascript"></script>
  <!-- Control Center for Now Ui Kit: parallax effects, scripts for the example pages etc -->
  <script src="./assets/js/now-ui-kit.js?v=1.3.0" type="text/javascript"></script>
  <script>


    function scrollToDownload() {

      if ($('.section-download').length != 0) {
        $("html, body").animate({
          scrollTop: $('.section-download').offset().top
        }, 1000);
      }
    }
  </script>
</body>

</html>