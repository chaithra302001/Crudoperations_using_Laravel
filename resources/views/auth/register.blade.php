@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-success text-white">
                    {{ __('REGISTER') }}
                </div>

                <div class="card-body">
                    <form id="registerForm" method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required pattern="^[A-Za-z\s]+$" title="Only letters are allowed">
                            <div class="invalid-feedback">Only letters are allowed.</div>
                            @error('name')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            <div class="invalid-feedback">Enter a valid email address.</div>
                            @error('email')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Password is required.</div>
                            @error('password')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                            <div class="invalid-feedback">Passwords must match.</div>
                        </div>

                        <!-- Register Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Bootstrap Form Validation
    (function () {
        'use strict';
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
    })();

    // Toggle Password Visibility
    function togglePassword() {
        var passwordField = document.getElementById("password");
        var type = passwordField.type === "password" ? "text" : "password";
        passwordField.type = type;
    }

    // Validate Confirm Password
    function validatePasswordMatch() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("password_confirmation");
        if (confirmPassword.value !== password) {
            confirmPassword.setCustomValidity("Passwords do not match");
        } else {
            confirmPassword.setCustomValidity("");
        }
    }

    document.getElementById("password_confirmation").addEventListener("keyup", validatePasswordMatch);

    // AJAX Form Submission
    $(document).ready(function() {
        $('#registerForm').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('register') }}",  // Your route to the register function
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
    // If successful, show success message with OK and Cancel options
    Swal.fire({
        icon: 'success',
        title: 'Registration Successful!',
        text: response.message,
        //showCancelButton: true,
        confirmButtonText: 'OK',
        //cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            // If user clicks OK, redirect to the login page
            window.location.href = "{{ route('login') }}";
        } else {
            // If user clicks Cancel, stay on the registration page
            window.location.href = "{{ route('register') }}"; // Optional: you can keep them on the same page or redirect elsewhere
        }
    });
},
                error: function(xhr, status, error) {
                    // Handle validation errors
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        $.each(errors, function(key, value) {
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
