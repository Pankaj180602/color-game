<?php
session_start();
require_once 'db.php'; // ensure this file connects to the database

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$user_id = $_SESSION['user_id'];
$amount = floatval($_POST['amount'] ?? 0);
$type = $_POST['type'] ?? '';

if ($amount <= 0 || !in_array($type, ['add', 'withdraw'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Fetch current wallet balance
$stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$current_balance = floatval($user['wallet_balance']);

// Perform action
if ($type === 'add') {
    $new_balance = $current_balance + $amount;
    $transaction_type = 'deposit';
} else {
    if ($amount > $current_balance) {
        echo json_encode(['success' => false, 'message' => 'Insufficient balance']);
        exit;
    }
    $new_balance = $current_balance - $amount;
    $transaction_type = 'withdraw';
}

// Update wallet
$update = $pdo->prepare("UPDATE users SET wallet_balance = ? WHERE id = ?");
$update->execute([$new_balance, $user_id]);

// Insert into transactions
$insert = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, status, created_at) VALUES (?, ?, ?, 'success', NOW())");
$insert->execute([$user_id, $transaction_type, $amount]);

echo json_encode(['success' => true, 'new_balance' => $new_balance, 'message' => ucfirst($transaction_type) . ' successful']);
