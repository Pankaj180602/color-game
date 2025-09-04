<?php
// File: /api/get_last_result.php
require_once '../auth/db.php';

// Get the SECOND LAST game result
$result = $conn->query("SELECT * FROM game_results ORDER BY id DESC LIMIT 1 OFFSET 1");

if ($result && $result->num_rows > 0) {
    $last = $result->fetch_assoc();
    echo json_encode([
        'color' => $last['winning_color'],
        'number' => $last['winning_number'],
        'time' => $last['round_time']
    ]);
} else {
    echo json_encode([
        'color' => '-',
        'number' => '-',
        'time' => '-'
    ]);
}
$conn->close();
?>
