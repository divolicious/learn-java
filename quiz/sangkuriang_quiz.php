<?php
session_start();
include '../koneksi/koneksi.php';

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['username']);
if (!$is_logged_in) {
    header("Location: ../users/beginner_stories.php");
    exit();
}

$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

// Array pertanyaan quiz
$questions = [
    [
        'question' => 'Siapakah nama ibu Sangkuriang?',
        'options' => ['Dayang Sumbi', 'Dewi Sri', 'Nyi Roro Kidul', 'Dewi Nawangwulan'],
        'correct_answer' => 'Dayang Sumbi'
    ],
    [
        'question' => 'Apa yang terjadi dengan anjing kesayangan Sangkuriang, Tumang?',
        'options' => [
            'Dia hilang di hutan',
            'Dia dibunuh oleh Sangkuriang',
            'Dia mati karena sakit',
            'Dia berubah menjadi manusia'
        ],
        'correct_answer' => 'Dia dibunuh oleh Sangkuriang'
    ],
    [
        'question' => 'Apa syarat yang diberikan Dayang Sumbi kepada Sangkuriang untuk dapat menikahinya?',
        'options' => [
            'Membangun istana dalam semalam',
            'Membuat danau dan perahu dalam semalam',
            'Mengalahkan 100 prajurit',
            'Mendaki gunung tertinggi'
        ],
        'correct_answer' => 'Membuat danau dan perahu dalam semalam'
    ],
    [
        'question' => 'Bagaimana Dayang Sumbi menggagalkan pembangunan danau dan perahu?',
        'options' => [
            'Dia meminta bantuan dewa',
            'Dia menyuruh penduduk desa membantu',
            'Dia membuat fajar palsu dengan membakar kain merah',
            'Dia menggunakan sihir'
        ],
        'correct_answer' => 'Dia membuat fajar palsu dengan membakar kain merah'
    ],
    [
        'question' => 'Apa yang terjadi dengan perahu yang ditendang Sangkuriang karena marah?',
        'options' => [
            'Perahu hancur berkeping-keping',
            'Perahu berubah menjadi Gunung Tangkuban Perahu',
            'Perahu tenggelam ke dalam danau',
            'Perahu terbang ke langit'
        ],
        'correct_answer' => 'Perahu berubah menjadi Gunung Tangkuban Perahu'
    ]
];

