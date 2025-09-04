<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'color_game');
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM transactions WHERE user_id = $user_id ORDER BY created_at DESC");
echo "<table><tr><th>Type</th><th>Amount</th><th>Date</th></tr>";
while($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['type']}</td><td>{$row['amount']}</td><td>{$row['created_at']}</td></tr>";
}
echo "</table>";
?>
