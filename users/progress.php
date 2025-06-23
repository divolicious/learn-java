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
$user_id = $user['id_user'];

// Ambil data progress membaca
$progress_query = "SELECT s.*, rs.read_at, rs.start_time, rs.end_time FROM stories s JOIN read_stories rs ON s.id = rs.story_id WHERE rs.user_id = $user_id ORDER BY rs.read_at DESC";
$progress_result = mysqli_query($koneksi, $progress_query);

$target_minutes = 10; // target waktu baca (misal 10 menit)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress - Learn Java by Stories</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    .progress-container {
        max-width: 900px;
        margin: 2rem auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        padding: 2rem;
    }

    .progress-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 1.5rem;
        color: black;
    }

    .progress-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
    }

    .progress-table th,
    .progress-table td {
        padding: 12px 10px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    .progress-table td {
        color: black;
    }

    .progress-table th {
        background: #f9fafb;
        font-weight: 600;
        color: #232d3e;
    }

    .progress-table tr:hover {
        background: #f3f3f3;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: #eee;
        border-radius: 5px;
        margin-top: 4px;
    }

    .progress-fill {
        height: 100%;
        background: #4CAF50;
        border-radius: 5px;
    }

    .empty-state {
        text-align: center;
        color: #888;
        padding: 2rem;
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
    <div class="progress-container">
        <div class="progress-title">Your Reading Progress</div>
        <?php if (mysqli_num_rows($progress_result) > 0): ?>
        <table class="progress-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Last Read</th>
                    <th>Progress</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($story = mysqli_fetch_assoc($progress_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($story['title']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($story['read_at'])); ?></td>
                    <td style="min-width:120px;">
                        <?php
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
                        <span><?php echo $progress; ?>%</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($progress == 100): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_progress_id" value="<?php echo $story['id']; ?>">
                            <button type="submit" name="delete_progress" class="btn-back"
                                style="background:#f44336;padding:0.3rem 0.8rem;font-size:0.95rem;"><i
                                    class="fas fa-trash"></i> Hapus</button>
                        </form>
                        <?php else: ?>
                        <a href="read_story.php?id=<?php echo $story['id']; ?>" class="btn-back"
                            style="padding:0.3rem 0.8rem;font-size:0.95rem;">Continue</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-chart-line fa-3x"></i>
            <p>You haven't started reading any stories yet.</p>
            <a href="dashboard_user.php" class="btn-back">Browse Stories</a>
        </div>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
// Proses hapus progress
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_progress'])) {
    $delete_id = (int)$_POST['delete_progress_id'];
    mysqli_query($koneksi, "DELETE FROM read_stories WHERE user_id = $user_id AND story_id = $delete_id");
    echo "<script>window.location.reload();</script>";
    exit();
}
?>