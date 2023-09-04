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
                                <span class="icon"> </span> Select Photo
                            </label><hr>
                            <input type="file" name="pic" id="pic" class="form-control @error('pic') is-invalid @enderror" accept="image/*" onchange="displayImage(this)">
                            @error('pic')
                            <span class="invalid-feedback" role="alert">
                                **You forgot to select a photo
                            </span>
                            @enderror
                            <img id="picPreview" src="#" alt="Selected Image" style="display: none; max-width: 100%; max-height: 200px;">
                        </div>
                        

                        <div class="form-group">
                            <label for="registrationnumber">Registration Number</label>
                            <input type="text" name="registrationnumber" id="registrationnumber" class="form-control @error('registrationnumber') is-invalid @enderror" required>
                            @error('registrationnumber')
                            <span class="invalid-feedback" role="alert">
                               **{{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="unitname">Unit/Name</label>
                            <input type="text" name="unitname" id="unit_name" class="form-control @error('unitname') is-invalid @enderror" required>
                            @error('unitname')
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

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
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


