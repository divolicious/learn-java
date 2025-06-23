<?php
session_start();
include '../koneksi/koneksi.php';


if (isset($_POST['delete_user'])) {
    $user_id = (int)$_POST['user_id'];


    $delete_reviews = "DELETE FROM review_website WHERE user_id = $user_id";
    mysqli_query($koneksi, $delete_reviews);

    $delete_comments = "DELETE FROM comments WHERE user_id = $user_id";
    mysqli_query($koneksi, $delete_comments);


    $delete_stories = "DELETE FROM stories WHERE author_id = $user_id";
    mysqli_query($koneksi, $delete_stories);


    $delete_user = "DELETE FROM users WHERE id_user = $user_id";
    if (mysqli_query($koneksi, $delete_user)) {
        header("Location: manage_users.php");
        exit();
    }
}



$users_query = "SELECT * FROM users WHERE level != 'superadmin'";
$users_result = mysqli_query($koneksi, $users_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .users-container {
        background: #fff;
        border-radius: 16px;
        margin: 0 40px 40px 40px;
        padding: 28px 24px 24px 24px;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.07);
    }

    .users-header {
        margin-bottom: 24px;
    }

    .users-header h2 {
        color: #232d3e;
        font-size: 1.3rem;
        font-weight: bold;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th,
    .users-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .users-table td {
        color: black;
    }

    .users-table th {
        font-weight: 600;
        color: #232d3e;
        background: rgb(255, 245, 245);
    }

    .users-table tr:hover {
        background: rgb(131, 130, 130);
    }

    .user-name {
        font-weight: 500;
        color: #232d3e;
    }

    .user-email {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-delete {
        background: #ef4444;
        color: #fff;
        border: none;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .btn-delete:hover {
        background: #dc2626;
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
                <h1>Manage Users</h1>
                <div class="user-icon"><span style="font-family:Arial;">&#128100;</span></div>
            </div>
            <div class="users-container">
                <div class="users-header">
                    <h2>All Users</h2>
                </div>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['nama']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo ucfirst($user['level']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id_user']; ?>">
                                    <button type="submit" name="delete_user" class="btn-delete">
                                        <i class="fa fa-trash"></i> Delete
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