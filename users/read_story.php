<?php
session_start();
include '../koneksi/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Ambil id story dari URL
$story_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query story dan author
$story_query = "SELECT s.*, u.nama as author_name FROM stories s LEFT JOIN users u ON s.author_id = u.id_user WHERE s.id = $story_id AND s.status = 'published'";
$story_result = mysqli_query($koneksi, $story_query);
$story = mysqli_fetch_assoc($story_result);

if (!$story) {
    echo '<div style="text-align:center;margin-top:3rem;">Story not found or not published.<br><a href="dashboard_user.php">Back to Dashboard</a></div>';
    exit();
}

$user_id = $_SESSION['id_user'];
$now = date('Y-m-d H:i:s');
$check = mysqli_query($koneksi, "SELECT * FROM read_stories WHERE user_id = $user_id AND story_id = $story_id");
if (mysqli_num_rows($check) > 0) {
    $row = mysqli_fetch_assoc($check);
    if (is_null($row['start_time'])) {
        mysqli_query($koneksi, "UPDATE read_stories SET start_time = '$now', read_at = NOW() WHERE user_id = $user_id AND story_id = $story_id");
    } else {
        mysqli_query($koneksi, "UPDATE read_stories SET read_at = NOW() WHERE user_id = $user_id AND story_id = $story_id");
    }
} else {
    mysqli_query($koneksi, "INSERT INTO read_stories (user_id, story_id, read_at, start_time) VALUES ($user_id, $story_id, NOW(), '$now')");
}

