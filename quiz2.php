<?php
session_start();
include 'connect.php';
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$questions = [
    ['quest' => '1. Who is the first computer programmer?', 'answer' => 'ADA LOVELACE'],
    ['quest' => '2. What does HTTP stand for?','answer' => 'HYPERTEXT TRANSFER PROTOCOL'],
    ['quest' => '3. What does HTML stand for?','answer' => 'HYPERTEXT MARKUP LANGUAGE'],
    ['quest' => '4. What does GUI stand for?','answer' => 'GRAPHICAL USER INTERFACE'],
    ['quest' => '5. What does SQL stand for?','answer' => 'STRUCTURED QUERY LANGUAGE'],
    ['quest' => '6. Who invented the C programming language?','answer' => 'DENNIS RITCHIE'],
    ['quest' => '7. Who developed the concept of "Object-oriented Programming"?','answer' => 'ALAN KAY'],
    ['quest' => '8. What does CSS stand for?','answer' => 'CASCADING STYLE SHEETS'],
    ['quest' => '9. What does DOS stand for in computer terms?','answer' => 'DISK OPERATING SYSTEM'],
    ['quest' => '10. Who is known for the development of the COBOL programming language?','answer' => 'GRACE HOPPER'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userAnswers = $_POST['answers'] ?? [];
    $totalQuestions = count($questions);
    $correctAnswers = 0;
    $allAnswer = true;

    foreach ($questions as $index => $question) {
        if (empty($userAnswers[$index])) {
            $allAnswer = false;
            break;
        }
    }

    if (!$allAnswer) {
        $_SESSION['error_message'] = 'Please answer all the questions.';
        $_SESSION['userAnswers'] = $userAnswers;
        header("Location: quiz2.php");
        exit();
    } else {
        foreach ($questions as $index => $question) {
            if (strcasecmp(trim($userAnswers[$index]), $question['answer']) == 0) {
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
        header("Location: results2.php");
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
    <title>QUIZ - Identification</title>
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
        <p style="color: red; text-align:center; font-size:large;"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
        <?php unset($_SESSION['error_message']); endif; ?>

        <form action="quiz2.php" method="post">
            <?php foreach ($questions as $index => $question): ?>
            <div class="questbox">
                <?php echo htmlspecialchars($question['quest']); ?><br><br>
                <input type="text" name="answers[<?php echo $index; ?>]" size='30' style="text-align: center; border-top: 0; border-right: 0; border-left: 0; background: none; font-size:18px; color: #00536f;" value="<?php echo htmlspecialchars($_SESSION['userAnswers'][$index] ?? ''); ?>"><br>
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
