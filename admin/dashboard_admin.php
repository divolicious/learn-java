<?php
include '../koneksi/koneksi.php';
include '../koneksi/session.php';


checkSession();
checkUserLevel('superadmin');


$users_query = "SELECT COUNT(*) as total_users FROM users WHERE level = 'guest'";
$users_result = mysqli_query($koneksi, $users_query);
$total_users = mysqli_fetch_assoc($users_result)['total_users'];


$stories_query = "SELECT COUNT(*) as total_stories FROM stories";
$stories_result = mysqli_query($koneksi, $stories_query);
$total_stories = mysqli_fetch_assoc($stories_result)['total_stories'];


$comments_query = "SELECT COUNT(*) as total_comments FROM comments";
$comments_result = mysqli_query($koneksi, $comments_query);
$total_comments = mysqli_fetch_assoc($comments_result)['total_comments'];


$recent_stories_query = "SELECT s.*, u.nama as author_name 
                        FROM stories s 
                        JOIN users u ON s.author_id = u.id_user 
                        ORDER BY s.created_at DESC 
                        LIMIT 3";
$recent_stories = mysqli_query($koneksi, $recent_stories_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LearnJava by Stories</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <!-- Font Awesome for icons (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <h1>Admin</h1>
                <div class="user-icon"><span style="font-family:Arial;">&#128100;</span></div>
            </div>
            <div class="stats-cards">
                <div class="stat-card users">
                    <span class="label">Users</span>
                    <span class="value"><?php echo number_format($total_users); ?></span>
                </div>
                <div class="stat-card stories">
                    <span class="label">Stories</span>
                    <span class="value"><?php echo number_format($total_stories); ?></span>
                </div>
                <div class="stat-card comments">
                    <span class="label">Comments</span>
                    <span class="value"><?php echo number_format($total_comments); ?></span>
                </div>
            </div>
            <div class="recent-stories">
                <h2>Recent Stories</h2>
                <table class="stories-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($story = mysqli_fetch_assoc($recent_stories)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($story['title']); ?></td>
                            <td><?php echo htmlspecialchars($story['author_name']); ?></td>
                            <td><?php echo date('F d, Y', strtotime($story['created_at'])); ?></td>
                            <td>
                                <span class="badge-status <?php echo $story['status']; ?>">
                                    <?php echo ucfirst($story['status']); ?>
                                </span>
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