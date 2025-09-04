<?php
session_start();
header('Content-Type: application/json');

require_once '..\auth\db.php'; // Your DB connection

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['bet_type']) || !isset($data['amount'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing bet details']);
    exit;
}

$bet_type = trim($data['bet_type']);
$amount = floatval($data['amount']);

// Validate amount
if ($amount <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid bet amount']);
    exit;
}

// Check wallet balance
$stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode(['status' => 'error', 'message' => 'Wallet not found']);
    exit;
}

$current_balance = floatval($row['balance']);

if ($current_balance < $amount) {
    echo json_encode(['status' => 'error', 'message' => 'Insufficient balance']);
    exit;
}

// Deduct from wallet
$new_balance = $current_balance - $amount;
$update_stmt = $conn->prepare("UPDATE users SET balance = ? WHERE id = ?");
$update_stmt->bind_param("di", $new_balance, $user_id);
$update_stmt->execute();

// Save bet (no bet_value)
$bet_stmt = $conn->prepare("INSERT INTO bets (user_id, bet_type, amount, created_at) VALUES (?, ?, ?, NOW())");
$bet_stmt->bind_param("isd", $user_id, $bet_type, $amount);
$bet_stmt->execute();

echo json_encode([
    'status' => 'success',
    'message' => 'Bet placed successfully',
    'new_balance' => $new_balance
]);
