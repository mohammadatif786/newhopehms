@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('patient-details.index') }}">@lang('Patient')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Patient')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@lang('Edit Patient')</h3>
                </div>
                <div class="card-body">
                    <form id="departmentForm" class="form-material form-horizontal"
                        action="{{ route('patient-details.update', $patientDetail) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <b class="ambitious-crimson"></b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', $patientDetail->name) }}"
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
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email', $patientDetail->email) }}"
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
                                    <label for="phone">@lang('Phone')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" id="phone" name="phone"
                                            value="{{ old('phone', $patientDetail->phone) }}"
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
                                            <option value="male" @if (old('gender', $patientDetail->gender) == 'male') selected @endif>
                                                @lang('Male')</option>
                                            <option value="female" @if (old('gender', $patientDetail->gender) == 'female') selected @endif>
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
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">@lang('Password')</label>
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
                            <div class="form-group col-md-6">
                                <label for="doctor_id">@lang('Select Doctor')</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                                    </div>
                                    <select name="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror"
                                        id="doctor_id">
                                        <option value="">--@lang('Choose a doctor')--</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}"
                                                @if (old('doctor_id', $patientDetail->doctor_id) == $doctor->id) selected @endif>
                                                {{ $doctor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('doctor_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">

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
                                            @foreach (config('constant.blood_groups') as $bloodGroup)
                                                <option value="{{ $bloodGroup }}"
                                                    @if (old('blood_group', $patientDetail->blood_group) == $bloodGroup) selected @endif>{{ $bloodGroup }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('blood_group')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth">@lang('Age')</label>
                                    <div class="input-group mb-3">
                                        {{-- <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
                                        </div> flatpickr --}}
                                        <input type="text" name="date_of_birth" id="date_of_birth"
                                            class="form-control @error('date_of_birth') is-invalid @enderror"
                                            placeholder="@lang('Enter Age')"
                                            value="{{ old('date_of_birth', $patientDetail->date_of_birth) }}">
                                        @error('date_of_birth')
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
                                    <input id="photo" class="dropify" name="photo" type="file"
                                        data-allowed-file-extensions="png jpg jpeg" data-max-file-size="2024K" />
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
                                            placeholder="@lang('Address')">{{ old('address', $patientDetail->address) }}</textarea>
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
                                    <label for="status">@lang('Status') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                        </div>
                                        <select
                                            class="form-control ambitious-form-loading @error('status') is-invalid @enderror"
                                            required name="status" id="status">
                                            <option value="1"
                                                {{ old('status', $patientDetail->status) === '1' ? 'selected' : '' }}>
                                                @lang('Active')</option>
                                            <option value="0"
                                                {{ old('status', $patientDetail->status) === '0' ? 'selected' : '' }}>
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
                                    <label for="total_payment">@lang('Payment')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rs</span> <!-- Currency sign prepended -->
                                        </div>
                                        <input type="number" step="0.01" name="total_payment" id="total_payment"
                                            value="{{ old('total_payment', $patientDetail->total_payment) }}"
                                            class="form-control @error('total_payment') is-invalid @enderror"
                                            placeholder="@lang('Enter total payment amount')">
                                        @error('total_payment')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="previous_drug">@lang('Prescription')</label>
                                    <div class="input-group mb-3">
                                        <textarea type="text" name="previous_drug" id="previous_drug"
                                            class="form-control @error('previous_drug') is-invalid @enderror"
                                            value="{{ old('previous_drug', $patientDetail->previous_drug) }}" placeholder="@lang('Enter Prescription')">{{ $patientDetail->previous_drug }}</textarea>
                                        @error('previous_drug')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="custom_field">@lang('Discharge Note ')</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="custom_field" id="custom_field"
                                            class="form-control @error('custom_field') is-invalid @enderror"
                                            value="{{ old('custom_field', $patientDetail->custom_field) }}"
                                            placeholder="@lang('Enter Custom Field 2')">
                                        @error('custom_field')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="text_field">@lang('Medical History')</label>
                                    <div class="input-group mb-3">
                                        <textarea type="text" name="text_field" id="text_field"
                                            class="form-control @error('text_field') is-invalid @enderror"
                                            value="{{ old('text_field', $patientDetail->text_field) }}" placeholder="@lang('Medical History')">{{ $patientDetail->text_field }}</textarea>
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
                                        <a href="{{ route('patient-details.index') }}"
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
@endsection
