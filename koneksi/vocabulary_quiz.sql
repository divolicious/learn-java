-- Tabel untuk menyimpan history quiz vocabulary
CREATE TABLE vocabulary_quiz_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quiz_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    score INT NOT NULL,
    total_questions INT NOT NULL,
    time_taken INT NOT NULL, -- dalam detik
    FOREIGN KEY (user_id) REFERENCES users(id_user)
); 