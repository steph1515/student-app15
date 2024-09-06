<?php
session_start();
include 'connect.php'; 

$username = $password = "";
$usernameErr = $passwordErr = "";

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

        $query = "SELECT username FROM users WHERE username = '$username'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $usernameErr = "Username already exists.";
        } else {
            $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
            if ($conn->query($query)) {
                $_SESSION["success_message"] = "Registered successfully!";
                header("Location: index.php");
                exit();
            } else {
                $usernameErr = "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>REGISTER</title>
</head>
<body>
<div class="login-container">
        <div class="login-box">
            <fieldset>
            <h2 class="login-header">REGISTER</h2> 
                <legend>
                    <img src="profile.png" alt="Login Image">
                </legend>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" placeholder="username" name="username" value="<?php echo $username; ?>">
                <span class="error"> <?php echo $usernameErr; ?></span> <br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" placeholder="password" name="password" value="<?php echo $password; ?>">
                <p class="error"> <?php echo $passwordErr; ?></span> <br><br>

                <input type="submit" value="Register">
            </form>
            <p class="register-link"><a href="index.php">Login here</a></p>
            </fieldset>
        </div>
        
    </div>
    
</body>
</html>
