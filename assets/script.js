<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include_once '../auth/db.php'; // DB connection

// Get the latest round
$stmt = $pdo->query("SELECT created_at FROM rounds ORDER BY id DESC LIMIT 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $created_at = strtotime($row['created_at']) * 1000; // ms
    $next_round_time = $created_at + 30000; // 30s later

    echo json_encode([
        "success" => true,
        "created_at" => $created_at,
        "next_round_time" => $next_round_time
    ]);
} else {
    echo json_encode(["success" => false, "message" => "No rounds found"]);
}
?>