@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@lang('Add Patient')</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('patient-details.store.attachments', $user->id) }}" id="uploadForm" method="post"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-md-6">
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
                        </div>

                        <br /><br />
                        <input type="submit" class="btn btn-primary" value="Upload" />
                    </form>
                    <div id="message"></div>
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

            // Handle form submission
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Check if the button is already disabled to prevent multiple submissions
                if ($('#submit-btn').prop('disabled')) {
                    return; // Do nothing if the button is already disabled
                }

                // Disable the submit button to prevent multiple clicks
                $('#submit-btn').prop('disabled', true).text('Uploading...');

                // Create a FormData object from the form
                var formData = new FormData(this); // Automatically includes file input data

                // Append each selected file to the FormData
                selectedFiles.forEach(file => {
                    formData.append('files[]', file); // Append file to FormData
                });

                // Submit the form using AJAX
                $.ajax({
                    url: $(this).attr('action'), // Get the form action URL
                    type: $(this).attr('method'), // Get the form method (POST)
                    data: formData, // Send the FormData object
                    contentType: false, // Tell jQuery not to set content type
                    processData: false, // Tell jQuery not to process data
                    success: function(response) {
                        // Show a success message in a popup
                        alert('Files uploaded successfully!');

                        // Change the button text after success
                        $('#submit-btn').text('Uploaded');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Show an error message in a popup
                        alert('Error uploading files. Please try again.');

                        // Re-enable the button after an error
                        $('#submit-btn').prop('disabled', false).text('Submit');
                    }
                });
            });
        });
    </script>
@endsection
