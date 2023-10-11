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
                        Add Expenses
                    </h1>
                </div>
            </div>

            <nav class="mt-4 rounded" aria-label="breadcrumb">
                <ol class="breadcrumb px-3 py-2 rounded mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('expenses.allExpenses') }}">Expenses</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div>
</header>
<!-- END: Header -->

<!-- BEGIN: Main Page Content -->
<div class="container-xl px-2 mt-n10">
    <form action="{{ route('expenses.storeExpenses') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xl-4">
                <!-- Profile picture card-->
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Uplode Image</div>
                    <div class="card-body text-center">
                        <!-- Profile picture image -->
                        <img class="img-account-profile rounded-circle mb-2" src="{{ asset('assets/img/demo/user-placeholder.svg') }}" alt="" id="image-preview" />
                        <!-- Profile picture help block -->
                        <div class="small font-italic text-muted mb-2">JPG or PNG no larger than 2 MB</div>
                        <!-- Profile picture input -->
                        <input class="form-control form-control-solid mb-2 @error('photo') is-invalid @enderror" type="file"  id="image" name="image" accept="image/*" onchange="previewImage();">
                        @error('image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <!-- BEGIN: Expenses Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        Expenses Details
                    </div>
                <div class="card-body">
                    <div class="row gx-3 mb-3">
                        <!-- Form Group (date) -->
                        <div class="col-md-6">
                            <label class="small my-1" for="expenses_date">Date <span class="text-danger">*</span></label>
                            <input class="form-control form-control-solid example-date-input @error('expenses_date') is-invalid @enderror" name="expenses_date" id="date" type="date" value="{{ old('expenses_date') }}" required>
                            @error('expenses_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small my-1" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control form-control-solid example-date-input @error('name') is-invalid @enderror" name="name" id="name" type="text" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small my-1" for="category">Category <span class="text-danger">*</span></label>
                            <input class="form-control form-control-solid example-date-input @error('category') is-invalid @enderror" name="category" id="category" type="text" value="{{ old('category') }}" required>
                            @error('category')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small my-1" for="amount">Amount <span class="text-danger">*</span></label>
                            <input class="form-control form-control-solid example-date-input @error('amount') is-invalid @enderror" name="amount" id="amount" type="number" value="{{ old('amount') }}" required>
                            @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small my-1" for="recipient_name">Recipient Name <span class="text-danger">*</span></label>
                            <input class="form-control form-control-solid example-date-input @error('recipient_name') is-invalid @enderror" name="recipient_name" id="recipient_name" type="text" value="{{ old('recipient_name') }}" required>
                            @error('recipient_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small my-1" for="payment_type">Payment Type<span class="text-danger">*</span></label>
                            <select name="payment_type" id="payment_type" class="form-control form-control-solid example-date-input @error('payment_type') is-invalid @enderror" value="{{ old('payment_type') }}" required>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="card">Card</option>
                                <option value="bkash">Bkash</option>
                                <option value="nogad">Nagad</option>
                                <option value="rocket">Rocket</option>
                            </select>
                            @error('payment_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="small my-1" for="expenses_note">Expenses Note<span class="text-danger"></span></label>
                            <textarea class="form-control form-control-solid" id="expenses_note" name="expenses_note" rows="3" spellcheck="false"></textarea>
                        </div>
                    </div>
                        <!-- Submit button -->
                        <button class="btn btn-primary" type="submit">Add</button>
                        <a class="btn btn-danger" href="{{ route('expenses.allExpenses') }}">Cancel</a>
                    </div>
                </div>
                <!-- END: Expenses Details -->
            </div>
        </div>
    </form>
</div>
<!-- END: Main Page Content -->
@endsection
