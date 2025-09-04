<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';
include '../email/send_verification.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST["username"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Generate unique token
    $token = bin2hex(random_bytes(16));

    // Save token in database
    $sql = "INSERT INTO users (username, email, password, balance, is_verified, verify_token)
            VALUES ('$username', '$email', '$password', 0, 0, '$token')";

    if ($conn->query($sql) === TRUE) {
        if (send_verification_email($email, $username, $token)) {
            echo "✅ Registration successful. Check your email for verification link.";
        } else {
            echo "❌ Registration successful but failed to send verification email.";
        }
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-container">
    <h2>Register</h2>
    <form method="POST" action="register.php">
        <input name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
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