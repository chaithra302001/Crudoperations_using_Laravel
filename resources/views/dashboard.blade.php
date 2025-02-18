@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 form-container">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="fw-bold fs-4">{{ __('JOB DETAILS') }}</span>

                    <!-- Display User Profile (Profile Icon, Name, Email, and Logout) -->
                    <span class="d-flex align-items-center">
                        @if(Auth::check())
                            <div class="me-3">
                                <i class="fa fa-user-circle" style="font-size: 2rem;"></i>
                            </div>
                        @endif

                        <div class="d-flex flex-column align-items-start">
                            <div class="mb-2"><strong>{{ $user->name }}</strong></div>
                            <div class="mb-2">{{ $user->email }}</div>

                            <form action="{{ route('logout') }}" method="POST" class="ms-2">
                                @csrf
                                <button type="submit" class="btn btn-danger" style="font-size: 1rem;">Logout</button>
                            </form>
                        </div>
                    </span>
                </div>

                <div class="card-body">
                    <!-- Dashboard Form -->
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" id="addButton">ADD</button>
                    </div>

                    <!-- Hidden Add Job Form -->
                    <div id="addJobForm" class="mt-4" style="display: none;">
                    <form id="jobForm" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf

    <div class="mb-3">
        <label for="jobId" class="form-label">Job ID <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="jobId" name="jobId" required data-parsley-trigger="keyup" data-parsley-required-message="Job ID is required">
    </div>

    <div class="mb-3">
        <label for="jobTitle" class="form-label">Job Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="jobTitle" name="jobTitle" required data-parsley-trigger="keyup" data-parsley-required-message="Job Title is required">
    </div>

    <div class="mb-3">
        <label class="form-label">Job Level <span class="text-danger">*</span></label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jobLevel" id="entryLevel" value="Entry-level" required data-parsley-errors-container="#jobLevelError">
            <label class="form-check-label" for="entryLevel">Entry-level</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jobLevel" id="midLevel" value="Mid-level" required>
            <label class="form-check-label" for="midLevel">Mid-level</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jobLevel" id="seniorLevel" value="Senior" required>
            <label class="form-check-label" for="seniorLevel">Senior</label>
        </div>
        <div id="jobLevelError" class="text-danger"></div>
    </div>

    <div class="mb-3">
        <label for="companyName" class="form-label">Company Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="companyName" name="companyName" required data-parsley-trigger="keyup" data-parsley-required-message="Company Name is required">
    </div>

    <div class="mb-3">
    <label for="companyLogo" class="form-label">Company Logo (Optional)</label>
    <input type="file" class="form-control" id="companyLogo" name="companyLogo"
        accept="image/jpeg, image/png, image/jpg, image/gif"
        data-parsley-max-file-size="2048"
        data-parsley-filemimetypes="image/jpeg, image/png, image/jpg, image/gif"
        data-parsley-errors-container="#companyLogoError">

    <div id="companyLogoError" class="text-danger"></div>
</div>


    <div class="mb-3">
        <label for="jobLocation" class="form-label">Job Location <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="jobLocation" name="jobLocation" required data-parsley-trigger="keyup" data-parsley-required-message="Job Location is required">
    </div>

    <div class="mb-3">
        <label for="jobType" class="form-label">Job Type <span class="text-danger">*</span></label>
        <select class="form-control selectpicker" id="jobType" name="jobType" data-live-search="true" required data-parsley-errors-container="#jobTypeError" data-parsley-required-message="Please select a Job Type">
            <option value="" disabled selected>Select Job Type</option>
            <option value="Full-Time">Full-Time</option>
            <option value="Part-Time">Part-Time</option>
            <option value="Contract">Contract</option>
            <option value="Internship">Internship</option>
        </select>
        <div id="jobTypeError" class="text-danger"></div>
    </div>

    <div class="mb-3">
        <label for="salaryRange" class="form-label">Salary</label>
        <input type="text" class="form-control" id="salaryRange" name="salaryRange" data-parsley-type="digits" data-parsley-trigger="keyup" data-parsley-type-message="Salary must be a number">
    </div>

    <div class="mb-3">
        <label for="vacancies" class="form-label">Number of Vacancies <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="vacancies" name="vacancies" required min="1" data-parsley-trigger="keyup" data-parsley-required-message="Please enter the number of vacancies" data-parsley-min-message="Vacancies must be at least 1">
    </div>

    <div class="mb-3">
    <label for="jobDate" class="form-label">Due Date <span class="text-danger">*</span></label>
    <input type="text" class="form-control datepicker" id="jobDate" name="jobDate"
        required
        data-parsley-trigger="change"
        data-parsley-required-message="Please select a due date"
        data-parsley-errors-container="#jobDateError">
    <div id="jobDateError" class="text-danger"></div>
