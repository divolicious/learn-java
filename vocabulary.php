<?php
session_start();
include 'koneksi/koneksi.php';

// Array pertanyaan kuis
$questions = [
    [
        'question' => 'Apa arti dari kata "Matur Nuwun"?',
        'options' => [
            'Terima kasih',
            'Selamat pagi',
            'Silakan',
            'Permisi'
        ],
        'correct' => 0,
        'explanation' => '"Matur Nuwun" adalah ungkapan terima kasih dalam bahasa Jawa.'
    ],
    [
        'question' => 'Kata "Mangga" dalam bahasa Jawa berarti...',
        'options' => [
            'Buah mangga',
            'Silakan',
            'Sudah',
            'Tidak'
        ],
        'correct' => 1,
        'explanation' => '"Mangga" dalam bahasa Jawa berarti "Silakan", bukan merujuk pada buah mangga.'
    ],
    [
        'question' => 'Arti dari "Sugeng Enjing" adalah...',
        'options' => [
            'Selamat malam',
            'Selamat siang',
            'Selamat pagi',
            'Selamat sore'
        ],
        'correct' => 2,
        'explanation' => '"Sugeng Enjing" adalah ucapan selamat pagi dalam bahasa Jawa.'
    ],
    [
        'question' => 'Kata "Sampun" dalam bahasa Jawa artinya...',
        'options' => [
            'Belum',
            'Sedang',
            'Akan',
            'Sudah'
        ],
        'correct' => 3,
        'explanation' => '"Sampun" berarti "Sudah" dalam bahasa Jawa.'
    ],
    [
        'question' => 'Apa arti dari kata "Inggih"?',
        'options' => [
            'Ya',
            'Tidak',
            'Mungkin',
            'Bisa jadi'
        ],
        'correct' => 0,
        'explanation' => '"Inggih" adalah kata formal untuk mengatakan "Ya" dalam bahasa Jawa.'
    ]
];

// Inisialisasi atau ambil skor dari session
if (!isset($_SESSION['quiz_score'])) {
    $_SESSION['quiz_score'] = 0;
    $_SESSION['current_question'] = 0;
    $_SESSION['questions_answered'] = [];
}

// Proses jawaban
if (isset($_POST['answer'])) {
    $current = $_SESSION['current_question'];
    $selected_answer = (int)$_POST['answer'];
    
    // Pastikan jawaban hanya dihitung sekali per pertanyaan
    if (!isset($_SESSION['questions_answered'][$current])) {
        if ($selected_answer === $questions[$current]['correct']) {
            $_SESSION['quiz_score']++;
        }
        
        $_SESSION['questions_answered'][$current] = [
            'selected' => $selected_answer,
            'correct' => $questions[$current]['correct']
        ];
    }
    
    $_SESSION['current_question']++;

    // Jika ini adalah pertanyaan pertama, mulai menghitung waktu
    if ($current == 0) {
        $_SESSION['quiz_start_time'] = time();
    }

    // Jika sudah selesai quiz, simpan hasilnya ke database
    if ($_SESSION['current_question'] >= count($questions) && isset($_SESSION['id_user'])) {
        $user_id = $_SESSION['id_user'];
        $score = $_SESSION['quiz_score'];
        $total_questions = count($questions);
        $time_taken = time() - $_SESSION['quiz_start_time'];
        
        $save_query = "INSERT INTO vocabulary_quiz_history (user_id, score, total_questions, time_taken) 
                      VALUES ($user_id, $score, $total_questions, $time_taken)";
        mysqli_query($koneksi, $save_query);
    }
}

// Reset kuis
if (isset($_POST['restart'])) {
    $_SESSION['quiz_score'] = 0;
    $_SESSION['current_question'] = 0;
    $_SESSION['questions_answered'] = [];
    unset($_SESSION['quiz_start_time']);
}

