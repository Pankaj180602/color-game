<?php
$conn = new mysqli('localhost', 'root', '', 'color_game');
$color = ['Red', 'Green', 'Violet'][rand(0, 2)];
$bets = $conn->query("SELECT * FROM bets WHERE game_result IS NULL");
while ($b = $bets->fetch_assoc()) {
    $result = $b['color'] === $color ? 'Win' : 'Lose';
    $user_id = $b['user_id'];
    $amount = $b['amount'];
    if ($result === 'Win') {
        $conn->query("UPDATE users SET balance = balance + ($amount * 2) WHERE id = $user_id");
    }
    $conn->query("INSERT INTO transactions(user_id, type, amount) VALUES ($user_id, '$result', $amount)");
    $conn->query("UPDATE bets SET result = '$result' WHERE id = {$b['id']}");
}
echo "Resolved with result: $color";
?>
