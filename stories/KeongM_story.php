<?php
include '../koneksi/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keong Mas - Learn Java by Stories</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .story-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .story-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .story-title {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
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
            padding: 20px;
        }
        
        .story-section {
            margin-bottom: 2rem;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
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

        body {
            background: #2E8B57;
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
        
        <div class="story-header">
            <h1 class="story-title">Keong Mas</h1>
            <img src="../img/keong_mas.png" alt="Keong Mas Story" class="story-image">
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
            </div>

            <div class="story-section">
                <div class="language-label">Indonesian Translate</div>
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
                    <span class="vocabulary-indonesian">///</span>
                </div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">...</span>
                    <span class="vocabulary-indonesian">///</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle dark/light mode
        const toggleBtn = document.querySelector('.toggle');
        const body = document.body;
        
        toggleBtn.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                toggleBtn.innerHTML = '<span class="moon"></span> Dark';
            } else {
                toggleBtn.innerHTML = '<span class="moon"></span> Light';
            }
        });
    </script>

    <?php include '../components/footer.php'; ?>
</body>
</html>
