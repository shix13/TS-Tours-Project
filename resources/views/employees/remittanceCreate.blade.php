@extends('layouts.empbar')

@section('title')
    TS | New Remittance
@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 offset-md-0">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" style="color: orangered">
                        <i class="fas fa-plus-circle" ></i> Add Remittance
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('remittance.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label for="clerk" style="color: black">
                                <i class="fas fa-user" ></i> Clerk
                            </label>
                            <input style="background: white;color:black;font-size:17px" type="text" name="clerk" id="clerk" value="{{ Auth::guard('employee')->user()->firstName }} {{ Auth::guard('employee')->user()->lastName }}" class="form-control" readonly>
                            <input type="hidden" name="clerkID" id="clerkID" value="{{ Auth::guard('employee')->user()->empID }}">
                            @error('clerk')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="driver" style="color: black">
                                <i class="fas fa-car"></i> Driver
                            </label>
                            <select class="form-control" id="driver" name="driver" style="font-size:17px">
                                @foreach($drivers as $driver)
                                <option value="{{ $driver->empID }}">{{ $driver->firstName }} {{ $driver->lastName }}</option>
                                @endforeach
                            </select>
                            @error('driver')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="rent" style="color: black">
                                <i class="fas fa-file-alt"></i> Rent ID
                            </label>
                            <input type="text"  style="background: white;color:black;font-size:17px"" name="rent" id="rent" class="form-control" value="{{ request('id') }}" readonly>
                            @error('rent')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="receipt_num" style="color: black">
                                <i class="fas fa-receipt"></i> Receipt Number
                            </label>
                            <input type="text" name="receipt_num" style="font-size:17px" id="receipt_num" class="form-control @error('receipt_num') is-invalid @enderror" required>
                            @error('receipt_num')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="amount" style="color: black">
                                <i class="fas fa-money-bill-alt"></i> Amount
                            </label>
                            <input type="text" name="amount" id="amount" style="font-size:17px" class="form-control @error('amount') is-invalid @enderror" required>
                            @error('amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Remittance
                            </button>
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
