<?php
// Pastikan session dimulai di awal
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fungsi untuk mendapatkan base URL
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script_name = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    return $protocol . '://' . $host . $script_name;
}

// Fungsi untuk memeriksa dan memperbarui session
function checkSession() {
    // Jika tidak ada session login, redirect ke login
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        // Tentukan path redirect berdasarkan lokasi file
        $current_path = $_SERVER['PHP_SELF'];
        if (strpos($current_path, '/admin/') !== false) {
            header("Location: ../index.php");
        } else if (strpos($current_path, '/users/') !== false) {
            header("Location: ../index.php");
        } else {
            header("Location: index.php");
        }
        exit();
    }

    // Update waktu aktivitas terakhir
    $_SESSION['last_activity'] = time();
}

// Fungsi untuk login
function loginUser($userData) {
    // Generate session ID unik
    $session_id = uniqid($userData['username'] . '_', true);
    
    // Set session baru
    $_SESSION['session_id'] = $session_id;
    $_SESSION['logged_in'] = true;
    $_SESSION['id_user'] = $userData['id_user'];
    $_SESSION['username'] = $userData['username'];
    $_SESSION['nama'] = $userData['nama'];
    $_SESSION['email'] = $userData['email'];
    $_SESSION['level'] = $userData['level'];
    $_SESSION['last_activity'] = time();
}

// Fungsi untuk logout
function logoutUser() {
    // Hanya hapus session saat ini
    $_SESSION = array();
    
    // Tentukan path redirect berdasarkan lokasi file
    $current_path = $_SERVER['PHP_SELF'];
    if (strpos($current_path, '/admin/') !== false) {
        header("Location: ../index.php");
    } else if (strpos($current_path, '/users/') !== false) {
        header("Location: ../index.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

// Fungsi untuk memeriksa level user
function checkUserLevel($requiredLevel) {
    if (!isset($_SESSION['level']) || $_SESSION['level'] !== $requiredLevel) {
        // Tentukan path redirect berdasarkan lokasi file
        $current_path = $_SERVER['PHP_SELF'];
        if (strpos($current_path, '/admin/') !== false) {
            header("Location: ../index.php");
        } else if (strpos($current_path, '/users/') !== false) {
            header("Location: ../index.php");
        } else {
            header("Location: index.php");
        }
        exit();
    }
}
?> 