@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-success text-white">
                <h4 class="mb-0">{{ __('REGISTRATION FORM') }}</h4>
                </div>

                <div class="card-body">
                <form id="registerForm" method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}" required pattern="^[A-Za-z\s]+$"
                               title="Only letters are allowed" placeholder="Enter your full name">
                        <div class="invalid-feedback">Only letters are allowed.</div>
                        @error('name')
                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required placeholder="Enter your email">
                        <div class="invalid-feedback">Enter a valid email address.</div>
                        @error('email')
                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>

<!-- Password -->
<div class="mb-2">
    <label for="password" class="form-label">{{ __('Password') }}</label>
    <div class="input-group">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
               name="password" required placeholder="Enter your password">
    </div>
    <div class="invalid-feedback">Password is required.</div>
    @error('password')
        <div class="text-danger"><strong>{{ $message }}</strong></div>
    @enderror
</div>


                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password_confirmation" type="password" class="form-control"
                               name="password_confirmation" required placeholder="Re-enter password">
                        <div class="invalid-feedback">Passwords must match.</div>
                    </div>

                    <!-- Register Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" style="font-size: 1.1rem;">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    // Bootstrap Form Validation
    $(document).ready(function () {
    'use strict';

    // Bootstrap Validation
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            validatePasswordMatch();
            form.classList.add('was-validated');
        }, false);
    });

    // Toggle Password Visibility




    // Validate Confirm Password
    function validatePasswordMatch() {
        var password = $("#password").val();
        var confirmPassword = $("#password_confirmation");

        if (confirmPassword.val() !== password) {
            confirmPassword[0].setCustomValidity("Passwords do not match");
        } else {
            confirmPassword[0].setCustomValidity("");
        }
    }

    $("#password_confirmation").keyup(validatePasswordMatch);

    // AJAX Form Submission
    $('#registerForm').submit(function (event) {
        event.preventDefault(); // Prevent default form submission

        var formData = new FormData(this);

        $.ajax({
            url: "{{ route('register') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // If successful, show success message with OK and Cancel options
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful!',
                    text: response.message,
                    confirmButtonText: 'OK',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to the login page
                        window.location.href = "{{ route('login') }}";
                    } else {
                        // Stay on the registration page (optional)
                        window.location.href = "{{ route('register') }}";
                    }
                });
            },
            error: function (xhr, status, error) {
                // Handle validation errors
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function (key, value) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key).next('.text-danger').html(value[0]);
                    });
                }
            }
        });
    });
});

</script>
@endsection
