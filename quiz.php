<?php
session_start();
include 'connect.php';
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$questions = [
    ['quest' => '1. Who is the first computer programmer?','choices' => ['Ada Lovelace', 'Lord Byron', 'Charles Babbage', 'Archimedes'],'answer' => 0],
    ['quest' => '2. What device uses IP addresses to decide where to send information?','choices' => ['Scanner', 'Camera', 'Router', 'Printer'],'answer' => 2],
    ['quest' => '3. What does HTML stand for?','choices' => ['HyperText Management Language', 'HyperText Markup Language','HyperText Model Language', 'Hyperlink Text Markup Language'],'answer' => 1],
    ['quest' => '4. What is the purpose of an algorithm in computer science?','choices' => [' To create hardware components', 'To store data permanently', 'To manage network traffic', 'To provide a step-by-step procedure for solving a problem'],'answer' => 3],
    ['quest' => '5. What is "Cloud Computing"?','choices' => ['Computing using physical hardware only', 'Computing that only happens in the local network', 'Computing services delivered over the internet', 'Computing done by a single computer'],'answer' => 2],
    ['quest' => '6. Which protocol is used to securely transmit data over the internet?','choices' => ['HTTPS', 'HTTP', 'SMTP', 'FTP'],'answer' => 0],
    ['quest' => '7. What does SQL stand for?','choices' => ['Simple Query Language', 'Structured Question Language', 'Structured Query Language', 'Sequential Query Language'],'answer' => 2],
    ['quest' => '8. What is a "bug" in computer programming?','choices' => ['A feature that enhances performance', 'An error or flaw in software code', 'A type of hardware malfunction', 'A term for high-level programming languages'],'answer' => 1],
    ['quest' => 'Which programming language is known for its use in web development and has a syntax similar to C++?','choices' => ['Python', 'Ruby', 'Java', 'JavaScript'],'answer' => 3],
    ['quest' => '10. What does CPU stand for?','choices' => ['Central Performance Unit', 'Central Power Unit', 'Central Processing Unit', 'Central Portal Unit'],'answer' => 2]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userAnswers = $_POST['answers'] ?? [];
    $totalQuestions = count($questions);
    $correctAnswers = 0;
    $allAnswer = true;

    foreach ($questions as $index => $question) {
        if (!isset($userAnswers[$index])) {
            $allAnswer = false;
            break;
        }
    }

    if (!$allAnswer) {
        $_SESSION['error_message'] = 'Please answer all the questions.';
        $_SESSION['userAnswers'] = $userAnswers;
        header("Location: quiz.php");
        exit();
    } else {
        foreach ($questions as $index => $question) {
            if ($userAnswers[$index] == $question['answer']) {
                $correctAnswers++;
            }
        }

        $_SESSION['score'] = $correctAnswers;
        $_SESSION['totalQuestions'] = $totalQuestions;
        $loggedUsername = $_SESSION['username'];

        $escapedUsername = $conn->real_escape_string($loggedUsername);
        $escapedScore = (int) $correctAnswers;
        $escapedTotalQuestions = (int) $totalQuestions;
        $sql = "INSERT INTO results (username, score, total_quest) 
                VALUES ('$escapedUsername', $escapedScore, $escapedTotalQuestions)";

        if ($conn->query($sql) === TRUE) {
        unset($_SESSION['userAnswers']);
        header("Location: results.php");
        exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUIZ - Multiple Choice</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

    <div class="sidebar hidden" id="sidebar">
        <a href="home.php"><i class="fas fa-home"></i><span>&nbsp; Home</span></a>
        <a href="calcu.php"><i class="fas fa-calculator"></i><span>&nbsp; Calculator</span></a>
        <a href="quiz.php"><i class="fas fa-brain"></i><span>&nbsp; Quiz #1</span></a>
        <a href="quiz2.php"><i class="fas fa-question-circle"></i><span>&nbsp; Quiz #2</span></a>
        <a href="timer.php"><i class="fas fa-stopwatch"></i><span>&nbsp; Timer </span></a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>&nbsp; Logout</span></a>
    </div>

    <div class="content sidebar-hidden" id="content">
        <div class="nav-bar">
            <h2>STUDENT APP</h2>
            <a href="#" onclick="toggleProfileDropdown(event)" class="profile-icon"><i class="fas fa-user"></i></a>
                <div class="profile-dropdown" id="profile-dropdown">
                    <span>User: <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
                </div>
        </div>
        <h3>Take a Quiz!</h3>
        <div align="center">
</div>
        <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; text-align:center; font-size: large;"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
        <?php unset($_SESSION['error_message']); endif; ?>
        
        <form action="quiz.php" method="post">
            <?php foreach ($questions as $index => $question): ?>
            <div class="questbox">
                <?php echo htmlspecialchars($question['quest']); ?>
                <?php foreach ($question['choices'] as $choice_index => $choice): ?>
                <label for="choices">
                    <input type="radio" name="answers[<?php echo $index; ?>]" value="<?php echo $choice_index; ?>"
                        <?php echo (isset($_SESSION['userAnswers'][$index]) && $_SESSION['userAnswers'][$index] == $choice_index) ? 'checked' : ''; ?>>
                    <?php echo htmlspecialchars($choice); ?>
                </label><br>
                <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <input type="submit" value="SUBMIT" class="submit quiz">
        </form>

    </div>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            var content = document.getElementById("content");
            if (sidebar.classList.contains("hidden")) {
                sidebar.classList.remove("hidden");
                content.classList.remove("sidebar-hidden");
            } else {
                sidebar.classList.add("hidden");
                content.classList.add("sidebar-hidden");
            }
        }

        function toggleProfileDropdown(event) {
            event.preventDefault();
            var dropdown = document.getElementById("profile-dropdown");
            dropdown.classList.toggle("active");
        }

        document.addEventListener('click', function(event) {
            var dropdown = document.getElementById("profile-dropdown");
            var profileIcon = document.querySelector(".profile-icon");
            if (!profileIcon.contains(event.target)) {
                dropdown.classList.remove("active");
            }
        });
    </script>
</body>
</html>
