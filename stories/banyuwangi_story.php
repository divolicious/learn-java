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
    <title>Legenda Banyuwangi - Learn Java by Stories</title>
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

        .language-label {
            font-weight: bold;
            color: #4a90e2;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .vocabulary-item {
            display: flex;
            margin-bottom: 0.5rem;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        
        .vocabulary-java {
            font-weight: 600;
            min-width: 200px;
        }
        
        .vocabulary-indonesian {
            color: #666;
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
                <a href="../users/beginner_stories.php" class="menu-link">BEGINNER</a>
                <a href="../users/intermediate_stories.php" class="menu-link">INTERMEDIATE</a>
                <a href="#" class="menu-link">UPGRADE</a>
                <a href="../practice.php" class="menu-link">PRACTICE</a>
            </div>
        </div>
        <div class="navbar-right">
            <div class="user-icon-tooltip">
                <i class="fas fa-user" id="openLogin"></i>
            </div>
            <button class="toggle"><span class="moon"></span> Light</button>
        </div>
    </nav>

    <div class="story-container">
        <a href="../users/intermediate_stories.php" class="back-btn">← Kembali ke Cerita</a>
        <h1 class="story-title">Legenda Banyuwangi</h1>
        <img src="../img/jaka_tarub.png" alt="Banyuwangi Story">
        <div class="story-content">
            <p>Sri Tanjung melompat ke sungai demi membuktikan kesetiaannya kepada suaminya. Ajaib, air sungai berubah harum, membungkam semua fitnah. Dari sanalah nama Banyuwangi lahir — "air yang harum".</p>
            <!-- Add full story content here -->
        </div>

        <div class="story-content">
            <div class="story-section">
                <div class="language-label">Basa Jawa (Javanese)</div>
                <p>
                    ...
                </p>
                <p>
                    ...
                </p>
                <p>
                    ...
                </p>
            </div>

            <div class="story-section">
                <div class="language-label">Indonesian Translation</div>
                <p>
                    ...
                </p>
                <p>
                    ...
                </p>
                <p>
                    ...
                </p>
            </div>

            <div class="story-section">
                <div class="language-label">Vocabulary</div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">...</span>
                    <span class="vocabulary-indonesian"></span>
                </div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">...</span>
                    <span class="vocabulary-indonesian">///</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        //toggle dark/light mode
        const toggleBtn = document.querySelector('.toggle');
        const body = document.body;

        toggleBtn.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                toggleBtn.innerHTML = '<span class="sun"></span> Dark';
            } else {
                toggleBtn.innerHTML = '<span class="moon"></span> Light';
            }
        });
    </script>

    <?php include '../components/footer.php'; ?>
</body>
</html>
