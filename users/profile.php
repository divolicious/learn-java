<?php
session_start();
include '../koneksi/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Ambil data user
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

// Ambil statistik quiz vocabulary
$user_id = $_SESSION['id_user'];
$quiz_query = "SELECT * FROM vocabulary_quiz_history WHERE user_id = $user_id ORDER BY quiz_date DESC";
$quiz_result = mysqli_query($koneksi, $quiz_query);

// Hitung statistik
$total_quiz = mysqli_num_rows($quiz_result);
$total_score = 0;
$highest_score = 0;
$total_time = 0;

if ($total_quiz > 0) {
    while ($row = mysqli_fetch_assoc($quiz_result)) {
        $score_percentage = ($row['score'] / $row['total_questions']) * 100;
        $total_score += $score_percentage;
        $highest_score = max($highest_score, $score_percentage);
        $total_time += $row['time_taken'];
    }
    $average_score = $total_score / $total_quiz;
    $average_time = $total_time / $total_quiz;
    
    // Reset pointer untuk penggunaan result set lagi
    mysqli_data_seek($quiz_result, 0);
} else {
    $average_score = 0;
    $average_time = 0;
}

function formatTime($seconds) {
    $minutes = floor($seconds / 60);
    $seconds = $seconds % 60;
    return sprintf("%02d:%02d", $minutes, $seconds);
}

// Proses update profile
if (isset($_POST['update_profile'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $error = '';
    
    // Validasi password jika user ingin mengubah password
    if (!empty($current_password)) {
        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_query = "UPDATE users SET nama='$nama', email='$email', password='$hashed_password' WHERE username='$username'";
            } else {
                $error = "New password and confirm password don't match!";
            }
        } else {
            $error = "Current password is incorrect!";
        }
    } else {
        $update_query = "UPDATE users SET nama='$nama', email='$email' WHERE username='$username'";
    }
    
    if (empty($error)) {
        if (mysqli_query($koneksi, $update_query)) {
            $success = "Profile updated successfully!";
            // Refresh user data
            $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
            $user = mysqli_fetch_assoc($query);
        } else {
            $error = "Failed to update profile!";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_story'])) {
    $title = mysqli_real_escape_string($koneksi, $_POST['title']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);
    $content = mysqli_real_escape_string($koneksi, $_POST['content']);
    $author_id = $_SESSION['id_user'];
    $status = 'pending';

    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = '../assets/images/stories/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_url = 'assets/images/stories/' . $new_filename;
        }
    }

    $insert_query = "INSERT INTO stories (title, content, description, image_url, author_id, status) 
                    VALUES ('$title', '$content', '$description', '$image_url', $author_id, '$status')";
    if (mysqli_query($koneksi, $insert_query)) {
        $success = 'Story submitted! Waiting for admin approval.';
    } else {
        $error = 'Failed to add story: ' . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Learn Java by Stories</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    .profile-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 20px;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .profile-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-icon {
        font-size: 3rem;
        color: #4a90e2;
        margin-bottom: 10px;
    }

    .profile-email {
        color: #666;
        margin-top: 5px;
    }

    .profile-info {
        border-top: 1px solid #eee;
        padding-top: 15px;
    }

    .profile-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .profile-link {
        display: flex;
        align-items: center;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        color: #333;
        text-decoration: none;
        transition: background 0.3s ease;
    }

    .profile-link:hover {
        background: #e9ecef;
    }

    .profile-link i {
        margin-right: 10px;
        color: #4a90e2;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .stat-item {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .stat-value {
        font-size: 1.5rem;
        color: #4a90e2;
        font-weight: 600;
        margin: 5px 0;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }

    .progress-bar-container {
        width: 100%;
        height: 8px;
        background: #eee;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 10px;
    }

    .progress-bar {
        height: 100%;
        background: #4CAF50;
        transition: width 0.3s ease;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        color: white;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge.gold { background: #FFD700; }
    .badge.silver { background: #C0C0C0; }
    .badge.bronze { background: #CD7F32; }
    .badge.gray { background: #808080; }

    .recent-activity {
        margin-top: 20px;
    }

    .activity-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    /* Dark mode styles */
    body.dark-mode .profile-card {
        background: #333;
        color: #fff;
    }

    body.dark-mode .profile-email {
        color: #aaa;
    }

    body.dark-mode .profile-info {
        border-color: #444;
    }

    body.dark-mode .profile-link,
    body.dark-mode .stat-item {
        background: #444;
        color: #fff;
    }

    body.dark-mode .profile-link:hover {
        background: #555;
    }

    body.dark-mode .stat-label {
        color: #aaa;
    }

    body.dark-mode .progress-bar-container {
        background: #444;
    }

    body.dark-mode .activity-item {
        border-color: #444;
    }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="dashboard_user.php" class="logo">Learn Java by Stories</a>
        </div>
        <div class="navbar-right">
            <a href="dashboard_user.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </nav>

    <div class="profile-container">
        <h1>Profil Saya</h1>
        
        <div class="profile-grid">
            <!-- Informasi Profil -->
            <div class="profile-card">
                <div class="profile-header">
                    <i class="fas fa-user-circle profile-icon"></i>
                    <h2><?php echo $user['nama']; ?></h2>
                    <p class="profile-email"><?php echo $user['email']; ?></p>
                </div>
                <div class="profile-info">
                    <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
                    <p><strong>Level:</strong> <?php echo ucfirst($user['level']); ?></p>
                </div>
            </div>

            <!-- Progress Quiz -->
            <div class="profile-card">
        <div class="profile-header">
                    <i class="fas fa-tasks profile-icon"></i>
                    <h2>Progress Quiz Vocabulary</h2>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value"><?php echo $total_quiz; ?></div>
                        <div class="stat-label">Total Quiz</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?php echo number_format($average_score, 1); ?>%</div>
                        <div class="stat-label">Rata-rata Skor</div>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: <?php echo $average_score; ?>%"></div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?php echo number_format($highest_score, 1); ?>%</div>
                        <div class="stat-label">Skor Tertinggi</div>
                    </div>
                </div>

                <?php if ($total_quiz > 0): ?>
                <div class="recent-activity">
                    <h3>Quiz Terakhir</h3>
                    <?php 
                    $latest_quiz = mysqli_fetch_assoc($quiz_result);
                    $latest_score = ($latest_quiz['score'] / $latest_quiz['total_questions']) * 100;
                    $badge_color = getScoreBadgeColor($latest_score);
                    ?>
                    <div class="activity-item">
                        <div>
                            <div><?php echo date('d M Y H:i', strtotime($latest_quiz['quiz_date'])); ?></div>
                            <div>Skor: <?php echo $latest_quiz['score']; ?>/<?php echo $latest_quiz['total_questions']; ?></div>
                        </div>
                        <span class="badge <?php echo $badge_color; ?>"><?php echo ucfirst($badge_color); ?></span>
                    </div>
                </div>
                <?php else: ?>
                <p style="text-align: center; margin-top: 20px; color: #666;">
                    Belum ada quiz yang diselesaikan.<br>
                    <a href="../practice.php" style="color: #4a90e2; text-decoration: none;">Mulai Quiz Sekarang!</a>
                </p>
                <?php endif; ?>
            </div>
        </div>

        <h2>Edit Profile</h2>
        <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="profile-form" method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" value="<?php echo $user['username']; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="nama">Full Name</label>
                <input type="text" id="nama" name="nama" value="<?php echo $user['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>

            <h3>Change Password (Optional)</h3>
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password">
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>

            <button type="submit" name="update_profile" class="btn-update">Update Profile</button>
        </form>
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
    </script>
</body>

</html>