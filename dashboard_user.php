<?php
session_start();
include 'koneksi/koneksi.php';


if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}


$user_id = $_SESSION['id_user'];
$query_user = "SELECT * FROM users WHERE id_user = $user_id";
$result_user = mysqli_query($koneksi, $query_user);
$user = mysqli_fetch_assoc($result_user);


$query_stories = "SELECT * FROM stories ORDER BY level, id";
$result_stories = mysqli_query($koneksi, $query_stories);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Learn Java by Stories</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 20px;
        }

        .welcome-section {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .welcome-text {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .user-info {
            color: #666;
            font-size: 1.1rem;
        }

        .stories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .story-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            position: relative;
        }

        .story-card:hover {
            transform: translateY(-5px);
        }

        .story-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .story-content {
            padding: 20px;
        }

        .story-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #4CAF50;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .story-badge.premium {
            background: #FFC107;
        }

        .story-level {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #2196F3;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .story-level.intermediate {
            background: #FF9800;
        }

        .story-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 10px 0;
            color: #333;
        }

        .story-desc {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .read-btn {
            display: inline-block;
            background: #4a90e2;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .read-btn:hover {
            background: #357abd;
        }

        .level-section {
            margin-bottom: 40px;
        }

        .level-title {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .logout-btn {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="dashboard_user.php" class="logo">Learn Java by Stories</a>
            <div class="nav-menu">
                <a href="users/beginner_stories.php" class="menu-link">BEGINNER</a>
                <a href="users/intermediate_stories.php" class="menu-link">INTERMEDIATE</a>
                <a href="practice.php" class="menu-link">PRACTICE</a>
            </div>
        </div>
        <div class="navbar-right">
            <a href="<?php echo dirname($_SERVER['PHP_SELF']) . '/users/profile.php'; ?>" class="profile-btn" style="margin-right: 15px; background: #4a90e2; color: white; padding: 8px 16px; border-radius: 5px; text-decoration: none; transition: background 0.3s ease;">
                <i class="fas fa-user"></i> Profile
            </a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="welcome-section">
            <h1 class="welcome-text">Selamat datang, <?php echo htmlspecialchars($user['nama']); ?>!</h1>
            <p class="user-info">Level akun: <?php echo htmlspecialchars($user['level']); ?></p>
        </div>

        <?php
        $current_level = '';
        mysqli_data_seek($result_stories, 0);
        while ($story = mysqli_fetch_assoc($result_stories)) {
            if ($story['level'] != $current_level) {
                if ($current_level != '') {
                    echo '</div></div>'; // Tutup stories-grid dan level-section sebelumnya
                }
                $current_level = $story['level'];
                echo '<div id="' . strtolower($current_level) . '" class="level-section">';
                echo '<h2 class="level-title">' . htmlspecialchars($current_level) . ' Stories</h2>';
                echo '<div class="stories-grid">';
            }
        ?>
            <div class="story-card">
                <span class="story-badge <?php echo $story['is_free'] ? '' : 'premium'; ?>">
                    <?php echo $story['is_free'] ? 'Free Story' : 'Premium'; ?>
                </span>
                <span class="story-level <?php echo strtolower($story['level']); ?>">
                    <?php echo htmlspecialchars($story['level']); ?>
                </span>
                <img src="<?php echo htmlspecialchars($story['image_path']); ?>" 
                     alt="<?php echo htmlspecialchars($story['title']); ?>" 
                     class="story-image">
                <div class="story-content">
                    <h2 class="story-title"><?php echo htmlspecialchars($story['title']); ?></h2>
                    <p class="story-desc"><?php echo htmlspecialchars($story['description']); ?></p>
                    <a href="beginner_story.php?id=<?php echo $story['id']; ?>" class="read-btn">Read Story</a>
                </div>
            </div>
        <?php
        }
        if ($current_level != '') {
            echo '</div></div>'; // Tutup stories-grid dan level-section terakhir
        }
        ?>
    </div>
</body>
</html>
