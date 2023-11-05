@extends('layouts.empbar')

@section('title')
    TS | Edit Vehicle Type
@endsection

@section('content')
<br><br>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Vehicle Type</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('vehiclesTypes.update', ['vehicle_type' => $vehicleType->vehicle_Type_ID]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="pic"  class="custom-file-upload" style="color: black">
                                <span class="icon"><i class="fas fa-upload"></i> Select Photo</span>
                            </label>
                            <hr>
                            <input type="file" name="pic" id="pic" class="form-control @error('pic') is-invalid @enderror" accept="image/*" onchange="displayImage(this)">
                            @error('pic')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                            <img id="picPreview" src="{{ asset('storage/' . $vehicleType->pic) }}" alt="Selected Image" style="max-width: 100%; max-height: 200px;">
                        </div>

                        <div class="form-group">
                            <label for="vehicleType"  style="color: black"><i class="fas fa-car"></i>Vehicle Type</label>
                            <input type="text" style="background: white; color: black" name="vehicle_type" value="{{ $vehicleType->vehicle_type }}" placeholder="{{ $vehicleType->vehicle_Type }}" id="vehicleType" class="form-control @error('vehicle_type') is-invalid @enderror" readonly>
                            @error('vehicle_type')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" style="color: black"><i class="fas fa-sticky-note"></i> Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ $vehicleType->description }}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Vehicle Type</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function displayImage(input) {
        var picPreview = document.getElementById('picPreview');
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                picPreview.src = e.target.result;
                picPreview.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
