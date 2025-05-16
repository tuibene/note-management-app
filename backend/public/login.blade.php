<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sign in to Note Management App">
    <meta name="csrf-token" content="GENERATED_CSRF_TOKEN">
    <title>Sign In - Note Management App</title>
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
    <div class="auth-card" role="region" aria-labelledby="login-title">
        <h2 id="login-title">Sign In Notes</h2>
        <form id="loginForm" novalidate>
            <div class="form-group">
                <label for="loginEmail" class="form-label">Email Address</label>
                <input type="email" id="loginEmail" class="form-control" placeholder="Enter your email" required aria-describedby="loginEmailError">
                <p id="loginEmailError" class="error" role="alert"></p>
            </div>
            <div class="form-group">
                <label for="loginPassword" class="form-label">Password</label>
                <div style="position: relative;">
                    <input type="password" id="loginPassword" class="form-control" placeholder="Enter your password" required aria-describedby="loginPasswordError">
                    <i class="fas fa-eye password-toggle" id="passwordToggle" aria-label="Toggle password visibility"></i>
                </div>
                <p id="loginPasswordError" class="error" role="alert"></p>
            </div>
            <button type="submit" class="btn btn-primary" id="loginButton">Sign In</button>
            <div class="links">
                <p>Don't have an account? <a href="register.blade.php" aria-label="Create a new account">Sign Up</a></p>
                <p><a href="forgot-password.blade.php" aria-label="Reset your password">Forgot Password?</a></p>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('loginEmail');
    const passwordInput = document.getElementById('loginPassword');
    const passwordToggle = document.getElementById('passwordToggle');
    const loginButton = document.getElementById('loginButton');
    const toast = document.getElementById('toastNotification');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // Show toast notification
    const showToast = (message, type) => {
        toast.textContent = message;
        toast.className = `toast ${type}`;
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    };

    // Clear form errors
    const clearErrors = () => {
        document.querySelectorAll('.error').forEach(error => {
            error.textContent = '';
            error.style.display = 'none';
        });
        document.querySelectorAll('.form-control').forEach(input => {
            input.classList.remove('is-invalid');
        });
    };

    // Display form errors
    const displayErrors = (errors) => {
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
    };

    // Toggle password visibility
    const togglePasswordVisibility = (input, toggle) => {
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        toggle.classList.toggle('fa-eye', isPassword);
        toggle.classList.toggle('fa-eye-slash', !isPassword);
    };

    // Client-side form validation
    const validateForm = () => {
        const errors = {};
        if (!emailInput.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            errors.loginEmail = 'Please enter a valid email address';
        }
        if (!passwordInput.value.trim()) {
            errors.loginPassword = 'Password is required';
        }
        return errors;
    };

    // Handle API request
    const loginRequest = async (email, password) => {
        if (!csrfToken) {
            throw new Error('CSRF token is missing');
        }

        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ email, password }),
        });

        const data = await response.json();
        return { response, data };
    };

    // Handle API response
    const handleResponse = ({ response, data }) => {
        if (!response.ok) {
            if (response.status === 422) {
                const errors = {};
                for (const [field, messages] of Object.entries(data.errors || {})) {
                    errors[field.replace('.', '')] = messages[0];
                }
                displayErrors(errors);
            } else if (response.status === 401) {
                showToast(data.message || 'Invalid credentials', 'error');
            } else if (response.status === 419) {
                showToast('Session expired, please refresh the page', 'error');
            } else {
                showToast(data.message || 'Login failed', 'error');
            }
            return false;
        }

        if (!data.token) {
            showToast('Invalid response from server', 'error');
            return false;
        }

        localStorage.setItem('auth_token', data.token);
        showToast('Login successful!', 'success');
        setTimeout(() => {
            window.location.href = '/dashboard.blade.php';
        }, 2000);
        return true;
    };

    // Form submission handler
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();

        const errors = validateForm();
        if (Object.keys(errors).length > 0) {
            displayErrors(errors);
            return;
        }

        loginButton.disabled = true;
        loginButton.classList.add('btn-loading');

        try {
            const result = await loginRequest(emailInput.value, passwordInput.value);
            await handleResponse(result);
        } catch (error) {
            showToast('An error occurred, please try again', 'error');
            console.error('Login error:', error.message);
        } finally {
            loginButton.disabled = false;
            loginButton.classList.remove('btn-loading');
        }
    });

    // Password toggle event listener
    passwordToggle.addEventListener('click', () => togglePasswordVisibility(passwordInput, passwordToggle));
});
    </script>
</body>
</html>