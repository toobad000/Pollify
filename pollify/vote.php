<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to vote']);
    exit();
}

$user_id = $_SESSION['user_id'];
$poll_id = (int)$_POST['poll_id'];
$option = strtoupper($_POST['option']); // 'A' or 'B'

// Validate option
if (!in_array($option, ['A', 'B'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid option']);
    exit();
}

// Fetch voter_ids from DB
$stmt = $pdo->prepare("SELECT voter_ids FROM polls WHERE poll_id = ?");
$stmt->execute([$poll_id]);
$poll = $stmt->fetch();

if (!$poll) {
    echo json_encode(['success' => false, 'message' => 'Poll not found']);
    exit();
}

// Normalize voter_ids string with leading/trailing commas
$voter_ids = ',' . trim($poll['voter_ids'], ',') . ',';
$check_pattern = ',' . $user_id . ',';

// Check if user already voted
if (strpos($voter_ids, $check_pattern) !== false) {
    echo json_encode(['success' => false, 'message' => 'You have already voted on this poll.']);
    exit();
}

// Prepare vote count update
$column = strtolower($option) . '_count';

try {
    $pdo->beginTransaction();

    // Update vote count and append user ID to voter_ids
    $stmt = $pdo->prepare("
        UPDATE polls 
        SET $column = $column + 1,
            voter_ids = CONCAT(voter_ids, :user_id, ',')
        WHERE poll_id = :poll_id
    ");
    $stmt->execute([
        ':user_id' => $user_id,
        ':poll_id' => $poll_id
    ]);

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Vote recorded successfully!']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>