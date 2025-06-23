<?php
session_start();
include '../koneksi/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_story'])) {
    $title = mysqli_real_escape_string($koneksi, $_POST['title']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);
    $content = mysqli_real_escape_string($koneksi, $_POST['content']);
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
        $success = 'Story submitted! Waiting for admin approval.';
    } else {
        $error = 'Failed to add story: ' . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Story - Learn Java by Stories</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    .add-story-form {
        max-width: 600px;
        margin: 2rem auto;
        background: #fff;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .add-story-form h2 {
        margin-bottom: 1rem;
        color: black;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .form-group label {
        font-weight: 600;
        color: black;
    }

    .form-group input,
    .form-group textarea {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .btn-submit {
        background-color: #4CAF50;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
    }

    .btn-submit:hover {
        background-color: #45a049;
    }

    .alert {
        padding: 1rem;
        border-radius: 5px;
        margin-bottom: 1rem;
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
    <div class="add-story-form">
        <h2>Add New Story</h2>
        <?php if (isset($success)) echo '<div class="alert alert-success">'.$success.'</div>'; ?>
        <?php if (isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Level</label>
                <select name="level" required>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                </select>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>
            <div class="form-group">
                <label>Content</label>
                <textarea name="content" required></textarea>
            </div>
            <div class="form-group">
                <label>Cover Image</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <button type="submit" name="add_story" class="btn-submit">Add Story</button>
        </form>
    </div>
</body>

</html>