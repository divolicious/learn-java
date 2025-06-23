<?php
session_start();
include '../koneksi/koneksi.php';

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
    <title>Beginner Stories - Learn Java by Stories</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .stories-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 20px;
        }

        .page-title {
            text-align: center;
            font-size: 3rem;
            color: #fff;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }

        .page-description {
            text-align: center;
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 3rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .golden-text {
            color: #FFD700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
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
            padding: 20px;
            position: relative;
            text-align: center;
        }

        .story-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .story-card:hover .story-overlay {
            opacity: 1;
        }

        .story-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 15px;
        }

        .story-overlay-text {
            color: white;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
        }

        .story-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #FFA500;
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }

        .story-icon {
            width: 100%;
            max-width: 300px;
            height: auto;
            margin: 0 auto 20px;
            display: block;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .story-icon:hover {
            transform: scale(1.05);
        }

        .story-title {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
            font-weight: 600;
        }

        .story-desc {
            color: #666;
            text-align: center;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .login-btn {
            display: block;
            background: #4a90e2;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            margin: 15px auto 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: fit-content;
            min-width: 200px;
            text-align: center;
        }

        .login-btn:hover {
            background-color: #DAA520 !important;
            color: white;
        }

        .story-card .login-btn:hover {
            background-color: #DAA520 !important;
        }

        body {
            background: #4a90e2;
        }

        .header-section {
            text-align: center;
            padding: 40px 20px;
            margin-bottom: 20px;
        }

        .story-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: white;
            background: #28a745;
        }

        .premium-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            border-radius: 12px;
            pointer-events: none;
        }

        .story-card:hover .premium-overlay {
            opacity: 1;
        }

        .premium-text {
            color: white;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1.1rem;
        }

        #openLoginBtn2, #openLoginBtn3 {
            background: #FFA500;
        }

        #openLoginBtn2:hover, #openLoginBtn3:hover {
            background: #FFD700 !important;
        }

        .free-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: white;
            background: #28a745;
            z-index: 2;
        }

        .free-story-btn {
            background: #28a745 !important;
        }

        .free-story-btn:hover {
            background: #FFD700 !important;
        }

        /* Tambahkan style untuk tooltip dan icon */
        .user-icon-tooltip {
            position: relative;
            cursor: pointer;
            padding: 5px;
        }

        .user-icon-tooltip i {
            font-size: 1.2rem;
            color: #fff;
        }

        .tooltip-text {
            visibility: hidden;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            text-align: center;
            padding: 5px 10px;
            border-radius: 6px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            font-size: 0.8rem;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .user-icon-tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* Style untuk modal user menu */
        .user-profile {
            text-align: center;
            padding: 20px 0;
        }

        .user-avatar i {
            font-size: 4rem;
            color: #4a90e2;
        }

        .user-menu {
            margin-top: 20px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            transition: background 0.3s;
        }

        .menu-item:hover {
            background: #f5f5f5;
        }

        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .logout {
            color: #dc3545;
        }

        /* Style untuk modal dan tombol close */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 500px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            color: #666;
            cursor: pointer;
            z-index: 1001;
        }

        .close:hover {
            color: #000;
        }

        /* Styles for Quiz Section */
        .quiz-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            margin: 4rem 0;
            width: 100%;
            border-top: 1px solid #e1e1e1;
            border-bottom: 1px solid #e1e1e1;
        }
        
        .quiz-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            font-size: 2.8rem;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-family: 'Playfair Display', serif;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: #4a90e2;
        }

        .section-desc {
            text-align: center;
            color: #555;
            max-width: 800px;
            margin: 0 auto 3.5rem;
            font-size: 1.2rem;
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
        }

        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px 0;
        }

        .quiz-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .quiz-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .quiz-icon {
            width: 100%;
            max-width: 180px;
            height: auto;
            margin: 0 auto 15px;
            display: block;
            border-radius: 12px;
        }

        .quiz-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }

        .quiz-desc {
            color: #666;
            margin-bottom: 15px;
            font-size: 1rem;
            line-height: 1.5;
        }

        .start-quiz-btn {
            display: inline-block;
            background: #4a90e2;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
            margin-top: 10px;
        }

        .quiz-progress {
            width: 100%;
            background: #e0e0e0;
            border-radius: 10px;
            margin-bottom: 15px;
            position: relative;
            height: 20px;
        }

        .progress-bar {
            height: 100%;
            border-radius: 10px;
            background: #4CAF50;
            transition: width 0.5s ease;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #333;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .start-quiz-btn:hover {
            background: #357abd;
        }

        .story-card-new {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            align-items: stretch;
        }

        .icon-container {
            background: #2E7D32;
            padding: 20px;
            width: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-box {
            background: #FFD700;
            border-radius: 10px;
            padding: 15px;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-box img {
            width: 100%;
            height: auto;
        }

        .story-content {
            background: #FFD700;
            padding: 20px;
            flex-grow: 1;
        }

        .story-title-new {
            font-size: 1.5rem;
            color: #000;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }

        .story-desc-new {
            color: #000;
            font-size: 1rem;
            line-height: 1.5;
        }

        .story-desc-new i {
            font-style: italic;
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
                <a href="../practice.php" class="menu-link">PRACTICE</a>
            </div>
        </div>
        <div class="navbar-right">
            <?php if ($is_logged_in): ?>
            <div class="user-icon-tooltip" style="margin-right: 15px;">
                <a href="dashboard_user.php" style="color: inherit; text-decoration: none;">
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

    <div class="header-section">
        <h1 class="page-title">Beginner Folklore</h1>
    <p class="page-description golden-text"><i>Discover bite-sized legends and myths from around the world--perfect for curious minds starting their
        journey into folklore</i></p>
    </div>

    <div class="stories-container">
        <div class="stories-grid">
            <!-- Story Card 1 -->
            <div class="story-card">
                <span class="story-badge">Beginner</span>
                <span class="free-badge">Free Story</span>
                <img src="../img/Sangkuriang_comic_illust.png" alt="Sangkuriang Story" class="story-icon">
                <h2 class="story-title">Sangkuriang Folklore</h2>
                <p class="story-desc"><b>Sangkuriang</b> is a West Javanese folktale about a man who unknowingly falls in love with his mother, Dayang Sumbi. To stop the marriage, she asks him to build a lake and boat in one night. He fails, gets angry, and kicks the boat, which becomes Mount Tangkuban Perahu.</p>
                <a href="sangkuriang_story.php" class="login-btn free-story-btn">Read Story</a>
            </div>

            <!-- Story Card 2 -->
            <div class="story-card">
                <span class="story-badge">Beginner</span>
                <img src="../img/malin_kundangcomic.png" alt="Malin Kundang Story" class="story-icon">
                <h2 class="story-title">Malin Kundang Folklore</h2>
                <p class="story-desc"><b>Malin Kundang</b> is a folktale from West Sumatra about a poor boy who becomes rich after sailing away. When he returns, he is ashamed of his humble mother and refuses to acknowledge her. Heartbroken, his mother curses him, and he turns into stone as a result.</p>
                <?php if ($is_logged_in): ?>
                <a href="../stories/malinK_story.php" class="login-btn">Read Story</a>
                <?php else: ?>
                <a href="#" class="login-btn" onclick="openLoginModal(event)">Login to Read More !</a>
                <?php endif; ?>
            </div>

            <!-- Story Card 3 -->
            <div class="story-card">
                <span class="story-badge">Beginner</span>
                <img src="../img/roro_jonggrangcomic.png" alt="Roro Jonggrang Story" class="story-icon">
                <h2 class="story-title">Story of Roro Jonggrang</h2>
                <p class="story-desc"><b>Roro Jonggrang</b> is a Central Javanese folktale about a princess who is asked to marry a prince named Bandung Bondowoso. To avoid it, she demands he build 1,000 temples in one night. He almost succeeds, but she tricks him by faking dawn. Angry, he curses her into stone, completing the 1,000th temple.</p>
                <?php if ($is_logged_in): ?>
                <a href="../stories/roroJong_stories.php" class="login-btn">Read Story</a>
                <?php else: ?>
                <a href="#" class="login-btn" onclick="openLoginModal(event)">Login to Read More !</a>
                <?php endif; ?>
            </div>

            <!--Story Card 4-->
            <div class="story-card">
                <span class="story-badge">Beginner</span>
                <img src="../img/timun_mas_story.png" alt="Timun Mas Story" class="story-icon">
                <h2 class="story-title">Story of Timun Mas</h2>
                <p class="story-desc"><b>Timun Mas</b>Seorang petani tua mendapat anak dari biji timun ajaib, namun harus menyerahkannya pada raksasa saat dewasa. Saat waktunya tiba, Timun Mas melarikan diri dengan bekal jimat dari pertapa.
                Dengan kecerdikan dan keberanian, ia berhasil mengalahkan raksasa dan hidup bebas.</p>
                <?php if ($is_logged_in): ?>
                <a href="timun_mas_story.php" class="login-btn">Read Story</a>
                <?php else: ?>
                <a href="#" class="login-btn" onclick="openLoginModal(event)">Login to Read More !</a>
                <?php endif; ?>
            </div>

            <!--Story Card 5-->
            <div class="story-card">
                <span class="story-badge">Beginner</span>
                <img src="../img/bawmerah_bawputih.png" alt="Bawang Merah Bawang Putih" class="story-icon">
                <h2 class="story-title">Bawang Merah Bawang Putih</h2>
                <p class="story-desc"><b>Bawang Merah Bawang Putih</b> Bawang Putih hidup sabar bersama ibu tiri dan saudara tirinya yang kejam, Bawang Merah. Suatu hari, kebaikan Bawang Putih dibalas dengan hadiah ajaib, sementara keserakahan Bawang Merah berakhir dengan bencana. 
                Kisah ini mengajarkan bahwa kebaikan selalu akan menemukan jalannya.</p>
                <?php if ($is_logged_in): ?>
                <a href="../stories/bawang_merah_putih_story.php" class="login-btn">Read Story</a>
                <?php else: ?>
                <a href="#" class="login-btn" onclick="openLoginModal(event)">Login to Read More !</a>
                <?php endif; ?>
            </div>

            <!--Story Card 6-->
            <div class="story-card">
                <span class="story-badge">Beginner</span>
                <img src="../img/leg_gn_kelud_story.png" alt="Legenda Gunung Kelud" class="story-icon">
                <h2 class="story-title">Legenda Gunung Kelud</h2>
                <p class="story-desc"><b>Legenda Gunung Kelud</b>bermula dari cinta Lembusura kepada Putri Dewi Kilisuci yang tak terbalas. 
                Karena marah, Lembusura dikutuk menjadi gunung berapi yang terus meletus. Setiap letusannya dipercaya sebagai amarah Lembusura yang belum padam.</p>
                <?php if ($is_logged_in): ?>
                <a href="../stories/gunung_kelud_story.php" class="login-btn">Read Story</a>
                <?php else: ?>
                <a href="#" class="login-btn" onclick="openLoginModal(event)">Login to Read More !</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quiz Section -->
    <div class="quiz-section">
        <div class="quiz-container">
            <h2 class="section-title">Test Your Knowledge</h2>
            <p class="section-desc">Take these quizzes to test your understanding of the stories you've read</p>
            
            <div class="quiz-grid">
                <!-- Quiz 1 - Sangkuriang -->
                <div class="quiz-card">
                    <img src="../img/Sangkuriang_comic_illust.png" alt="Sangkuriang Quiz" class="quiz-icon">
                    <h3 class="quiz-title">Sangkuriang Quiz</h3>
                    <p class="quiz-desc">Test your knowledge about the legend of Sangkuriang and the origin of Mount Tangkuban Perahu</p>
                    <?php if ($is_logged_in): ?>
                    <a href="../quiz/sangkuriang_quiz.php" class="start-quiz-btn">Start Quiz</a>
                    <?php else: ?>
                    <a href="#" class="start-quiz-btn" onclick="openLoginModal(event)">Login untuk Akses</a>
                    <?php endif; ?>
                </div>

                <!-- Quiz 2 - Malin Kundang -->
                <div class="quiz-card">
                    <img src="../img/malin_kundangcomic.png" alt="Malin Kundang Quiz" class="quiz-icon">
                    <h3 class="quiz-title">Malin Kundang Quiz</h3>
                    <p class="quiz-desc">How well do you know the story of Malin Kundang and his ungrateful behavior?</p>
                    <?php if ($is_logged_in): ?>
                    <a href="../quiz/malin_quiz.php" class="start-quiz-btn">Start Quiz</a>
                    <?php else: ?>
                    <a href="#" class="start-quiz-btn" onclick="openLoginModal(event)">Login untuk Akses</a>
                    <?php endif; ?>
                </div>

                <!-- Quiz 3 - Roro Jonggrang -->
                <div class="quiz-card">
                    <div class="quiz-progress">
                        <div class="progress-bar" style="width: 0%"></div>
                        <span class="progress-text">0% Complete</span>
                    </div>
                    <img src="../img/roro_jonggrangcomic.png" alt="Roro Jonggrang Quiz" class="quiz-icon">
                    <h3 class="quiz-title">Roro Jonggrang Quiz</h3>
                    <p class="quiz-desc">Challenge yourself with questions about the legend of Roro Jonggrang and the thousand temples</p>
                    <?php if ($is_logged_in): ?>
                    <a href="../quiz/roroJ_quiz.php" class="start-quiz-btn">Continue Quiz</a>
                    <?php else: ?>
                    <a href="#" class="start-quiz-btn" onclick="openLoginModal(event)">Login untuk Akses</a>
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
            document.getElementById('openLoginBtn2').onclick = openLoginModal;
            document.getElementById('openLoginBtn3').onclick = openLoginModal;
            <?php endif; ?>
        });
    </script>
</body>
</html>
