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
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block" style="background-image: url('/img/register.jpg'); background-size: cover;"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Buat Akun Baru!</h1>
                                </div>
                                
                                <!-- Registration Form -->
                                <form action="{{ route('register') }}" method="POST" novalidate class="user">
                                    @csrf

                                    <!-- Username -->
                                    <div class="form-group">
                                        <input 
                                            type="text" 
                                            id="typeUsernameX" 
                                            name="username" 
                                            class="form-control form-control-user @error('username') is-invalid @enderror" 
                                            value="{{ old('username') }}" 
                                            required 
                                            autocomplete="username"
                                            placeholder="Username"
                                            maxlength="10"
                                        />
                                        @error('username')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group">
                                        <input 
                                            type="email" 
                                            id="typeEmailX" 
                                            name="email" 
                                            class="form-control form-control-user @error('email') is-invalid @enderror" 
                                            value="{{ old('email') }}" 
                                            required 
                                            autocomplete="email"
                                            placeholder="Email Address"
                                            maxlength="50"
                                        />
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Password Row -->
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <div class="position-relative">
                                                <input 
                                                    type="password" 
                                                    id="typePasswordX" 
                                                    name="password" 
                                                    class="form-control form-control-user @error('password') is-invalid @enderror" 
                                                    required 
                                                    autocomplete="new-password"
                                                    placeholder="Password"
                                                    minlength="8"
                                                />
                                                <button type="button" class="btn btn-link position-absolute password-toggle" onclick="togglePassword('typePasswordX', 'togglePasswordIcon')">
                                                    <i class="fas fa-eye text-muted" id="togglePasswordIcon"></i>
                                                </button>
                                                @error('password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Password minimal 8 karakter
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="position-relative">
                                                <input 
                                                    type="password" 
                                                    id="confirmPasswordX" 
                                                    name="password_confirmation" 
                                                    class="form-control form-control-user" 
                                                    required 
                                                    autocomplete="new-password"
                                                    placeholder="Repeat Password"
                                                    minlength="8"
                                                />
                                                <button type="button" class="btn btn-link position-absolute password-toggle" onclick="togglePassword('confirmPasswordX', 'toggleConfirmPasswordIcon')">
                                                    <i class="fas fa-eye text-muted" id="toggleConfirmPasswordIcon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Nama -->
                                    <div class="form-group">
                                        <input 
                                            type="text" 
                                            id="typeNamaX" 
                                            name="nama" 
                                            class="form-control form-control-user @error('nama') is-invalid @enderror" 
                                            value="{{ old('nama') }}" 
                                            required 
                                            autocomplete="name"
                                            placeholder="Nama Lengkap"
                                            maxlength="50"
                                        />
                                        @error('nama')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Alamat -->
                                    <div class="form-group">
                                        <textarea 
                                            id="typeAlamatX" 
                                            name="alamat" 
                                            class="form-control @error('alamat') is-invalid @enderror" 
                                            required 
                                            autocomplete="street-address"
                                            placeholder="Alamat Lengkap"
                                            rows="3"
                                            maxlength="100"
                                            style="border-radius: 10rem; padding: 1.5rem;"
                                        >{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- No HP -->
                                    <div class="form-group">
                                        <input 
                                            type="tel" 
                                            id="typeNoHpX" 
                                            name="no_hp" 
                                            class="form-control form-control-user @error('no_hp') is-invalid @enderror" 
                                            value="{{ old('no_hp') }}" 
                                            required 
                                            autocomplete="tel"
                                            placeholder="No. Handphone"
                                            pattern="[0-9+\-\s]*"
                                            maxlength="15"
                                        />
                                        @error('no_hp')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Format: 08xxxxxxxxxx atau +62xxxxxxxxxx
                                        </small>
                                    </div>

                                    <!-- Terms and Conditions -->
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="termsCheck" required>
                                            <label class="custom-control-label" for="termsCheck">
                                                Saya setuju dengan <a href="#" class="text-primary">syarat dan ketentuan</a>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Register Button -->
                                    <button 
                                        class="btn btn-primary btn-user btn-block" 
                                        type="submit"
                                        id="registerBtn"
                                    >
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>
                                        Daftar Akun
                                    </button>
                                </form>

                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ route('login') }}">Sudah memiliki akun? Masuk di sini</a>
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

.form-text {
    font-size: 0.8rem;
}

.bg-register-image {
    background: url('https://source.unsplash.com/600x800/?office,business') center center;
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

// Form validation and handling
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const registerBtn = document.getElementById('registerBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    form.addEventListener('submit', function(e) {
        // Show loading spinner
        loadingSpinner.classList.remove('d-none');
        registerBtn.disabled = true;
        
        // Basic validation
        const password = document.getElementById('typePasswordX').value;
        const confirmPassword = document.getElementById('confirmPasswordX').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak sama!');
            loadingSpinner.classList.add('d-none');
            registerBtn.disabled = false;
            return;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('Password minimal 8 karakter!');
            loadingSpinner.classList.add('d-none');
            registerBtn.disabled = false;
            return;
        }
    });
    
    // Real-time password confirmation validation
    const confirmPasswordInput = document.getElementById('confirmPasswordX');
    confirmPasswordInput.addEventListener('input', function() {
        const password = document.getElementById('typePasswordX').value;
        const confirmPassword = this.value;
        
        if (confirmPassword && password !== confirmPassword) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
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