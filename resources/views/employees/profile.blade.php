@extends('layouts.empbar')

@section('title')
    TS | Change Password
@endsection

@section('content')
  <style>
    .ts_input{
    color: black !important;
    }

    
  </style>
<br><br>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2" style="font-size: 16px;">
                <div class="card">
                    <div class="card-header" style="font-size: 25px;font-weight:700;color:orangered"><i class="fa-solid fa-key"></i> Change Password</div>
                  
                    <div class="card-body">
                        <!-- Password change form goes here -->
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                          
                            <div class="form-group">
                                <label for="current_password" class="ts_input" >Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="password" class="ts_input">New Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="ts_input">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                            </div>
                          
                            <button type="button" id="togglePassword" class="btn btn-info"><i class="fas fa-eye"></i> Show Password</button>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-lock"></i> Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
      $('#togglePassword').on('click', function() {
          togglePasswordVisibility('current_password');
          togglePasswordVisibility('password');
          togglePasswordVisibility('password_confirmation');
          toggleButtonText();
      });

      function togglePasswordVisibility(inputId) {
          const passwordInput = $('#' + inputId);
          const type = passwordInput.attr('type');

          if (type === 'password') {
              passwordInput.attr('type', 'text');
          } else {
              passwordInput.attr('type', 'password');
          }
      }

      function toggleButtonText() {
          const toggleButton = $('#togglePassword');
          const currentText = toggleButton.text();

          if (currentText.includes('Show')) {
              toggleButton.html('<i class="fas fa-eye-slash"></i> Hide Password');
              toggleButton.removeClass('btn-info').addClass('btn-danger');
          } else {
              toggleButton.html('<i class="fas fa-eye"></i> Show Password');
              toggleButton.removeClass('btn-danger').addClass('btn-info');
          }
      }
  });
</script>

@endsection
