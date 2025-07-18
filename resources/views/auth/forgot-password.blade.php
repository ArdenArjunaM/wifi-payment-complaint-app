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
                                    <h1 class="h4 text-gray-900 mb-2">Lupa Kata Sandi?</h1>
                                    <p class="mb-4">Tidak masalah, masukkan email Anda di bawah ini dan kami akan mengirimkan tautan untuk mereset kata sandi!</p>
                                </div>
                                
                                <!-- Success Message -->
                                @if (session('status'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        {{ session('status') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                
                                <!-- Form Reset Password -->
                                <form action="{{ route('password.email') }}" method="POST" class="user">
                                    @csrf
                                    
                                    <!-- Email -->
                                    <div class="form-group">
                                        <input 
                                            type="email" 
                                            id="typeEmailX" 
                                            class="form-control form-control-user @error('email') is-invalid @enderror" 
                                            name="email"
                                            placeholder="Masukkan Alamat Email..." 
                                            value="{{ old('email') }}" 
                                            required
                                        />
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Reset Button -->
                                    <button 
                                        class="btn btn-primary btn-user btn-block" 
                                        type="submit"
                                        id="resetBtn"
                                    >
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Kirim Tautan Reset
                                    </button>

                                </form>

                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ route('register') }}">Buat Akun Baru</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="{{ route('login') }}">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Kembali ke Login
                                    </a>
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
.bg-forgot-password-image {
    background: url('https://source.unsplash.com/600x800/?security,lock') center center;
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

/* Success alert custom styling */
.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-success .fas {
    color: #28a745;
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
</style>

<!-- JavaScript -->
<script>
// Form handling with loading spinner
// Form handling dengan validasi yang lebih aman
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const resetBtn = document.getElementById('resetBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    form.addEventListener('submit', function(e) {
        const email = document.getElementById('typeEmailX').value.trim();
        
        // Hanya tampilkan loading jika email tidak kosong
        if (email && email.length > 0) {
            // Show loading spinner
            loadingSpinner.classList.remove('d-none');
            resetBtn.disabled = true;
            
            // Optional: Tambahkan timeout untuk reset button jika terlalu lama
            setTimeout(function() {
                if (loadingSpinner && !loadingSpinner.classList.contains('d-none')) {
                    loadingSpinner.classList.add('d-none');
                    resetBtn.disabled = false;
                }
            }, 10000); // 10 detik timeout
        }
        
        // Biarkan Laravel handle validasi - jangan preventDefault di sini
        // Form akan tetap submit dan Laravel akan handle error/success
    });
    
    // Auto-hide success message after 5 seconds
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.transition = 'opacity 0.5s ease';
            successAlert.style.opacity = '0';
            setTimeout(function() {
                successAlert.remove();
            }, 500);
        }, 5000);
    }
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