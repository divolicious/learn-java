<?php
session_start();
include '../koneksi/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Ambil data user
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

// Ambil data stories yang sudah dibaca user
$user_id = $user['id_user'];
$read_stories_query = "SELECT s.*, rs.read_at, rs.start_time, rs.end_time 
                    FROM stories s 
                    JOIN read_stories rs ON s.id = rs.story_id 
                    WHERE rs.user_id = $user_id 
                    ORDER BY rs.read_at DESC";
$read_stories = mysqli_query($koneksi, $read_stories_query);

// Ambil data stories yang disimpan user
$saved_stories_query = "SELECT s.* 
                    FROM stories s 
                    JOIN saved_stories ss ON s.id = ss.story_id 
                    WHERE ss.user_id = $user_id 
                    ORDER BY ss.saved_at DESC";
$saved_stories = mysqli_query($koneksi, $saved_stories_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Stories - Learn Java by Stories</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    .stories-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .stories-section {
        margin-bottom: 3rem;
    }

    .stories-section h2 {
        margin-bottom: 1rem;
        color: #333;
    }

    .stories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .story-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.2s;
    }

    .story-card:hover {
        transform: translateY(-5px);
    }

    .story-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .story-content {
        padding: 1rem;
    }

    .story-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .story-desc {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .progress-bar {
        width: 100%;
        height: 5px;
        background: #eee;
        border-radius: 5px;
        margin-bottom: 0.5rem;
    }

    .progress-fill {
        height: 100%;
        background: #4CAF50;
        border-radius: 5px;
    }

    .story-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: #888;
    }

    .btn-continue {
        background: #4CAF50;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        margin-top: 0.5rem;
    }

    .btn-continue:hover {
        background: #45a049;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #666;
    }

    .btn-back-wrapper {
        background: #4a90e2;
        padding: 8px 15px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .btn-back-wrapper:hover {
        background: #357abd;
        transform: translateX(-5px);
    }

    .btn-back {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }

    .btn-back i {
        font-size: 16px;
    }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="dashboard_user.php" class="logo">Learn Java by Stories</a>
        </div>
        <div class="navbar-right">
            <div class="btn-back-wrapper">
                <a href="dashboard_user.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="stories-container">
        <div class="stories-section">
            <h2>Lanjutkan Membaca</h2>
            <button id="scrollToSavedBtn" style="background: #4CAF50; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer; margin-bottom: 1rem;">
                Lihat Cerita Pilihan
            </button>
            <div class="stories-grid">
                <?php if (mysqli_num_rows($read_stories) > 0): ?>
                <?php while($story = mysqli_fetch_assoc($read_stories)): ?>
                <div class="story-card">
                    <img src="../<?php echo $story['image_url']; ?>" alt="<?php echo $story['title']; ?>"
                        class="story-img">
                    <div class="story-content">
                        <div class="story-title"><?php echo $story['title']; ?></div>
                        <div class="story-desc"><?php echo substr($story['description'], 0, 100) . '...'; ?></div>
                        <?php
                        $target_minutes = 10;
                        $progress = 0;
                        if ($story['end_time']) {
                            $progress = 100;
                        } elseif ($story['start_time']) {
                            $start = strtotime($story['start_time']);
                            $now = time();
                            $elapsed = ($now - $start) / 60;
                            $progress = min(100, round(($elapsed / $target_minutes) * 100));
                        }
                        ?>
                        <?php if ($progress == 100): ?>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width:100%"></div>
                        </div>
                        <span style="color: #388e3c; font-weight:600;">Anda telah selesai membaca.</span>
                        <?php else: ?>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width:<?php echo $progress; ?>%"></div>
                        </div>
                        <span>Progress: <?php echo $progress; ?>%</span>
                        <?php endif; ?>
                        <div class="story-meta">
                            <span>Last read:
                                <?= isset($story['last_read']) ? date('M d, Y', strtotime($story['last_read'])) : 'Never' ?>
                            </span>
                        </div>
                        <a href="read_story.php?id=<?php echo $story['id']; ?>" class="btn-continue">Continue
                            Reading</a>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-book-open fa-3x"></i>
                    <p>You haven't started reading any stories yet.</p>
                    <a href="dashboard_user.php" class="btn-continue">Start Reading</a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="stories-section">
            <h2>Saved Stories</h2>
            <div class="stories-grid">
                <?php if (mysqli_num_rows($saved_stories) > 0): ?>
                <?php while($story = mysqli_fetch_assoc($saved_stories)): ?>
                <div class="story-card">
                    <img src="../<?php echo $story['image_url']; ?>" alt="<?php echo $story['title']; ?>"
                        class="story-img">
                    <div class="story-content">
                        <div class="story-title"><?php echo $story['title']; ?></div>
                        <div class="story-desc"><?php echo substr($story['description'], 0, 100) . '...'; ?></div>
                        <a href="read_story.php?id=<?php echo $story['id']; ?>" class="btn-continue">Read Story</a>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-bookmark fa-3x"></i>
                    <p>You haven't saved any stories yet.</p>
                    <a href="dashboard_user.php" class="btn-continue">Browse Stories</a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Featured Stories Section -->
        <div class="stories-section" id="featured-stories" style="margin-top: 3rem;">
            <h2>Featured Stories</h2>
            <div class="stories-grid">
                <div class="story-card">
                    <img src="../img/Sangkuriang_comic_illust.png" alt="Sangkuriang Story" class="story-img">
                    <div class="story-content">
                        <div class="story-title">Sangkuriang</div>
                        <div class="story-desc">Kisah legendaris tentang cinta terlarang antara ibu dan anak yang berakhir dengan terciptanya Tangkuban Perahu.</div>
                        <a href="read_story.php?id=1" class="btn-continue">Baca Cerita</a>
                    </div>
                </div>
                <div class="story-card">
                    <img src="../img/malin_kundangcomic.png" alt="Malin Kundang Story" class="story-img">
                    <div class="story-content">
                        <div class="story-title">Malin Kundang</div>
                        <div class="story-desc">Cerita tentang seorang anak yang durhaka kepada ibunya dan dikutuk menjadi batu.</div>
                        <a href="read_story.php?id=2" class="btn-continue">Baca Cerita</a>
                    </div>
                </div>
                <div class="story-card">
                    <img src="../img/roro_jonggrangcomic.png" alt="Roro Jonggrang Story" class="story-img">
                    <div class="story-content">
                        <div class="story-title">Roro Jonggrang</div>
                        <div class="story-desc">Kisah cinta antara Bandung Bondowoso dan Roro Jonggrang yang berakhir dengan kutukan menjadi patung.</div>
                        <a href="read_story.php?id=3" class="btn-continue">Baca Cerita</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Featured Stories Section -->
    </div>
    <script>
        // Ubah fungsi smooth scroll untuk menuju featured stories
        document.getElementById('scrollToSavedBtn').addEventListener('click', function() {
            document.getElementById('featured-stories').scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        });
    </script>
</body>

</html>