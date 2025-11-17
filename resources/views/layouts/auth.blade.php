<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Dorm')</title>
    
    <!-- Font Awesome -->
    <link href="{{ asset('css/fontawesome/all.min.css') }}" rel="stylesheet">
    
    <!-- Custom Auth CSS -->
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-wrapper">
            @yield('content')
        </div>
    </div>

    <script>
        // Password visibility toggle
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.querySelector(`[onclick="togglePassword('${inputId}')"] i`);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Form submission loading states
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('btn-loading');
                        const originalHTML = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                        
                        // Re-enable after 10 seconds in case of error
                        setTimeout(() => {
                            if (submitBtn.disabled) {
                                submitBtn.disabled = false;
                                submitBtn.classList.remove('btn-loading');
                                submitBtn.innerHTML = originalHTML;
                            }
                        }, 10000);
                    }
                });
            });
        });

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-auto-hide');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });

        // Password strength indicator
        function checkPasswordStrength(password) {
            const strengthMeter = document.getElementById('password-strength');
            if (!strengthMeter) return;
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;
            
            const strengthLevels = [
                { text: 'Very Weak', class: 'strength-weak' },
                { text: 'Weak', class: 'strength-weak' },
                { text: 'Good', class: 'strength-medium' },
                { text: 'Strong', class: 'strength-strong' },
                { text: 'Very Strong', class: 'strength-very-strong' }
            ];
            
            const level = strengthLevels[strength];
            strengthMeter.textContent = `Strength: ${level.text}`;
            strengthMeter.className = `password-strength ${level.class}`;
        }
    </script>
</body>
</html>