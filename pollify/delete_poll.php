<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$poll_id = (int)$_POST['poll_id'];
$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['permissions'] == 3;

// If admin, allow delete regardless of ownership
if ($is_admin) {
    $stmt = $pdo->prepare("DELETE FROM polls WHERE poll_id = ?");
    $stmt->execute([$poll_id]);
} else {
    // Regular users can only delete their own polls
    $stmt = $pdo->prepare("DELETE FROM polls WHERE poll_id = ? AND user_id = ?");
    $stmt->execute([$poll_id, $user_id]);
}

if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Delete failed or unauthorized.']);
}
?>