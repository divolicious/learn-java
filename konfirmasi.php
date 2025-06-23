<?php
include 'koneksi/koneksi.php';
include 'koneksi/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header("Location: index.php?gagal=userKosong");
        exit();
    }

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    
    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        
        // Login user dengan data yang valid
        loginUser($user);
        
        // Redirect berdasarkan level
        if ($user['level'] === 'superadmin') {
            header("Location: admin/dashboard_admin.php");
        } else {
            header("Location: users/dashboard_user.php");
        }
        exit();
    } else {
        header("Location: index.php?gagal=userSalah");
        exit();
    }
}
?>