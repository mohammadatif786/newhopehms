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
                        <li class="breadcrumb-item active">@lang('Patient Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{ $patientDetail->photo_url }}"
                            alt="" />
                    </div>
                    <h3 class="profile-username text-center">{{ $patientDetail->name }}</h3>
                </div>
            </div>
            <div class="card">
                <div class="row">
                    <div class="card-body">
                        <div class="col-md-4">
                            <div class="form-group d-flex justify-content-between align-items-center">
                                <label for="total_payment">@lang('Total Payment')</label>
                                <span class="mx-4">=</span> <!-- Added equal sign with margin -->
                                <p class="mb-0 ml-4"> <!-- Added more space with ml-4 -->
                                    @if ($patientDetail->total_payment)
                                        Rs:{{ $patientDetail->total_payment }}
                                    @else
                                        <span class="badge badge-pill badge-secondary">@lang('Not Available')</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group d-flex justify-content-between align-items-center">
                                <label for="paid_payment">@lang('Paid Payment')</label>
                                <span class="mx-4">=</span> <!-- Added equal sign with margin -->
                                <p class="mb-0 ml-4">Rs:{{ $patientDetail->paid_payment ?? 'N/A' }}</p>
                                <!-- Added more space with ml-4 -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group d-flex justify-content-between align-items-center">
                                <label for="due_payment">@lang('Due Payment')</label>
                                <span class="mx-4">=</span> <!-- Added equal sign with margin -->
                                <p class="mb-0 ml-4">Rs:{{ $patientDetail->due_payment ?? 'N/A' }}</p>
                                <!-- Added more space with ml-4 -->
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@lang('Patient Info')</h3>
                    @can('patient-detail-update')
                        <div class="card-tools">
                            <a href="{{ route('patient-details.edit', $patientDetail) }}"
                                class="btn btn-info">@lang('Edit')</a>
                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">@lang('Name')</label>
                                <p>{{ $patientDetail->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">@lang('Email')</label>
                                <p>{{ $patientDetail->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone">@lang('Phone')</label>
                                <p>{{ $patientDetail->phone }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender">@lang('Gender')</label>
                                <p>{{ ucfirst($patientDetail->gender) }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="blood_group">@lang('Blood Group')</label>
                                <p>{{ $patientDetail->blood_group }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_of_birth">@lang('Date of Birth')</label>
                                <p>{{ $patientDetail->date_of_birth }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address">@lang('Address')</label>
                                <p>{!! $patientDetail->address !!}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="doctor">@lang('Doctor')</label>
                                <p>{{ $patientDetail->doctor ? $patientDetail->doctor->name : 'No Doctor Assigned' }}</p>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">@lang('Status')</label>
                                <p>
                                    @if ($patientDetail->status == 1)
                                        <span class="badge badge-pill badge-success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="previous_drug">@lang('Previous Drug')</label>
                                <p>
                                    @if ($patientDetail->previous_drug)
                                        {{ $patientDetail->previous_drug }}
                                    @else
                                        <span class="badge badge-pill badge-secondary">@lang('Not Available')</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="custom_field">@lang('Payment')</label>
                                <p>
                                    @if ($patientDetail->total_payment)
                                        Rs:{{ $patientDetail->total_payment }}
                                    @else
                                        <span class="badge badge-pill badge-secondary">@lang('Not Available')</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="text_field">@lang('Previous History')</label>
                                <p>
                                    @if ($patientDetail->text_field)
                                        {{ $patientDetail->text_field }}
                                    @else
                                        <span class="badge badge-pill badge-secondary">@lang('Not Available')</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_of_birth">@lang('Date of Admit')</label>
                                <p>{{ $patientDetail->created_at->format('F d, Y') }}</p>

                            </div>
                        </div>
                        @if ($patientDetail->status == 1 && $patientDetail->updated_at != $patientDetail->created_at)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_of_birth">@lang('Date of Re-Admit')</label>
                                    <p>{{ $patientDetail->updated_at->format('F d, Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">@lang('Discharge Note')</label>
                                    <p>{{ $patientDetail->custom_field }}</p>
                                </div>
                            </div>
                        @endif




                        {{-- <div>
                            <a href="{{ route('patient-details.attachments', $patientDetail->id) }}"
                                class="btn btn-info btn-outline btn-circle btn-lg" title="@lang('View Attachments')"
                                style="margin-left: 5px;">
                                <i class="fa fa-paperclip"></i> @lang('')
                            </a>
                        </div> --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address">@lang('View Attachments')</label>
                                <a href="{{ route('patient-details.attachments', $patientDetail->id) }}"
                                    class="btn btn-info btn-outline btn-circle btn-lg" title="@lang('View Attachments')"
                                    style="margin-left: 5px;">
                                    <i class="fa fa-paperclip"></i> @lang('')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