</div>



    <div class="text-center">
        <button type="submit" id="submitButton" class="btn btn-success">Submit</button>
        <button type="button" id="updateButton" class="btn btn-warning" style="display: none;">Update Job</button>
    </div>
</form>
                    </div>

                    <!-- Jobs Table -->
                    <div id="jobsList" class="mt-4" style="display: none;">
                    <table id="jobsTable" class="table table-striped">
    <thead>
        <tr>
            <th>Job Id</th>
            <th>Job Title</th>
            <th>Job Level</th>
            <th>Company Name</th>
            <th>Job Location</th>
            <th>Job Type</th>
            <th>Vacancies</th>
            <th>Due date</th>
            <th class="actions-column">Actions</th>
        </tr>
    </thead>
    <tbody id="jobsTableBody">
        <!-- Job rows will be appended here -->
    </tbody>
</table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

$(document).ready(function () {

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
    });
@endif

// Initialize Select and Datepicker
//$('.selectpicker').selectpicker('refresh');
//$('.selectpicker').selectpicker('destroy'); // Destroy previous instance
$('.selectpicker').selectpicker('destroy').selectpicker();
$('.datepicker').datepicker({
            format: 'yyyy-mm-dd',  // Date format
            startDate: '+1d',      // Disables past & today’s date
            autoclose: true,       // Auto-close after selection
            todayHighlight: true   // Highlights today’s date
        });
// Initialize Parsley validation
$('#jobForm').parsley();
$('#companyLogo').parsley();
$('#jobDate').parsley();

// CSRF Token setup for AJAX requests
$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

// Function to load job data via AJAX
// Function to load job data via AJAX
function loadJobs() {
    $.ajax({
        url: "{{ route('job.index') }}",
        method: 'GET',
        success: function (response) {
            if (response.jobs.length > 0) {
                $('#jobsList').show();
                $('#jobsTableBody').empty();

                response.jobs.forEach(function (job) {
                    var jobHtml = `
                        <tr data-id="${job.jobId}">
                            <td>${job.jobId}</td>
                            <td>${job.jobTitle}</td>
                            <td>${job.jobLevel}</td>
                            <td>${job.companyName}</td>
                            <td>${job.jobLocation}</td>
                            <td>${job.jobType}</td>
                            <td>${job.vacancies}</td>
                            <td>${job.jobDate}</td>
                            <td>
                                <button class="edit-job-btn btn btn-warning" data-id="${job.jobId}">Edit</button>
                                <button class="delete-job-btn btn btn-danger" data-id="${job.jobId}">Delete</button>
                            </td>
                        </tr>
                    `;
                    $('#jobsTableBody').append(jobHtml);
                });

                // Initialize DataTables after appending rows
                $('#jobsTable').DataTable();
            } else {
                $('#jobsList').hide();
            }
        }
    });
}


// Load jobs on page load
loadJobs();

// Show Add Job Form
$('#addButton').click(function () {
    $('#jobForm')[0].reset();
    $('#jobId').prop('readonly', false);
    $('#addJobForm').show();
    $('#updateButton').hide();
    $('#submitButton').show();
    $(this).hide();
});

$('#submitButton').click(function (e) {
    e.preventDefault(); // Prevent default form submission

    // Trigger Parsley validation
    var isValid = $('#jobForm').parsley().validate();

    // Proceed only if the form is valid
    if (isValid) {
        var formData = new FormData($('#jobForm')[0]);

        $.ajax({
            url: "{{ route('job.store') }}",  // Replace with the correct route for storing job data
            method: 'POST',
            data: formData,
            processData: false,  // Don't process the data (files)
            contentType: false,  // Don't set content type (since we're sending FormData)
            success: function (response) {
                // Show success message after form submission with an OK button
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Created!',
                    text: response.success,
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirect to the dashboard after clicking OK
                    window.location.href = "{{ route('dashboard') }}"; // Replace with your actual dashboard route
                });
            },
            error: function (xhr) {
                // Handle error (e.g., validation error, etc.)
                Swal.fire("Job already exists");
            }
        });
    }
});



