<?php
session_start();
include '../koneksi/koneksi.php';

// Cek apakah user sudah login dan adalah admin
if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'superadmin') {
    header("Location: ../index.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($koneksi, $_POST['title']);
    $content = mysqli_real_escape_string($koneksi, $_POST['content']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);
    $author_id = $_SESSION['id_user'];
    $status = 'pending';

    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = '../assets/images/stories/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_url = 'assets/images/stories/' . $new_filename;
        }
    }

    $insert_query = "INSERT INTO stories (title, content, description, image_url, author_id, status, level) 
                    VALUES ('$title', '$content', '$description', '$image_url', $author_id, '$status', '$level')";

    if (mysqli_query($koneksi, $insert_query)) {
        header("Location: manage_stories.php");
        exit();
    } else {
        $error = "Error adding story: " . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Story - Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .story-form-container {
            background: #fff;
            border-radius: 16px;
            margin: 0 40px 40px 40px;
            padding: 28px 24px 24px 24px;
            box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.07);
        }
        .form-header {
            margin-bottom: 24px;
        }
        .form-header h2 {
            color: #232d3e;
            font-size: 1.3rem;
            font-weight: bold;
        }
        .story-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .form-group label {
            font-weight: 600;
            color: #232d3e;
        }
        .form-group input[type="text"],
        .form-group textarea {
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
        }
        .form-group textarea {
            min-height: 200px;
            resize: vertical;
        }
        .form-group input[type="file"] {
            padding: 10px;
            border: 1px dashed #e5e7eb;
            border-radius: 8px;
            background: #f9fafb;
        }
        .btn-submit {
            background: #16a34a;
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            align-self: flex-start;
        }
        .btn-submit:hover {
            background: #15803d;
        }
        .error-message {
            color: #ef4444;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="logo">LearnJava<br><span style="font-size:0.9rem;font-weight:400;">by Stories</span></div>
            <nav>
                <a href="dashboard_admin.php"><i class="fa fa-gauge"></i> Dashboard</a>
                <a href="manage_users.php"><i class="fa fa-users"></i> Users</a>
                <a href="manage_stories.php" class="active"><i class="fa fa-book"></i> Stories</a>
                <a href="manage_comments.php"><i class="fa fa-comments"></i> Comments</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="admin-header">
                <h1>Add New Story</h1>
                <div class="user-icon"><span style="font-family:Arial;">&#128100;</span></div>
            </div>
            <div class="story-form-container">
                <div class="form-header">
                    <h2>Story Details</h2>
                </div>
                <?php if (isset($error)): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                <form class="story-form" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="level">Level</label>
                        <select id="level" name="level" required>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea id="content" name="content" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Cover Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="btn-submit">Add Story</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html> 