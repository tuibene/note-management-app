<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Create a new account for Note Management App">
    <meta name="csrf-token" content="GENERATED_CSRF_TOKEN">
    <title>Create Account - Note Management App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --primary-hover: #0056b3;
            --error-color: #dc3545;
            --success-color: #28a745;
            --transition: all 0.3s ease;
        }

        body {
            background: linear-gradient(135deg, #f0f2f5 0%, #e6e9ef 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .auth-card {
            max-width: 440px;
            width: 100%;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            transition: var(--transition);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
        }

        .auth-card h2 {
            font-size: 1.875rem;
            color: #1a1a1a;
            margin-bottom: 28px;
            text-align: center;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #d1d5db;
            padding: 12px 16px;
            font-size: 1rem;
            transition: var(--transition);
            height: 48px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: var(--error-color);
            background-image: none;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .error {
            color: var(--error-color);
            font-size: 0.825rem;
            margin-top: 6px;
            display: none;
            transition: var(--transition);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            width: 100%;
            padding: 14px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
        }

        @keyframes spin {
            to { transform: translateY(-50%) rotate(360deg); }
        }

        .links {
            text-align: center;
            margin-top: 24px;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .links a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 250px;
            padding: 15px;
            border-radius: 8px;
            color: white;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
            display: none;
        }

        .toast.error {
            background: var(--error-color);
        }

        .toast.success {
            background: var(--success-color);
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 8px;
            transition: var(--transition);
        }

        .strength-weak { background: var(--error-color); width: 33%; }
        .strength-medium { background: #ffc107; width: 66%; }
        .strength-strong { background: var(--success-color); width: 100%; }

        @media (max-width: 480px) {
            .auth-card {
                padding: 24px;
                margin: 16px;
            }
            .auth-card h2 {
                font-size: 1.5rem;
            }
            .form-control, .btn-primary {
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>
    <div class="toast" id="toastNotification"></div>
    <div class="auth-card" role="region" aria-labelledby="register-title">
        <h2 id="register-title">Create Account</h2>
        <form id="registerForm" novalidate>
            <div class="form-group">
                <label for="registerEmail" class="form-label">Email Address</label>
                <input type="email" id="registerEmail" class="form-control" placeholder="Enter your email" required aria-describedby="registerEmailError">
                <p id="registerEmailError" class="error" role="alert"></p>
            </div>
            <div class="form-group">
                <label for="registerName" class="form-label">Display Name</label>
                <input type="text" id="registerName" class="form-control" placeholder="Enter your display name" required aria-describedby="registerNameError">
                <p id="registerNameError" class="error" role="alert"></p>
            </div>
            <div class="form-group">
                <label for="registerPassword" class="form-label">Password</label>
                <div style="position: relative;">
                    <input type="password" id="registerPassword" class="form-control" placeholder="Enter your password" required aria-describedby="registerPasswordError">
                    <i class="fas fa-eye password-toggle" id="passwordToggle" aria-label="Toggle password visibility"></i>
                </div>
                <div class="password-strength" id="passwordStrength"></div>
                <p id="registerPasswordError" class="error" role="alert"></p>
            </div>
            <div class="form-group">
                <label for="registerPasswordConfirmation" class="form-label">Confirm Password</label>
                <div style="position: relative;">
                    <input type="password" id="registerPasswordConfirmation" class="form-control" placeholder="Confirm your password" required aria-describedby="registerPasswordConfirmationError">
                    <i class="fas fa-eye password-toggle" id="passwordConfirmationToggle" aria-label="Toggle confirm password visibility"></i>
                </div>
                <p id="registerPasswordConfirmationError" class="error" role="alert"></p>
            </div>
            <button type="submit" class="btn btn-primary" id="registerButton">Register</button>
            <div class="links">
                <p>Already have an account? <a href="login.blade.php" aria-label="Sign in to your account">Sign In</a></p>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('registerForm');
            const emailInput = document.getElementById('registerEmail');
            const nameInput = document.getElementById('registerName');
            const passwordInput = document.getElementById('registerPassword');
            const passwordConfirmationInput = document.getElementById('registerPasswordConfirmation');
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordConfirmationToggle = document.getElementById('passwordConfirmationToggle');
            const passwordStrength = document.getElementById('passwordStrength');
            const registerButton = document.getElementById('registerButton');
            const toast = document.getElementById('toastNotification');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            // Show toast notification
            function showToast(message, type) {
                toast.textContent = message;
                toast.className = `toast ${type}`;
                toast.style.display = 'block';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 3000);
            }

            // Clear form errors
            function clearErrors() {
                document.querySelectorAll('.error').forEach(error => {
                    error.textContent = '';
                    error.style.display = 'none';
                });
                document.querySelectorAll('.form-control').forEach(input => {
                    input.classList.remove('is-invalid');
                });
            }

            // Display form errors
            function displayErrors(errors) {
                clearErrors();
                for (const [field, message] of Object.entries(errors)) {
                    const errorElement = document.getElementById(`${field}Error`);
                    const inputElement = document.getElementById(field);
                    if (errorElement && inputElement) {
                        errorElement.textContent = message;
                        errorElement.style.display = 'block';
                        inputElement.classList.add('is-invalid');
                    }
                }
            }

            // Password strength indicator
            function updatePasswordStrength() {
                const password = passwordInput.value;
                let strength = 0;
                if (password.length >= 6) strength++;
                if (/[A-Z]/.test(password) && /[0-9]/.test(password)) strength++;
                if (password.length >= 8 && /[^A-Za-z0-9]/.test(password)) strength++;

                passwordStrength.className = 'password-strength';
                if (strength === 0) {
                    passwordStrength.style.width = '0%';
                } else if (strength === 1) {
                    passwordStrength.classList.add('strength-weak');
                } else if (strength === 2) {
                    passwordStrength.classList.add('strength-medium');
                } else {
                    passwordStrength.classList.add('strength-strong');
                }
            }

            // Toggle password visibility
            function togglePasswordVisibility(input, toggle) {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                toggle.classList.toggle('fa-eye', isPassword);
                toggle.classList.toggle('fa-eye-slash', !isPassword);
            }

            // Client-side form validation
            function validateForm() {
                const errors = {};
                if (!emailInput.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                    errors.registerEmail = 'Please enter a valid email address';
                }
                if (!nameInput.value.trim()) {
                    errors.registerName = 'Display name is required';
                }
                if (passwordInput.value.length < 6) {
                    errors.registerPassword = 'Password must be at least 6 characters';
                }
                if (passwordInput.value !== passwordConfirmationInput.value) {
                    errors.registerPasswordConfirmation = 'Passwords do not match';
                }
                return errors;
            }

            // Form submission handler
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                clearErrors();
                const errors = validateForm();
                if (Object.keys(errors).length > 0) {
                    displayErrors(errors);
                    return;
                }

                registerButton.disabled = true;
                registerButton.classList.add('btn-loading');

                try {
                    const response = await fetch('/api/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            email: emailInput.value,
                            display_name: nameInput.value,
                            password: passwordInput.value,
                            password_confirmation: passwordConfirmationInput.value,
                        }),
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422) {
                            const errors = {};
                            for (const [field, messages] of Object.entries(data.errors)) {
                                errors[field.replace('.', '')] = messages[0];
                            }
                            displayErrors(errors);
                        } else {
                            showToast(data.message || 'Registration failed', 'error');
                        }
                        return;
                    }

                    showToast('Registration successful! Please verify your email.', 'success');
                    setTimeout(() => {
                        window.location.href = 'login.html';
                    }, 2000);

                } catch (error) {
                    showToast('An error occurred. Please try again.', 'error');
                    console.error('Registration error:', error);
                } finally {
                    registerButton.disabled = false;
                    registerButton.classList.remove('btn-loading');
                }
            });

            // Event listeners for real-time updates
            passwordInput.addEventListener('input', updatePasswordStrength);
            passwordToggle.addEventListener('click', () => togglePasswordVisibility(passwordInput, passwordToggle));
            passwordConfirmationToggle.addEventListener('click', () => togglePasswordVisibility(passwordConfirmationInput, passwordConfirmationToggle));
        });
    </script>
</body>
</html>