<?php
session_start();
include '../koneksi/koneksi.php';

// Optional: Get user info if logged in
$is_logged_in = isset($_SESSION['username']);
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
    <title>Alas Purwo Story - Learn Java by Stories</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
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
        
        .vocabulary-english {
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
                <a href="../beginner_stories.php" class="menu-link">BEGINNER</a>
                <a href="../intermediate_stories.php" class="menu-link">INTERMEDIATE</a>
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
        <a href="../users/intermediate_stories.php" class="back-btn">← Back to Stories</a>
        
        <div class="story-header">
            <h1 class="story-title">Alas Purwo</h1>
            <img src="../img/alas_purwo.png" alt="Alas Purwo Story" class="story-image">
        </div>

        <div class="story-content">
            <div class="story-section">
                <div class="language-label">Basa Jawa (Javanese)</div>
                <p>
                Ing jaman biyen, ana putri ayu sing jenenge Dayang Sumbi. Dheweke duwe anak lanang sing jenenge Sangkuriang. 
                Sangkuriang seneng banget berburu karo asu kesayangane sing jenenge Tumang. Nanging, Sangkuriang ora ngerti nek Tumang kuwi sakjane bapake sing wis disihir dadi asu.
                </p>
                <p>
                Ing sawijining dina, nalika Sangkuriang berburu, Tumang ora gelem nuruti perintahe. Sangkuriang dadi nesu lan mateni Tumang. 
                Dheweke njupuk atine Tumang lan diwenehake marang Dayang Sumbi. Nalika Dayang Sumbi ngerti nek kuwi atine Tumang, dheweke nesu banget lan nggebug sirahe Sangkuriang nganggo enthong.
                </p>
                <p>
                Sangkuriang lunga saka omahe kanthi tatu ing sirah lan ora eling karo kedadeyan kuwi. Puluhan taun kemudian, Sangkuriang ketemu karo wanita ayu lan kepengin ngawini dheweke. 
                Nanging, wanita kuwi ora liya yaiku Dayang Sumbi, ibune dhewe. Dayang Sumbi ngerti saka tatu ing sirahe Sangkuriang.
                </p>
            </div>

            <div class="story-section">
                <div class="language-label">English Translation</div>
                <p>
                Long ago, there was a beautiful princess named Dayang Sumbi. She had a son named Sangkuriang. 
                Sangkuriang loved hunting with his favorite dog named Tumang. However, Sangkuriang didn't know that Tumang was actually his father who had been cursed into a dog.
                </p>
                <p>
                One day, while hunting, Tumang refused to follow Sangkuriang's orders. Sangkuriang became angry and killed Tumang. 
                He took Tumang's heart and gave it to Dayang Sumbi. When Dayang Sumbi learned that it was Tumang's heart, she became very angry and hit Sangkuriang's head with a ladle.
                </p>
                <p>
                Sangkuriang left home with a wound on his head and no memory of the incident. Decades later, Sangkuriang met a beautiful woman and wanted to marry her. 
                However, that woman was none other than Dayang Sumbi, his own mother. Dayang Sumbi recognized him from the scar on his head.
                </p>
            </div>

            <div class="story-section">
                <div class="language-label">Vocabulary</div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">jaman biyen</span>
                    <span class="vocabulary-english">long ago</span>
                </div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">putri ayu</span>
                    <span class="vocabulary-english">beautiful princess</span>
                </div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">berburu</span>
                    <span class="vocabulary-english">hunting</span>
                </div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">asu</span>
                    <span class="vocabulary-english">dog</span>
                </div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">nesu</span>
                    <span class="vocabulary-english">angry</span>
                </div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">sirah</span>
                    <span class="vocabulary-english">head</span>
                </div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">tatu</span>
                    <span class="vocabulary-english">wound</span>
                </div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">eling</span>
                    <span class="vocabulary-english">remember</span>
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
</body>
</html>