// Handle save/unsave story
$save_success = $save_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_story'])) {
    // Cek apakah sudah disimpan
    $check_save = mysqli_query($koneksi, "SELECT * FROM saved_stories WHERE user_id = $user_id AND story_id = $story_id");
    if (mysqli_num_rows($check_save) == 0) {
        if (mysqli_query($koneksi, "INSERT INTO saved_stories (user_id, story_id, saved_at) VALUES ($user_id, $story_id, NOW())")) {
            $save_success = 'Story saved!';
        } else {
            $save_error = 'Failed to save story.';
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unsave_story'])) {
    if (mysqli_query($koneksi, "DELETE FROM saved_stories WHERE user_id = $user_id AND story_id = $story_id")) {
        $save_success = 'Story removed from saved!';
    } else {
        $save_error = 'Failed to unsave story.';
    }
}
// Cek status saved
$is_saved = false;
$check_saved = mysqli_query($koneksi, "SELECT * FROM saved_stories WHERE user_id = $user_id AND story_id = $story_id");
if (mysqli_num_rows($check_saved) > 0) {
    $is_saved = true;
}

// Proses tambah komentar
$comment_success = $comment_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    $comment = mysqli_real_escape_string($koneksi, $_POST['comment']);
    if (trim($comment) !== '') {
        $insert_comment = "INSERT INTO comments (story_id, user_id, comment) VALUES ($story_id, $user_id, '$comment')";
        if (mysqli_query($koneksi, $insert_comment)) {
            $comment_success = 'Comment added!';
        } else {
            $comment_error = 'Failed to add comment.';
        }
    } else {
        $comment_error = 'Comment cannot be empty!';
    }
}
// Ambil komentar untuk story ini
$comments_query = "SELECT c.*, u.nama as user_name FROM comments c JOIN users u ON c.user_id = u.id_user WHERE c.story_id = $story_id ORDER BY c.created_at DESC";
$comments_result = mysqli_query($koneksi, $comments_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($story['title']); ?> - Learn Java by Stories</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    .story-container {
        max-width: 800px;
        margin: 2rem auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        padding: 2rem;
    }

    .story-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: black;
    }

    .story-meta {
        color: #888;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }

    .story-img {
        width: 100%;
        max-width: 350px;
        height: auto;
        display: block;
        margin: 0 auto 1.5rem auto;
        object-fit: contain;
        border-radius: 8px;
        background: #f3f3f3;
    }

    .story-desc {
        font-size: 1.1rem;
        color: #555;
        margin-bottom: 1.5rem;
    }

    .story-content {
        font-size: 1.15rem;
        color: #232d3e;
        line-height: 1.7;
        margin-bottom: 2rem;
    }

    .btn-back {
        display: inline-block;
        background: #4CAF50;
        color: #fff;
        padding: 0.5rem 1.2rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .btn-back:hover {
        background: #388e3c;
    }

    .btn-save {
        display: inline-block;
        background: #2196F3;
        color: #fff;
        padding: 0.5rem 1.2rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1rem;
        border: none;
        cursor: pointer;
        margin-right: 0.5rem;
    }

    .btn-save.saved {
        background: #f44336;
    }

    .alert {
        padding: 0.7rem 1rem;
        border-radius: 5px;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="dashboard_user.php" class="logo">Learn Java by Stories</a>
        </div>
        <div class="navbar-right">
            <a href="dashboard_user.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </nav>
    <div class="story-container">
        <?php if ($save_success): ?><div class="alert alert-success"><?php echo $save_success; ?></div><?php endif; ?>
        <?php if ($save_error): ?><div class="alert alert-danger"><?php echo $save_error; ?></div><?php endif; ?>
        <div class="story-title"><?php echo htmlspecialchars($story['title']); ?></div>
        <div class="story-meta">By <?php echo htmlspecialchars($story['author_name']); ?> |
            <?php echo date('F d, Y', strtotime($story['created_at'])); ?></div>
        <?php if ($story['image_url']): ?>
        <img src="../<?php echo htmlspecialchars($story['image_url']); ?>"
            alt="<?php echo htmlspecialchars($story['title']); ?>" class="story-img">
        <?php endif; ?>
        <div class="story-desc"><?php echo htmlspecialchars($story['description']); ?></div>
        <div class="story-content"><?php echo nl2br(htmlspecialchars($story['content'])); ?></div>
        <!-- Komentar Section -->
        <div class="comments-section" style="margin-top:2.5rem;">
            <h3 style="margin-bottom:1rem;">Comments</h3>
            <?php if ($comment_success): ?><div class="alert alert-success"><?php echo $comment_success; ?></div><?php endif; ?>
            <?php if ($comment_error): ?><div class="alert alert-danger"><?php echo $comment_error; ?></div><?php endif; ?>
            <form method="POST" style="margin-bottom:1.5rem;">
                <textarea name="comment" rows="3" style="width:100%;padding:0.7rem;border-radius:6px;border:1px solid #ddd;resize:vertical;" placeholder="Write your comment..." required></textarea>
                <button type="submit" name="add_comment" class="btn-back" style="margin-top:0.5rem;">Post Comment</button>
            </form>
            <div class="comments-list">
                <?php if (mysqli_num_rows($comments_result) > 0): ?>
                    <?php while($c = mysqli_fetch_assoc($comments_result)): ?>
                        <div style="margin-bottom:1.2rem;padding:1rem;background:#f9fafb;border-radius:8px;">
                            <div style="font-weight:600;color:#232d3e;"><?php echo htmlspecialchars($c['user_name']); ?></div>
                            <div style="color:#555;margin:0.3rem 0 0.2rem 0;font-size:0.98rem;">"<?php echo htmlspecialchars($c['comment']); ?>"</div>
                            <div style="font-size:0.85rem;color:#888;"><?php echo date('M d, Y H:i', strtotime($c['created_at'])); ?></div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="color:#888;">No comments yet.</div>
                <?php endif; ?>
            </div>
        </div>
        <form method="POST" style="display:inline;">
            <?php if ($is_saved): ?>
            <button type="submit" name="unsave_story" class="btn-save saved"><i class="fas fa-bookmark"></i> Unsave
                Story</button>
            <?php else: ?>
            <button type="submit" name="save_story" class="btn-save"><i class="far fa-bookmark"></i> Save Story</button>
            <?php endif; ?>
        </form>
        <a href="dashboard_user.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>

</html>