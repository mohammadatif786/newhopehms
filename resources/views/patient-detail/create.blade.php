@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('patient-details.index') }}">@lang('Patient')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add Patient')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@lang('Add Patient')</h3>
                </div>
                <div class="card-body">
                    <form id="patientForm" class="form-material form-horizontal"
                        action="{{ route('patient-details.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <b class="ambitious-crimson"></b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="@lang('Name')" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">@lang('Email') <b class="ambitious-crimson"></b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                                        </div>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="@lang('Email')">
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categorey">@lang('Patient Categorey')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        </div>
                                        <select name="categorey"
                                            class="form-control @error('categorey') is-invalid @enderror" id="categorey">
                                            <option value="">--@lang('Select')--</option>
                                            <option value="VIP" {{ old('categorey') === 'VIP' ? 'selected' : '' }}>VIP
                                            </option>
                                            <option value="General" {{ old('categorey') === 'General' ? 'selected' : '' }}>
                                                General</option>
                                        </select>
                                        @error('categorey')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">


                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">@lang('Password') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="@lang('Password')">
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">@lang('Phone')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="@lang('Phone')">
                                        @error('phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="doctor_id">@lang('Select Doctor')</label>
                                <select id="doctor_id" name="doctor_id"
                                    class="form-control @error('doctor_id') is-invalid @enderror">
                                    <option value="">@lang('Choose a doctor')</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                    @endforeach
                                </select>

                                @error('doctor')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">@lang('Gender')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        </div>
                                        <select name="gender" class="form-control @error('gender') is-invalid @enderror"
                                            id="gender">
                                            <option value="">--@lang('Select')--</option>
                                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>
                                                @lang('Male')</option>
                                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>
                                                @lang('Female')</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blood_group">@lang('Blood Group')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-heartbeat"></i></span>
                                        </div>
                                        <select name="blood_group"
                                            class="form-control @error('gender') is-invalid @enderror" id="blood_group">
                                            <option value="">--@lang('Select')--</option>
                                            <option value="A+" {{ old('blood_group') === 'A+' ? 'selected' : '' }}>A+
                                            </option>
                                            <option value="A-" {{ old('blood_group') === 'A-' ? 'selected' : '' }}>A-
                                            </option>
                                            <option value="B+" {{ old('blood_group') === 'B+' ? 'selected' : '' }}>B+
                                            </option>
                                            <option value="B-" {{ old('blood_group') === 'B-' ? 'selected' : '' }}>B-
                                            </option>
                                            <option value="O+" {{ old('blood_group') === 'O+' ? 'selected' : '' }}>O+
                                            </option>
                                            <option value="O-" {{ old('blood_group') === 'O-' ? 'selected' : '' }}>O-
                                            </option>
                                            <option value="AB+" {{ old('blood_group') === 'AB+' ? 'selected' : '' }}>
                                                AB+</option>
                                            <option value="AB-" {{ old('blood_group') === 'AB-' ? 'selected' : '' }}>
                                                AB-</option>
                                        </select>
                                        @error('blood_group')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="photo" class="col-md-12 col-form-label">
                                    <h4>{{ __('Photo') }}</h4>
                                </label>
                                <div class="col-md-12">
                                    <input id="photo" class="dropify" name="photo" value="{{ old('photo') }}"
                                        type="file" data-allowed-file-extensions="png jpg jpeg"
                                        data-max-file-size="2024K" />
                                    <p>{{ __('Max Size: 2MB, Allowed Format: png, jpg, jpeg') }}</p>
                                </div>
                                @error('photo')
                                    <div class="error ambitious-red">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">@lang('Address')</label>
                                    <div class="input-group mb-3">
                                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="4"
                                            placeholder="@lang('Address')">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth">@lang('Age')</label>
                                    <div class="input-group mb-3">
                                        {{-- <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
                                        </div> flatpickr --}}
                                        <input type="text" name="date_of_birth" id="date_of_birth"
                                            class="form-control  @error('date_of_birth') is-invalid @enderror"
                                            placeholder="@lang('Enter Age')" value="{{ old('date_of_birth') }}">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">@lang('Status') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                        </div>
                                        <select
                                            class="form-control ambitious-form-loading @error('status') is-invalid @enderror"
                                            required name="status" id="status">
                                            <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>
                                                @lang('Active')</option>
                                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                                                @lang('Inactive')</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="previous_drug">@lang('Prescription')</label>
                                    <div class="input-group mb-3">
                                        <textarea name="previous_drug" id="previous_drug" class="form-control @error('previous_drug') is-invalid @enderror"
                                            rows="4" placeholder="@lang('Enter Prescription ')">{{ old('previous_drug') }}</textarea>
                                        @error('previous_drug')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_payment">@lang('Payment')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rs</span> <!-- Dollar sign prepended -->
                                        </div>
                                        <input type="number" step="0.01" name="total_payment" id="total_payment"
                                            value="{{ old('total_payment') }}"
                                            class="form-control @error('total_payment') is-invalid @enderror"
                                            placeholder="@lang('Enter total_payment Amount')">
                                        @error('total_payment')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <label for="files" class="col-md-12 col-form-label">
                                    <h4>{{ __('Files') }}</h4>
                                </label>
                                <div class="col-md-12">
                                    <input id="files" class="dropify" name="files[]" type="file" multiple
                                        data-allowed-file-extensions="*" data-max-file-size="2024K" />
                                    <p>{{ __('Max Size: 2MB, Allowed Format: all file types') }}</p>
                                    <ul id="file-list" class="file-list"></ul> <!-- This will show selected files -->
                                </div>
                                @error('files.*')
                                    <div class="error ambitious-red">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div> --}}

                            <div id="file-list" class="file-list"></div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="text_field">@lang('Medical History')</label>
                                    <div class="input-group mb-3">
                                        <textarea name="text_field" id="text_field" class="form-control @error('text_field') is-invalid @enderror"
                                            rows="4" placeholder="@lang('Enter Medical History')">{{ old('text_field') }}</textarea>
                                        @error('text_field')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 col-form-label"></label>
                                    <div class="col-md-8">
                                        <input type="submit" value="{{ __('Submit') }}"
                                            class="btn btn-outline btn-info btn-lg" />
                                        <a href="{{ route('doctor-details.index') }}"
                                            class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Dropify (if you're using it for styling)
            $('.dropify').dropify();

            // Array to keep track of selected files
            let selectedFiles = [];

            // Handle file selection
            $('#files').on('change', function(event) {
                const files = event.target.files; // Get the selected files

                // Convert FileList to Array
                const filesArray = Array.from(files);

                // Merge new files with already selected files
                selectedFiles = [...selectedFiles, ...filesArray];

                // Clear the file list display
                $('#file-list').empty();

                // Create a list of all selected file names
                selectedFiles.forEach(file => {
                    $('#file-list').append('<li>' + file.name +
                        '</li>'); // Add file name to the list
                });
            });
        })
    </script>
@endsection
