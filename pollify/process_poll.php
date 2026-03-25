<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    die("Please login to create polls");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prompt = trim($_POST['prompt']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $is_public = isset($_POST['is_public']) ? 1 : 0;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO polls (user_id, prompt, option_a, option_b, is_public) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_SESSION['user_id'],
            $prompt,
            $option_a,
            $option_b,
            $is_public
        ]);
        
        header("Location: " . ($is_public ? "public.php" : "private.php"));
        exit();
    } catch (PDOException $e) {
        die("Error creating poll: " . $e->getMessage());
    }
}
?>