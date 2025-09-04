<?php
session_start();
header('Content-Type: application/json');
require_once '../auth/db.php';

// Check login
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = intval($_SESSION['id']); // ensure integer

try {
    $stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Statement prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'balance' => floatval($row['balance']) // make sure it's a number
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
