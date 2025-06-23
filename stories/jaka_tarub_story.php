<?php
session_start();
include '../koneksi/koneksi.php';

// Optional: Get user info if logged in
$is_logged_in = isset($_SESSION['username']);
if ($is_logged_in) {
    $username = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: #2E8B57;
            margin: 0;
            font-family: "Inter", Arial, sans-serif;
        }
        .story-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 20px;
            background: #FFF3E0;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .story-title {
            color: #000;
            font-size: 2rem;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }
        .story-content {
            line-height: 1.6;
            color: #333;
        }

        .story-section {
            margin-bottom: 2rem;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .language-label {
            font-weight: bold;
            color: #4a90e2;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .vocabulary-item {
            display: flex;
            margin-bottom: 0.5rem;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        
        .vocabulary-java {
            font-weight: 600;
            min-width: 200px;
        }
        
        .vocabulary-english {
            color: #666;
        }

        
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #357abd;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="../index.php" class="logo">Learn Java by Stories</a>
            <div class="nav-menu">
                <a href="../users/beginner_stories.php" class="menu-link">BEGINNER</a>
                <a href="../users/intermediate_stories.php" class="menu-link">INTERMEDIATE</a>
                <a href="#" class="menu-link">UPGRADE</a>
                <a href="../practice.php" class="menu-link">PRACTICE</a>
            </div>
        </div>
        <div class="navbar-right">
            <div class="user-icon-tooltip">
                <i class="fas fa-user" id="openLogin"></i>
            </div>
            <button class="toggle"><span class="moon"></span> Light</button>
        </div>
    </nav>

    <div class="story-container">
        <a href="../users/intermediate_stories.php" class="back-btn">← Kembali ke Cerita</a>
        <h1 class="story-title">Jaka Tarub</h1>
        <img src="../img/jaka_tarub.png" alt="Jaka Tarub Story">
        <div class="story-content">
            <p>Kisah legendaris tentang putri ayu sing dadi candhi, ngandharake perjuangan antarane kekuatan lan kecerdikan.</p>
            <!-- Add full story content here -->
        </div>

        <div class="story-content">
            <div class="story-section">
                <div class="language-label">Basa Jawa (Javanese)</div>
                <p>
                Nalika jaman mbiyen, ana sawijining pemuda jenenge Jaka Tarub. Dheweke anak nom-noman kang urip ing sawijining desa cilik, cedhak alas. 
                Saben dina, Jaka Tarub kerep mlebu alas golek kayu bakar lan woh-wohan kanggo kebutuhan saben dinane.
                </p>
                <p>
                Ing sawijining dina, nalika mlaku-mlaku ing alas, Jaka Tarub ndeleng sendang kang bening lan sepi. 
                Nalika kuwi, ana pitu bidadari sing padha mudhun saka swarga lan padha adus ing sendang iku. 
                Selendang bidadari iku didadekake piranti supaya bisa bali maneh menyang kayangan.
                </p>
                <p>
                Jaka Tarub banjur ndhelik lan nyolong salah siji selendang bidadari mau. 
                Sawisé rampung adus, bidadari-bidadari mau padha nganggo selendang lan bali menyang kayangan, nanging siji ora bisa bali amarga selendangé ilang. 
                Bidadari kuwi jenenge Nawang Wulan.
                </p>
                <p>
                Jaka Tarub banjur metu saka panggonan ndheliké lan pura-pura nulungi Nawang Wulan. 
                Awit ora bisa bali menyang kayangan, Nawang Wulan banjur manut urip bebarengan karo Jaka Tarub lan pungkasané padha rabi. 
                Saka rabi kuwi, padha duwé anak siji.
                </p>
                <p>
                Nalika urip bebarengan, Nawang Wulan duwe kasekten. Dheweke masak sega mung nganggo sak genggem beras, nanging bisa dadi akeh. 
                Nanging Jaka Tarub penasaran lan mbukak wadah beras, lan ambruk rahasia kasekten Nawang Wulan. 
                Amarga kasektené ilang, wiwit semana Nawang Wulan kudu masak kaya manungsa lumrah, nggunakake beras luwih akeh.
                </p>
                <p>
                Sawijining dina, Nawang Wulan nemokake selendangé maneh. Kanthi atine sedhih, dheweke pamit marang Jaka Tarub lan anaké kanggo bali maneh menyang kayangan. 
                Jaka Tarub lan anaké mung bisa pasrah lan ngenteni kanthi rasa kangen.
                </p>
            </div>

            <div class="story-section">
                <div class="language-label">Indonesian Translation</div>
                <p>
                Pada zaman dahulu, hiduplah seorang pemuda bernama Jaka Tarub. 
                Ia adalah seorang pemuda yang tinggal di sebuah desa kecil dekat hutan. 
                Setiap hari, Jaka Tarub sering masuk ke dalam hutan untuk mencari kayu bakar dan buah-buahan sebagai kebutuhan sehari-harinya.
                </p>
                <p>
                Suatu hari, saat berjalan-jalan di hutan, Jaka Tarub melihat sebuah telaga yang bening dan sepi. 
                Saat itu, ada tujuh bidadari yang turun dari langit dan sedang mandi di telaga tersebut. 
                Selendang para bidadari itu digunakan sebagai alat agar mereka bisa kembali ke kayangan.
                </p>
                <p>
                Jaka Tarub lalu bersembunyi dan mencuri salah satu selendang bidadari tersebut. 
                Setelah selesai mandi, para bidadari mengenakan kembali selendangnya dan terbang kembali ke kayangan, kecuali satu bidadari yang tidak bisa kembali karena selendangnya hilang. 
                Bidadari itu bernama Nawang Wulan.
                </p>
                <p>
                Jaka Tarub kemudian keluar dari persembunyiannya dan pura-pura menolong Nawang Wulan. 
                Karena tidak bisa kembali ke kayangan, Nawang Wulan akhirnya mau hidup bersama Jaka Tarub dan mereka pun menikah. 
                Dari pernikahan itu, mereka dikaruniai seorang anak.
                </p>
                <p>
                Selama hidup bersama, Nawang Wulan memiliki kesaktian. 
                Ia bisa memasak nasi hanya dengan segenggam beras, namun hasilnya cukup banyak. 
                Namun, Jaka Tarub merasa penasaran dan membuka wadah beras itu, sehingga rahasia kesaktian Nawang Wulan terbongkar. 
                Karena kesaktiannya hilang, sejak saat itu Nawang Wulan harus memasak seperti manusia biasa, menggunakan beras lebih banyak.
                </p>
                <p>
                Suatu hari, Nawang Wulan menemukan kembali selendangnya. 
                Dengan hati sedih, ia berpamitan kepada Jaka Tarub dan anaknya untuk kembali ke kayangan. 
                Jaka Tarub dan anaknya hanya bisa pasrah dan menunggu dengan penuh rasa rindu.
                </p>
            </div>

            <div class="story-section">
                <div class="language-label">Vocabulary</div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">tes aja</span>
                    <span class="vocabulary-english">just a test</span>
                </div>
                <div class="vocabulary-item">
                    <span class="vocabulary-java">putri aja</span>
                    <span class="vocabulary-english">just putri</span>
                </div>
                <div class="vocabulary-java"></div>
                    <!--lanjut sampai finish-->
                </div>
            </div>
        </div>
    </div>

    <script>
        //toggle dark/light mode
        const toggleBtn = document.querySelector('.toggle');
        const body = document.body;

        toggleBtn.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                toggleBtn.innerHTML = '<span class="sun"></span> Dark';
            } else {
                toggleBtn.innerHTML = '<span class="moon"></span> Light';
            }
        });
    </script>
</body>
</html>
