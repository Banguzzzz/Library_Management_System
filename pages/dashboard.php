<?php
session_start();
session_regenerate_id(true); // Security enhancement

if (!isset($_SESSION['user_id'])) {
    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/Library_Management_System/pages/Login.php");
    exit();
}

include '../config/db_connect.php';

$user_id = $_SESSION['user_id'];

// FIXED: Using user_id instead of id
$query = "SELECT username, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>alert('User not found. Please log in again.'); window.location.href='login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-glow': 'pulse-glow 3s ease-in-out infinite',
                        'gradient': 'gradient 8s linear infinite',
                        'shimmer': 'shimmer 3s ease-in-out infinite',
                        'bounce-slow': 'bounce 3s ease-in-out infinite',
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
                        }
                    }
                }
            }
        }
    </script>
    <title>BASC Library Dashboard</title>
</head>

<body class="bg-slate-950 text-white overflow-x-hidden selection:bg-blue-500/30">

    <!-- Animated Background -->
    <div class="fixed inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 bg-[length:400%_400%] animate-gradient"></div>
    <div class="fixed inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(59,130,246,0.1),transparent_70%)]"></div>

    <!-- Floating Particles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-2 h-2 bg-blue-400/50 rounded-full animate-float"></div>
        <div class="absolute top-40 right-20 w-3 h-3 bg-purple-400/40 rounded-full animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-40 left-20 w-1 h-1 bg-cyan-400/60 rounded-full animate-float" style="animation-delay: 4s;"></div>
        <div class="absolute top-1/3 right-1/3 w-2 h-2 bg-emerald-400/40 rounded-full animate-float" style="animation-delay: 1s;"></div>
    </div>

    <!-- Main Container -->
    <div class="flex min-h-screen relative z-10">

        <!-- Sidebar -->
        <div class="w-80 backdrop-blur-xl bg-slate-900/80 border-r border-white/10 shadow-2xl flex flex-col">

            <!-- Logo Section -->
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center text-xl font-bold shadow-lg animate-pulse-glow">
                        📚
                    </div>
                    <div>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                            Dashboard
                        </h1>
                        <p class="text-sm text-slate-400">Manage your library</p>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div class="p-6 border-b border-white/10">
                <div id="userProfile" class="group relative cursor-pointer">
                    <div class="absolute -inset-2 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-4 rounded-2xl hover:bg-white/10 transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-xl flex items-center justify-center text-xl font-bold">
                                👤
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-white"><?php echo htmlspecialchars($user['username']); ?></h4>
                                <p class="text-sm text-slate-400"><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                            <div class="text-yellow-400 animate-bounce-slow">⭐</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-6 space-y-2">
                <div class="mb-6">
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Collections</h3>
                    <ul class="space-y-2">
                        <li onclick="showPage('circulation')" class="nav-item group relative cursor-pointer">
                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 rounded-xl blur opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10 transition-all duration-300">
                                <div class="text-2xl">🔄</div>
                                <span class="font-medium">Circulation</span>
                            </div>
                        </li>
                        <li onclick="showPage('filipiniana')" class="nav-item group relative cursor-pointer">
                            <div class="absolute -inset-1 bg-gradient-to-r from-red-500/20 to-yellow-500/20 rounded-xl blur opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10 transition-all duration-300">
                                <div class="text-2xl">🇵🇭</div>
                                <span class="font-medium">Filipiniana</span>
                            </div>
                        </li>
                        <li onclick="showPage('it')" class="nav-item group relative cursor-pointer">
                            <div class="absolute -inset-1 bg-gradient-to-r from-green-500/20 to-blue-500/20 rounded-xl blur opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10 transition-all duration-300">
                                <div class="text-2xl">💻</div>
                                <span class="font-medium">Information Technology</span>
                            </div>
                        </li>
                        <li onclick="showPage('ied')" class="nav-item group relative cursor-pointer">
                            <div class="absolute -inset-1 bg-gradient-to-r from-purple-500/20 to-pink-500/20 rounded-xl blur opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10 transition-all duration-300">
                                <div class="text-2xl">📖</div>
                                <span class="font-medium">IED</span>
                            </div>
                        </li>
                        <li onclick="showPage('engineering')" class="nav-item group relative cursor-pointer">
                            <div class="absolute -inset-1 bg-gradient-to-r from-orange-500/20 to-red-500/20 rounded-xl blur opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10 transition-all duration-300">
                                <div class="text-2xl">⚙️</div>
                                <span class="font-medium">Engineering</span>
                            </div>
                        </li>
                        <li onclick="showPage('hm')" class="nav-item group relative cursor-pointer">
                            <div class="absolute -inset-1 bg-gradient-to-r from-yellow-500/20 to-orange-500/20 rounded-xl blur opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10 transition-all duration-300">
                                <div class="text-2xl">🍽️</div>
                                <span class="font-medium">Hospitality Management</span>
                            </div>
                        </li>
                        <li onclick="showPage('agriculture')" class="nav-item group relative cursor-pointer">
                            <div class="absolute -inset-1 bg-gradient-to-r from-green-500/20 to-emerald-500/20 rounded-xl blur opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10 transition-all duration-300">
                                <div class="text-2xl">🌱</div>
                                <span class="font-medium">Agriculture</span>
                            </div>
                        </li>
                        <li onclick="showPage('general-information')" class="nav-item group relative cursor-pointer">
                            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 rounded-xl blur opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10 transition-all duration-300">
                                <div class="text-2xl">ℹ️</div>
                                <span class="font-medium">General Information</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Actions</h3>
                    <ul class="space-y-2">
                        <li onclick="showPage('borrowed-books')" class="nav-item group relative cursor-pointer">
                            <div class="absolute -inset-1 bg-gradient-to-r from-cyan-500/20 to-blue-500/20 rounded-xl blur opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10 transition-all duration-300">
                                <div class="text-2xl">📚</div>
                                <span class="font-medium">Borrowed Books</span>
                            </div>
                        </li>
                        <li onclick="logout()" class="nav-item group relative cursor-pointer">
                            <div class="absolute -inset-1 bg-gradient-to-r from-red-500/20 to-pink-500/20 rounded-xl blur opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="relative flex items-center space-x-3 p-3 rounded-xl hover:bg-red-500/20 transition-all duration-300">
                                <div class="text-2xl">🚪</div>
                                <span class="font-medium">Log out</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">

            <!-- Header Bar -->
            <header class="backdrop-blur-xl bg-slate-900/50 border-b border-white/10 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                            Library Dashboard
                        </h1>
                        <p class="text-slate-400">Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center animate-pulse-glow">
                            🔔
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 p-6">
                <div id="content">
                    <!-- Welcome Section -->
                    <div class="welcome-container text-center py-20">
                        <div class="max-w-4xl mx-auto">
                            <div class="relative group mb-8">
                                <div class="absolute -inset-4 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-3xl blur-xl opacity-70 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative w-32 h-32 mx-auto bg-gradient-to-br from-slate-800/50 to-slate-700/50 backdrop-blur-sm rounded-3xl flex items-center justify-center text-6xl animate-float border border-white/10">
                                    📚
                                </div>
                            </div>

                            <h1 class="text-4xl md:text-6xl font-black mb-6 tracking-tight leading-tight">
                                <span class="bg-gradient-to-r from-white via-blue-200 to-purple-200 bg-clip-text text-transparent animate-shimmer bg-[length:200%_200%]">
                                    Welcome to the
                                </span>
                                <br>
                                <span class="bg-gradient-to-r from-blue-400 via-purple-400 to-cyan-400 bg-clip-text text-transparent animate-shimmer bg-[length:200%_200%]" style="animation-delay: 0.5s;">
                                    Library Dashboard
                                </span>
                            </h1>

                            <p class="text-xl text-slate-300 mb-8 max-w-2xl mx-auto">
                                Explore our extensive collection of books across different categories and manage your reading journey.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                                <div class="group relative">
                                    <div class="absolute -inset-2 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 rounded-3xl hover:bg-white/10 transition-all duration-500 transform hover:scale-105">
                                        <div class="text-4xl mb-4">📖</div>
                                        <h3 class="text-xl font-bold mb-2 text-white">Browse Collections</h3>
                                        <p class="text-slate-400">Discover books from various academic disciplines</p>
                                    </div>
                                </div>

                                <div class="group relative">
                                    <div class="absolute -inset-2 bg-gradient-to-r from-purple-500/20 to-pink-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 rounded-3xl hover:bg-white/10 transition-all duration-500 transform hover:scale-105">
                                        <div class="text-4xl mb-4">📚</div>
                                        <h3 class="text-xl font-bold mb-2 text-white">Manage Borrowed</h3>
                                        <p class="text-slate-400">Track and return your borrowed books</p>
                                    </div>
                                </div>

                                <div class="group relative">
                                    <div class="absolute -inset-2 bg-gradient-to-r from-cyan-500/20 to-blue-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-8 rounded-3xl hover:bg-white/10 transition-all duration-500 transform hover:scale-105">
                                        <div class="text-4xl mb-4">🔄</div>
                                        <h3 class="text-xl font-bold mb-2 text-white">Circulation</h3>
                                        <p class="text-slate-400">Access the most popular and circulated books</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editForm" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 opacity-0 invisible transition-all duration-300">
        <div class="relative max-w-md w-full mx-4">
            <div class="absolute -inset-4 bg-gradient-to-r from-blue-500/30 to-purple-500/30 rounded-3xl blur-2xl"></div>
            <div class="relative backdrop-blur-xl bg-slate-900/90 border border-white/20 rounded-3xl p-8 shadow-2xl">
                <h2 class="text-2xl font-bold mb-6 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent text-center">
                    Edit Profile
                </h2>

                <form action="../config/update_profile.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">New Username:</label>
                        <input type="text" name="new_username" value="<?php echo htmlspecialchars($user['username']); ?>" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Current Password:</label>
                        <input type="password" name="current_password" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">New Password:</label>
                        <input type="password" name="new_password" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Confirm New Password:</label>
                        <input type="password" name="confirm_password" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>

                    <div class="flex space-x-4 pt-4">
                        <button type="submit" name="update"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 via-purple-500 to-cyan-500 bg-[length:200%_200%] animate-gradient font-semibold rounded-xl shadow-2xl hover:shadow-blue-500/25 transition-all duration-300 transform hover:scale-105">
                            Update Profile
                        </button>
                        <button type="button" onclick="toggleEditForm()"
                            class="px-6 py-3 backdrop-blur-sm bg-white/10 border border-white/20 font-semibold rounded-xl hover:bg-white/20 transition-all duration-300">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Borrowed Books Section -->
    <section id="borrowed-books" class="hidden fixed inset-0 bg-slate-950/95 backdrop-blur-xl z-40 overflow-y-auto">
        <div class="min-h-screen p-6">
            <div class="max-w-6xl mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                        Borrowed Books
                    </h2>
                    <button onclick="showPage('home')" class="px-6 py-3 backdrop-blur-sm bg-white/10 border border-white/20 font-semibold rounded-xl hover:bg-white/20 transition-all duration-300">
                        Back to Dashboard
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $query = "SELECT * FROM borrowed_books WHERE user_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='book group relative' data-book-id='" . $row['id'] . "'>";
                            echo "<div class='absolute -inset-2 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500'></div>";
                            echo "<div class='relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 rounded-3xl hover:bg-white/10 transition-all duration-500'>";
                            echo "<div class='text-4xl mb-4 text-center'>📖</div>";
                            echo "<h3 class='text-xl font-bold mb-4 text-white text-center'>" . htmlspecialchars($row['book_title']) . "</h3>";
                            echo "<button class='return w-full px-4 py-3 bg-gradient-to-r from-red-500 to-pink-500 font-semibold rounded-xl shadow-lg hover:shadow-red-500/25 transition-all duration-300 transform hover:scale-105' onclick='returnBook(" . $row['id'] . ")'>Return Book</button>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='col-span-full text-center py-20'>";
                        echo "<div class='text-6xl mb-4'>📚</div>";
                        echo "<h3 class='text-2xl font-bold text-slate-400 mb-2'>No Borrowed Books</h3>";
                        echo "<p class='text-slate-500'>You haven't borrowed any books yet. Explore our collections to get started!</p>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Message Toast -->
    <div id="messageToast" class="fixed top-6 right-6 z-50 transform translate-x-full transition-transform duration-300">
        <div class="backdrop-blur-xl bg-slate-900/90 border border-white/20 rounded-2xl p-4 shadow-2xl">
            <div id="messageContent" class="flex items-center space-x-3">
                <div id="messageIcon" class="text-2xl"></div>
                <div id="messageText" class="font-medium"></div>
            </div>
        </div>
    </div>

    <script>
        // Global Variables
        let currentUserId = <?php echo $user_id; ?>;

        // Navigation Functions
        function showPage(page) {
            // Hide all sections
            document.getElementById('content').classList.add('hidden');
            document.getElementById('borrowed-books').classList.add('hidden');

            if (page === 'borrowed-books') {
                document.getElementById('borrowed-books').classList.remove('hidden');
            } else if (page === 'home') {
                document.getElementById('content').classList.remove('hidden');
                document.getElementById('borrowed-books').classList.add('hidden');
            } else {
                // Handle other pages (filipiniana, it, etc.)
                document.getElementById('content').classList.remove('hidden');
                // You can add specific content loading logic here for each section
                loadSectionContent(page);
            }
        }

        function loadSectionContent(section) {
            const content = document.getElementById('content');
            // This is where you would load specific content for each section
            // For now, we'll show a placeholder
            content.innerHTML = `
                <div class="text-center py-20">
                    <div class="max-w-2xl mx-auto">
                        <div class="text-6xl mb-6">📚</div>
                        <h1 class="text-4xl font-bold mb-4 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent capitalize">
                            ${section.replace('-', ' ')} Section
                        </h1>
                        <p class="text-xl text-slate-400 mb-8">
                            Explore our ${section.replace('-', ' ')} collection
                        </p>
                        <button onclick="showPage('home')" class="px-8 py-4 bg-gradient-to-r from-blue-500 via-purple-500 to-cyan-500 bg-[length:200%_200%] animate-gradient font-semibold rounded-2xl shadow-2xl hover:shadow-blue-500/25 transition-all duration-300 transform hover:scale-105">
                            Back to Dashboard
                        </button>
                    </div>
                </div>
            `;
        }

        // Profile Management
        function toggleEditForm() {
            const form = document.getElementById('editForm');
            const isVisible = !form.classList.contains('invisible');

            if (isVisible) {
                form.classList.add('opacity-0', 'invisible');
            } else {
                form.classList.remove('opacity-0', 'invisible');
            }
        }

        // Message System
        function showMessage(message, type = 'success') {
            const toast = document.getElementById('messageToast');
            const icon = document.getElementById('messageIcon');
            const text = document.getElementById('messageText');

            // Set message content
            text.textContent = message;

            // Set icon based on type
            if (type === 'success') {
                icon.textContent = '✅';
            } else if (type === 'error') {
                icon.textContent = '❌';
            } else {
                icon.textContent = 'ℹ️';
            }

            // Show toast
            toast.classList.remove('translate-x-full');

            // Hide after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 3000);
        }

        // Return Book Function
        function returnBook(bookId) {
            if (confirm('Are you sure you want to return this book?')) {
                fetch('../config/return_book.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'book_id=' + bookId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage('Book returned successfully!', 'success');
                            // Remove the book element from display
                            document.querySelector(`[data-book-id="${bookId}"]`).remove();
                        } else {
                            showMessage('Error returning book: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('Network error occurred', 'error');
                    });
            }
        }

        // Logout Function
        function logout() {
            if (confirm('Are you sure you want to log out?')) {
                window.location.href = '../config/logout.php';
            }
        }

        // Profile click handler
        document.getElementById('userProfile').addEventListener('click', function() {
            toggleEditForm();
        });
    </script>
    <script>
        // Add this script at the end of your HTML, just before the closing </body> tag

        // Book data - place this at the top of your script
        const booksData = {
            Circulation: [{
                    title: "Webster's New Collegiate Dictionary",
                    author: "Merriam-Webster",
                    img: "../assets/Circulation/websters_new_collegiate_dictionary.jpg",
                },
                {
                    title: "A Course Module for Teaching Literacy in the Elementary Grades through Literature",
                    author: "Ferdinand Bulusan, Marcelo Revibes Raqueño, Irene Blanco-Hamada, Greg Tabios Pawilen",
                    img: "../assets/Circulation/teaching_literacy_elementary.jpg",
                },
                {
                    title: "The World Book Encyclopedia (UV volume)",
                    author: "World Book",
                    img: "../assets/Circulation/world_book_encyclopedia_uv.jpg",
                },
                {
                    title: "Webster's New World Dictionary with Student Handbook",
                    author: "Webster",
                    img: "../assets/Circulation/websters_new_world_dictionary.jpg",
                },
                {
                    title: "World Book L-12",
                    author: "World Book",
                    img: "../assets/Circulation/world_book_l12.jpg",
                },
                {
                    title: "The World Book Encyclopedia (Research Guide Index)",
                    author: "World Book",
                    img: "../assets/Circulation/world_book_encyclopedia_uv.jpg",
                },
                {
                    title: "The New Dictionary of Cultural Literacy",
                    author: "E. D. Hirsch Jr., Joseph F. Kett, James Trefil",
                    img: "../assets/Circulation/new_dictionary_cultural_literacy.jpg",
                },
            ],
            Filipiniana: [{
                    title: "The Philippines Yearbook",
                    author: "Generations",
                    img: "../assets/Filipiniana/philippines_yearbook.jpg",
                },
                {
                    title: "State Names, Seals, Flags, and Symbols",
                    author: "Benjamin F. Shearer and Barbara S. Shearer",
                    img: "../assets/Filipiniana/state_names_seals_flags_symbols.jpg",
                },
                {
                    title: "Komunikasyon sa Akademikon Filipino (Dulog Modulayr)",
                    author: "Helen E. Golloso, Gina S. Luna, Hipolito S. Ruzol, Allan Roy M. Ungriano, Magdalena O. Jocson",
                    img: "../assets/Filipiniana/komunikasyon_sa_akademikon_filipino.jpg",
                },
                {
                    title: "The New Philippine Almanac",
                    author: "Unknown",
                    img: "../assets/Filipiniana/new_philippine_almanac.jpg",
                },
            ],
            IT: [{
                    title: "Introduction to Visual C++ 6.0 Programming",
                    author: "Copernicus P. Pepito",
                    img: "../assets/Information Tech/introduction_to_visual_c++6.0_programming.jpg",
                },
                {
                    title: "Introduction to Database using Microsoft Access",
                    author: "Dean Marmelo V. Abante",
                    img: "../assets/Information Tech/introduction_to_database_using_microsoft_access.jpg",
                },
                {
                    title: "Visual Basic",
                    author: "Unknown",
                    img: "../assets/Information Tech/visual_basic.jpg",
                },
                {
                    title: "Introduction to C Programming",
                    author: "Jake R. Pomperada, Kristine T. Soberano",
                    img: "../assets/Information Tech/introduction_to_c_programming.jpg",
                },
                {
                    title: "Fundamentals of Python Programming",
                    author: "Jake R. Pomperada, Rollyn M. Moises, Sunday Vince V. Latergo",
                    img: "../assets/Information Tech/fundamentals_of_python_programming.jpg",
                },
                {
                    title: "Concise Encyclopedia of Computer Science",
                    author: "Edwin D. Reilly",
                    img: "../assets/Information Tech/concise_encyclopedia_of_computer_science.jpg",
                },
                {
                    title: "Introduction to Go Programming",
                    author: "Jake Rodriguez Pomperada",
                    img: "../assets/Information Tech/introduction_to_go_programming.jpg",
                },
                {
                    title: "Web Application Programming",
                    author: "Dr. Marmelo V. Abante",
                    img: "../assets/Information Tech/web_application_programming.jpg",
                },
                {
                    title: "Web Programming using PHP & MySQL",
                    author: "Harley V. Lampawog, Jake R. Pomperada",
                    img: "../assets/Information Tech/web_programming_using_php&_mysql.jpg",
                },
                {
                    title: "Introduction to HTML 5",
                    author: "Copernicus P. Pepito",
                    img: "../assets/Information Tech/introduction_to_html_5.jpg",
                },
            ],
            IED: [{
                    title: "Measurement and Evaluation",
                    author: "Jose F. Calderon, Expectacion C. Gonzales",
                    img: "../assets/IED/measurement_and_evaluation.jpg",
                },
                {
                    title: "General Chemistry 2",
                    author: "Voltaire G. Organo, Dominic U. Villanueva",
                    img: "../assets/IED/general_chemistry_2.jpg",
                },
                {
                    title: "Assessment of Learning Outcomes (Cognitive Domain) Book I",
                    author: "Danilo S. Gutierrez, Ph.D., Ma. Corazon V. Tadeña, Ph.D., Nenita P. Macatulad, Ph.D.",
                    img: "../assets/IED/assessment_of_learning_outcomes(cognitive_domain)book_i.jpg",
                },
                {
                    title: "Facilitating Human Learning (2nd Edition)",
                    author: "Avelina M. Aquino, Ed.D.",
                    img: "../assets/IED/facilitating_human_learning_(2nd_edition).jpg",
                },
                {
                    title: "Action Research for Beginners in Classroom-based Contexts",
                    author: "Elmer B. de Leon, LPT, DEM",
                    img: "../assets/IED/action_research_for_beginners_in_classroom-based_contexts.jpg",
                },
            ],
            Engineering: [{
                    title: "Engineering Economics for Computerized Licensure Examination (2nd Edition)",
                    author: "Besavilla",
                    img: "../assets/Engineering/engineering_economics_for_computerized_licensure_examination_(2nd_edition).jpg",
                },
                {
                    title: "McGraw-Hill Encyclopedia of Science and Technology (Volume 4)",
                    author: "McGraw-Hill",
                    img: "../assets/Engineering/mcgraw-hill_encyclopedia_of_science_and_technology_(volume_4).jpg",
                },
                {
                    title: "Engineering Mathematics Volume 1 (2nd Edition)",
                    author: "Besavilla",
                    img: "../assets/Engineering/engineering_mathematics_volume_1_(2nd_edition.jpg",
                },
                {
                    title: "Solutions to Problems in Engineering Mechanics (SI Metric Edition)",
                    author: "Matias A. Arreola",
                    img: "../assets/Engineering/solutions_to_problems_in_engineering_mechanics_(si_metric_edition).jpg",
                },
                {
                    title: "Structural Steel Design",
                    author: "Besavilla",
                    img: "../assets/Engineering/structural_steel_design.jpg",
                },
            ],
            HM: [{
                    title: "Strategic Marketing (Ninth Edition)",
                    author: "McGraw-Hill International Edition",
                    img: "../assets/Hospitality Management/strategic_marketing_(ninth_edition).jpg",
                },
                {
                    title: "Travel Manual",
                    author: "Fellowship of Christians in Government, Inc.",
                    img: "../assets/Hospitality Management/travel_manual.jpg",
                },
                {
                    title: "Food, Water and Environmental Sanitation and Safety",
                    author: "Grace Portugal-Perdigon, Virginia Serradon-Claudio, Libia de Lima-Chavez, Adela Jamorabo-Ruiz",
                    img: "../assets/Hospitality Management/food_water_and_environmental_sanitation_and_safety.jpg",
                },
                {
                    title: "Housekeeping Management (Revised Edition 2010)",
                    author: "Amelia Samson Roldan, Amelia Malapitan Crespo",
                    img: "../assets/Hospitality Management/housekeeping_management_(revised_edition_2010).jpg",
                },
                {
                    title: "Experimental Cookery and Food Preservation (Second Edition)",
                    author: "Eva Nebril-Flores, Ph.D.",
                    img: "../assets/Hospitality Management/experimental_cookery_and_food_preservation_(second_edition).jpg",
                },
            ],
            Agriculture: [{
                    title: "Pest Management of Rice Farmers in Asia",
                    author: "K.L. Heong, M.M. Escalada",
                    img: "../assets/Agriculture/pest_management_of_rice_farmers_in_asia.jpg",
                },
                {
                    title: "Agribusiness Management Resource Materials Vol 1",
                    author: "Unknown",
                    img: "../assets/Agriculture/agribusiness_management_resource_materials_vol_1.jpg",
                },
                {
                    title: "Basic Agriculture",
                    author: "Bro. Manuel V. de Leon, FMS",
                    img: "../assets/Agriculture/basic_agriculture.jpg",
                },
                {
                    title: "Banana Production and Entrepreneurship Training Manual",
                    author: "Unknown",
                    img: "../assets/Agriculture/banana_production_and_entrepreneurship_training_manual.jpg",
                },
                {
                    title: "Farming Handbook",
                    author: "Andres H. Celestino, Marilyn S. San Pascual",
                    img: "../assets/Agriculture/farming_handbook.jpg",
                },
            ],
            General_Information: [{
                    title: "Alice in Wonderland",
                    author: "Lewis Carroll",
                    img: "../assets/General Information/alice_in_wonderland.jpg",
                    library_id: "BL13410",
                },
                {
                    title: "In Search of a Sandhill Crane",
                    author: "Keith Robertson",
                    illustrator: "Richard Cuffari",
                    img: "../assets/General Information/in_search_of_a_sandhill_crane.jpg",
                },
                {
                    title: "Sky Key",
                    author: "James Frey and Nils Johnson-Shelton",
                    img: "../assets/General Information/sky_key.jpg",
                    note: "New York Times Bestselling Authors",
                },
                {
                    title: "The High King",
                    author: "Lloyd Alexander",
                    img: "../assets/General Information/the_high_king.jpg",
                    library_id: "BL00233",
                },
                {
                    title: "The Little Shepherd of Kingdom Come",
                    author: "John Fox Jr.",
                    img: "../assets/General Information/the_little_shepherd_of_kingdom_come.jpg",
                    library_id: "BL10858",
                },
            ],
        };

        // Main navigation functionality
        document.addEventListener("DOMContentLoaded", function() {
            // Navigation click handlers
            document.querySelectorAll(".nav-item").forEach(item => {
                item.addEventListener("click", function() {
                    const category = this.querySelector("span").textContent.trim();
                    showBooksPage(category);
                });
            });

            function showBooksPage(category) {
                const content = document.getElementById("content");
                content.innerHTML = generateBooksHTML(category);
                attachBookEvents();
            }

            function generateBooksHTML(category) {
                // Category mapping to match your data structure
                const categoryMap = {
                    "Circulation": "Circulation",
                    "Filipiniana": "Filipiniana",
                    "Information Technology": "IT",
                    "IED": "IED",
                    "Engineering": "Engineering",
                    "Hospitality Management": "HM",
                    "Agriculture": "Agriculture",
                    "General Information": "General_Information"
                };

                const mappedCategory = categoryMap[category] || category;
                const books = booksData[mappedCategory] || [];

                if (books.length === 0) {
                    return `
                <div class="text-center py-20">
                    <div class="text-6xl mb-6">📚</div>
                    <h1 class="text-4xl font-bold mb-4 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                        ${category} Section
                    </h1>
                    <p class="text-xl text-slate-400 mb-8">
                        No books available for this category yet.
                    </p>
                </div>
            `;
                }

                let booksHTML = `
            <div class="py-8">
                <h1 class="text-3xl font-bold mb-8 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent text-center">
                    ${category} Books
                </h1>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        `;

                books.forEach(book => {
                    booksHTML += `
                <div class="book group relative">
                    <div class="absolute -inset-2 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 p-6 rounded-3xl hover:bg-white/10 transition-all duration-500 h-full flex flex-col">
                        <div class="flex-1">
                            <img src="${book.img}" alt="${book.title}" class="w-full h-48 object-cover rounded-xl mb-4">
                            <h3 class="text-xl font-bold mb-2 text-white">${book.title}</h3>
                            <p class="text-slate-400 mb-4">${book.author}</p>
                            ${book.library_id ? `<p class="text-sm text-slate-500">ID: ${book.library_id}</p>` : ''}
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button class="borrow flex-1 px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl hover:shadow-blue-500/25 transition-all duration-300">
                                Borrow
                            </button>
                            <button class="return flex-1 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl hover:shadow-red-500/25 transition-all duration-300" style="display:none;">
                                Return
                            </button>
                        </div>
                    </div>
                </div>
            `;
                });

                booksHTML += `</div></div>`;
                return booksHTML;
            }

            function attachBookEvents() {
                document.querySelectorAll(".borrow").forEach(button => {
                    button.addEventListener("click", function() {
                        const bookCard = this.closest('.book');
                        const bookTitle = bookCard.querySelector('h3').textContent;

                        // Show loading state
                        const originalHTML = this.innerHTML;
                        this.innerHTML = '<span class="animate-pulse">Processing...</span>';
                        this.disabled = true;

                        // Simulate API call
                        setTimeout(() => {
                            this.style.display = "none";
                            this.nextElementSibling.style.display = "block";
                            this.innerHTML = originalHTML;
                            this.disabled = false;
                            showMessage(`"${bookTitle}" borrowed successfully!`, 'success');
                        }, 1000);
                    });
                });

                // Update your return button handler:

                document.querySelectorAll(".return").forEach(button => {
                    button.addEventListener("click", async function() {
                        const bookCard = this.closest('.book');
                        const bookTitle = bookCard.querySelector('h3').textContent;

                        if (!confirm(`Are you sure you want to return "${bookTitle}"?`)) {
                            return;
                        }

                        // Show loading state
                        const originalHTML = this.innerHTML;
                        this.innerHTML = '<span class="animate-pulse">Processing...</span>';
                        this.disabled = true;

                        try {
                            const response = await fetch('../config/return_book.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    title: bookTitle,
                                    user_id: <?php echo $user_id; ?>
                                })
                            });

                            const data = await response.json();

                            if (data.success) {
                                bookCard.remove();
                                showMessage(`"${bookTitle}" returned successfully!`, 'success');
                            } else {
                                showMessage(data.message || 'Error returning book', 'error');
                                this.innerHTML = originalHTML;
                                this.disabled = false;
                            }
                        } catch (error) {
                            showMessage('Network error occurred', 'error');
                            this.innerHTML = originalHTML;
                            this.disabled = false;
                        }
                    });
                });
            }

            async function borrowBook(bookElement) {
    const bookId = bookElement.dataset.bookId;
    const borrowBtn = bookElement.querySelector('.borrow');
    
    if (!bookId) {
        showSweetAlert('Error: Book ID not found', 'error');
        return;
    }

    // Show loading state
    const originalText = borrowBtn.innerHTML;
    borrowBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing';
    borrowBtn.disabled = true;

    try {
        const response = await fetch('../config/borrow_book.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                book_id: bookId,
                user_id: <?php echo $user_id; ?>
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            borrowBtn.style.display = 'none';
            bookElement.querySelector('.return').style.display = 'inline-block';
            showSweetAlert('Book borrowed successfully!', 'success');
        } else {
            showSweetAlert(data.message || 'Error borrowing book', 'error');
        }
    } catch (error) {
        showSweetAlert('Network error occurred', 'error');
        console.error('Error:', error);
    } finally {
        borrowBtn.innerHTML = originalText;
        borrowBtn.disabled = false;
    }
}

