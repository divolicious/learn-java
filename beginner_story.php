<?php
session_start();
include 'koneksi/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: beginner_not_logged_in.php");
    exit();
}

// Ambil ID cerita dari parameter URL (contoh: beginner_story.php?id=1)
$story_id = isset($_GET['id']) ? $_GET['id'] : 1;

// Query untuk mengambil cerita lengkap
$query = "SELECT * FROM stories WHERE id = $story_id AND level = 'beginner'";
$result = mysqli_query($koneksi, $query);
$story = mysqli_fetch_assoc($result);

// Jika cerita tidak ditemukan, redirect ke halaman cerita pemula
if (!$story) {
    header("Location: beginner_not_logged_in.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($story['title']); ?> - Learn Java by Stories</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .story-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 20px;
        }
        
        .story-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .story-title {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .story-image {
            width: 100%;
            max-width: 600px;
            height: auto;
            margin: 20px auto;
            display: block;
            border-radius: 8px;
        }
        
        .story-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #444;
        }
        
        .story-section {
            margin-bottom: 2rem;
        }
        
        .language-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .vocabulary-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .vocabulary-item {
            background: #f5f5f5;
            padding: 8px 12px;
            border-radius: 4px;
            margin-right: 8px;
            margin-bottom: 8px;
        }
        
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }
        
        .nav-button {
            padding: 10px 20px;
            background: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        
        .nav-button:hover {
            background: #357abd;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-left">
            <a href="index.php" class="logo">Learn Java by Stories</a>
            <div class="nav-menu">
                <a href="beginner_not_logged_in.php" class="menu-link">BEGINNER</a>
                <a href="#" class="menu-link">INTERMEDIATE</a>
                <a href="#" class="menu-link">UPGRADE</a>
                <a href="#" class="menu-link">PRACTICE</a>
            </div>
        </div>
        <div class="navbar-right">
            <img src="img/logo2.png" alt="Logo" style="height:50px; margin-right:15px;">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="story-container">
        <div class="story-header">
            <h1 class="story-title"><?php echo htmlspecialchars($story['title']); ?></h1>
            <img src="<?php echo htmlspecialchars($story['image_path']); ?>" alt="<?php echo htmlspecialchars($story['title']); ?>" class="story-image">
        </div>

        <div class="story-content">
            <div class="story-section">
                <div class="language-label">Bahasa Jawa:</div>
                <?php echo nl2br(htmlspecialchars($story['content_java'])); ?>
            </div>

            <div class="story-section">
                <div class="language-label">English Translation:</div>
                <?php echo nl2br(htmlspecialchars($story['content_english'])); ?>
            </div>

            <div class="story-section">
                <div class="language-label">Vocabulary:</div>
                <div class="vocabulary-container">
                    <?php 
                    $vocabItems = explode("\n", $story['vocabulary']);
                    foreach ($vocabItems as $item) {
                        if (trim($item) !== '') {
                            echo '<div class="vocabulary-item">' . htmlspecialchars(trim($item)) . '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="navigation-buttons">
            <?php if ($story_id > 1): ?>
            <a href="?id=<?php echo $story_id - 1; ?>" class="nav-button">Previous Story</a>
            <?php else: ?>
            <span></span>
            <?php endif; ?>

            <a href="?id=<?php echo $story_id + 1; ?>" class="nav-button">Next Story</a>
        </div>
    </div>
</body>
</html>
