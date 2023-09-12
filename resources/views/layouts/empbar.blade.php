<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="{{ asset('assets/img/ts_logo.jpg') }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    @yield('title')
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- CSS Files -->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/now-ui-dashboard.css?v=1.5.1') }}" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="orange">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow |black"
    -->
    <div class="sidebar" data-color="dark-blue">
      <div class="logo">
        <a href="{{ url('/employee/vehicles') }}" class="simple-text logo-normal">
            <img src="{{ asset('storage/images/amvtsuKUK8PKSpZz1LvfqSL13YIbJSCv90KPx7kG.jpg') }}" alt="TS Tours | Rental Services Logo" class="img-fluid" style="max-width: 200px; max-height: 100px;">
            TS tours
        </a>
    </div>
    
        <div class="sidebar-wrapper" id="sidebar-wrapper">
            <ul class="nav">
                <li class="{{ Request::is('employee') ? 'active' : '' }}">
                    <a href="{{ route('employee.dashboard') }}">
                        <i class="now-ui-icons design_app"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="{{ Request::is('employee/vehicles') ? 'active' : '' }}">
                    <a href="{{ route('employee.vehicle') }}">
                        <i class="now-ui-icons transportation_bus-front-12"></i>
                        <p>Vehicles</p>
                    </a>
                </li>
                @if(Auth::guard('employee')->check() && Auth::guard('employee')->user()->accountType == 'Manager')
              <li class="{{ Request::is('employee/account') ? 'active' : '' }}">
                <a href="{{ route('employee.accounts') }}">
                    <i class="now-ui-icons users_circle-08"></i>
                    <p>Accounts</p>
                </a>
              </li>
               @endif
                <li class="{{ Request::is('employee/tariff') ? 'active' : '' }}">
                    <a href="{{ route('employee.tariff') }}">
                        <i class="now-ui-icons business_money-coins"></i>
                        <p>Tariffs</p>
                    </a>
                </li>
                <li class="{{ Request::is('employee/maintenance') ? 'active' : '' }}">
                    <a href="{{ route('employee.maintenance') }}">
                        <i class="now-ui-icons ui-2_settings-90"></i>
                        <p>Maintenance</p>
                    </a>
                </li>
                <li class="{{ Request::is('employee/booking-rental/booking') ? 'active' : '' }}">
                    <a href="{{ route('employee.booking') }}">
                        <i class="now-ui-icons shopping_delivery-fast"></i>
                        <p>Booking & Rental</p>
                    </a>
                </li>
                <li class="{{ Request::is('payment') ? 'active' : '' }}">
                    <a href="{{ url('payment') }}">
                        <i class="now-ui-icons shopping_credit-card"></i>
                        <p>Payment</p>
                    </a>
                </li>
                <li class="{{ Request::is('map') ? 'active' : '' }}">
                    <a href="{{ url('map') }}">
                        <i class="now-ui-icons location_map-big"></i>
                        <p>Map</p>
                    </a>
                </li>
                <li class="{{ Request::is('reports') ? 'active' : '' }}">
                    <a href="{{ url('reports') }}">
                        <i class="now-ui-icons files_paper"></i>
                        <p>Reports</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    </div>
    <div class="main-panel" id="main-panel" >
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent bg-primary navbar-absolute"  >
        <div class="container-fluid" >
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" style="font-size: 15px;margin-top:10px;">Employee Online System</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navigation" >
            <ul class="navbar-nav">
              <li class="nav-item">
                 <!-- Authentication Links -->
                 @guest('employee')
                 @if (Route::has('employee.login'))
                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('employee.login') }}">{{ __('Login') }}</a>
                     </li>
                 @endif
             @else
                 <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span style="font-size: 12px;"> <strong>{{Auth::guard('employee')->user()->firstName}} {{Auth::guard('employee')->user()->lastName}}</strong> </span>
                       
                    </a>

                     <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('employee.profile') }}">Profile</a>
                         <a class="dropdown-item" href="{{ route('employee.logout') }}"
                            onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">
                             {{ __('Logout') }}
                         </a>

                         <form id="logout-form" action="{{ route('employee.logout') }}" method="get" class="d-none">
                             @csrf
                         </form>
                     </div>
                 </li>
             @endguest
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="panel-header panel-header-sm">
        hi
      </div>
      <div class="content">
        @yield('content')
      </div>
      <footer class="footer">
        <div class=" container-fluid ">
          <nav>
            <ul>
              <li>
                <a href="https://www.creative-tim.com">
                  Creative Tim
                </a>
              </li>
              <li>
                <a href="http://presentation.creative-tim.com">
                  About Us
                </a>
              </li>
              <li>
                <a href="http://blog.creative-tim.com">
                  Blog
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright" id="copyright">
            &copy; <script>
              document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
            </script>, Designed by <a href="https://www.invisionapp.com" target="_blank">Invision</a>. Coded by <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a>.
          </div>
        </div>
      </footer>
    </div>
  </div>

<!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>

  <!--  Google Maps Plugin    -->
  <!--<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>-->
  <!-- Chart JS -->
  <!--<script src="../assets/js/plugins/chartjs.min.js"></script>-->
  <!--  Notifications Plugin    -->
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script><!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
  <script src="../assets/demo/demo.js"></script>
  <!--bootstrap-->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


  @yield('scripts')
</body>

</html>