<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/db_connect.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    // Input validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required!";
        header("Location: login.php");
        exit();
    }

    // Prepare SQL statement to prevent SQL injection
    $query = "SELECT user_id, username, email, password FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        $_SESSION['error'] = "Database error. Please try again later.";
        header("Location: login.php");
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Password verification
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            // Login successful
            $_SESSION['user_id'] = $user['user_id'];  // Consistent with your table structure
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Remember me functionality
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 days

                // Store token in database
                $token_query = "INSERT INTO user_tokens (user_id, token, expires_at) 
                                VALUES (?, ?, ?) 
                                ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)";
                $token_stmt = $conn->prepare($token_query);
                $token_stmt->bind_param("iss", $user['user_id'], $token, $expires);
                $token_stmt->execute();

                // Set secure cookies
                setcookie("remember_token", $token, time() + (86400 * 30), "/", "", true, true);
                setcookie("user_email", $email, time() + (86400 * 30), "/", "", true, true);
            }

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password!";
        }
    } else {
        $_SESSION['error'] = "Email not found!";
    }

    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Your existing tailwind config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-glow': 'pulse-glow 3s ease-in-out infinite',
                        'gradient': 'gradient 8s linear infinite',
                        'shimmer': 'shimmer 3s ease-in-out infinite',
                        'fadeIn': 'fadeIn 0.8s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px) rotate(0deg)'
                            },
                            '33%': {
                                transform: 'translateY(-10px) rotate(2deg)'
                            },
                            '66%': {
                                transform: 'translateY(5px) rotate(-1deg)'
                            }
                        },
                        'pulse-glow': {
                            '0%, 100%': {
                                boxShadow: '0 0 20px rgba(59, 130, 246, 0.3)'
                            },
                            '50%': {
                                boxShadow: '0 0 40px rgba(59, 130, 246, 0.6), 0 0 60px rgba(59, 130, 246, 0.3)'
                            }
                        },
                        gradient: {
                            '0%, 100%': {
                                backgroundPosition: '0% 50%'
                            },
                            '50%': {
                                backgroundPosition: '100% 50%'
                            }
                        },
                        shimmer: {
                            '0%, 100%': {
                                backgroundPosition: '0% 50%'
                            },
                            '50%': {
                                backgroundPosition: '100% 50%'
                            }
                        },
                        fadeIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        }
                    }
                }
            }
        }
    </script>
    <link rel="icon" type="image/x-icon" href="../assets/libpng.png">
    <title>BASC Library - Login</title>
</head>

<body class="min-h-screen bg-slate-950 text-white overflow-hidden selection:bg-blue-500/30">

    <!-- Your existing animated background and particles -->
    <div class="fixed inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 bg-[length:400%_400%] animate-gradient"></div>
    <div class="fixed inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(59,130,246,0.1),transparent_70%)]"></div>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-2 h-2 bg-blue-400/50 rounded-full animate-float"></div>
        <div class="absolute top-40 right-20 w-3 h-3 bg-purple-400/40 rounded-full animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-40 left-20 w-1 h-1 bg-cyan-400/60 rounded-full animate-float" style="animation-delay: 4s;"></div>
        <div class="absolute top-1/3 right-1/3 w-2 h-2 bg-emerald-400/40 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-20 right-10 w-2 h-2 bg-pink-400/50 rounded-full animate-float" style="animation-delay: 3s;"></div>
    </div>

    <!-- Debug link - REMOVE after fixing -->
    <div style="position: fixed; top: 10px; right: 10px; z-index: 9999;">
        <a href="?debug=users" style="background: #333; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; font-size: 12px;">
            🔍 Debug: View Database Users
        </a>
    </div>

    <!-- Header -->
    <header class="relative z-50 flex items-center justify-between p-6">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center text-xl font-bold">
                📚
            </div>
            <h1 class="text-2xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                BASC Library
            </h1>
        </div>
        <a href="../index.html" class="px-6 py-2 backdrop-blur-sm bg-white/10 border border-white/20 rounded-full hover:bg-white/20 transition-all duration-300 text-sm font-medium">
            Back to Home
        </a>
    </header>

    <!-- Main Login Container -->
    <div class="relative z-10 flex justify-center items-center min-h-screen px-6 py-20">
        <div class="w-full max-w-md animate-fadeIn">

            <!-- Login Card -->
            <div class="relative group">
                <div class="absolute -inset-4 bg-gradient-to-r from-blue-500/20 via-purple-500/20 to-cyan-500/20 rounded-3xl blur-xl opacity-70 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-3xl p-8 shadow-2xl">

                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl text-2xl mb-4 animate-pulse-glow">
                            🔐
                        </div>
                        <h2 class="text-3xl font-black bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                            Welcome Back
                        </h2>
                        <p class="text-slate-400 mt-2">Sign in to access your library account</p>
                    </div>

                    <!-- Success Message -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-2xl backdrop-blur-sm">
                            <div class="flex items-center space-x-3">
                                <div class="text-green-400 text-xl">✅</div>
                                <div class="text-green-300 font-medium"><?php echo $_SESSION['success'];
                                                                        unset($_SESSION['success']); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Error Message Display -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-2xl backdrop-blur-sm">
                            <div class="flex items-center space-x-3">
                                <div class="text-red-400 text-xl">⚠️</div>
                                <div class="text-red-300 font-medium"><?php echo $_SESSION['error'];
                                                                        unset($_SESSION['error']); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form action="login.php" method="POST" class="space-y-6">

                        <!-- Email Field -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-300">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input type="email" name="email" required
                                    class="w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 text-white placeholder-slate-400 backdrop-blur-sm transition-all duration-300"
                                    placeholder="Enter your email"
                                    value="<?php echo isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : ''; ?>">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-300">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input type="password" name="password" required id="passwordField"
                                    class="w-full pl-12 pr-12 py-4 bg-white/5 border border-white/10 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 text-white placeholder-slate-400 backdrop-blur-sm transition-all duration-300"
                                    placeholder="Enter your password">
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <svg id="eyeIcon" class="w-5 h-5 text-slate-400 hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="remember" id="remember"
                                    class="w-5 h-5 rounded border-2 border-white/20 bg-white/5 checked:bg-blue-500 checked:border-blue-500 focus:ring-2 focus:ring-blue-500/50 transition-all duration-300"
                                    <?php echo isset($_COOKIE['user_email']) ? 'checked' : ''; ?>>
                                <span class="text-sm text-slate-300 font-medium">Remember me</span>
                            </label>
                            <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors font-medium">
                                Forgot Password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="group w-full py-4 bg-gradient-to-r from-blue-500 via-purple-500 to-cyan-500 bg-[length:200%_200%] animate-gradient font-bold text-lg rounded-2xl shadow-2xl hover:shadow-blue-500/25 transition-all duration-300 transform hover:scale-105 hover:-translate-y-1">
                            <span class="flex items-center justify-center space-x-2">
                                <span>Sign In</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </button>

                        <!-- Rest of your form (social login, signup link, etc.) -->
                        <div class="text-center pt-6 border-t border-white/10">
                            <p class="text-slate-400">
                                Don't have an account?
                                <a href="register.php" class="text-blue-400 hover:text-blue-300 transition-colors font-semibold ml-1">
                                    Sign up here
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Your existing JavaScript -->
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('passwordField');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordField.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
    </script>

    <style>
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</body>

</html>