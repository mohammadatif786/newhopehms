@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">@lang('Role List')</a></li>
                        <li class="breadcrumb-item active">@lang('Create Permission')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>@lang('Create Permission')</h3>
                </div>
                <div class="card-body">
                    <form class="form-material form-horizontal" action="{{ route('permissions.store') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label ambitious-center">
                                <h4>@lang('Permission Name') <b class="ambitious-crimson">*</b></h4>
                            </label>
                            <div class="col-md-8">
                                <input class="form-control ambitious-form-loading @error('name') is-invalid @enderror"
                                    name="name" id="name" type="text" placeholder="@lang('Role Name')"
                                    value="{{ old('name') }}" >
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label ambitious-center">
                                <h4>@lang('Display Name') <b class="ambitious-crimson">*</b></h4>
                            </label>
                            <div class="col-md-8">
                                <input
                                    class="form-control ambitious-form-loading @error('display_name') is-invalid @enderror"
                                    name="display_name" id="display_name" type="text" placeholder="@lang('Display Name')"
                                    value="{{ old('display_name') }}" >
                                @error('display_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="form-group row mb-0">
                            <label class="col-md-2 col-form-label"></label>
                            <div class="col-md-8">
                                <input type="submit" value="@lang('Submit')" id="submit"
                                    class="btn btn-outline btn-info btn-lg" />
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script src="{{ asset('assets/js/custom/roles/create.js') }}"></script>
@endpush
