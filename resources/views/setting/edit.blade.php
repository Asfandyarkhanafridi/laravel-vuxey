@extends('layouts.nav')
@section('title', 'Setting Edit')
@section('app-content', 'app-content')

@section('main-content')
    <div class="container-fluid content-area">
        <div class="sideapp">
            <!-- page-header -->
            <div class="page-header mt-0 mb-0">
                <ol class="breadcrumb"><!-- breadcrumb -->
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Setting') }}</li>
                </ol><!-- End breadcrumb -->
            </div>
            <!-- End page-header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        @include('partials.message')
                        <div class="card-header">
                            <h3 class="mb-0 card-title">{{ __('Edit Sale') }}</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('settings.update',Auth::id()) }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="selected_date">Select Date:</label>
                                    <input type="text" id="selected_date" name="selected_date" value="{{ $settingDate }}" placeholder="Select Date" class="form-control datepicker" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