// Pastikan skor tidak melebihi jumlah pertanyaan
if ($_SESSION['quiz_score'] > count($questions)) {
    $_SESSION['quiz_score'] = count($questions);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocabulary Quiz - Learn Java by Stories</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .quiz-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 20px;
        }

        .quiz-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .quiz-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .quiz-progress {
            background: #f0f0f0;
            height: 10px;
            border-radius: 5px;
            margin: 1rem 0;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: #4a90e2;
            transition: width 0.3s ease;
        }

        .question-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .question-text {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .options-list {
            display: grid;
            gap: 1rem;
        }

        .option-btn {
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background: none;
            cursor: pointer;
            font-size: 1rem;
            text-align: left;
            transition: all 0.3s ease;
        }

        .option-btn:hover {
            background: #f5f5f5;
            border-color: #4a90e2;
        }

        .option-btn.correct {
            background: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }

        .option-btn.incorrect {
            background: #f44336;
            color: white;
            border-color: #f44336;
        }

        .explanation {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            color: #666;
        }

        .result-card {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .score {
            font-size: 3rem;
            color: #4a90e2;
            margin: 1rem 0;
        }

        .restart-btn {
            background: #4a90e2;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        .restart-btn:hover {
            background: #357abd;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="index.php" class="logo">Learn Java by Stories</a>
            <div class="nav-menu">
                <a href="users/beginner_stories.php" class="menu-link">BEGINNER</a>
                <a href="users/intermediate_stories.php" class="menu-link">INTERMEDIATE</a>
                <a href="#" class="menu-link">UPGRADE</a>
                <a href="practice.php" class="menu-link">PRACTICE</a>
            </div>
        </div>
        <div class="navbar-right">
            <button class="toggle"><span class="moon"></span> Light</button>
        </div>
    </nav>

    <div class="quiz-container">
        <div class="quiz-header">
            <h1 class="quiz-title">Kuis Kosakata Bahasa Jawa</h1>
            <div class="quiz-progress">
                <div class="progress-bar" style="width: <?php echo ($_SESSION['current_question'] / count($questions)) * 100; ?>%"></div>
            </div>
            <p>Pertanyaan <?php echo $_SESSION['current_question'] + 1; ?> dari <?php echo count($questions); ?></p>
        </div>

        <?php if ($_SESSION['current_question'] < count($questions)): ?>
            <!-- Tampilkan pertanyaan -->
            <div class="question-card">
                <p class="question-text"><?php echo $questions[$_SESSION['current_question']]['question']; ?></p>
                <form method="post" class="options-list">
                    <?php foreach ($questions[$_SESSION['current_question']]['options'] as $index => $option): ?>
                        <button type="submit" name="answer" value="<?php echo $index; ?>" class="option-btn">
                            <?php echo $option; ?>
                        </button>
                    <?php endforeach; ?>
                </form>
            </div>
        <?php else: ?>
            <!-- Tampilkan hasil -->
            <div class="result-card">
                <h2>Kuis Selesai!</h2>
                <?php if ($_SESSION['quiz_score'] == count($questions)): ?>
                    <h3 style="color: #4CAF50; margin: 1rem 0;">Benar Semua!</h3>
                <?php else: ?>
                    <h3 style="color: #4a90e2; margin: 1rem 0;">Benar <?php echo $_SESSION['quiz_score']; ?> dari <?php echo count($questions); ?></h3>
                <?php endif; ?>
                
                <!-- Tampilkan review jawaban -->
                <?php foreach ($_SESSION['questions_answered'] as $index => $answer): ?>
                    <div class="question-review">
                        <p><strong>Pertanyaan <?php echo $index + 1; ?>:</strong> <?php echo $questions[$index]['question']; ?></p>
                        <p>Jawaban Anda: <?php echo $questions[$index]['options'][$answer['selected']]; ?></p>
                        <p>Jawaban Benar: <?php echo $questions[$index]['options'][$answer['correct']]; ?></p>
                        <div class="explanation">
                            <i class="fas fa-info-circle"></i> <?php echo $questions[$index]['explanation']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center;">
                    <form method="post" style="margin: 0;">
                        <button type="submit" name="restart" class="restart-btn">Mulai Ulang Kuis</button>
                    </form>
                    <a href="practice.php" class="restart-btn" style="background: #666;">Kembali ke Practice</a>
                    <?php if (isset($_SESSION['id_user'])): ?>
                    <a href="users/progress_practice.php" class="restart-btn" style="background: #2196F3;">Lihat Progress</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Light/Dark mode toggle
        const toggleBtn = document.querySelector('.toggle');
        const body = document.body;
        let isLight = true;

        function setToggle() {
            if (isLight) {
                toggleBtn.innerHTML = `<svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='#FDB813' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><circle cx='12' cy='12' r='5'/><path d='M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42'/></svg> Light`;
            } else {
                toggleBtn.innerHTML = `<svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='#fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z'/></svg> Dark`;
            }
        }

        setToggle();
        toggleBtn.addEventListener('click', function() {
            isLight = !isLight;
            if (isLight) {
                body.classList.remove('dark-mode');
            } else {
                body.classList.add('dark-mode');
            }
            setToggle();
        });
    </script>
</body>
</html> 