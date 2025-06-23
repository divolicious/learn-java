<?php
session_start();
include '../koneksi/koneksi.php';


if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'superadmin') {
    header("Location: ../index.php");
    exit();
}


if (isset($_POST['update_status'])) {
    $story_id = (int)$_POST['story_id'];
    $new_status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $update_query = "UPDATE stories SET status = '$new_status' WHERE id = $story_id";
    if (mysqli_query($koneksi, $update_query)) {
        header("Location: manage_stories.php");
        exit();
    }
}


if (isset($_POST['delete_story'])) {
    $story_id = (int)$_POST['story_id'];

    $image_query = "SELECT image_url FROM stories WHERE id = $story_id";
    $image_result = mysqli_query($koneksi, $image_query);
    $story = mysqli_fetch_assoc($image_result);
    
    $delete_query = "DELETE FROM stories WHERE id = $story_id";
    if (mysqli_query($koneksi, $delete_query)) {
        // Delete story image if exists
        if ($story['image_url'] && file_exists('../' . $story['image_url'])) {
            unlink('../' . $story['image_url']);
        }
        header("Location: manage_stories.php");
        exit();
    }
}


$stories_query = "SELECT s.*, u.nama as author_name 
                FROM stories s 
                LEFT JOIN users u ON s.author_id = u.id_user 
                ORDER BY s.created_at DESC";
$stories_result = mysqli_query($koneksi, $stories_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Stories - Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .stories-container {
        background: #fff;
        border-radius: 16px;
        margin: 0 40px 40px 40px;
        padding: 28px 24px 24px 24px;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.07);
    }

    .stories-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .stories-header h2 {
        color: #232d3e;
        font-size: 1.3rem;
        font-weight: bold;
    }

    .btn-add {
        background: #16a34a;
        color: #fff;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add:hover {
        background: #15803d;
    }

    .stories-table {
        width: 100%;
        border-collapse: collapse;
    }

    .stories-table th,
    .stories-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .stories-table th {
        font-weight: 600;
        color: #232d3e;
        background: #f9fafb;
    }

    .stories-table tr:hover {
        background: #f9fafb;
    }

    .story-title {
        font-weight: 500;
        color: #232d3e;
    }

    .story-author {
        color: #6b7280;
    }

    .story-status {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-published {
        background: #dcfce7;
        color: #166534;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-edit,
    .btn-delete {
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .btn-edit {
        background: #3b82f6;
        color: #fff;
    }

    .btn-edit:hover {
        background: #2563eb;
    }

    .btn-delete {
        background: #ef4444;
        color: #fff;
        border: none;
        cursor: pointer;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    .story-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
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
                <h1>Manage Stories</h1>
                <div class="user-icon"><span style="font-family:Arial;">&#128100;</span></div>
            </div>
            <div class="stories-container">
                <div class="stories-header">
                    <h2>All Stories</h2>
                    <a href="add_story.php" class="btn-add">
                        <i class="fa fa-plus"></i> Add New Story
                    </a>
                </div>
                <table class="stories-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($story = mysqli_fetch_assoc($stories_result)): ?>
                        <tr>
                            <td>
                                <?php if ($story['image_url']): ?>
                                <img src="../<?php echo htmlspecialchars($story['image_url']); ?>"
                                    alt="<?php echo htmlspecialchars($story['title']); ?>" class="story-image">
                                <?php else: ?>
                                <div class="story-image" style="background: #e5e7eb;"></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="story-title"><?php echo htmlspecialchars($story['title']); ?></div>
                                <div class="story-description" style="color: #6b7280; font-size: 0.875rem;">
                                    <?php echo htmlspecialchars(substr($story['description'], 0, 100)) . '...'; ?>
                                </div>
                            </td>
                            <td class="story-author"><?php echo htmlspecialchars($story['author_name']); ?></td>
                            <td>
                                <span class="story-status status-<?php echo $story['status']; ?>">
                                    <?php echo ucfirst($story['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($story['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_story.php?id=<?php echo $story['id']; ?>" class="btn-edit">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" style="display: inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this story?');">
                                        <input type="hidden" name="story_id" value="<?php echo $story['id']; ?>">
                                        <button type="submit" name="delete_story" class="btn-delete">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
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