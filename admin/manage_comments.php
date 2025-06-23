<?php
session_start();
include '../koneksi/koneksi.php';


if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'superadmin') {
    header("Location: ../index.php");
    exit();
}


if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];
    $delete_query = "DELETE FROM comments WHERE id = $comment_id";
    mysqli_query($koneksi, $delete_query);
}

$comments_query = "SELECT c.*, u.nama as user_name, s.title as story_title 
                FROM comments c 
                JOIN users u ON c.user_id = u.id_user 
                JOIN stories s ON c.story_id = s.id 
                ORDER BY c.created_at DESC";
$comments = mysqli_query($koneksi, $comments_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments - Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .comments-container {
        background: #fff;
        border-radius: 16px;
        margin: 0 40px 40px 40px;
        padding: 28px 24px 24px 24px;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.07);
        color: #333;
    }

    .comments-header {
        margin-bottom: 24px;
    }

    .comments-header h2 {
        color: #232d3e;
        font-size: 1.3rem;
        font-weight: bold;
    }

    .comments-table {
        width: 100%;
        border-collapse: collapse;
        color: #333;
    }

    .comments-table th,
    .comments-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        color: #333;
    }

    .comments-table th {
        font-weight: 600;
        color: #232d3e;
    }

    .comment-content {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #333;
    }

    .btn-delete {
        background: #ef4444;
        color: #fff;
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    .story-link {
        color: #3b82f6;
        text-decoration: none;
    }

    .story-link:hover {
        text-decoration: underline;
    }

    .admin-container {
        color: #333;
    }

    .admin-header h1 {
        color: #333;
    }

    .comments-table tbody tr td {
        color: #333;
    }
    </style>
</head>

<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="logo">LearnJava<br><span style="font-size:0.9rem;font-weight:400;">by Stories</span></div>
            <nav>
                <a href="dashboard_admin.php" class="active"><i class="fa fa-gauge"></i> Dashboard</a>
                <a href="manage_users.php"><i class="fa fa-users"></i> Users</a>
                <a href="manage_stories.php"><i class="fa fa-book"></i> Stories</a>
                <a href="manage_comments.php"><i class="fa fa-comments"></i> Comments</a>
                <a href="manage_reviews.php"><i class="fa fa-star"></i> Website Reviews</a>
                <a href="../users/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="admin-header">
                <h1>Manage Comments</h1>
                <div class="user-icon"><span style="font-family:Arial;">&#128100;</span></div>
            </div>
            <div class="comments-container">
                <div class="comments-header">
                    <h2>All Comments</h2>
                </div>
                <table class="comments-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Story</th>
                            <th>Comment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($comment = mysqli_fetch_assoc($comments)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($comment['user_name']); ?></td>
                            <td>
                                <a href="../read_story.php?id=<?php echo $comment['story_id']; ?>" class="story-link">
                                    <?php echo htmlspecialchars($comment['story_title']); ?>
                                </a>
                            </td>
                            <td class="comment-content"><?php echo htmlspecialchars($comment['comment']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></td>
                            <td>
                                <form method="POST" style="display: inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                    <button type="submit" name="delete_comment" class="btn-delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>