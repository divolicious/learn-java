<?php
session_start();
include '../koneksi/koneksi.php';

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['username']);
if (!$is_logged_in) {
    header("Location: ../users/beginner_stories.php");
    exit();
}

$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

// Array pertanyaan quiz
$questions = [
    [
        'question' => 'Apa yang diucapkan oleh Malin dalam foto tersebut?',
        'options' => ['Opsi A', 'Opsi B', 'Opsi C'],
        'correct_answer' => 'Opsi A'
    ],
    [
        'question' => 'Apa yang makna dari kata yang diucap oleh Malin dalam adegan tersebut?',
        'options' => ['Opsi A', 'Opsi B', 'Opsi C'],
        'correct_answer' => 'Opsi B'
    ],
    [
        'question' => 'Apa yang makna dari kata yang diucap oleh Malin dalam adegan tersebut?',
        'options' => ['Opsi A', 'Opsi B', 'Opsi C'],
        'correct_answer' => 'Opsi C'
    ]
];

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_pages = count($questions);
$score = 0;
$message = '';
$show_results = false;

// Initialize session for storing answers
if (!isset($_SESSION['quiz_answers'])) {
    $_SESSION['quiz_answers'] = array_fill(0, $total_pages, null);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['answer'])) {
        $_SESSION['quiz_answers'][$current_page - 1] = $_POST['answer'];
        
        // If last question, calculate results
        if ($current_page === $total_pages) {
            $score = 0;
            foreach ($questions as $index => $question) {
                if (isset($_SESSION['quiz_answers'][$index]) && 
                    $_SESSION['quiz_answers'][$index] === $question['correct_answer']) {
                    $score++;
                }
            }
            $percentage = ($score / $total_pages) * 100;
            $show_results = true;
            
            if ($percentage >= 80) {
                $message = "Selamat! Anda mendapat nilai $percentage%. Pemahaman Anda tentang cerita Malin Kundang sangat baik!";
            } elseif ($percentage >= 60) {
                $message = "Bagus! Anda mendapat nilai $percentage%. Anda cukup memahami cerita Malin Kundang.";
            } else {
                $message = "Anda mendapat nilai $percentage%. Mungkin Anda perlu membaca kembali cerita Malin Kundang.";
            }
            
            // Clear session after showing results
            unset($_SESSION['quiz_answers']);
        } else {
            // Redirect to next question
            header("Location: malin_quiz.php?page=" . ($current_page + 1));
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Malin Kundang - Learn Java by Stories</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <style>
        .quiz-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: linear-gradient(135deg, #7ab6e2 0%, #a0d3f9 100%);
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .quiz-title {
            text-align: center;
            color: #FFD700;
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-style: italic;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .quiz-subtitle {
            text-align: center;
            color: #ffffff;
            margin-bottom: 3rem;
            font-size: 1.1rem;
            font-family: 'Playfair Display', serif;
        }

        .quiz-content {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .story-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .story-card h3 {
            color: #000;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }

        .story-card .story-title {
            color: #f39c12;
            font-style: italic;
            margin-bottom: 0.5rem;
        }

        .story-card p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .question-card {
            background: #f39c12;
            padding: 1.5rem;
            border-radius: 10px;
            color: #000000;
        }

        .question-card h3 {
            color: #000000;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .options {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .option {
            background: #666;
            color: white;
            padding: 0.8rem 1.2rem;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-align: left;
            width: 100%;
        }

        .option:hover {
            background: #555;
            transform: translateY(-2px);
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: #232d3e;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 5px;
            background-color: #ffffff;
            border: none;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            transform: translateX(-5px);
        }

        .next-btn {
            display: block;
            width: 120px;
            padding: 0.8rem;
            background: #f39c12;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-left: auto;
        }

        .next-btn:hover {
            background: #e67e22;
        }

        .result-message {
            text-align: center;
            padding: 2rem;
            background: #f0f7f0;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .result-message.success {
            background: #c8e6c9;
            color: #2E7D32;
        }

        .result-message.warning {
            background: #fff3e0;
            color: #f57c00;
        }

        .back-btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 1rem;
            transition: background 0.3s ease;
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
            <?php if ($is_logged_in): ?>
            <div class="user-icon-tooltip">
                <a href="../dashboard_user.php" style="color: inherit; text-decoration: none;">
                    <i class="fas fa-user"></i>
                    <span class="tooltip-text">Dashboard</span>
                </a>
            </div>
            <?php else: ?>
            <div class="user-icon-tooltip">
                <i class="fas fa-user" id="openLogin"></i>
                <span class="tooltip-text">Login</span>
            </div>
            <?php endif; ?>
        </div>
    </nav>

    <div class="quiz-container">
        <h1 class="quiz-title">"Story of Malin Kundang"</h1>
        <p class="quiz-subtitle">Mari menguji pemahaman Anda tentang cerita Malin Kundang</p>
        
        <?php if ($show_results): ?>
            <div class="result-message <?php echo $score >= 3 ? 'success' : 'warning'; ?>">
                <h2>Hasil Quiz Anda</h2>
                <p><?php echo $message; ?></p>
                <p>Skor: <?php echo $score; ?> dari <?php echo count($questions); ?></p>
                <a href="../users/beginner_stories.php" class="back-btn">Kembali ke Cerita</a>
            </div>
        <?php else: ?>
            <form method="POST" action="">
                <div class="quiz-content">
                    <div class="story-card">
                        <h3>Class A</h3>
                        <div class="story-title">Story of Malin Kundang</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean leo dolor, viverra congue sodales sagittis, malesuada sit amet nulla.</p>
                    </div>
                    <div class="question-card">
                        <h3>Question <?php echo $current_page; ?> of <?php echo $total_pages; ?></h3>
                        <p><?php echo $questions[$current_page-1]['question']; ?></p>
                        <div class="options">
                            <?php foreach ($questions[$current_page-1]['options'] as $option): ?>
                                <button type="button" class="option" onclick="selectOption(this, '<?php echo $current_page-1; ?>', '<?php echo htmlspecialchars($option); ?>')">
                                    <?php echo $option; ?>
                                </button>
                                <input type="radio" name="answer" value="<?php echo $option; ?>" 
                                    <?php echo (isset($_SESSION['quiz_answers'][$current_page-1]) && $_SESSION['quiz_answers'][$current_page-1] === $option) ? 'checked' : ''; ?>
                                    style="display: none;" required>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="navigation-buttons">
                    <?php if ($current_page > 1): ?>
                        <a href="malin_quiz.php?page=<?php echo $current_page - 1; ?>" class="back-button">
                            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Previous
                        </a>
                    <?php else: ?>
                        <a href="../users/beginner_stories.php" class="back-button">
                            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Kembali ke Cerita
                        </a>
                    <?php endif; ?>
                    
                    <button type="submit" class="next-btn">
                        <?php echo ($current_page == $total_pages) ? 'Submit' : 'Next'; ?>
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script>
        function selectOption(button, questionIndex, value) {
            // Remove active class from all options in the same question
            const questionCard = button.closest('.question-card');
            const options = questionCard.getElementsByClassName('option');
            Array.from(options).forEach(opt => opt.style.background = '#666');
            
            // Add active class to selected option
            button.style.background = '#555';
            
            // Set the radio input value
            const radioInput = button.parentElement.querySelector(`input[name="answers[${questionIndex}]"]`);
            radioInput.value = value;
            radioInput.checked = true;
        }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</body>
</html>
