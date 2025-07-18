<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <!-- Left Image -->
                        <div class="col-lg-6 d-none d-lg-block bg-verify-image"></div>

                        <!-- Right Content -->
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Verify Your Email Address</h1>
                                    <div class="mb-4">
                                        <i class="fas fa-envelope fa-3x text-primary"></i>
                                    </div>
                                </div>

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="text-center">
                                    <p class="text-gray-600 mb-4">
                                        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
                                    </p>

                                    <p class="text-gray-600 mb-4">
                                        <strong>{{ Auth::user()->email }}</strong>
                                    </p>

                                    <p class="text-gray-600 mb-4">
                                        If you didn't receive the email, we will gladly send you another.
                                    </p>

                                    <!-- Resend Form -->
                                    <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            <i class="fas fa-paper-plane mr-2"></i>
                                            Resend Verification Email
                                        </button>
                                    </form>

                                    <hr>

                                    <!-- Logout Form -->
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-link text-gray-600">
                                            <i class="fas fa-sign-out-alt mr-2"></i>
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Style -->
<style>
.bg-verify-image {
    background: url('https://source.unsplash.com/600x800/?email,verification') center center;
    background-size: cover;
}
</style>

<!-- Bootstrap JS and dependencies -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
</body>
</html>