// Handle Edit Job Click
$(document).on('click', '.edit-job-btn', function () {
    var jobId = $(this).data('id');
    $.ajax({
        url: "/jobs/" + jobId + "/edit",
        method: 'GET',
        success: function (response) {
            if (response.job) {
                $('#jobId').val(response.job.jobId);
                $('#jobTitle').val(response.job.jobTitle);
                $('input[name="jobLevel"][value="' + response.job.jobLevel + '"]').prop("checked", true);
                $('#companyName').val(response.job.companyName);
                $('#jobLocation').val(response.job.jobLocation);
                $('#jobType').val(response.job.jobType).change();
                $('#salaryRange').val(response.job.salaryRange);
                $('#vacancies').val(response.job.vacancies);
                $('#jobDate').val(response.job.jobDate);

                $('#addJobForm').show();
                $('#updateButton').show().data('id', jobId);
                $('#submitButton').hide();
                $('#addButton').hide();
            }
        },
        error: function () {
            Swal.fire("Job id is Unique");
        }
    });
});

// Handle Job Update
$('#updateButton').click(function (e) {
    e.preventDefault();

    var formData = new FormData($('#jobForm')[0]);
    var jobId = $('#jobId').val();
    formData.append('_method', 'PUT');

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to update this job?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/jobs/" + jobId,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    Swal.fire("Updated!", response.success, "success").then(() => {
                        loadJobs();
                        $('#addJobForm').hide();
                        $('#addButton').show();
                    });
                },
                error: function (xhr) {
                    Swal.fire("Error!", "Failed to update job: " + xhr.responseText, "error");
                }
            });
        } else if (result.isDismissed) {
            // Optionally handle the case when the user clicks "Cancel"
            console.log("Update canceled");
        }
    });
});


// Handle Job Delete
$(document).on('click', '.delete-job-btn', function () {
    var jobId = $(this).data('id');

    Swal.fire({
        title: "Are you sure?",
        text: "This job will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/jobs/" + jobId,
                method: "POST",
                data: { _method: "DELETE" },
                headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                success: function (response) {
                    Swal.fire("Deleted!", response.success, "success");
                    loadJobs();
                },
                error: function (xhr) {
                    Swal.fire("Error", "Failed to delete job: " + xhr.responseText, "error");
                }
            });
        }
    });
});




// File validation - Parsley custom validators
window.Parsley.addValidator('filemimetypes', {
            requirementType: 'string',
            validateString: function(value, requirement, parsleyInstance) {
                var file = parsleyInstance.$element[0].files[0];
                if (!file) {
                    return true; // No file uploaded, allow it (nullable)
                }
                var allowedTypes = requirement.split(',');
                return allowedTypes.includes(file.type);
            },
            messages: {
                en: 'Only JPEG, PNG, JPG, and GIF files are allowed.'
            }
        });

        window.Parsley.addValidator('maxFileSize', {
            requirementType: 'integer',
            validateString: function(value, maxSize, parsleyInstance) {
                var file = parsleyInstance.$element[0].files[0];
                if (!file) {
                    return true; // No file uploaded, allow it (nullable)
                }
                return file.size <= maxSize * 1024; // Convert KB to Bytes
            },
            messages: {
                en: 'File size must not exceed 2MB.'
            }
        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',  // Date format
            startDate: 'tomorrow', // Restricts past dates
            autoclose: true,       // Auto-close on date selection
            todayHighlight: true   // Highlight today's date
        });

        // Custom Parsley Validator to Check Future Date
        window.Parsley.addValidator('futureDate', {
            requirementType: 'string',
            validateString: function(value) {
                var selectedDate = new Date(value);
                var today = new Date();
                today.setHours(0, 0, 0, 0); // Remove time for accurate comparison

                return selectedDate > today; // Returns true if the selected date is in the future
            },
            messages: {
                en: 'Please select a future date.'
            }
        });


});
</script>
@endsection