$score = 0;
$message = '';
$show_results = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['answers'])) {
        $user_answers = $_POST['answers'];
        foreach ($questions as $index => $question) {
            if (isset($user_answers[$index]) && $user_answers[$index] === $question['correct_answer']) {
                $score++;
            }
        }
        $percentage = ($score / count($questions)) * 100;
        $show_results = true;
        
        if ($percentage >= 80) {
            $message = "Selamat! Anda mendapat nilai $percentage%. Pemahaman Anda tentang cerita Sangkuriang sangat baik!";
        } elseif ($percentage >= 60) {
            $message = "Bagus! Anda mendapat nilai $percentage%. Anda cukup memahami cerita Sangkuriang.";
        } else {
            $message = "Anda mendapat nilai $percentage%. Mungkin Anda perlu membaca kembali cerita Sangkuriang.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Sangkuriang - Learn Java by Stories</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <style>
        .quiz-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .quiz-title {
            text-align: center;
            color: #2E7D32;
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 2rem;
        }

        .question {
            background: #f0f7f0;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .question h3 {
            color: #2E7D32;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }

        .question p {
            color: #000000;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .option {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .option:hover {
            border-color: #2E7D32;
            background: #f0f7f0;
        }

        .option input[type="radio"] {
            width: 20px;
            height: 20px;
        }

        .option label {
            flex: 1;
            cursor: pointer;
            color: #000000;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 1rem;
            background: #2E7D32;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 2rem;
        }

        .submit-btn:hover {
            background: #1B5E20;
        }

        .result-message {
            text-align: center;
            padding: 2rem;
            background: #f0f7f0;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .result-message.success {
            background: #c8e6c9;
            color: #2E7D32;
        }

        .result-message.warning {
            background: #fff3e0;
            color: #f57c00;
        }

        .back-btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 1rem;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: #357abd;
        }

        .back-button {
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .back-button:hover {
            color: #1B5E20;
            transform: translateX(-5px);
        }

        /* Navbar styles from style.css */
        body {
            margin: 0;
            font-family: "Inter", Arial, sans-serif;
            background: linear-gradient(135deg, #7ab6e2 0%, #a0d3f9 100%);
            color: #fff;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #232d3e;
            padding: 16px 40px;
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .logo {
            font-family: "Playfair Display", serif;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 2px;
            color: #fff;
            margin-right: 32px;
        }

        .nav-menu {
            display: flex;
            gap: 28px;
        }

        .nav-menu a {
            text-align: center;
            margin-right: 10px;
            color: #fff;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 600;
            transition: color 0.2s;
        }

        .nav-menu a:hover {
            color: #4ea1ff;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .toggle {
            background: #fff;
            color: #232d3e;
            border-radius: 30px;
            padding: 8px 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }

        .toggle .moon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #232d3e;
            display: inline-block;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="../index.php" class="logo">Learn Java by Stories</a>
            <div class="nav-menu">
                <a href="../users/beginner_stories.php" class="menu-link">BEGINNER</a>
                <a href="../users/intermediate_stories.php" class="menu-link">INTERMEDIATE</a>
                <a href="#" class="menu-link">UPGRADE</a>
                <a href="../practice.php" class="menu-link">PRACTICE</a>
            </div>
        </div>
        <div class="navbar-right">
            <?php if ($is_logged_in): ?>
            <div class="user-icon-tooltip" style="margin-right: 15px;">
                <a href="../dashboard_user.php" style="color: inherit; text-decoration: none;">
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
                <span class="tooltip-text">Login</span>
            </div>
            <?php endif; ?>
            <button class="toggle"><span class="moon"></span> Light</button>
        </div>
    </nav>

    <!-- Tombol Kembali -->
    <div style="max-width: 800px; margin: 1rem auto; padding: 0 2rem;">
        <a href="../users/beginner_stories.php" class="back-button" style="display: inline-flex; align-items: center; text-decoration: none; color: #2E7D32; font-weight: 600;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Kembali ke Cerita
        </a>
    </div>

    <div class="quiz-container">
        <h1 class="quiz-title">Quiz Cerita Sangkuriang</h1>
        
        <?php if ($show_results): ?>
            <div class="result-message <?php echo $score >= 3 ? 'success' : 'warning'; ?>">
                <h2>Hasil Quiz Anda</h2>
                <p><?php echo $message; ?></p>
                <p>Skor: <?php echo $score; ?> dari <?php echo count($questions); ?></p>
                <a href="../users/beginner_stories.php" class="back-btn">Kembali ke Cerita</a>
            </div>
        <?php else: ?>
            <form method="POST" action="">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="question">
                        <h3>Pertanyaan <?php echo $index + 1; ?>:</h3>
                        <p><?php echo $question['question']; ?></p>
                        <div class="options">
                            <?php foreach ($question['options'] as $option): ?>
                                <div class="option">
                                    <input type="radio" 
                                        name="answers[<?php echo $index; ?>]" 
                                        id="q<?php echo $index; ?>_<?php echo $option; ?>"
                                        value="<?php echo $option; ?>" 
                                        required>
                                    <label for="q<?php echo $index; ?>_<?php echo $option; ?>"><?php echo $option; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="submit-btn">Submit Jawaban</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Tambahkan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- User Menu Modal -->
    <?php if ($is_logged_in): ?>
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
                <a href="../dashboard_user.php" class="menu-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="../users/profile.php" class="menu-item"><i class="fas fa-user-edit"></i> Edit Profile</a>
                <a href="../users/add_story.php" class="menu-item"><i class="fas fa-plus"></i> Add Story</a>
                <a href="../users/my_stories.php" class="menu-item"><i class="fas fa-book"></i> My Stories</a>
                <a href="../users/progress.php" class="menu-item"><i class="fas fa-chart-line"></i> Progress</a>
                <a href="../logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Modal Login -->
    <div class="modal" id="modalLogin">
        <div class="modal-content">
            <span class="close" id="closeLogin" onclick="closeAllModals()">&times;</span>
            <h2>Login</h2>
            <form action="../konfirmasi.php" method="post">
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
            <form action="../register.php" method="post">
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
        // Function to close all modals
        function closeAllModals() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.style.display = 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Click outside modal handler
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

            // Add click handler for login button
            document.getElementById('openLogin').onclick = openLoginModal;
            <?php endif; ?>
        });
    </script>
</body>
</html>