async function returnBook(bookElement) {
    const bookId = bookElement.dataset.bookId;
    const returnBtn = bookElement.querySelector('.return');
    
    if (!confirm('Are you sure you want to return this book?')) {
        return;
    }

    // Show loading state
    const originalText = returnBtn.innerHTML;
    returnBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing';
    returnBtn.disabled = true;

    try {
        const response = await fetch('../config/return_book.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                book_id: bookId,
                user_id: <?php echo $user_id; ?>
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            bookElement.remove();
            showSweetAlert('Book returned successfully!', 'success');
        } else {
            showSweetAlert(data.message || 'Error returning book', 'error');
        }
    } catch (error) {
        showSweetAlert('Network error occurred', 'error');
        console.error('Error:', error);
    } finally {
        returnBtn.innerHTML = originalText;
        returnBtn.disabled = false;
    }
}

            function showMessage(message, type) {
                const toast = document.getElementById('messageToast');
                const icon = document.getElementById('messageIcon');
                const text = document.getElementById('messageText');

                // Set message content
                text.textContent = message;

                // Set icon based on type
                if (type === 'success') {
                    icon.textContent = '✅';
                    toast.style.backgroundColor = 'rgba(76, 175, 80, 0.9)';
                } else {
                    icon.textContent = '❌';
                    toast.style.backgroundColor = 'rgba(244, 67, 54, 0.9)';
                }

                // Show toast
                toast.classList.remove('translate-x-full');

                // Hide after 3 seconds
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                }, 3000);
            }

            // Update your showPage function
            function showPage(page) {
                // Hide all sections
                document.getElementById('content').classList.add('hidden');
                document.getElementById('borrowed-books').classList.add('hidden');

                if (page === 'borrowed-books') {
                    document.getElementById('borrowed-books').classList.remove('hidden');
                } else if (page === 'home') {
                    document.getElementById('content').classList.remove('hidden');
                    document.getElementById('borrowed-books').classList.add('hidden');
                } else {
                    // Handle other pages (filipiniana, it, etc.)
                    document.getElementById('content').classList.remove('hidden');
                    showBooksPage(page);
                }
            }

            // Make sure to add all other existing functions (toggleEditForm, returnBook, logout, etc.)
        });
    </script>
    <script>
        async function borrowBook(bookElement) {
    const bookId = bookElement.dataset.bookId;
    const borrowBtn = bookElement.querySelector('.borrow');
    
    if (!bookId) {
        showSweetAlert('Error: Book ID not found', 'error');
        return;
    }

    // Show loading state
    const originalText = borrowBtn.innerHTML;
    borrowBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing';
    borrowBtn.disabled = true;

    try {
        const response = await fetch('../config/borrow_book.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                book_id: bookId,
                user_id: <?php echo $user_id; ?>
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            borrowBtn.style.display = 'none';
            bookElement.querySelector('.return').style.display = 'inline-block';
            showSweetAlert('Book borrowed successfully!', 'success');
        } else {
            showSweetAlert(data.message || 'Error borrowing book', 'error');
        }
    } catch (error) {
        showSweetAlert('Network error occurred', 'error');
        console.error('Error:', error);
    } finally {
        borrowBtn.innerHTML = originalText;
        borrowBtn.disabled = false;
    }
}

async function returnBook(bookElement) {
    const bookId = bookElement.dataset.bookId;
    const returnBtn = bookElement.querySelector('.return');
    
    if (!confirm('Are you sure you want to return this book?')) {
        return;
    }

    // Show loading state
    const originalText = returnBtn.innerHTML;
    returnBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing';
    returnBtn.disabled = true;

    try {
        const response = await fetch('../config/return_book.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                book_id: bookId,
                user_id: <?php echo $user_id; ?>
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            bookElement.remove();
            showSweetAlert('Book returned successfully!', 'success');
        } else {
            showSweetAlert(data.message || 'Error returning book', 'error');
        }
    } catch (error) {
        showSweetAlert('Network error occurred', 'error');
        console.error('Error:', error);
    } finally {
        returnBtn.innerHTML = originalText;
        returnBtn.disabled = false;
    }
}
    </script>
</body>

</html>