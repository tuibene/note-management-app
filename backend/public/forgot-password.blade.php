<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Reset your password for Note Management App">
    <meta name="csrf-token" content="GENERATED_CSRF_TOKEN">
    <title>Reset Password - Note Management App</title>
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
    <div class="auth-card" role="region" aria-labelledby="reset-title">
        <h2 id="reset-title">Reset Password</h2>
        <form id="resetForm" novalidate>
            <div class="form-group">
                <label for="resetEmail" class="form-label">Email Address</label>
                <input type="email" id="resetEmail" class="form-control" placeholder="Enter your email" required aria-describedby="resetEmailError">
                <p id="resetEmailError" class="error" role="alert"></p>
            </div>
            <button type="submit" class="btn btn-primary" id="resetButton">Request Reset</button>
            <div class="links">
                <p><a href="login.html" aria-label="Sign in to your account">Sign In</a></p>
                <p>No account? <a href="register.html" aria-label="Create a new account">Sign Up</a></p>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('resetForm');
            const emailInput = document.getElementById('resetEmail');
            const resetButton = document.getElementById('resetButton');
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

            // Client-side form validation
            function validateForm() {
                const errors = {};
                if (!emailInput.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                    errors.resetEmail = 'Please enter a valid email address';
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

                resetButton.disabled = true;
                resetButton.classList.add('btn-loading');

                try {
                    const response = await fetch('/api/password/reset-request', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            email: emailInput.value,
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
                        } else if (response.status === 404) {
                            showToast(data.message || 'We can\'t find a user with that email address.', 'error');
                        } else {
                            showToast(data.message || 'Unable to send reset link', 'error');
                        }
                        return;
                    }

                    showToast('Reset link sent! Check your email.', 'success');
                    setTimeout(() => {
                        window.location.href = 'login.html';
                    }, 2000);

                } catch (error) {
                    showToast('An error occurred. Please try again.', 'error');
                    console.error('Reset request error:', error);
                } finally {
                    resetButton.disabled = false;
                    resetButton.classList.remove('btn-loading');
                }
            });
        });
    </script>
</body>
</html>