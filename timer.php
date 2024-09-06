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
    <title>Timer</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

    <div class="container">
        <div class="wrapper">
            <p>
                <span class="minutes">00</span>:
                <span class="seconds">00</span>:
                <span class="tens">00</span>
            </p><br><br>
            <button class="btn-start btn">Start</button>
            <button class="btn-pause btn">Pause</button>
            <button class="btn-reset btn">Reset</button>
            <button class="btn-resume btn">Continue</button>
        </div>
    </div>

    <script>
        let minutes = 00;
        let seconds = 00;
        let tens = 00;
        let getMins = document.querySelector('.minutes');
        let getSecs = document.querySelector('.seconds');
        let getTens = document.querySelector('.tens');
        let btnStart = document.querySelector('.btn-start');
        let btnPause = document.querySelector('.btn-pause');
        let btnReset = document.querySelector('.btn-reset');
        let btnResume = document.querySelector('.btn-resume');
        let interval;
        let isRunning = false;
        let isPaused = false;

        btnStart.addEventListener('click', () => {
            if (!isRunning) {
                interval = setInterval(startTimer, 10);
                isRunning = true;
            }
        })

        btnPause.addEventListener('click',()=> {
            if (isRunning) { 
                clearInterval(interval);
                isRunning = false;
            }
        })
        btnReset.addEventListener('click',()=> {
            clearInterval(interval);
            tens = '00';
            seconds = '00';
            minutes = '00';
            getSecs.innerHTML = seconds;
            getTens.innerHTML = tens;
            getMins.innerHTML = '00';
            isRunning = false;
        })
        btnResume.addEventListener('click', () => {
            if (!isRunning) {
                interval = setInterval(startTimer, 10);
                isRunning = true;
            }
        });
        function startTimer(){
            tens++;
            if(tens <= 9){
                getTens.innerHTML = '0' + tens;
            }
            if(tens > 9){
                getTens.innerHTML = tens;
            }
            if(tens > 99){
                seconds++;
                getSecs.innerHTML = '0' + seconds;
                tens = 0;
                getTens.innerHTML = '0' + 0;
            }
            if(seconds > 9){
                getSecs.innerHTML = seconds;
            }
            if(seconds >= 60){
                minutes++
                getMins.innerHTML = '0' + minutes;
                seconds = 0;
                getSecs.innerHTML = '00';
            }
            if(minutes > 9){
                getMins.innerHTML = minutes;
            }
        }

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