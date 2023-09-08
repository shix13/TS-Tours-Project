@extends('layouts.empbar')

@section('title')
    TS | Profile
@endsection

@section('content')
<br>
<br>
    <div class="content">
        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Edit Profile</h5>
              </div>
              <div class="card-body">
                <form>
                  
                            
                
                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" placeholder="Company" value="Mike">
                      </div>
                    </div>
                    <div class="col-md-6 pl-1">
                      <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" placeholder="Last Name" value="Andrew">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3 pr-1">
                      <div class="form-group">
                        <label>Account Type</label>
                        <input type="text" disabled class="form-control"  value="{{$employee->accountType}}">
                      </div>
                    </div>
                    <div class="col-md-5 pl-1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" value="{{$employee->email}}">
                      </div>
                    </div>
                  
                  <div class="col-md-4 pl-1">
                    <div class="form-group">
                      <label for="email">Mobile Number</label>
                      <input type="email" class="form-control" value="{{$employee->mobileNum}}">
                    </div>
                  </div>
                </div>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changePasswordModal">
                  Change Password
              </button>
              <button type="button" class="btn btn-danger">Save Changes</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card card-user">
              <div class="image">
                <img src="{{ asset('storage/profile_images/def.png') }}" alt="Background Img">
              </div>
              <div class="card-body">
                <div class="author">
                  <div class="form-group">
                    
                    <input type="file" name="profile_img" id="profile_img" class="form-control @error('profile_img') is-invalid @enderror" accept="image/*" onchange="displayProfileImage(this)">
                    @error('profile_img')
                    <span class="invalid-feedback" role="alert">
                        **You forgot to select a profile image
                    </span>
                    @enderror
                
                    @if ($employee->profile_img)
                    <!-- Display the profile_img if it's not null -->
                    <img id="profileImgPreview" src="{{ asset('storage/' . $employee->profile_img) }}" alt="Profile Image" style="max-width: 100%; max-height: 200px;border-radius:50%;">
                    @else
                    <!-- Display a default image or message if profile_img is null -->
                    <p>No profile image available.</p>
                    @endif <br><br>
                    <label for="profile_img" class="custom-file-upload">
                      <span class="icon"> Upload New Profile Picture</span> 
                  </label>
                </div> 
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection

@section('scripts')
<script>
  function displayProfileImage(input) {
      const profileImgPreview = document.getElementById('profileImgPreview');
      console.log('Function called'); // Debugging: Check if the function is called
      if (input.files && input.files[0]) {
          console.log('File selected:', input.files[0].name); // Debugging: Check the selected file
          const reader = new FileReader();
          reader.onload = function(e) {
              profileImgPreview.src = e.target.result;
              profileImgPreview.style.display = 'block';
          };
          reader.readAsDataURL(input.files[0]);
      } else {
          console.log('No file selected'); // Debugging: Check if no file is selected
          profileImgPreview.style.display = 'none';
      }
  }
</script>

@endsection