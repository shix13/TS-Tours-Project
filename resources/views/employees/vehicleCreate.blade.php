@extends('layouts.empbar')

@section('title')
    TS | Add Vehicle
@endsection

@section('content')



      
@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="container">
    <div class="row">
        <div class="col-md-12 offset-md-0">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" style="color: red;font-weight:700">Add Vehicle</h4>
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
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label for="registrationnumber">License Plate Number</label>
                            <input type="text" name="registrationNumber" id="registrationnumber" class="form-control @error('registrationNumber') is-invalid @enderror" required>
                            @error('registrationNumber')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="unitname">Unit/Name</label>
                            <input type="text" name="unitName" id="unit_name" class="form-control @error('unitName') is-invalid @enderror" required>
                            @error('unitName')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="pax">Pax</label>
                            <input type="number" name="pax" id="pax" class="form-control @error('pax') is-invalid @enderror" min="1" required>
                            @error('pax')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-2">
                            <label for="yearModel">Year Model</label>
                            <input type="number" name="yearModel" id="yearModel" class="form-control @error('yearModel') is-invalid @enderror" min="1950" required>
                            @error('yearModel')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    

                        <div class="form-group col-md-2">
                            <label for="color">Color</label>
                            <input type="text" name="color" id="color" class="form-control @error('color') is-invalid @enderror" required>
                            @error('color')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-2">
                            <label for="ownership_type">Ownership Type</label>
                            <select name="ownership_type" id="ownership_type" class="form-control @error('ownership_type') is-invalid @enderror">
                                <option value="Owned" {{ old('ownership_type') == 'Owned' ? 'selected' : '' }}>Owned</option>
                                <option value="Outsourced" {{ old('ownership_type') == 'Outsourced' ? 'selected' : '' }}>Outsourced</option>
                            </select>
                            @error('ownership_type')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    
                        <div class="form-group col-md-4" id="outsourced_from_container" style="{{ old('ownership_type') == 'Outsourced' ? '' : 'display: none;' }}">
                            <label for="outsourced_from">Outsourced From</label>
                            <input type="text" name="outsourced_from" id="outsourced_from" class="form-control @error('outsourced_from') is-invalid @enderror" value="{{ old('outsourced_from') }}">
                            @error('outsourced_from')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
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
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
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

     // JavaScript to toggle visibility of outsourced_from field based on ownership_type selection
     document.getElementById('ownership_type').addEventListener('change', function() {
            var outsourcedContainer = document.getElementById('outsourced_from_container');
            if (this.value === 'Outsourced') {
                outsourcedContainer.style.display = '';
            } else {
                outsourcedContainer.style.display = 'none';
            }
        });
</script>
@endsection
