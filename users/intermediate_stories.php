<?php
session_start();
include '../koneksi/koneksi.php';

// Cek apakah user sudah login
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
    <title>Intermediate Stories - Learn Java by Stories</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        body {
            background: #2E8B57;  /* Warna hijau yang lebih terang */
            margin: 0;
            font-family: "Inter", Arial, sans-serif;
        }

        .stories-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 20px;
        }

        .page-title {
            text-align: center;
            font-size: 3rem;
            color: #FFD700;  /* Warna emas untuk judul */
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

        .stories-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .story-card {
            background: #FFF3E0;  /* Warna cream yang lebih lembut */
            border-radius: 15px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: move;
        }

        .story-card.ui-sortable-helper {
            transform: rotate(3deg);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            z-index: 100;
        }

        .story-card.ui-sortable-placeholder {
            visibility: visible !important;
            background: rgba(255, 243, 224, 0.5);
            border: 2px dashed #FFA500;
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
            width: 80px;
            height: 80px;
            background: #4a4a4a;
            border-radius: 12px;
            padding: 0;
            object-fit: cover;
        }

        .story-content {
            flex: 1;
        }

        .story-title {
            font-size: 1.2rem;
            color: #000;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .story-desc {
            color: #333;
            font-size: 0.95rem;
            line-height: 1.5;
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

        .section-title {
            color: #FFD700;
            font-size: 2.5rem;
            text-align: center;
            margin: 3rem 0;
            font-family: 'Playfair Display', serif;
        }

        .section-desc {
            color: #fff;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        /* Progressive Training Section */
        .progressive-section {
            margin-top: 4rem;
            padding: 2rem 0;
        }

        /* Mempertahankan style navbar yang ada */
        .navbar {
            background: #232d3e;
        }

        .golden-text {
            color: #FFD700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
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

        .header-section {
            text-align: center;
            padding: 40px 20px;
            margin-bottom: 20px;
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
        <h1 class="page-title">Intermediate Folklore</h1>
        <p class="page-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean leo dolor, viverra congue sodales sagittis, malesuada sit amet nulla.</p>
    </div>

    <div class="stories-container">
        <div class="stories-grid">
            <?php
            $stories = [
                [
                    'title' => 'Keong Emas',
                    'desc' => 'Putri cantik dikutuk menjadi keong emas oleh ibu tirinya yang dengki. 
                    Ia hanyut hingga ditemukan oleh nenek baik hati. 
                    Cinta sejatinya akhirnya datang, mematahkan kutukan.',
                    'icon' => '../img/keong_mas.png'
                ],
                [
                    'title' => 'Roro Jonggrang',
                    'desc' => 'Putri Roro Jonggrang menantang Bandung Bondowoso membangun 1000 candi dalam semalam. 
                    Saat hampir selesai, ia menipu sang pangeran, memancing amarah. 
                    Akibatnya, ia dikutuk menjadi arca candi terakhir.',
                    'icon' => '../img/roro_jonggrang_illust2.png'
                ],
                [
                    'title' => 'Jaka Tarub',
                    'desc' => 'Jaka Tarub mencuri selendang bidadari Nawang Wulan agar ia tinggal di bumi. Mereka hidup bahagia hingga rahasianya terbongkar. 
                    Nawang Wulan kembali ke kahyangan, meninggalkan suaminya yang patah hati.',
                    'icon' => '../img/jaka_tarub.png'
                ],
                [
                    'title' => 'Alas Purwo',
                    'desc' => 'Di tengah rimbunnya hutan, Alas Purwo menyimpan cerita gaib dan kerajaan makhluk halus. Konon, siapa yang masuk tanpa izin bisa tersesat selamanya. 
                    Di balik keindahannya, ada misteri yang terus hidup.',
                    'icon' => '../img/alas_purwo.png'
                ],
                [
                    'title' => 'Legenda Banyuwangi',
                    'desc' => 'Sri Tanjung melompat ke sungai demi membuktikan kesetiaannya kepada suaminya. 
                    Ajaib, air sungai berubah harum, membungkam semua fitnah.
                    Dari sanalah nama Banyuwangi lahir — "air yang harum',
                    'icon' => '../img/banyuwangi_img.png'
                ],
                [
                    'title' => 'Legenda Reog Ponorogo',
                    'desc' => 'Dengan topeng singa raksasa dan bulu merak megah, Reog memukau setiap mata. Tarian ini bercerita tentang perjuangan Raja Klono Sewandono demi cintanya. 
                    Keberanian, kekuatan, dan budaya bersatu dalam setiap gerakannya.',
                    'icon' => '../img/reogP_img.png'
                ]
            ];

            foreach ($stories as $story): ?>
                <a href="<?php 
                    // Special case for Roro Jonggrang and other stories
                    $storyFile = match($story['title']) {
                        'Roro Jonggrang' => '../stories/roroJ_story.php',
                        'Keong Emas' => '../stories/KeongM_story.php',
                        'Jaka Tarub' => '../stories/jaka_tarub_story.php',
                        'Alas Purwo' => '../stories/alas_purwo_story.php',
                        'Legenda Reog Ponorogo' => '../stories/reogP_story.php',
                        'Legenda Banyuwangi' => '../stories/banyuwangi_story.php',
                        default => strtolower(str_replace(' ', '_', $story['title'])) . '_story.php'
                    };
                    // Debug: Uncomment to check file paths
                    // echo "<!-- Debug: {$story['title']} -> $storyFile -->";
                    echo file_exists($storyFile) ? $storyFile : 'read_story.php?title=' . urlencode($story['title']);
                ?>" class="story-link" style="text-decoration: none; color: inherit;">
            <div class="story-card">
                <span class="story-badge">Intermediate</span>
                    <img src="<?php echo $story['icon']; ?>" alt="<?php echo $story['title']; ?>" class="story-icon">
                    <div class="story-content">
                        <h2 class="story-title"><?php echo $story['title']; ?></h2>
                        <p class="story-desc"><?php echo $story['desc']; ?></p>
                    </div>
                    <div class="story-overlay">
                        <span class="story-overlay-text">Click to Read More</span>
                    </div>
                    </div>
                </a>
            <?php endforeach; ?>
            </div>

        <h2 class="section-title">Progressive Training</h2>
    <p class="section-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean leo dolor, viverra congue sodales sagittis, malesuada sit amet nulla.</p>

    <div class="stories-grid">
        <?php
            $training_stories = array_slice($stories, 0, 3); // Mengambil 3 cerita pertama
                foreach ($training_stories as $story): ?>
            <div class="story-card">
                <img src="<?php echo $story['icon']; ?>" alt="<?php echo $story['title']; ?>" class="story-icon">
            <div class="story-content">
                <h2 class="story-title"><?php echo $story['title']; ?></h2>
                <p class="story-desc"><?php echo $story['desc']; ?></p>
            </div>
            <div class="story-overlay">
                <span class="story-overlay-text">Click to Access Quiz</span>
            </div>
        </div>
    <?php endforeach; ?>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        // Function to close all modals - make it global
        function closeAllModals() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.style.display = 'none';
            });
        }

        // Save story order to database
        function saveStoryOrder() {
            const storyOrder = [];
            $('.story-card').each(function(index) {
                storyOrder.push({
                    id: $(this).data('story-id') || index,
                    title: $(this).find('.story-title').text()
                });
            });

            $.ajax({
                url: 'save_story_order.php',
                method: 'POST',
                data: { order: JSON.stringify(storyOrder) },
                success: function(response) {
                    console.log('Order saved successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Error saving order:', error);
                }
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

            // Initialize drag and drop for stories
            $(function() {
                $(".stories-grid").sortable({
                    items: ".story-card",
                    cursor: "move",
                    opacity: 0.7,
                    placeholder: "story-card ui-sortable-placeholder",
                    update: function(event, ui) {
                        saveStoryOrder();
                    }
                });
            });
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
            document.getElementById('openLoginBtn1').onclick = openLoginModal;
            document.getElementById('openLoginBtn2').onclick = openLoginModal;
            document.getElementById('openLoginBtn3').onclick = openLoginModal;
            <?php endif; ?>
        });
    </script>
</body>
</html> 
