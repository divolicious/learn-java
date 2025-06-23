<?php
session_start();
include 'koneksi/koneksi.php';

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['id_user']);

// Ambil data user jika sudah login
$user = null;
if ($is_logged_in) {
    $username = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice - Learn Java by Stories</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .practice-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 20px;
        }

        .practice-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .practice-title {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }

        .practice-desc {
            color: #666;
            font-size: 1.1rem;
            max-width: 800px;
            margin: 0 auto;
            font-family: 'Playfair Display', sans-serif;
        }

        .practice-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .practice-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            position: relative;
        }

        .practice-card:hover {
            transform: translateY(-5px);
        }

        .practice-card-content {
            padding: 20px;
        }

        .practice-card-title {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 10px;
        }

        .practice-card-desc {
            color: #666;
            margin-bottom: 20px;
        }

        .start-btn {
            display: inline-block;
            background: #4a90e2;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .start-btn:hover {
            background: #357abd;
        }

        .practice-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: white;
        }

        .practice-badge.free {
            background: #4CAF50;
        }

        .practice-badge.premium {
            background: #FFC107;
        }

        .practice-preview {
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .practice-preview-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .practice-preview-content {
            color: #666;
            font-size: 0.9rem;
        }

        .login-prompt {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="index.php" class="logo">Learn Java by Stories</a>
            <div class="nav-menu">
                <a href="users/beginner_stories.php" class="menu-link">BEGINNER</a>
                <a href="users/intermediate_stories.php" class="menu-link">INTERMEDIATE</a>
                <a href="practice.php" class="menu-link">PRACTICE</a>
            </div>
        </div>
        <div class="navbar-right">
            <?php if ($is_logged_in): ?>
                <div class="user-icon-tooltip" style="margin-right: 15px;">
                    <a href="users/dashboard_user.php" style="color: inherit; text-decoration: none;">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="tooltip-text">Dashboard</span>
                    </a>
                </div>
                <div class="user-icon-tooltip">
                    <i class="fas fa-user" id="openUserMenu"></i>
                    <span class="tooltip-text"><?php echo $user['nama']; ?></span>
                </div>
            <?php else: ?>
                <div class="user-icon-tooltip">
                    <i class="fas fa-user" id="openLogin"></i>
                </div>
            <?php endif; ?>
            <button class="toggle"><span class="moon"></span> Light</button>
        </div>
    </nav>

    <div class="practice-container">
        <div class="practice-header">
            <h1 class="practice-title">Practice Your Javanese</h1>
            <p class="practice-desc"><i>Improve your Javanese language skills with 
                our interactive exercises and quizzes.</i></p>
        </div>

        <div class="practice-grid">
            <!-- Vocabulary Quiz - Free -->
            <div class="practice-card">
                <span class="practice-badge free">Free</span>
                <div class="practice-card-content">
                    <h2 class="practice-card-title">Vocabulary Quiz</h2>
                    <p class="practice-card-desc">Test your knowledge of basic Javanese words and their meanings.</p>
                    
                    <div class="practice-preview">
                        <div class="practice-preview-title">Preview Question:</div>
                        <div class="practice-preview-content">
                            What is the Javanese word for "Thank you"?<br>
                            a) Matur nuwun<br>
                            b) Sugeng enjing<br>
                            c) Mangga<br>
                            d) Sampun
                        </div>
                    </div>

                    <a href="vocabulary.php" class="start-btn">Try Quiz</a>
                </div>
            </div>

            <!-- Grammar Exercise - Premium -->
            <div class="practice-card">
                <span class="practice-badge premium">Intermediate</span>
                <div class="practice-card-content">
                    <h2 class="practice-card-title">Grammar Exercises</h2>
                    <p class="practice-card-desc">Practice Javanese grammar with interactive exercises.</p>
                    
                    <div class="practice-preview">
                        <div class="practice-preview-title">Example Exercise:</div>
                        <div class="practice-preview-content">
                            Complete the sentence:<br>
                            "Ibu _____ tindak dhateng peken"<br>
                            (Mother ___ going to the market)
                        </div>
                    </div>

                    <?php if ($is_logged_in): ?>
                    <div class="login-prompt" style="background: #FFF3CD; color: #856404; border: 1px solid #FFEEBA;">
                        Coming Soon! This feature is under development.
                    </div>
                    <?php else: ?>
                    <div class="login-prompt">Login to access full grammar exercises</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reading Practice - Premium -->
            <div class="practice-card">
                <span class="practice-badge premium">Intermediate</span>
                <div class="practice-card-content">
                    <h2 class="practice-card-title">Reading Comprehension</h2>
                    <p class="practice-card-desc">Read short stories and answer questions to test your understanding.</p>
                    
                    <div class="practice-preview">
                        <div class="practice-preview-title">Sample Story:</div>
                        <div class="practice-preview-content">
                            "Ing sawijining dina, ana bocah sing seneng maca buku..."<br>
                            (One day, there was a child who loved reading books...)
                        </div>
                    </div>

                    <?php if ($is_logged_in): ?>
                    <div class="login-prompt" style="background: #FFF3CD; color: #856404; border: 1px solid #FFEEBA;">
                        Coming Soon! This feature is under development.
                    </div>
                    <?php else: ?>
                    <div class="login-prompt">Login to access full reading exercises</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php if ($is_logged_in): ?>
    <!-- User Menu Modal -->
    <div class="modal" id="userMenuModal">
        <div class="modal-content">
            <span class="close" onclick="closeAllModals()">&times;</span>
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h3><?php echo $user['nama']; ?></h3>
                <p class="user-email"><?php echo $user['email']; ?></p>
                <p class="user-level">Level: <?php echo ucfirst($user['level']); ?></p>
            </div>

            <div class="user-menu">
                <a href="dashboard_user.php" class="menu-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="profile.php" class="menu-item"><i class="fas fa-user-edit"></i> Edit Profile</a>
                <a href="add_story.php" class="menu-item"><i class="fas fa-plus"></i> Add Story</a>
                <a href="my_stories.php" class="menu-item"><i class="fas fa-book"></i> My Stories</a>
                <a href="progress.php" class="menu-item"><i class="fas fa-chart-line"></i> Progress</a>
                <a href="logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Modal Login -->
    <div class="modal" id="modalLogin">
        <div class="modal-content">
            <span class="close" id="closeLogin" onclick="closeAllModals()">&times;</span>
            <h2>Login</h2>
            <form action="konfirmasi.php" method="post">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Username" name="username" required>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            <p class="modal-switch">Don't have an account? <a href="#" onclick="openRegisterModal(event)">Register</a></p>
        </div>
    </div>

    <!-- Modal Register -->
    <div class="modal" id="modalRegister">
        <div class="modal-content">
            <span class="close" id="closeRegister" onclick="closeAllModals()">&times;</span>
            <h2>Register</h2>
            <form action="register.php" method="post">
                <div class="input-group mb-3">
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group mb-3">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="login-btn">Register</button>
            </form>
            <p class="modal-switch">Already have an account? <a href="#" onclick="openLoginModal(event)">Login</a></p>
        </div>
    </div>

    <script>
        // Function to close all modals - make it global
        function closeAllModals() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.style.display = 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Click outside modal handler - for all modals
            window.onclick = function(event) {
                if (event.target.classList.contains('modal')) {
                    closeAllModals();
                }
            };

            <?php if ($is_logged_in): ?>
            // User Menu Modal
            const userMenuModal = document.getElementById('userMenuModal');
            const userMenuBtn = document.getElementById('openUserMenu');
            
            if (userMenuBtn && userMenuModal) {
                userMenuBtn.onclick = function(e) {
                    e.preventDefault();
                    userMenuModal.style.display = 'block';
                };
            }
            <?php else: ?>
            // Modal logic untuk user yang belum login
            const modalRegister = document.getElementById('modalRegister');
            const modalLogin = document.getElementById('modalLogin');
            
            // Function to open login modal
            window.openLoginModal = function(e) {
                if (e) e.preventDefault();
                closeAllModals();
                modalLogin.style.display = 'block';
            }

            // Function to open register modal
            window.openRegisterModal = function(e) {
                if (e) e.preventDefault();
                closeAllModals();
                modalRegister.style.display = 'block';
            }

            // Add click handlers for all login buttons
            document.getElementById('openLogin').onclick = openLoginModal;
            document.getElementById('openLoginBtn1').onclick = openLoginModal;
            document.getElementById('openLoginBtn2').onclick = openLoginModal;
            document.getElementById('openLoginBtn3').onclick = openLoginModal;
            <?php endif; ?>
        });
    </script>
</body>
</html>
