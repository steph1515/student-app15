<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$answer = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num1 = $_POST["num1"];
    $num2 = $_POST["num2"];
    $operation = isset($_POST["op"]) ? $_POST["op"] : null;

    switch ($operation) {
        case "+":
            $answer = $num1 + $num2;
            break;
        case "-":
            $answer = $num1 - $num2;
            break;
        case "*":
            $answer = $num1 * $num2;
            break;
        case "/":
            if ($num2 == 0) {
                $answer = "Can't divide by zero.";
            } else {
                $answer = $num1 / $num2;
            }
            break;
        default:
            $answer = "No operation selected.";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CALCULATOR</title>
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
        <h3 align=center>Calculator</h3>
        <div class="calculator-box">
            <img src="calcuicon.png" width="75px" alt="">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                Number 1:
                <input type="number" name="num1" value=""> <br><br>
                Number 2:
                <input type="number" name="num2" value=""> <br><br>

                OPERATION: <br><br>
                <div class="operation">
                    <input type="radio" name="op" value="+"> ADD
                    <input type="radio" name="op" value="-"> SUBTRACT
                    <input type="radio" name="op" value="*"> MULTIPLY
                    <input type="radio" name="op" value="/"> DIVIDE <br><br>
                </div><br>
                <input type="submit" value="CALCULATE" class="submit"><br><br><br>
            </form>
            <?php if ($answer !== ""): ?>
                <div class="answer">
                    <b><?php echo $num1 . " " . $operation . " " . $num2 . " = " . "<i>" . htmlspecialchars($answer) . "</i>"; ?></b>
                </div><br><br>
            <?php endif; ?>
            <div align="center">
</div>
        </div>
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
