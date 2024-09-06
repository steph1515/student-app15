<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
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
        <div class="homepage">
    <img src="https://manycam.com/blog/wp-content/uploads/2020/08/Blog-online-education-background-ideas.png" alt="" width=100%>
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
