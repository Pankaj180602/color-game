<?php
// get_current_round.php
include_once __DIR__ . '/../auth/db.php'; 
header('Content-Type: application/json');

$round_duration = 30;

// Get latest round
$res = $conn->query("SELECT * FROM rounds ORDER BY id DESC LIMIT 1");
$latest_round = $res->fetch_assoc();

if (!$latest_round) {
    echo json_encode([
        'success' => false,
        'message' => 'No rounds yet',
        'time_left' => $round_duration
    ]);
    $conn->close();
    exit;
}

// Calculate time left
$created_at = strtotime($latest_round['created_at']);
$elapsed = time() - $created_at;

$time_left = $round_duration - ($elapsed % $round_duration);
if ($time_left <= 0) {
    $time_left = $round_duration;
}

$next_period = $latest_round['period'] + 1;

echo json_encode([
    'success' => true,
    'current_period' => $latest_round['period'],
    'current_color' => $latest_round['color'],
    'current_number' => $latest_round['number'],
    'time_left' => $time_left,
    'next_period' => $next_period,
    'next_round_start' => date('Y-m-d H:i:s', time() + $time_left)
]);

$conn->close();
