<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="{{ asset('assets/img/ts_logo.jpg') }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>TS Tours</title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/fontawesome-free-6.4.2-web/css/all.min.css') }}">
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/now-ui-dashboard.css?v=1.5.1') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/employee-dashboard.css') }}" rel="stylesheet" />
  
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body {
      background: #001f3f;
      /*color: #fff;*/
    }

    .card-login {
      background-color: #fff;
    }

    .card-login h1 {
      color: #001f3f;
    }

    .card-login label {
      font-size: 1.6rem;
      color: #444;
    }

    .navbar-brand img {
      max-height: 40px;
      margin-right: 10px;
    }

    .navbar-brand h3 {
      font-weight: 700;
      color: #fff;
      margin: 0;
    }

    .navbar-brand-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .navbar-brand span {
      font-size: 18px;
      color: #fff;
      margin-left: 10px;
    }

    .sticky {
    position: fixed;
    top: 0;
    z-index: 100;
    width: 100%;
    }
  </style>
</head>

<body>
  <nav class="navbar sticky" style="background: midnightblue; font-weight: 700;" id="navbar">
    <a class="navbar-brand" href="#">
      <div class="navbar-brand-wrapper">
        <img src="{{ asset('images/amvtsuKUK8PKSpZz1LvfqSL13YIbJSCv90KPx7kG.jpg') }}" alt="Company Logo">
        <h3>TS Tours</h3>
      </div>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fa-solid fa-bars" style="color: #ffffff;"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <div class="col-md-7 mt-2 mt-md-0">
      <div class="form-row bg-transparent p-3 rounded">
          <a class="dropdown-item" href="{{ route('driver.active') }}">
              {{ __('Active Tasks') }}
          </a>
      </div>
      <div class="form-row bg-transparent p-3 rounded">
          <a class="dropdown-item" href="{{ route('driver.upcoming') }}">
              {{ __('Upcoming Tasks') }}
          </a>
      </div>
      <div class="form-row bg-transparent p-3 rounded">
          <a class="dropdown-item" href="{{ route('driver.logout') }}">
              {{ __('Logout') }}
          </a>
      </div>
      </div>
    </div>
  </nav>
    <main style="margin-top:80px;" class="py-4">
    @yield('content')
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      /*
      $(document).ready(function() {
        // Get the initial position of the navbar
        var navbar = $('#navbar');
        var offset = navbar.offset().top;

        // Function to add/remove the sticky class
        function toggleStickyNavbar() {
            if ($(window).scrollTop() > offset) {
                navbar.addClass('sticky');
            } else {
                navbar.removeClass('sticky');
            }
        }

        // Initial call to the function
        toggleStickyNavbar();

        // Call the function when the user scrolls
        $(window).scroll(function() {
            toggleStickyNavbar();
        });
      });
      */
    </script>
</body>

</html>
