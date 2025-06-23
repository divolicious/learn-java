<?php
session_start();
include '../koneksi/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reog Ponorogo - Learn Java by Stories</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: #2E8B57;
            margin: 0;
            font-family: "Inter", Arial, sans-serif;
        }
        .story-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 20px;
            background: #FFF3E0;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .story-title {
            color: #000;
            font-size: 2rem;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }
        .story-content {
            line-height: 1.6;
            color: #333;
        }
        
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #357abd;
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
                <a href="../practice.php">PRACTICE</a>
            </div>
        </div>
        <div class="navbar-right">
            <div class="user-icon-tooltip">
                <i class="fas fa-user" id="openLogin"></i>
            </div>
            <button class="toggle"><span class="moon"></span>Light</button>
        </div>
    </nav>

    <div class="story-container">
        <a href="../users/intermediate_stories.php" class="back-btn">Kembali ke Cerita</a>
        <div class="story-content">
            <p>ijat banyak yapping</p>
        </div>
    </div>

    <?php include '../components/footer.php'; ?>
</body>
</html>