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
                    <h4 class="card-title" style="color: red;">Add Remittance</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('remittance.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label for="clerk">Clerk</label>
                            <input type="text" name="clerk" id="clerk" value=" {{Auth::guard('employee')->user()->firstName}} {{Auth::guard('employee')->user()->lastName}}" class="form-control @error('clerk') is-invalid @enderror" readonly>
                            <input type="hidden" name="clerkID" id="clerkID" value="{{ Auth::guard('employee')->user()->empID }}">
                            @error('clerk')
                            <span class="invalid-feedback" role="alert">
                               **{{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="driver">Driver</label>
                                <select class="form-control" id="driver" name="driver">
                                    @foreach($drivers as $driver)
                                    <option value="{{ $driver->empID }}">{{ $driver->firstName }} {{ $driver->lastName }}</option>
                                    @endforeach
                                </select>
                            @error('driver')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="pax">Rent ID</label>
                            <input type="text" name="rent" id="rent" class="form-control" value="{{ request('id') }}" readonly>
                            @error('rent')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="specification">Receipt Number</label>
                            <input type="text" name="receipt_num" id="receipt_num" class="form-control @error('receipt_number') is-invalid @enderror" required>
                            @error('receipt_number')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="pax">Amount</label>
                            <input type="text" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" required>
                            @error('amount')
                            <span class="invalid-feedback" role="alert">
                                **{{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add Remittance</button>
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


