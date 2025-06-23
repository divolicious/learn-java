<?php
include '../koneksi/koneksi.php';
include '../koneksi/session.php';

// Cek session dan level admin
checkSession();
checkUserLevel('superadmin');

// Proses hapus review
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id_review = (int)$_GET['delete'];
    
    // Debug: Tampilkan ID yang akan dihapus
    error_log("Attempting to delete review with ID: " . $id_review);
    
    // Cek apakah review ada
    $check_query = "SELECT id_review FROM review_website WHERE id_review = $id_review";
    $check_result = mysqli_query($koneksi, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // Review ditemukan, lakukan penghapusan
        $delete_query = "DELETE FROM review_website WHERE id_review = $id_review";
        if (mysqli_query($koneksi, $delete_query)) {
            error_log("Successfully deleted review ID: " . $id_review);
            echo "<script>
                alert('Review berhasil dihapus!');
                window.location.href = 'manage_reviews.php';
            </script>";
        } else {
            error_log("Failed to delete review ID: " . $id_review . " - Error: " . mysqli_error($koneksi));
            echo "<script>
                alert('Gagal menghapus review: " . mysqli_error($koneksi) . "');
                window.location.href = 'manage_reviews.php';
            </script>";
        }
    } else {
        error_log("Review ID not found: " . $id_review);
        echo "<script>
            alert('Review tidak ditemukan!');
            window.location.href = 'manage_reviews.php';
        </script>";
    }
    exit();
}

// Ambil semua review website dengan error handling
$reviews_query = "SELECT r.*, u.nama as user_name, u.email as user_email 
                FROM review_website r 
                JOIN users u ON r.user_id = u.id_user 
                ORDER BY r.created_at DESC";
$reviews_result = mysqli_query($koneksi, $reviews_query);

if (!$reviews_result) {
    error_log("Error fetching reviews: " . mysqli_error($koneksi));
    echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews - LearnJava by Stories</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .reviews-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .reviews-table th,
    .reviews-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
        color: #333;
    }

    .reviews-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .reviews-table tr:hover {
        background-color: #f8f9fa;
    }

    .delete-btn {
        color: #dc3545;
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .delete-btn:hover {
        background-color: #dc3545;
        color: white;
    }

    .review-content {
        max-width: 400px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #333;
    }

    .page-title {
        margin-bottom: 20px;
        color: #333;
    }

    .stats {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        flex: 1;
    }

    .stat-card .label {
        color: #666;
        font-size: 0.9rem;
    }

    .stat-card .value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin-top: 5px;
    }

    .admin-container {
        color: #333;
    }

    .admin-header h1 {
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
                <h1>Website Reviews</h1>
                <div class="user-icon"><span style="font-family:Arial;">&#128100;</span></div>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <div class="label">Total Reviews</div>
                    <div class="value"><?php echo mysqli_num_rows($reviews_result); ?></div>
                </div>
            </div>

            <table class="reviews-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($review = mysqli_fetch_assoc($reviews_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['user_name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($review['user_email'] ?? ''); ?></td>
                        <td class="review-content" title="<?php echo htmlspecialchars($review['review'] ?? ''); ?>">
                            <?php echo htmlspecialchars($review['review'] ?? ''); ?>
                        </td>
                        <td><?php echo date('M d, Y H:i', strtotime($review['created_at'] ?? 'now')); ?></td>
                        <td>
                            <button class="delete-btn"
                                onclick="if(confirm('Are you sure you want to delete this review?')) { window.location='manage_reviews.php?delete=<?php echo htmlspecialchars($review['id_review'] ?? ''); ?>'; }">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>

</html>