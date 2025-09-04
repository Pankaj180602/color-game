<?php
// get_rounds.php
include_once '../auth/db.php';

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$stmt = $conn->prepare("SELECT period, number, odd_even, color, created_at FROM rounds ORDER BY period DESC LIMIT ?");
$stmt->bind_param('i', $limit);
$stmt->execute();
$res = $stmt->get_result();

$rounds = [];
while ($row = $res->fetch_assoc()) {
    $rounds[] = $row;
}

header('Content-Type: application/json');
echo json_encode(['success'=>true, 'rounds'=>$rounds]);
$conn->close();
