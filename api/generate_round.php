<?php
include_once __DIR__ . '/../auth/db.php'; // DB connection
header('Content-Type: application/json');

// Config
$round_duration = 30;

function pick($arr) {
    return $arr[array_rand($arr)];
}

// Get latest round
$res = $conn->query("SELECT * FROM rounds ORDER BY id DESC LIMIT 1");
$latest_round = $res->fetch_assoc();

if (!$latest_round) {
    // 🔥 Create first round
    $period = 1;
    $colors = ['red', 'green', 'violet+red'];
    $color = pick($colors);

    if ($color === 'red') {
        $number = pick([2,4,6,8]);
    } elseif ($color === 'green') {
        $number = pick([1,3,7,9]);
    } else {
        $number = pick([0,5]);
    }

    $odd_even = ($number % 2 === 0) ? 'even' : 'odd';

    $stmt = $conn->prepare("INSERT INTO rounds (period, number, odd_even, color, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiss", $period, $number, $odd_even, $color);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => 'First round created',
        'current_period' => $period,
        'current_color' => $color,
        'current_number' => $number,
        'time_left' => $round_duration,
        'next_period' => $period + 1
    ]);
    exit;
}

// If rounds already exist, check elapsed time
$created_at = strtotime($latest_round['created_at']);
$elapsed = time() - $created_at;

// If 30s passed → create a new round
if ($elapsed >= $round_duration) {
    $period = $latest_round['period'] + 1;
    $colors = ['red', 'green', 'violet+red'];
    $color = pick($colors);

    if ($color === 'red') {
        $number = pick([2,4,6,8]);
    } elseif ($color === 'green') {
        $number = pick([1,3,7,9]);
    } else {
        $number = pick([0,5]);
    }

    $odd_even = ($number % 2 === 0) ? 'even' : 'odd';

    $stmt = $conn->prepare("INSERT INTO rounds (period, number, odd_even, color, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiss", $period, $number, $odd_even, $color);
    $stmt->execute();

    $latest_round = [
        'period' => $period,
        'color' => $color,
        'number' => $number,
        'created_at' => date('Y-m-d H:i:s')
    ];

    $elapsed = 0; // just created
}

// Calculate remaining time for current round
$time_left = $round_duration - ($elapsed % $round_duration);
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
