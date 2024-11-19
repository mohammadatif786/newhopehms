@extends('layouts.layout')
@section('content')
    <!-- Include Bootstrap and jQuery -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMt23C1hZ5UJwGJ5LQv9D1pJ5jjy0T2AcqT7lI" crossorigin="anonymous">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @can('patient-detail-create')
                        <h3><a href="{{ route('patient-details.create') }}" class="btn btn-outline btn-info">+
                                @lang('Add Patient')</a>
                            <span class="pull-right"></span>
                        </h3>
                    @endcan
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Patient List')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@lang('Patient List') </h3>
                    <div class="card-tools">
                        <a class="btn btn-primary" target="_blank" href="{{ route('patient-details.index') }}?export=1"><i
                                class="fas fa-cloud-download-alt"></i> @lang('Export')</a>
                        <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i>
                            @lang('Filter')</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                        <div class="card-body border">
                            <form action="" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Name')</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ request()->name }}" placeholder="@lang('Name')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Email')</label>
                                            <input type="text" name="email" class="form-control"
                                                value="{{ request()->email }}" placeholder="@lang('Email')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Phone')</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ request()->phone }}" placeholder="@lang('Phone')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Categorey')</label>
                                            <input type="text" name="categorey" class="form-control"
                                                value="{{ request()->categorey }}" placeholder="@lang('Categorey')">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if (request()->isFilterActive)
                                            <a href="{{ route('patient-details.index') }}"
                                                class="btn btn-secondary">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- @if ($errors->has('discharge_error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('discharge_error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif --}}


                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>@lang('ID')</th>
                                <th>@lang('Category')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Phone')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Due Amount')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patientDetails as $patientDetail)
                                <tr>
                                    <td>{{ $patientDetail->id }}</td>
                                    <td>{{ $patientDetail->categorey ?? 'N/A' }}</td>
                                    <td>{{ $patientDetail->name }}</td>

                                    <td>{{ $patientDetail->phone ?? 'N/A' }}</td>
                                    <td>
                                        @if ($patientDetail->status == 1)
                                            <span class="badge badge-pill badge-success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($patientDetail->due_payment === null)
                                            RS: {{ $patientDetail->total_payment }}
                                        @elseif ($patientDetail->due_payment > 0)
                                            RS: {{ $patientDetail->due_payment }}
                                        @else
                                            Payment Done
                                        @endif
                                    </td>


                                    <td>
                                        <a href="{{ route('patient-details.show', $patientDetail) }}"
                                            class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip"
                                            title="@lang('View')">
                                            <i class="fa fa-eye ambitious-padding-btn"></i>
                                        </a>

                                        <form action="{{ route('patients.mark-old', $patientDetail->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-outline btn-circle"
                                                data-toggle="tooltip" title="Mark as Old Patient"
                                                style="width: 50px; height: 50px; border-radius: 50%; margin-left: 5px;">
                                                <i class="fa fa-clock ambitious-padding-btn"></i>
                                            </button>
                                        </form>
                                        <!-- Button to trigger modal -->
                                        <button type="button" class="btn btn-success btn-outline btn-circle"
                                            data-toggle="modal" data-target="#paymentModal"
                                            data-userid="{{ $patientDetail->id }}"
                                            data-totalpayment="{{ $patientDetail->total_payment }}"
                                            data-paidpayment="{{ $patientDetail->paid_payment }}" title="Update Payments"
                                            style="width: 50px; height: 50px; border-radius: 50%; margin-left: 5px;">
                                            <i class="fas fa-money-bill-wave ambitious-padding-btn"></i>
                                        </button>




                                        @can('patient-detail-update')
                                            <a href="{{ route('patient-details.edit', $patientDetail) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip"
                                                title="@lang('Edit')" style="margin-left: 5px;">
                                                <i class="fa fa-edit ambitious-padding-btn"></i>
                                            </a>

                                            <form action="{{ route('patient-details.discharge', $patientDetail) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-outline btn-circle btn-lg"
                                                    data-toggle="tooltip" title="@lang('Discharge Patient')"
                                                    style="margin-left: 5px;">
                                                    <i class="fa fa-user-times ambitious-padding-btn"></i>
                                                </button>
                                            </form>
                                        @endcan

                                        @can('patient-detail-delete')
                                            <a href="#"
                                                data-href="{{ route('patient-details.destroy', $patientDetail) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal"
                                                data-target="#myModal" title="@lang('Delete')" style="margin-left: 5px;">
                                                <i class="fa fa-trash ambitious-padding-btn"></i>
                                            </a>
                                        @endcan
                                        <!-- Trigger Button -->
                                        <!-- Button to open attachments modal -->
                                        <!-- Button to view user attachments -->


                                        <a href="{{ route('patient-details.upload-attachments', $patientDetail->id) }}"
                                            class="btn btn-info btn-outline btn-circle btn-lg" title="@lang('Upload Attachments')"
                                            style="margin-left: 5px;">
                                            <i class="fa fa-paperclip"></i> @lang('')
                                        </a>




                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $patientDetails->withQueryString()->links() }}
                    <!-- Payment Modal -->
                    <!-- Modal -->
                    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog"
                        aria-labelledby="paymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="paymentModalLabel">Update Payments</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('patients.update-payment') }}" method="POST" id="paymentForm">
                                    @csrf
                                    <!-- Hidden field for user_id -->
                                    <input type="hidden" name="user_id" id="user_id">

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="total_payment">Total Payment</label>
                                            <input type="number" class="form-control" id="total_payment"
                                                name="total_payment" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="paid_payment">Paid Payment</label>
                                            <input type="number" class="form-control" id="paid_payment"
                                                name="paid_payment" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="due_payment">Due Payment</label>
                                            <input type="number" class="form-control" id="due_payment"
                                                name="due_payment" readonly>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save Payment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Error Modal -->
                    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog"
                        aria-labelledby="errorModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content" style="border: 2px solid red;">
                                <div class="modal-header" style="background-color: red;">
                                    <h5 class="modal-title text-white" id="errorModalLabel">Error</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-danger font-weight-bold" id="errorMessage"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Attachments Modal -->


                    <!-- Attachments Modal -->



                    <script>
                        $(document).ready(function() {
                            @if ($errors->has('discharge_error'))
                                // Set the error message
                                $('#errorMessage').text('{{ $errors->first('discharge_error') }}');
                                // Show the modal
                                $('#errorModal').modal('show');
                            @endif
                        });
                    </script>
                    <script>
                        $('#paymentModal').on('show.bs.modal', function(event) {
                            var button = $(event.relatedTarget); // Button that triggered the modal
                            var userId = button.data('userid'); // Get user_id from the button
                            var totalPayment = button.data('totalpayment'); // Get total payment
                            var paidPayment = button.data('paidpayment') || 0; // Default to 0 if no paid payment

                            var modal = $(this);
                            modal.find('#user_id').val(userId); // Set user_id in the hidden input field
                            modal.find('#total_payment').val(totalPayment); // Set total payment
                            modal.find('#paid_payment').val(paidPayment); // Set paid payment
                            modal.find('#due_payment').val(totalPayment - paidPayment); // Calculate and set due payment

                            // Recalculate due payment when the paid payment changes
                            modal.find('#paid_payment').on('input', function() {
                                var newPaidPayment = parseFloat($(this).val()) || 0;
                                modal.find('#due_payment').val(totalPayment - newPaidPayment); // Update due payment
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.delete_modal')
@endsection
