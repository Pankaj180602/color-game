<?php
include_once 'auth/db.php'; // make sure path is correct

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $token = $conn->real_escape_string($token);

    $sql = "SELECT * FROM users WHERE verify_token = '$token'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $update = "UPDATE users SET is_verified = 1, verify_token = NULL WHERE verify_token = '$token'";
        if ($conn->query($update) === TRUE) {
            echo "✅ Email verified successfully. You can now login.";
        } else {
            echo "❌ Failed to update user.";
        }
    } else {
        echo "❌ Invalid or expired verification token.";
    }
} else {
    echo "❌ No token provided.";
}
?>
