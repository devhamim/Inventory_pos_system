@extends('dashboard.body.main')

@section('content')
<!-- BEGIN: Header -->
<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto my-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fa-solid fa-cash-register"></i></div>
                        Expenses List
                    </h1>
                </div>
                <div class="col-auto my-4">
                    <a href="{{ route('expenses.getExpensesReport') }}" class="btn btn-success add-list my-1"><i class="fa-solid fa-file-export me-3"></i>Export</a>
                    <a href="{{ route('expenses.createExpenses') }}" class="btn btn-primary add-list my-1"><i class="fa-solid fa-plus me-3"></i>Add</a>
                    <a href="{{ route('expenses.allExpenses') }}" class="btn btn-danger add-list my-1"><i class="fa-solid fa-trash me-3"></i>Clear Search</a>
                </div>
            </div>

            <nav class="mt-4 rounded" aria-label="breadcrumb">
                <ol class="breadcrumb px-3 py-2 rounded mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">All Expenses</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- BEGIN: Alert -->
    <div class="container-xl px-4 mt-n4">
        @if (session()->has('success'))
        <div class="alert alert-success alert-icon" role="alert">
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="alert-icon-aside">
                <i class="far fa-flag"></i>
            </div>
            <div class="alert-icon-content">
                {{ session('success') }}
            </div>
        </div>
        @endif
    </div>
    <!-- END: Alert -->
</header>
<!-- END: Header -->

<!-- BEGIN: Main Page Content -->
<div class="container px-2 mt-n10">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row mx-n4">
                <div class="col-lg-12 card-header mt-n4">
                    <form action="#" method="GET">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="form-group row align-items-center">
                                <label for="row" class="col-auto">Row:</label>
                                <div class="col-auto">
                                    <select class="form-control" name="row">
                                        <option value="10" @if(request('row') == '10')selected="selected"@endif>10</option>
                                        <option value="25" @if(request('row') == '25')selected="selected"@endif>25</option>
                                        <option value="50" @if(request('row') == '50')selected="selected"@endif>50</option>
                                        <option value="100" @if(request('row') == '100')selected="selected"@endif>100</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row align-items-center justify-content-between">
                                <label class="control-label col-sm-3" for="search">Search:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" id="search" class="form-control me-1" name="search" placeholder="Search order" value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="input-group-text bg-primary"><i class="fa-solid fa-magnifying-glass font-size-20 text-white"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <hr>

                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">@sortablelink('expenses_id')</th>
                                    <th scope="col">@sortablelink('name')</th>
                                    <th scope="col">@sortablelink('category')</th>
                                    <th scope="col">@sortablelink('expenses_date', 'Date')</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">@sortablelink('recipient_name')</th>
                                    <th scope="col">@sortablelink('payment_type')</th>
                                    <th scope="col">Expenses Note</th>
                                    {{-- <th scope="col">Status</th>
                                    <th scope="col">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expenses as $expense)
                                    <tr>
                                        <th scope="row">{{ (($expenses->currentPage() * (request('row') ? request('row') : 10)) - (request('row') ? request('row') : 10)) + $loop->iteration  }}</th>
                                        <td scope="row">
                                            <div style="max-height: 80px; max-width: 80px;">
                                                <img class="img-fluid"  src="{{ $expense->image ? asset('storage/expenses/'.$expense->image) : asset('assets/img/products/default.webp') }}">
                                            </div>
                                        </td>
                                        <td>{{ $expense->expenses_id }}</td>
                                        <td>{{ $expense->name }}</td>
                                        <td>{{ $expense->category }}</td>
                                        <td>{{ $expense->expenses_date }}</td>
                                        <td>{{ $expense->amount }}</td>
                                        <td>{{ $expense->recipient_name }}</td>
                                        <td>{{ $expense->payment_type }}</td>
                                        <td>{{ $expense->expenses_note }}</td>
                                        {{-- @if ($expense->expenses_status == 1)
                                            <td>
                                                <span class="btn btn-success btn-sm text-uppercase">approved</span>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('expenses.expensesDetails', $expense->id) }}" class="btn btn-outline-success btn-sm mx-1"><i class="fa-solid fa-eye"></i></a>
                                                </div>
                                            </td>
                                        @else
                                            <td>
                                                <span class="btn btn-warning btn-sm text-uppercase">pending</span>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('expenses.expensesDetails', $expense->id) }}" class="btn btn-outline-success btn-sm mx-1"><i class="fa-solid fa-eye"></i></a>
                                                    <form action="{{ route('expenses.deleteExpenses', $expense->id) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif --}}
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $expenses->links() }}
            </div>
        </div>
    </div>
</div>
<!-- END: Main Page Content -->
@endsection
