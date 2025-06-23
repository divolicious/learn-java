<?php
include 'koneksi/koneksi.php';

session_start();


$isLoggedIn = isset($_SESSION['username']);
if ($isLoggedIn) {
    $username = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = 'guest'; 


    $cekUsername = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cekUsername) > 0) {
        echo "<script>alert('Username sudah dipakai, silakan pilih username lain.'); window.location='index.php';</script>";
        exit();
    }


    $cekEmail = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cekEmail) > 0) {
        echo "<script>alert('Email sudah dipakai, silakan gunakan email lain.'); window.location='index.php';</script>";
        exit();
    }


    $query = "INSERT INTO users (nama, email, username, password, level) 
            VALUES ('$nama', '$email', '$username', '$password', '$level')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal: " . mysqli_error($koneksi) . "'); window.location='index.php';</script>";
    }
}


$review_success = $review_error = '';
if (isset($_SESSION['id_user']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    $user_id = $_SESSION['id_user'];
    $review = mysqli_real_escape_string($koneksi, $_POST['review']);
    if (trim($review) !== '') {
        $insert_review = "INSERT INTO review_website (user_id, review) VALUES ($user_id, '$review')";
        if (mysqli_query($koneksi, $insert_review)) {
            $review_success = 'Thank you for your review!';
        } else {
            $review_error = 'Failed to submit review.';
        }
    } else {
        $review_error = 'Review cannot be empty!';
    }
}


$reviews_query = "SELECT r.*, u.nama as user_name FROM review_website r JOIN users u ON r.user_id = u.id_user ORDER BY r.created_at DESC";
$reviews_result = mysqli_query($koneksi, $reviews_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn Java by Stories</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .navbar {
            background: #232d3e;
            color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar .logo,
        .navbar .menu-link {
            color: white;
        }
        .navbar .menu-link:hover {
            color: #4ea1ff;
            text-shadow: 0 0 5px rgba(0,0,0,0.3);
        }
        .story-card {
            position: relative;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        @keyframes titleDescent {
            0% {
                opacity: 0;
                transform: translateY(-50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-title {
            animation: titleDescent 1.5s ease-out forwards;
        }

        @keyframes descDescent {
            0% {
                opacity: 0;
                transform: translateY(-30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-desc {
            animation: descDescent 1.5s ease-out 0.5s forwards;
            opacity: 0;
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            line-height: 1.6;
        }

        #startReadingBtn {
            animation: descDescent 1.5s ease-out 1s forwards;
            opacity: 0;
        }

        .story-card:hover {
            transform: translateY(-5px);
        }

        .story-link {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .story-btn {
            position: relative;
            z-index: 2;
            background: #3a7ac2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .story-btn:hover {
            background: #2b62a0;
        }

        .start-reading-btn:hover {
            background-color: #28a745 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="navbar-left">
            <span class="logo">Learn Java by Stories</span>
            <div class="nav-menu">
                <a href="users/beginner_stories.php" class="menu-link">BEGINNER</a>
                <a href="users/intermediate_stories.php" class="menu-link">INTERMEDIATE</a>
                <a href="practice.php" class="menu-link">PRACTICE</a>
            </div>
        </div>
        <div class="navbar-right">
            <?php if ($isLoggedIn): ?>
            <div class="user-icon-tooltip" style="margin-right: 15px;">
                <a href="users/dashboard_user.php" style="color: inherit; text-decoration: none;">
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
            </div>
            <?php endif; ?>
            <button class="toggle"><span class="moon"></span> Light</button>
        </div>
    </nav>
    <!-- Modal Register -->
    <div class="modal" id="modalRegister">
        <div class="modal-content">
            <span class="close" data-modal="modalRegister">&times;</span>
            <h2>Register</h2>
            <form action="index.php" method="post">
                <p style=" font-weight: 40; margin-bottom: 20px;">Silahkan isi form dibawah ini untuk
                    membuat akun baru</p>
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
            <p class="modal-switch">Already have an account? <a href="#" id="switchToLogin">Login</a></p>
        </div>
    </div>
    <!-- Modal Login -->
    <div class="modal" id="modalLogin">
        <div class="modal-content">
            <span class="close" data-modal="modalLogin">&times;</span>
            <h2>Login</h2>
            <form action="konfirmasi.php" method="post">
                <?php if(!empty($_GET['gagal'])){?>
                <?php if($_GET['gagal']=="userKosong"){?>
                <span class="text-danger">
                    Maaf Username Tidak Boleh Kosong
                </span>
                <?php } else if($_GET['gagal']=="passKosong"){?>
                <span class="text-danger">
                    Maaf Password Tidak Boleh Kosong
                </span>
                <?php } else {?>
                <span class="text-danger">
                    Maaf Username dan Password Anda Salah
                </span>
                <?php }?>
                <?php }?>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Username" name="username" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <button class="login-btn" type="submit">Login</button>
            </form>
            <p class="modal-switch">Don't have an account? <a href="#" id="switchToRegister">Register</a></p>
        </div>
    </div>
    <?php if ($isLoggedIn): ?>
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
                <a href="users/dashboard_user.php" class="menu-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="users/profile.php" class="menu-item"><i class="fas fa-user-edit"></i> Edit Profile</a>
                <a href="users/add_story.php" class="menu-item"><i class="fas fa-plus"></i> Add Story</a>
                <a href="users/my_stories.php" class="menu-item"><i class="fas fa-book"></i> My Stories</a>
                <a href="users/progress.php" class="menu-item"><i class="fas fa-chart-line"></i> Progress</a>
                <a href="users/logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <section class="hero">
        <div class="hero-title">Learn Javanese,<br>Made Simple</div>
        <div class="hero-desc"><i>
            Improve your Javanese reading skills with classic Indonesian folklores,<br>
            translated and designed for learners.</i>
        </div>
        <button class="menu-link start-reading-btn" id="startReadingBtn"
            style="background-color:rgb(97, 97, 97); color: #fff; border: none; border-radius: 10px; padding: 10px 28px; font-size: 1.1rem; font-weight: 600; cursor: pointer; margin-left: 4px; transition: all 0.3s ease;">Start
            Reading</button>
    </section>
    <section class="featured-stories" id="featured-stories">
        <h2 class="featured-title">FEATURED STORIES</h2>
        <p class="featured-desc">Read our carefully selected stories to boost your Javanese skills!</p>
        <div class="stories-list">
            <div class="story-card">
                <a href="users/sangkuriang_story.php" class="story-link"></a>
                <span class="badge free">Free Story</span>
                <img src="img/Sangkuriang_comic_illust.png" alt="Sangkuriang Story" class="story-img">
                <span class="badge level beginner">Beginner</span>
                <div class="story-title">Story of Sangkuriang | Cerita Sangkuriang</div>
                <div class="story-desc">Sangkuriang jatuh cinta pada ibunya sendiri tanpa sadar. 
                    Ia gagal membangun perahu dalam semalam dan murka saat rahasia terungkap. 
                    Gunung Tangkuban Perahu pun lahir dari amarahnya.</div>
                <a href="users/sangkuriang_story.php" class="story-btn">Read Story</a>
            </div>
            <div class="story-card">
                <a href="users/beginner_stories.php" class="story-link"></a>
                <span class="badge free">Free Story</span>
                <img src="img/malin_kundangcomic.png" alt="Malin Kundang" class="story-img">
                <span class="badge level beginner">Beginner</span>
                <div class="story-title">Story Malin Kundang | Cerita Malin Kundang</div>
                <div class="story-desc">Malin Kundang menolak mengakui ibunya setelah jadi orang kaya. 
                    Sang ibu yang terluka hati mengutuknya jadi batu. Kutukan itu abadi di tepi pantai sebagai 
                    pengingat durhaka.</div>
                <a href="users/beginner_stories.php" class="story-btn">Read Story</a>
            </div>
            <div class="story-card">
                <a href="users/beginner_stories.php" class="story-link"></a>
                <span class="badge free">Free Story</span>
                <img src="img/roro_jonggrangcomic.png" alt="Roro Jonggrang" class="story-img">
                <span class="badge level intermediate">Intermediate</span>
                <div class="story-title">Roro Jonggrang | Cerita Roro Jonggrang</div>
                <div class="story-desc">Roro Jonggrang menolak lamaran Bandung Bondowoso dengan tipu daya. 
                    Ia memintanya membangun 1.000 candi dalam semalam, 
                    lalu menggagalkannya. Karena marah, sang pangeran mengutuknya menjadi arca terakhir.</div>
                <a href="users/intermediate_stories.php" class="story-btn">Read Story</a>
            </div>
        </div>
    </section>
    <!-- Review Website Section -->
    <div class="website-reviews"
        style="max-width:900px;margin:2rem auto 2.5rem auto;padding:2rem;background:#fff;border-radius:12px;box-shadow:0 0 10px rgba(0,0,0,0.07);">
        <h2 style="margin-bottom:1.2rem; color: black;">Website Reviews</h2>

        <div class="reviews-list">
            <?php if (mysqli_num_rows($reviews_result) > 0): ?>
            <?php while($r = mysqli_fetch_assoc($reviews_result)): ?>
            <div style="margin-bottom:1.2rem;padding:1rem;background:#f9fafb;border-radius:8px;">
                <div style="font-weight:600;color:#232d3e;"><?php echo htmlspecialchars($r['user_name']); ?></div>
                <div style="color:#555;margin:0.3rem 0 0.2rem 0;font-size:0.98rem;">
                    "<?php echo htmlspecialchars($r['review']); ?>"</div>
                <div style="font-size:0.85rem;color:#888;">
                    <?php echo date('M d, Y H:i', strtotime($r['created_at'])); ?></div>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <div style="color:#888;">No website reviews yet.</div>
            <?php endif; ?>
        </div>
    </div>
    <!-- END Review Website Section -->
    <footer class="site-footer">
        <div class="footer-main">
            <div class="footer-brand">
                <div class="footer-logo">Learn Java by Stories</div>
                <p class="footer-desc">
                    Learn Java by Stories is dedicated to enhancing your learning experience. We gather insights through
                    data analytics and feature curated content to support our services. For details on how we handle
                    your information, kindly refer to our <a href="#" class="footer-link">Privacy Policy</a>.
                </p>
                <p class="footer-desc">
                    All content on this site is the exclusive property of Learn Java by Stories. Reproduction or
                    distribution without explicit written consent is strictly prohibited.
                </p>
            </div>
            <div class="footer-menus">
                <div class="footer-menu">
                    <div class="footer-menu-title">PURCHASE</div>
                    <a href="#" class="footer-link">Practice Book</a>
                    <a href="#" class="footer-link">Upgrade</a>
                </div>
                <div class="footer-menu">
                    <div class="footer-menu-title">SUPPORT</div>
                    <a href="#" class="footer-link">Contact</a>
                    <a href="#" class="footer-link">About</a>
                </div>
                <div class="footer-menu">
                    <div class="footer-menu-title">RESOURCES</div>
                    <a href="users/beginner_stories.php" class="footer-link">Beginner Stories</a>
                    <a href="users/intermediate_stories.php" class="footer-link">Intermediate Stories</a>
                    <a href="practice.php" class="footer-link">Practice</a>
                    <a href="#" class="footer-link">Android App</a>
                </div>
                <div class="footer-menu">
                    <div class="footer-menu-title">LEGAL</div>
                    <a href="#" class="footer-link">Privacy</a>
                    <a href="#" class="footer-link">Terms and Conditions</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-divider"></div>
            <div class="footer-copyright">
                &copy; 2024 Learn Java by Stories - Divo Farrelly Sattar. All Rights Reserved.<br>
                <span class="footer-meta">Jl. Contoh No. 123, Yogyakarta | Business License: 123-45-67890 | (+62)
                    811-9232-210</span>
            </div>

        </div>
    </footer>
    <script>
    const toggleBtn = document.querySelector('.toggle');
    const body = document.body;
    let isLight = true;
    const sunSVG =
        `<svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='#FDB813' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><circle cx='12' cy='12' r='5'/><path d='M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42'/></svg>`;
    const moonSVG =
        `<svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='#fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z'/></svg>`;

    function setToggle() {
        if (isLight) {
            toggleBtn.innerHTML = sunSVG + ' Light';
        } else {
            toggleBtn.innerHTML = moonSVG + ' Dark';
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

    <?php if ($isLoggedIn): ?>
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
    <?php else: ?>
    // Modal logic for non-logged in users
    const modalRegister = document.getElementById('modalRegister');
    const modalLogin = document.getElementById('modalLogin');
    document.getElementById('openLogin').onclick = function(e) {
        e.preventDefault();
        modalLogin.style.display = 'block';
        modalRegister.style.display = 'none';
    };
    document.querySelectorAll('.close').forEach(btn => {
        btn.onclick = function() {
            document.getElementById(this.dataset.modal).style.display = 'none';
        };
    });
    document.getElementById('switchToLogin').onclick = function(e) {
        e.preventDefault();
        modalRegister.style.display = 'none';
        modalLogin.style.display = 'block';
    };
    document.getElementById('switchToRegister').onclick = function(e) {
        e.preventDefault();
        modalLogin.style.display = 'none';
        modalRegister.style.display = 'block';
    };
    window.onclick = function(event) {
        if (event.target === modalRegister) modalRegister.style.display = 'none';
        if (event.target === modalLogin) modalLogin.style.display = 'none';
    };
    <?php endif; ?>

    // Handle Practice link click based on login status
    document.querySelectorAll('.nav-menu .menu-link:nth-child(3)').forEach(link => {
        link.addEventListener('click', function(e) {
            <?php if (!$isLoggedIn): ?>
            e.preventDefault();
            modalLogin.style.display = 'block';
            modalRegister.style.display = 'none';
            <?php endif; ?>
        });
    });

    // Tambahkan fungsi smooth scroll
    document.getElementById('startReadingBtn').addEventListener('click', function() {
        document.getElementById('featured-stories').scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    });
    </script>
</body>
</html>
