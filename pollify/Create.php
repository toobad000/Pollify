<?php session_start(); ?>
<!DOCTYPE html>
<html>
<?php $pageTitle = "Create Poll"; ?>
<?php include 'snippets/head.php'; ?>
<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<body>
    <?php include 'snippets/header.php'; ?>
    <main>    
        <div class="create">
            <h1>Create a New Poll</h1>
            <h2>Enter your poll details:</h2><br>

            <form method="POST" action="process_poll.php" class="poll-form">
                <div class="form-group">
                    <label for="prompt">Poll Question:</label>
                    <input type="text" id="prompt" name="prompt" required placeholder="What's your favorite...?">
                </div>
                <br>
                <div class="form-group">
                    <label for="option_a">Option A:</label>
                    <input type="text" id="option_a" name="option_a" required placeholder="First choice">
                </div>
                <br>
                <div class="form-group">
                    <label for="option_b">Option B:</label>
                    <input type="text" id="option_b" name="option_b" required placeholder="Second choice">
                </div>
                <br>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_public" checked> Make this poll public
                    </label>
                </div>
                <br>

                <button type="submit" class="cta-button">Create Poll</button>
            </form>
        </div>       
    </main>
    <?php include "snippets/footer.php"; ?>
</body>
</html>