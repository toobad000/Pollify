<?php 
session_start();
require 'db_connection.php';
$pageTitle = "Public Polls";
include "snippets/head.php"; 
include "snippets/header.php";

$sort = $_GET['sort'] ?? 'newest';
$orderBy = "ORDER BY p.created_at DESC";

switch ($sort) {
    case 'oldest':
        $orderBy = "ORDER BY p.created_at ASC";
        break;
    case 'most-votes':
        $orderBy = "ORDER BY (p.a_count + p.b_count) DESC";
        break;
    case 'least-votes':
        $orderBy = "ORDER BY (p.a_count + p.b_count) ASC";
        break;
}

// Fetch public polls
$stmt = $pdo->prepare("
    SELECT p.*, u.username
    FROM polls p
    LEFT JOIN users u ON p.user_id = u.user_id
    WHERE p.is_public = 1
    $orderBy
");
$stmt->execute();
$polls = $stmt->fetchAll();
?>

<main>
    <h1>Public Polls</h1>

    <div class="poll-container">
        
        <div class="sort-controls">
            <label for="sort-method" style="color: white;">Sort by:</label>
            <select id="sort-method">
                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest</option>
                <option value="most-votes" <?= $sort === 'most-votes' ? 'selected' : '' ?>>Most Votes</option>
                <option value="least-votes" <?= $sort === 'least-votes' ? 'selected' : '' ?>>Least Votes</option>
            </select>
        </div>

        <?php if (!empty($polls)): ?>
            <div class="poll-grid">
                <?php foreach ($polls as $poll): 
                    $total_votes = $poll['a_count'] + $poll['b_count'];
                    $a_percent = $total_votes > 0 ? round(($poll['a_count'] / $total_votes) * 100) : 0;
                    $b_percent = $total_votes > 0 ? round(($poll['b_count'] / $total_votes) * 100) : 0;
                    $has_voted = isset($_SESSION['user_id']) && strpos($poll['voter_ids'], ",".$_SESSION['user_id'].",") !== false;
                ?>
                    <div class="poll-card">
                        <h3><?php echo htmlspecialchars($poll['prompt']); ?></h3>
                        <div class="poll-meta">
                            <span>By <?php echo htmlspecialchars($poll['username'] ?? 'Anonymous'); ?></span>
                            <span><?php echo date('M j, Y', strtotime($poll['created_at'])); ?></span>
                        </div>

                        <div class="poll-stats">
                            <div class="poll-stat">
                                <span><?php echo htmlspecialchars($poll['option_a']); ?></span>
                                <span><?php echo $a_percent; ?>% (<?php echo $poll['a_count']; ?>)</span>
                            </div>
                            <div class="poll-stat">
                                <span><?php echo htmlspecialchars($poll['option_b']); ?></span>
                                <span><?php echo $b_percent; ?>% (<?php echo $poll['b_count']; ?>)</span>
                            </div>
                        </div>
                            
                        <div class="poll-total">Total Votes: <?php echo $total_votes; ?></div>
                            
                        <div class="poll-actions">
                            <?php if (!$has_voted && isset($_SESSION['user_id'])): ?>
                                <button class="vote-button" data-poll-id="<?php echo $poll['poll_id']; ?>" data-option="A">Vote A</button>
                                <button class="vote-button" data-poll-id="<?php echo $poll['poll_id']; ?>" data-option="B">Vote B</button>
                            <?php elseif ($has_voted): ?>
                                <span class="voted-message">You voted!</span>
                            <?php else: ?>
                                <a href="login.php" class="cta-button">Login to Vote</a>
                            <?php endif; ?>
                            

                            <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 3): ?>
                                <form method="POST" class="admin-delete-form">
                                    <input type="hidden" name="poll_id" value="<?= $poll['poll_id'] ?>">
                                    <button type="submit" class="delete-button" data-poll-id="<?= $poll['poll_id'] ?>">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <br><br><p>No public polls available.</p>
        <?php endif; ?>
    </div>
</main>

<?php include "snippets/footer.php"; ?>
<script src="script.js"></script>

<!-- <button class="share-button" data-poll-id="<?php echo $poll['poll_id']; ?>">Share</button> -->


