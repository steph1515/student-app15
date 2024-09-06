<?php
session_start();
include 'connect.php'; 

$username = $password = "";
$usernameErr = $passwordErr = $loginErr = "";

$successMessage = "";
if (isset($_SESSION["success_message"])) {
    $successMessage = $_SESSION["success_message"];
    unset($_SESSION["success_message"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = htmlspecialchars($_POST["username"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = htmlspecialchars($_POST["password"]);
    }

    if (empty($usernameErr) && empty($passwordErr)) {
        $username = $conn->real_escape_string($username);
        $password = $conn->real_escape_string($password);

        $query = "SELECT password FROM users WHERE username = '$username'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $savePassword = $row['password'];
            if ($password === $savePassword) {
                $_SESSION["username"] = $username;
                header("Location: home.php");
                exit();
            } else {
                $loginErr = "Invalid username or password.";
            }
        } else {
            $loginErr = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOG IN</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <fieldset>
            <h2 class="login-header">LOG IN</h2>
                <legend>
                    <img src="profile.png" alt="Login Image">
                </legend>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <?php if ($successMessage): ?>
                        <p class="success-message"><?php echo $successMessage; ?></p>
                    <?php endif; ?>

                    <label for="username">Username:</label>
                    <input type="text" id="username" placeholder="username" name="username" value="<?php echo $username; ?>">
                    <span class="error"> <?php echo $usernameErr; ?></span> <br><br>

                    <label for="password">Password:</label>
                    <input type="password" id="password" placeholder="password" name="password" value="<?php echo $password; ?>">
                    <span class="error"> <?php echo $passwordErr; ?></span> <br><br>

                    <span class="error"><?php echo $loginErr; ?></span><br><br>

                    <input type="submit" value="Login">
                </form>
                <p class="register-link"><a href="register.php">Register here</a></p>
            </fieldset>
        </div>
    </div>
</body>
</html>