<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'color_game');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("SELECT id, password, is_verified FROM users WHERE username = ?");
    $stmt->bind_param("s", $_POST['username']);
    $stmt->execute();
    $stmt->bind_result($id, $hash, $is_verified);

    if ($stmt->fetch()) {
        if ($is_verified != 1) {
            echo "Please verify your email before logging in.";
        } elseif (password_verify($_POST['password'], $hash)) {
            $_SESSION['user_id'] = $id;
            header("Location: ../admin/dashboard.php");
            exit();
        } else {
            echo "Invalid credentials.";
        }
    } else {
        echo "Invalid credentials.";
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-container">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>

<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f2f2f2;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.auth-container {
    background: #fff;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    width: 90%;
    max-width: 500px;
    text-align: center;
}

.auth-container h2 {
    margin-bottom: 25px;
    color: #333;
}

.auth-container input {
    width: 100%;
    padding: 12px;
    margin-bottom: 18px;
    border: 1px solid #ccc;
    border-radius: 12px;
    font-size: 16px;
}

.auth-container button {
    width: 100%;
    padding: 14px;
    background-color: #3f51b5;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
}

.auth-container button:hover {
    background-color: #2c3e9f;
}

.auth-container p {
    margin-top: 20px;
    font-size: 14px;
}

.auth-container a {
    color: #3f51b5;
    text-decoration: none;
    font-weight: bold;
}
</style>