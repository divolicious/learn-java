<?php
session_start();
include '../koneksi/koneksi.php';
include '../koneksi/session.php';

// Cek session
checkSession();

// Ambil data user
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

$user_id = $_SESSION['id_user'];

// Ambil data quiz history
$query = "SELECT * FROM vocabulary_quiz_history WHERE user_id = $user_id ORDER BY quiz_date DESC";
$result = mysqli_query($koneksi, $query);

// Hitung statistik
$total_quiz = mysqli_num_rows($result);
$total_score = 0;
$highest_score = 0;
$total_time = 0;

if ($total_quiz > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $score_percentage = ($row['score'] / $row['total_questions']) * 100;
        $total_score += $score_percentage;
        $highest_score = max($highest_score, $score_percentage);
        $total_time += $row['time_taken'];
    }
    $average_score = $total_score / $total_quiz;
    $average_time = $total_time / $total_quiz;
    
    // Reset pointer untuk penggunaan result set lagi
    mysqli_data_seek($result, 0);
} else {
    $average_score = 0;
    $average_time = 0;
}

function formatTime($seconds) {
    $minutes = floor($seconds / 60);
    $seconds = $seconds % 60;
    return sprintf("%02d:%02d", $minutes, $seconds);
}

function getScoreBadgeColor($score) {
    if ($score >= 90) return 'gold';
    if ($score >= 75) return 'silver';
    if ($score >= 60) return 'bronze';
    return 'gray';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice Progress - Learn Java by Stories</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .progress-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            color: #4a90e2;
            margin: 0.5rem 0;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .history-table th,
        .history-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .history-table th {
            background: #f5f5f5;
            font-weight: 600;
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            color: white;
            font-weight: 600;
        }

        .badge.gold { background: #FFD700; }
        .badge.silver { background: #C0C0C0; }
        .badge.bronze { background: #CD7F32; }
        .badge.gray { background: #808080; }

        .back-btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .back-btn:hover {
            background: #357abd;
        }

        .progress-bar-container {
            width: 100%;
            height: 20px;
            background: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 0.5rem 0;
        }

        .progress-bar {
            height: 100%;
            background: #4CAF50;
            transition: width 0.3s ease;
        }

        /* Dark mode styles */
        body.dark-mode .stat-card,
        body.dark-mode .history-table {
            background: #333;
            color: #fff;
        }

        body.dark-mode .history-table th {
            background: #444;
        }

        body.dark-mode .history-table td {
            border-color: #444;
        }

        body.dark-mode .progress-bar-container {
            background: #444;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="../index.php" class="logo">Learn Java by Stories</a>
            <div class="nav-menu">
                <a href="beginner_stories.php" class="menu-link">BEGINNER</a>
                <a href="intermediate_stories.php" class="menu-link">INTERMEDIATE</a>
                <a href="#" class="menu-link">UPGRADE</a>
                <a href="../practice.php" class="menu-link">PRACTICE</a>
            </div>
        </div>
        <div class="navbar-right">
            <div class="user-icon-tooltip">
                <i class="fas fa-user" id="openUserMenu"></i>
                <span class="tooltip-text"><?php echo $user['nama']; ?></span>
            </div>
            <img src="../img/navbar_logo.png" alt="Logo" style="height:50px; margin-right:15px;">
            <button class="toggle"><span class="moon"></span> Light</button>
        </div>
    </nav>

    <!-- User Menu Modal -->
    <div class="modal" id="userMenuModal">
        <div class="modal-content">
            <span class="close" data-modal="userMenuModal">&times;</span>
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h3><?php echo $user['nama']; ?></h3>
                <p class="user-email"><?php echo $user['email']; ?></p>
                <p class="user-level">Level: <?php echo ucfirst($user['level']); ?></p>
            </div>

            <div class="user-menu">
                <a href="profile.php" class="menu-item"><i class="fas fa-user-edit"></i> Edit Profile</a>
                <a href="add_story.php" class="menu-item"><i class="fas fa-plus"></i> Add Story</a>
                <a href="my_stories.php" class="menu-item"><i class="fas fa-book"></i> My Stories</a>
                <a href="progress.php" class="menu-item"><i class="fas fa-chart-line"></i> Story Progress</a>
                <a href="progress_practice.php" class="menu-item"><i class="fas fa-tasks"></i> Practice Progress</a>
                <a href="logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    <div class="progress-container">
        <a href="../practice.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Practice</a>
        
        <h1>Progress Latihan Vocabulary</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Quiz</h3>
                <div class="stat-value"><?php echo $total_quiz; ?></div>
            </div>
            <div class="stat-card">
                <h3>Rata-rata Skor</h3>
                <div class="stat-value"><?php echo number_format($average_score, 1); ?>%</div>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: <?php echo $average_score; ?>%"></div>
                </div>
            </div>
            <div class="stat-card">
                <h3>Skor Tertinggi</h3>
                <div class="stat-value"><?php echo number_format($highest_score, 1); ?>%</div>
            </div>
            <div class="stat-card">
                <h3>Rata-rata Waktu</h3>
                <div class="stat-value"><?php echo formatTime($average_time); ?></div>
            </div>
        </div>

        <h2>Riwayat Quiz</h2>
        <?php if ($total_quiz > 0): ?>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Skor</th>
                        <th>Waktu</th>
                        <th>Badge</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php 
                        $score_percentage = ($row['score'] / $row['total_questions']) * 100;
                        $badge_color = getScoreBadgeColor($score_percentage);
                        ?>
                        <tr>
                            <td><?php echo date('d M Y H:i', strtotime($row['quiz_date'])); ?></td>
                            <td>
                                <?php echo $row['score']; ?>/<?php echo $row['total_questions']; ?>
                                (<?php echo number_format($score_percentage, 1); ?>%)
                            </td>
                            <td><?php echo formatTime($row['time_taken']); ?></td>
                            <td><span class="badge <?php echo $badge_color; ?>"><?php echo ucfirst($badge_color); ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Belum ada riwayat quiz. Mulai quiz vocabulary sekarang!</p>
        <?php endif; ?>
    </div>

    <script>
        // Light/Dark mode toggle
        const toggleBtn = document.querySelector('.toggle');
        const body = document.body;
        let isLight = true;

        function setToggle() {
            if (isLight) {
                toggleBtn.innerHTML = `<svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='#FDB813' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><circle cx='12' cy='12' r='5'/><path d='M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42'/></svg> Light`;
            } else {
                toggleBtn.innerHTML = `<svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='#fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z'/></svg> Dark`;
            }
        }

        setToggle();
        toggleBtn.addEventListener('click', function() {
            isLight = !isLight;
            if (isLight) {
                body.classList.remove('dark-mode');
            } else {
                body.classList.add('dark-mode');
            }
            setToggle();
        });

        // User Menu Modal
        const userMenuModal = document.getElementById('userMenuModal');
        document.getElementById('openUserMenu').onclick = function(e) {
            e.preventDefault();
            userMenuModal.style.display = 'block';
        };
        document.querySelectorAll('.close').forEach(btn => {
            btn.onclick = function() {
                document.getElementById(this.dataset.modal).style.display = 'none';
            };
        });
        window.onclick = function(event) {
            if (event.target === userMenuModal) userMenuModal.style.display = 'none';
        };
    </script>
</body>
</html>
