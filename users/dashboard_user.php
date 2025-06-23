<?php
include '../koneksi/koneksi.php';
include '../koneksi/session.php';

// Cek session dan pastikan bukan admin
checkSession();
if ($_SESSION['level'] === 'superadmin') {
    logoutUser();
}

// Ambil data user
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

// Ambil stories yang statusnya 'approved' (atau 'published' jika Anda pakai status itu)
$stories_query = "SELECT * FROM stories WHERE status = 'published' ORDER BY created_at DESC LIMIT 6";
$stories_result = mysqli_query($koneksi, $stories_query);

// Proses tambah review website
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
    <title>Dashboard - Learn Java by Stories</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="../index.php" class="logo" style="text-decoration: none; color: inherit;">Learn Java by Stories</a>
            <div class="nav-menu">
                <a href="beginner_stories.php" class="menu-link">BEGINNER</a>
                <a href="intermediate_stories.php" class="menu-link">INTERMEDIATE</a>
                <a href="../practice.php" class="menu-link">PRACTICE</a>
            </div>
        </div>
        <div class="navbar-right">
            <div class="user-icon-tooltip">
                <a href="profile.php" style="color: inherit;"><i class="fas fa-user" id="openUserMenu"></i></a>
                <span class="tooltip-text"><?php echo $user['nama']; ?></span>
            </div>
            <button class="toggle"><span class="moon"></span> Light</button>
        </div>
    </nav>

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
                <a href="profile.php" class="menu-item"><i class="fas fa-user-edit"></i> Edit Profile</a>
                <a href="add_story.php" class="menu-item"><i class="fas fa-plus"></i> Add Story</a>
                <a href="my_stories.php" class="menu-item"><i class="fas fa-book"></i> My Stories</a>
                <a href="progress.php" class="menu-item"><i class="fas fa-chart-line"></i> Progress</a>
                <a href="logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    <section class="hero">
        <div class="hero-title">Selamat Datang,<br><?php echo $user['nama']; ?>!</div>
        <div class="hero-desc">
            Continue your Javanese learning journey with our curated stories.<br>
            Track your progress and discover new stories.
        </div>
        <button id="continueReadingBtn" class="menu-link"
            style="background-color:rgb(97, 97, 97); color: #fff; border: none; border-radius: 10px; padding: 10px 28px; font-size: 1.1rem; font-weight: 600; cursor: pointer; margin-left: 4px; transition: background 0.2s;">Continue
        Reading</button>
    </section>

    <section class="featured-stories" id="featuredStoriesSection">
        <h2 class="featured-title">FEATURED STORIES</h2>
        <p class="featured-desc">Read our carefully selected stories to boost your Javanese skills!</p>
        <div class="stories-list">
            <?php while($story = mysqli_fetch_assoc($stories_result)): ?>
            <div class="story-card">
                <span class="badge free">Free Story</span>
                <img src="../<?php echo htmlspecialchars($story['image_url'] ?: 'assets/images/default.png'); ?>"
                    alt="<?php echo htmlspecialchars($story['title']); ?>" class="story-img">
                <span
                    class="badge level <?php echo strtolower($story['level']); ?>"><?php echo ucfirst($story['level']); ?></span>
                <div class="story-title"><?php echo htmlspecialchars($story['title']); ?></div>
                <div class="story-desc"><?php echo htmlspecialchars($story['description']); ?></div>
                <a href="read_story.php?id=<?php echo $story['id']; ?>" class="btn story-btn">Read Story</a>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <div class="website-reviews"
        style="max-width:900px;margin:2rem auto 2.5rem auto;padding:2rem;background:#fff;border-radius:12px;box-shadow:0 0 10px rgba(0,0,0,0.07);">
        <h2 style="margin-bottom:1.2rem;">Website Reviews</h2>
        <?php if (isset($_SESSION['id_user'])): ?>
        <?php if ($review_success): ?><div class="alert alert-success"><?php echo $review_success; ?></div>
        <?php endif; ?>
        <?php if ($review_error): ?><div class="alert alert-danger"><?php echo $review_error; ?></div><?php endif; ?>
        <form method="POST" style="margin-bottom:1.5rem;">
            <textarea name="review" rows="3"
                style="width:100%;padding:0.7rem;border-radius:6px;border:1px solid #ddd;resize:vertical;"
                placeholder="Write your review about this website..." required></textarea>
            <button type="submit" name="add_review"
                style="margin-top:0.5rem;background:#4CAF50;color:#fff;padding:0.5rem 1.2rem;border-radius:6px;border:none;font-weight:600;">Submit
                Review</button>
        </form>
        <?php endif; ?>
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
                    <a href="#" class="footer-link">Beginner Stories</a>
                    <a href="#" class="footer-link">Intermediate Stories</a>
                    <a href="#" class="footer-link">Resources</a>
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
                    812-3456-7890</span>
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

    // Tambahkan fungsi smooth scroll
    document.getElementById('continueReadingBtn').addEventListener('click', function() {
        document.getElementById('featuredStoriesSection').scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    });
    </script>
</body>

</html>
