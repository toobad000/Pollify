<?php 
session_start();
require 'db_connection.php';
$pageTitle = "Private Polls";
include "snippets/head.php"; 
include "snippets/header.php"; 

$sort = $_GET['sort'] ?? 'newest';
$orderBy = "ORDER BY created_at DESC";

switch ($sort) {
    case 'oldest':
        $orderBy = "ORDER BY created_at ASC";
        break;
    case 'most-votes':
        $orderBy = "ORDER BY (a_count + b_count) DESC";
        break;
    case 'least-votes':
        $orderBy = "ORDER BY (a_count + b_count) ASC";
        break;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user's private polls
$stmt = $pdo->prepare("
    SELECT * FROM polls 
    WHERE user_id = ? AND is_public = 0
    $orderBy
");
$stmt->execute([$_SESSION['user_id']]);
$polls = $stmt->fetchAll();
?>

<main>
    <h1>Your Private Polls</h1>


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

        <div class="sort-controls">
            <a href="create.php" class="cta-button">Create New Poll</a>
        </div>

        <?php if (!empty($polls)): ?>
            <div class="poll-grid">
                <?php foreach ($polls as $poll): 
                    $total_votes = $poll['a_count'] + $poll['b_count'];
                    $a_percent = $total_votes > 0 ? round(($poll['a_count'] / $total_votes) * 100) : 0;
                    $b_percent = $total_votes > 0 ? round(($poll['b_count'] / $total_votes) * 100) : 0;
                ?>
                    <div class="poll-card">
                        <h3><?php echo htmlspecialchars($poll['prompt']); ?></h3>
                        <div class="poll-meta">
                            <span>Created: <?php echo date('M j, Y', strtotime($poll['created_at'])); ?></span>
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
                            <button class="vote-button" data-poll-id="<?php echo $poll['poll_id']; ?>" data-option="A">Vote A</button>
                            <button class="vote-button" data-poll-id="<?php echo $poll['poll_id']; ?>" data-option="B">Vote B</button>
                            <button class="delete-button" data-poll-id="<?php echo $poll['poll_id']; ?>">Delete</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No private polls available. Make your first one today!</p><br><br><a href="create.php" class="cta-button">Create your first poll</a>
        <?php endif; ?>
    </div>
</main>

<?php include "snippets/footer.php"; ?>
<script src="script.js"></script>