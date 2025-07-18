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
                        <div class="col-lg-6 d-none d-lg-block" style="background-image: url('/img/reset-password.jpg'); background-size: cover;"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Reset Kata Sandi</h1>
                                    <p class="mb-4">Masukkan kata sandi baru untuk akun Anda</p>
                                </div>
                                
                                <!-- Form Reset Password -->
                                <form action="{{ route('password.update') }}" method="POST" class="user">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    
                                    <!-- Email (Hidden/Readonly) -->
                                    <div class="form-group">
                                        <input 
                                            type="email" 
                                            id="typeEmailX" 
                                            class="form-control form-control-user @error('email') is-invalid @enderror" 
                                            name="email"
                                            placeholder="Email Address" 
                                            value="{{ $email ?? old('email') }}" 
                                            readonly
                                        />
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- New Password -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input 
                                                type="password" 
                                                id="typePasswordX" 
                                                class="form-control form-control-user @error('password') is-invalid @enderror" 
                                                name="password"
                                                placeholder="Kata Sandi Baru" 
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

                                    <!-- Confirm Password -->
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <input 
                                                type="password" 
                                                id="typePasswordConfirmX" 
                                                class="form-control form-control-user @error('password_confirmation') is-invalid @enderror" 
                                                name="password_confirmation"
                                                placeholder="Konfirmasi Kata Sandi Baru" 
                                                required
                                            />
                                            <button type="button" class="btn btn-link position-absolute password-toggle" onclick="togglePassword('typePasswordConfirmX', 'togglePasswordConfirmIcon')">
                                                <i class="fas fa-eye text-muted" id="togglePasswordConfirmIcon"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Password Strength Indicator -->
                                    <div class="form-group">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="passwordStrengthText">Kekuatan kata sandi</small>
                                    </div>

                                    <!-- Reset Button -->
                                    <button 
                                        class="btn btn-primary btn-user btn-block" 
                                        type="submit"
                                        id="resetBtn"
                                    >
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>
                                        <i class="fas fa-key me-2"></i>
                                        Reset Kata Sandi
                                    </button>

                                </form>

                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ route('login') }}">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Kembali ke Login
                                    </a>
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

<!-- Custom CSS -->
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

.bg-reset-password-image {
    background: url('https://source.unsplash.com/600x800/?security,password') center center;
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

/* Password strength colors */
.progress-bar.weak {
    background-color: #dc3545;
}

.progress-bar.fair {
    background-color: #ffc107;
}

.progress-bar.good {
    background-color: #17a2b8;
}

.progress-bar.strong {
    background-color: #28a745;
}

/* Button hover effects */
.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

/* Form animation */
.form-group {
    margin-bottom: 1rem;
}

.form-control-user {
    transition: all 0.3s ease;
}

.form-control-user:focus {
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    border-color: #4e73df;
}

/* Link styling */
.small {
    text-decoration: none;
    color: #5a5c69;
    transition: color 0.3s ease;
}

.small:hover {
    color: #4e73df;
    text-decoration: underline;
}

/* Email field styling */
input[readonly] {
    background-color: #f8f9fc;
    border-color: #d1d3e2;
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

// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    let strengthText = '';
    let strengthClass = '';
    
    if (password.length >= 8) strength += 20;
    if (password.match(/[a-z]/)) strength += 20;
    if (password.match(/[A-Z]/)) strength += 20;
    if (password.match(/[0-9]/)) strength += 20;
    if (password.match(/[^a-zA-Z0-9]/)) strength += 20;
    
    if (strength <= 40) {
        strengthText = 'Lemah';
        strengthClass = 'weak';
    } else if (strength <= 60) {
        strengthText = 'Sedang';
        strengthClass = 'fair';
    } else if (strength <= 80) {
        strengthText = 'Baik';
        strengthClass = 'good';
    } else {
        strengthText = 'Kuat';
        strengthClass = 'strong';
    }
    
    return { strength, strengthText, strengthClass };
}

// Form handling with loading spinner
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const resetBtn = document.getElementById('resetBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const passwordInput = document.getElementById('typePasswordX');
    const passwordConfirmInput = document.getElementById('typePasswordConfirmX');
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('passwordStrengthText');
    
    // Password strength indicator
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const { strength, strengthText: text, strengthClass } = checkPasswordStrength(password);
        
        strengthBar.style.width = strength + '%';
        strengthBar.className = 'progress-bar ' + strengthClass;
        strengthText.textContent = text;
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        // Show loading spinner
        loadingSpinner.classList.remove('d-none');
        resetBtn.disabled = true;
        
        // Basic validation
        const password = passwordInput.value;
        const passwordConfirm = passwordConfirmInput.value;
        
        if (!password || !passwordConfirm) {
            e.preventDefault();
            alert('Silakan isi semua field yang diperlukan!');
            loadingSpinner.classList.add('d-none');
            resetBtn.disabled = false;
            return;
        }
        
        if (password !== passwordConfirm) {
            e.preventDefault();
            alert('Konfirmasi kata sandi tidak cocok!');
            loadingSpinner.classList.add('d-none');
            resetBtn.disabled = false;
            return;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('Kata sandi minimal 8 karakter!');
            loadingSpinner.classList.add('d-none');
            resetBtn.disabled = false;
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