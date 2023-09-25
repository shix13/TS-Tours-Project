@extends('layouts.empbar')

@section('title')
    TS | Add Vehicle
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 offset-md-0">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" style="color: red;">Add Vehicle</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('vehicles.save') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="pic" class="custom-file-upload">
                                <span class="icon">Select Photo</span>
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
                            <label for="registrationnumber">Registration Number</label>
                            <input type="text" name="registrationNumber" id="registrationnumber" class="form-control @error('registrationNumber') is-invalid @enderror" required>
                            @error('registrationNumber')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="unitname">Unit/Name</label>
                            <input type="text" name="unitName" id="unit_name" class="form-control @error('unitName') is-invalid @enderror" required>
                            @error('unitName')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="pax">Pax</label>
                            <input type="number" name="pax" id="pax" class="form-control @error('pax') is-invalid @enderror" required>
                            @error('pax')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="specification">Specifications</label>
                            <textarea name="specification" id="specification" class="form-control @error('specification') is-invalid @enderror" rows="4"></textarea>
                            @error('specification')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="Available">Available</option>
                                    <option value="Booked">Booked</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                                @error('status')
                                <span class="invalid-feedback" role="alert">
                                    **{{ $message }}
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="vehicleType">Vehicle Type</label>
                                <select name="vehicleType" id="vehicleType" class="form-control @error('vehicleType') is-invalid @enderror">
                                    @foreach($vehicleTypes as $type)
                                        <option value="{{ $type->vehicle_Type_ID }}">{{ $type->vehicle_Type }}</option>
                                    @endforeach
                                </select>
                                @error('vehicleType')
                                <span class="invalid-feedback" role="alert">
                                    **{{ $message }}
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-2" style="padding: 10px">
                                <a href="{{route ('vehicleTypes.view')}}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add New Type
                                </a>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add Vehicle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
