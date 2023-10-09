@extends('layouts.empbar')

@section('title')
    TS | Edit Vehicle
@endsection

@section('content')
<div class="container">
    @if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
    
<div class="row">
    <div class="col-md-12 offset-md-0">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title" style="color: red; font-weight: 700;">
                    <i class="fas fa-car"></i> Edit Vehicle
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('vehicles.update', $vehicle->unitID) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="pic" class="custom-file-upload" style="color: black">
                            <i class="fas fa-upload"></i> Upload New Photo
                        </label>
                        <hr>
                        <input type="file" name="pic" id="pic" class="form-control @error('pic') is-invalid @enderror" accept="image/*" onchange="displayImage(this)">
                        @error('pic')
                        <span class="invalid-feedback" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </span>
                        @enderror
                        <img id="picPreview" src="{{ asset('storage/' . $vehicle->pic) }}" alt="Selected Image" style="max-width: 100%; max-height: 200px;">
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="registrationnumber" style="color: black"><i class="fas fa-id-card"></i> License Plate Number</label>
                            <input type="text" name="registrationnumber" id="registrationnumber" class="form-control @error('registrationnumber') is-invalid @enderror" value="{{ $vehicle->registrationNumber }}" required disabled>
                            @error('registrationnumber')
                            <span class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="unitname" style="color: black"><i class="fas fa-car"></i> Unit/Name</label>
                            <input type="text" name="unitName" id="unitname" class="form-control @error('unitName') is-invalid @enderror" value="{{ $vehicle->unitName }}" required>
                            @error('unitName')
                            <span class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="pax" style="color: black"><i class="fas fa-users"></i> Pax</label>
                            <input type="number" name="pax" id="pax" class="form-control @error('pax') is-invalid @enderror" value="{{ $vehicle->pax }}" required>
                            @error('pax')
                            <span class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-2">
                            <label for="yearModel" style="color: black"><i class="fas fa-calendar-alt"></i> Year Model</label>
                            <input type="number" name="yearModel" id="yearModel" class="form-control @error('yearModel') is-invalid @enderror" value="{{ $vehicle->yearModel }}" min="1950" required>
                            @error('yearModel')
                            <span class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-2">
                            <label for="color" style="color: black"><i class="fas fa-paint-brush"></i> Color</label>
                            <input type="text" name="color" id="color" class="form-control @error('color') is-invalid @enderror" value="{{ $vehicle->color }}" required>
                            @error('color')
                            <span class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-2">
                            <label for="ownership_type" style="color: black"><i class="fas fa-home"></i> Ownership Type</label>
                            <select name="ownership_type" id="ownership_type" class="form-control @error('ownership_type') is-invalid @enderror">
                                <option value="Owned" {{ $vehicle->ownership_type === 'Owned' ? 'selected' : '' }}>Owned</option>
                                <option value="Outsourced" {{ $vehicle->ownership_type === 'Outsourced' ? 'selected' : '' }}>Outsourced</option>
                            </select>
                            @error('ownership_type')
                            <span class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4" id="outsourced_from_container" style="{{ $vehicle->ownership_type === 'Outsourced' ? '' : 'display: none;' }}">
                            <label for="outsourced_from" style="color: black"><i class="fas fa-handshake"></i> Outsourced From</label>
                            <input type="text" name="outsourced_from" id="outsourced_from" class="form-control @error('outsourced_from') is-invalid @enderror" value="{{ $vehicle->outsourced_from }}">
                            @error('outsourced_from')
                            <span class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="specification" style="color: black"><i class="fas fa-list-alt"></i> Specifications</label>
                        <textarea name="specification" id="specification" class="form-control @error('specification') is-invalid @enderror" rows="4">{{ $vehicle->specification }}</textarea>
                        @error('specification')
                        <span class="invalid-feedback" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="status" style="color: black"><i class="fas fa-check-circle"></i> Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="Active" {{ old('status', $vehicle->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status', $vehicle->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                            <span class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="vehicleType" style="color: black"><i class="fas fa-car"></i> Vehicle Type</label>
                            <select name="vehicleType" id="vehicleType" class="form-control @error('vehicleType') is-invalid @enderror">
                                @foreach($vehicleTypes as $type)
                                @if ($type->vehicle_Type_ID == $vehicle->vehicle_Type_ID)
                                <option value="{{ $type->vehicle_Type_ID }}" selected>{{ $type->vehicle_Type }}</option>
                                @else
                                <option value="{{ $type->vehicle_Type_ID }}">{{ $type->vehicle_Type }}</option>
                                @endif
                                @endforeach
                            </select>
                            @error('vehicleType')
                            <span class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-2" style="padding: 10px">
                            <a href="{{ route('vehicleTypes.view') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Add New Type
                            </a>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Update Vehicle
                        </button>
                    </div>
                </form>
            </div>
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
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                picPreview.src = e.target.result;
                picPreview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            picPreview.style.display = 'none';
        }
    }

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
