<?php
require_once '../auth/db.php';

// Step 1: Get current unresolved round
$roundQuery = $pdo->query("SELECT * FROM rounds WHERE resolved = 0 ORDER BY id ASC LIMIT 1");
$round = $roundQuery->fetch(PDO::FETCH_ASSOC);

if (!$round) {
    echo json_encode(['status' => 'no_round']);
    exit;
}

$roundId = $round['id'];

// Step 2: Generate random winning color and number
$colors = ['red', 'green', 'violet'];
$winning_color = $colors[array_rand($colors)];
$winning_number = rand(0, 9);

// Step 3: Resolve bets
$betStmt = $pdo->prepare("SELECT * FROM bets WHERE id = ?");
$betStmt->execute([$roundId]);
$bets = $betStmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($bets as $bet) {
    $is_win = 0;
    $win_amount = 0;

    if ($bet['bet_type'] === 'color' && $bet['prediction'] === $winning_color) {
        $is_win = 1;
        $win_amount = $bet['amount'] * 2;
    } elseif ($bet['bet_type'] === 'number' && $bet['prediction'] == $winning_number) {
        $is_win = 1;
        $win_amount = $bet['amount'] * 4;
    }

    // Update the bet record
    $updateBet = $pdo->prepare("UPDATE bets SET is_win = ?, win_amount = ?, resolved_at = NOW() WHERE id = ?");
    $updateBet->execute([$is_win, $win_amount, $bet['id']]);

    // Add to user's wallet if they won
    if ($is_win) {
        $walletUpdate = $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance + ? WHERE id = ?");
        $walletUpdate->execute([$win_amount, $bet['user_id']]);
    }
}

// Step 4: Mark the round as resolved
$updateRound = $pdo->prepare("UPDATE rounds SET winning_color = ?, winning_number = ?, resolved = 1, resolved_at = NOW() WHERE id = ?");
$updateRound->execute([$winning_color, $winning_number, $roundId]);

// Step 5: Respond with result
echo json_encode([
    'status' => 'success',
    'round_id' => $roundId,
    'winning_color' => $winning_color,
    'winning_number' => $winning_number
]);
