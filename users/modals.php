<!-- Modal Login -->
<div class="modal" id="modalLogin">
    <div class="modal-content">
        <span class="close" data-modal="modalLogin">&times;</span>
        <h2>Login</h2>
        <form action="konfirmasi.php" method="post">
            <?php if(!empty($_GET['gagal'])){?>
            <?php if($_GET['gagal']=="userKosong"){?>
            <span class="text-danger">
                Maaf Username Tidak Boleh Kosong
            </span>
            <?php } else if($_GET['gagal']=="passKosong"){?>
            <span class="text-danger">
                Maaf Password Tidak Boleh Kosong
            </span>
            <?php } else {?>
            <span class="text-danger">
                Maaf Username dan Password Anda Salah
            </span>
            <?php }?>
            <?php }?>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Username" name="username" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" class="form-control" placeholder="Password" name="password" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <button class="login-btn" type="submit">Login</button>
        </form>
        <p class="modal-switch">Don't have an account? <a href="#" id="switchToRegister">Register</a></p>
    </div>
</div>

<!-- Modal Register -->
<div class="modal" id="modalRegister">
    <div class="modal-content">
        <span class="close" data-modal="modalRegister">&times;</span>
        <h2>Register</h2>
        <form action="index.php" method="post">
            <p style="font-weight: 40; margin-bottom: 20px;">Silahkan isi form dibawah ini untuk membuat akun baru</p>
            <div class="input-group mb-3">
                <input type="text" name="nama" placeholder="Nama Lengkap" required>
            </div>
            <div class="input-group mb-3">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group mb-3">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group mb-3">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="login-btn">Register</button>
        </form>
        <p class="modal-switch">Already have an account? <a href="#" id="switchToLogin">Login</a></p>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100vw;
    height: 100vh;
    overflow: auto;
    background: rgba(30, 41, 59, 0.55);
    transition: background 0.2s;
}

.modal-content {
    background: #fff;
    color: #232d3e;
    margin: 80px auto;
    padding: 32px 28px 24px 28px;
    border-radius: 12px;
    max-width: 350px;
    box-shadow: 0 4px 32px 0 rgba(0, 0, 0, 0.18);
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    animation: modalIn 0.2s;
}

@keyframes modalIn {
    from {
        transform: translateY(-40px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.close {
    position: absolute;
    top: 16px;
    right: 18px;
    font-size: 1.5rem;
    color: #7ab6e2;
    cursor: pointer;
    font-weight: bold;
    transition: color 0.2s;
}

.close:hover {
    color: #f87171;
}

.modal-content h2 {
    font-family: "Playfair Display", serif;
    font-size: 1.5rem;
    color: #2c2c2c;
    margin-bottom: 18px;
    text-align: center;
}

.modal-content form {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.input-group {
    display: flex;
    align-items: stretch;
    margin-bottom: 16px;
    background: #f3f6fa;
    border-radius: 7px;
    border: 1px solid #a0d3f9;
    overflow: hidden;
}

.input-group input[type="text"],
.input-group input[type="email"],
.input-group input[type="password"] {
    border: none;
    background: transparent;
    padding: 10px 12px;
    font-size: 1rem;
    flex: 1 1 auto;
    outline: none;
}

.input-group input:focus {
    background: #eaf6ff;
}

.input-group-append {
    display: flex;
    align-items: center;
    background: #e0e7ef;
    padding: 0 10px;
    border-left: 1px solid #a0d3f9;
}

.input-group-text {
    color: #1769ff;
    font-size: 1.1rem;
}

.login-btn {
    background: #1769ff;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 12px 0;
    font-size: 1.08rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 8px;
    transition: background 0.2s;
    width: 100%;
}

.login-btn:hover {
    background: #1257cc;
}

.text-danger {
    color: #f87171;
    font-size: 0.98rem;
    margin-bottom: 10px;
    display: block;
    text-align: center;
}

.modal-content p {
    margin-bottom: 18px;
    text-align: center;
    color: #232d3e;
}

.modal-switch {
    text-align: center;
    margin-top: 16px;
    font-size: 0.98rem;
}

.modal-switch a {
    color: #1a4562;
    text-decoration: none;
    cursor: pointer;
}

@media (max-width: 500px) {
    .modal-content {
        max-width: 95vw;
        padding: 18px 4vw 18px 4vw;
    }
}

/* Dark mode styles */
body.dark-mode .modal-content {
    background-color: #333;
    color: #fff;
}

body.dark-mode .input-group {
    background: #444;
    border-color: #555;
}

body.dark-mode .input-group input {
    color: #fff;
}

body.dark-mode .input-group-append {
    background: #555;
    border-color: #666;
}

body.dark-mode .modal-content h2 {
    color: #fff;
}

body.dark-mode .modal-switch a {
    color: #7ab6e2;
}

body.dark-mode .text-danger {
    color: #f87171;
}
</style>

<div class="user-menu">
    <a href="users/profile.php" class="menu-item"><i class="fas fa-user-edit"></i> Edit Profile</a>
    <a href="users/add_story.php" class="menu-item"><i class="fas fa-plus"></i> Add Story</a>
    <a href="users/my_stories.php" class="menu-item"><i class="fas fa-book"></i> My Stories</a>
    <a href="users/progress.php" class="menu-item"><i class="fas fa-chart-line"></i> Story Progress</a>
    <a href="users/progress_practice.php" class="menu-item"><i class="fas fa-tasks"></i> Practice Progress</a>
    <a href="logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div> 