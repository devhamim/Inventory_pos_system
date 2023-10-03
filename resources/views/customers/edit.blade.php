@extends('dashboard.body.main')

@section('specificpagescripts')
<script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endsection

@section('content')
<!-- BEGIN: Header -->
<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fa-solid fa-users"></i></div>
                        Edit Customer
                    </h1>
                </div>
            </div>

            <nav class="mt-4 rounded" aria-label="breadcrumb">
                <ol class="breadcrumb px-3 py-2 rounded mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</header>
<!-- END: Header -->

<!-- BEGIN: Main Page Content -->
<div class="container-xl px-2 mt-n10">
    <form action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-xl-4">
                <!-- Profile picture card-->
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Profile Picture</div>
                    <div class="card-body text-center">
                        <!-- Profile picture image -->
                        <img class="img-account-profile mb-2" src="{{ $customer->photo ? asset('storage/customers/'.$customer->photo) : asset('assets/img/demo/user-placeholder.svg') }}" alt="" id="image-preview" />
                        <!-- Profile picture help block -->
                        <div class="small font-italic text-muted mb-2">JPG or PNG no larger than 2 MB</div>
                        <!-- Profile picture input -->
                        <input class="form-control form-control-solid mb-2 @error('photo') is-invalid @enderror" type="file"  id="image" name="photo" accept="image/*" onchange="previewImage();">
                        @error('photo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <!-- BEGIN: Customer Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        Customer Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Form Group (name) -->
                            <div class="mb-3">
                                <label class="small mb-1" for="name">Name <span class="text-danger">*</span></label>
                                <input class="form-control form-control-solid @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="" value="{{ old('name', $customer->name) }}" />
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <!-- Form Group (email address) -->
                            <div class="mb-3">
                                <label class="small mb-1" for="email">Email address <span class="text-danger">*</span></label>
                                <input class="form-control form-control-solid @error('email') is-invalid @enderror" id="email" name="email" type="text" placeholder="" value="{{ old('email', $customer->email) }}" />
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <!-- Form Group (phone number) -->
                            <div class="col-md-6">
                                <label class="small mb-1" for="phone">Phone number <span class="text-danger">*</span></label>
                                <input class="form-control form-control-solid @error('phone') is-invalid @enderror" id="phone" name="phone" type="text" placeholder="" value="{{ old('phone', $customer->phone) }}" />
                                @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>  
                            <div class="col-md-6">
                                <label class="small mb-1" for="account">Account numbe <span class="text-danger">*</span></label>
                                <input class="form-control form-control-solid" id="account" name="account" type="text" placeholder="" value="{{ old('account', $customer->account) }}" />
                            </div>  
                            <!-- Form Group (address) -->
                            <div class="mb-3">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-control-solid @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="city">City<span class="text-danger">*</span></label>
                                <input class="form-control form-control-solid @error('city') is-invalid @enderror" id="city" name="city" type="text" placeholder="" value="{{ old('city', $customer->city) }}" />
                                @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="state">State<span class="text-danger">*</span></label>
                                <input class="form-control form-control-solid" id="state" name="state" type="text" placeholder="" value="{{ old('state', $customer->state) }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="zip">Zip<span class="text-danger">*</span></label>
                                <input class="form-control form-control-solid" id="zip" name="zip" type="number" placeholder="" value="{{ old('zip', $customer->zip) }}" />
                            </div>
                            <div class="mb-3">
                                <label for="comments">Comments <span class="text-danger">*</span></label>
                                <textarea class="form-control form-control-solid" id="comments" name="comments" rows="3">{{ old('comments', $customer->comments) }}</textarea>
                            </div> 
                            <div class="mb-3">
                                <label class="small mb-1" for="company">Company name <span class="text-danger">*</span></label>
                                <input class="form-control form-control-solid" id="company" name="company" type="text" placeholder="" value="{{ old('company', $customer->company) }}" />
                            </div>
                            <div class="mb-3">
                                <label for="notes">Internal notes <span class="text-danger">*</span></label>
                                <textarea class="form-control form-control-solid" id="notes" name="notes" rows="3">{{ old('notes', $customer->notes) }}</textarea>
                            </div>
                        </div>
                        <!-- Submit button -->
                        <button class="btn btn-primary" type="submit">Update</button>
                        <a class="btn btn-danger" href="{{ route('customers.index') }}">Cancel</a>
                    </div>
                </div>
                <!-- END: Customer Details -->
            </div>
        </div>
    </form>
</div>
<!-- END: Main Page Content -->
@endsection
