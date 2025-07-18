@extends('layouts.app')

@section('css')
<!-- Custom fonts for this template-->
<link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

<!-- Custom styles for this template-->
<link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">

<!-- DataTables -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block" style="background-image: url('/img/register.jpg'); background-size: cover;"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Selamat Datang Kembali!</h1>
                                </div>
                                
                                <!-- Form Login -->
                                <form action="{{ route('login') }}" method="POST" class="user">
                                    @csrf
                                    
                                    <!-- Email -->
                                    <div class="form-group">
                                        <input 
                                            type="email" 
                                            id="typeEmailX" 
                                            class="form-control form-control-user @error('email') is-invalid @enderror" 
                                            name="email"
                                            placeholder="Enter Email Address..." 
                                            value="{{ old('email') }}" 
                                            required
                                        />
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input 
                                                type="password" 
                                                id="typePasswordX" 
                                                class="form-control form-control-user @error('password') is-invalid @enderror" 
                                                name="password"
                                                placeholder="Password" 
                                                required
                                            />
                                            <button type="button" class="btn btn-link position-absolute password-toggle" onclick="togglePassword('typePasswordX', 'togglePasswordIcon')">
                                                <i class="fas fa-eye text-muted" id="togglePasswordIcon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Remember Me -->
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="customCheck" name="remember">
                                            <label class="custom-control-label" for="customCheck">Tetap Masuk</label>
                                        </div>
                                    </div>

                                    <!-- Login Button -->
                                    <button 
                                        class="btn btn-primary btn-user btn-block" 
                                        type="submit"
                                        id="loginBtn"
                                    >
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>
                                        Login
                                    </button>

                                </form>

                                <hr>
                                <div class="text-center">
                                    <a class="small" href="/forgot-password">Lupa Kata Sandi?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="{{ route('register') }}">Buat Akun Baru</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for password toggle -->
<style>
.password-toggle {
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: none;
    padding: 0;
    z-index: 10;
}

.password-toggle:focus {
    box-shadow: none;
}

.position-relative {
    position: relative;
}

.bg-login-image {
    background: url('https://source.unsplash.com/600x800/?office,login') center center;
    background-size: cover;
}

/* Custom validation styles */
.is-invalid {
    border-color: #e74a3b;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #e74a3b;
}

/* Loading spinner */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}
</style>

<!-- JavaScript -->
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Form handling with loading spinner
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const loginBtn = document.getElementById('loginBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    form.addEventListener('submit', function(e) {
        // Show loading spinner
        loadingSpinner.classList.remove('d-none');
        loginBtn.disabled = true;
        
        // Basic validation
        const email = document.getElementById('typeEmailX').value;
        const password = document.getElementById('typePasswordX').value;
        
        if (!email || !password) {
            e.preventDefault();
            alert('Please fill in all required fields!');
            loadingSpinner.classList.add('d-none');
            loginBtn.disabled = false;
            return;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Please enter a valid email address!');
            loadingSpinner.classList.add('d-none');
            loginBtn.disabled = false;
            return;
        }
    });
});
</script>
@endsection

@section('scripts')
<!-- Bootstrap core JavaScript-->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<!-- Page level plugins -->
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
<script src="{{ asset('js/demo/dashboard-stats.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/notifications.js') }}"></script>
@endsection