@extends('layouts.empbar')

@section('title')
    TS | Add New Vehicle Type
@endsection

@section('content')
<br><br>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add New Vehicle Type</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('vehicleTypes.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="pic" class="custom-file-upload" style="color: black">
                                <span class="icon"><i class="fas fa-upload"></i> Select Photo</span>
                            </label>
                            <hr>
                            <input type="file" name="pic" id="pic" class="form-control @error('pic') is-invalid @enderror" accept="image/*" onchange="displayImage(this)">
                            @error('pic')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                            <img id="picPreview" src="#" alt="Selected Image" style="display: none; max-width: 100%; max-height: 200px;">
                        </div>

                        <div class="form-group">
                            <label for="vehicleType" style="color: black"><i class="fas fa-car"></i> Vehicle Type</label>
                            <input type="text" name="vehicle_type" id="vehicleType" class="form-control @error('vehicle_type') is-invalid @enderror" required>
                            @error('vehicle_type')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" style="color: black"><i class="fas fa-sticky-note"></i> Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4"></textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Vehicle Type</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function displayImage(input) {
        const picPreview = document.getElementById('picPreview');
        console.log('Function called'); // Debugging: Check if the function is called
        if (input.files && input.files[0]) {
            console.log('File selected:', input.files[0].name); // Debugging: Check the selected file
            const reader = new FileReader();
            reader.onload = function(e) {
                picPreview.src = e.target.result;
                picPreview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            console.log('No file selected'); // Debugging: Check if no file is selected
            picPreview.style.display = 'none';
        }
    }
</script>
@endsection
