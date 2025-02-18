@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-success text-white">
                    {{ __('LOGIN') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                        @csrf

                        <!-- Email Field -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autofocus>
                            <div class="invalid-feedback">Please enter a valid email.</div>
                        </div>

                        <!-- Password Field -->
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" required>
                            <div class="invalid-feedback">Password is required.</div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('register') }}" class="btn btn-link text-primary">
                            {{ __('Register here') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // AJAX Form Submission
        $('#loginForm').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            var formData = $(this).serialize(); // Serialize form data

            $.ajax({
                url: "{{ route('login') }}", // Route for login
                type: "POST",
                data: formData,
                success: function(response) {
                    // Show SweetAlert on successful login
                    Swal.fire({
                        icon: 'success',
                        title: 'Login successful!',
                        text: 'Do you want to proceed to your dashboard?',
                        //showCancelButton: true,
                        confirmButtonText: 'OK',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // If user clicks OK, redirect to dashboard
                            window.location.href = "{{ route('dashboard') }}"; // Redirect to dashboard
                        } else {
                            // If user clicks Cancel, stay on the login page
                            window.location.href = "{{ route('login') }}"; // Stay on the login page
                        }
                    });
                },
                error: function(xhr, status, error) {
                    // Handle errors, for example, invalid credentials
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        $.each(errors, function(key, value) {
                            $('#' + key).addClass('is-invalid');
                            $('#' + key).next('.invalid-feedback').html(value[0]);
                        });
                    }
                }
            });
        });



    });


</script>
@endsection
