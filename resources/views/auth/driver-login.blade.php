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


  <!-- CSS STYLE -->
  <style>
    body {
      background: linear-gradient(to bottom, #001f3f, #001a35);
      /* Adjust gradient colors as needed */
      color: #fff; /* Set text color to white for better contrast */
    }

    .card-login {
      background-color: #fff; /* Set background color to white */
    }

    .card-login h1 {
      color: #001f3f; /* Set text color to navy blue */
    }

    .card-login label {
      font-size: 1.6rem; /* Set font size to 1.6 times larger than the default */
      color: #444; /* Set a slightly darker gray color */
    }
  </style>
</head>

<body class="bg-light">
  <div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">
      <div class="col-md-6">
        <div class="card card-login">
          <div class="card-body text-center">
            <img src="{{ asset('images/amvtsuKUK8PKSpZz1LvfqSL13YIbJSCv90KPx7kG.jpg') }}" alt="Company Logo" class="img-fluid mb-4" style="max-height: 100px;" />
            <h1 class="text-center">Driver Login</h1>
            <form id="loginForm" onsubmit="return validateForm()" method="POST" action="{{ route('driver.login.submit') }}">
            @csrf
              <div class="form-group">
                <label for="mobileNum">Mobile Number</label>
                <input name="mobileNum" type="text" class="form-control @error('mobileNum') is-invalid @enderror" id="mobileNum" placeholder="Enter your mobile number" required>
                @error('mobileNum')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Enter your password" required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Core JS Files -->
  <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>
  <script src="../assets/js/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script>
  <script src="../assets/demo/demo.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


  <!-- Script for Required Input Fields -->
  <script>
    function validateForm() {
      var mobileNum = document.getElementById('mobileNum').value;
      var password = document.getElementById('password').value;

      if (mobileNum === '' || password === '') {
        alert('Mobile Number and Password are required!');
        return false;
      }

      return true;
    }
  </script>

</body>

</html>
