<?php 
session_start();
$pageTitle = "Login"; 
include 'snippets/head.php'; 
include 'snippets/header.php'; 
include 'db_connection.php'; // Using the new connection file

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT user_id, username, password_hash, permissions FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Special admin account handling (if needed)
    if ($username === 'admin' && $password === 'password') {
        session_regenerate_id(true);
        $_SESSION['user_id'] = 0; // or any unique admin ID
        $_SESSION['username'] = 'admin';
        $_SESSION['permissions'] = 3;
        $_SESSION['loggedin'] = true;
    
        header("Location: index.php"); // or employee.php if/when it exists
        exit();
    } else {
        $loginValid = ($user && password_verify($password, $user['password_hash']));
    }

    if ($loginValid) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['permissions'] = $user['permissions'] ?? 2; // Default to 2 (regular user) if not set
        $_SESSION['loggedin'] = true;
        
        // Update last login
        $updateStmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
        $updateStmt->execute([$user['user_id']]);
        
        // Redirect based on permissions (keeping your existing logic)
        header("Location: " . (($_SESSION['permissions'] == 3) ? "employee.php" : "index.php"));
        exit();
    } else {
        $login_error = "Invalid username or password";
    }
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $firstName = filter_input(INPUT_POST, 'first-name', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'last-name', FILTER_SANITIZE_STRING);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $referral = filter_input(INPUT_POST, 'referral', FILTER_SANITIZE_STRING);
    
    if ($referral === 'Other') {
        $referral = filter_input(INPUT_POST, 'other-referral-text', FILTER_SANITIZE_STRING);
    }

    try {
        // Insert into users table (modified for new structure)
        $permissions = ($username === 'admin') ? 3 : 2;

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, permissions, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$username, $email, $password, $permissions]);
        
        session_regenerate_id(true);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $username;
        $permCheck = $pdo->prepare("SELECT permissions FROM users WHERE user_id = ?");
        $permCheck->execute([$_SESSION['user_id']]);
        $_SESSION['permissions'] = $permCheck->fetchColumn();
        $_SESSION['loggedin'] = true;
        
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $register_error = "Registration failed: " . 
            (strpos($e->getMessage(), 'Duplicate entry') !== false ? 
            "Username/Email already exists" : $e->getMessage());
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<main>
    <div class="content-login">
        <h1><b class="title">Login To Get Started!</b></h1>
        
        <?php if (isset($_SESSION['loggedin'])): ?>
            <div class="welcome-message">
                <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <p>Account type: <?php echo ($_SESSION['permissions'] == 3) ? 'Employee' : 'Customer'; ?></p>
                <a class="logout-btn" href="login.php?logout">Logout</a>
            </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['loggedin'])): ?>
            <h2 id="form-title">---SIGN UP---</h2>
            
            <?php if (isset($login_error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($login_error); ?></div>
            <?php endif; ?>
            
            <?php if (isset($register_error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($register_error); ?></div>
            <?php endif; ?>
            
            <div id="register-form" class="login-form">
                <form action="login.php" method="POST" id="registration-form">
                    <input type="text" name="username" id="username" placeholder="Username" required maxlength="50">
                    <span id="username-error" class="error-message"></span>
                    
                    <input type="email" name="email" id="email" placeholder="Email" required maxlength="100">
                    <span id="email-error" class="error-message"></span>
                    
                    <input type="text" name="first-name" id="first-name" placeholder="First Name" maxlength="50">
                    <span id="first-name-error" class="error-message"></span>
                    
                    <input type="text" name="last-name" id="last-name" placeholder="Last Name" maxlength="50">
                    <span id="last-name-error" class="error-message"></span>
                    
                    <input type="password" name="password" id="password" placeholder="Password" required minlength="6" maxlength="50">
                    <span id="password-error" class="error-message"></span>
                    
                    <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password" required maxlength="50">
                    <span id="confirm-password-error" class="error-message"></span>

                    <br><br>
                    <label>How did you hear about us?</label>
                    <div class="referral-options">
                        <input type="radio" id="social-media" name="referral" value="Social Media">
                        <label for="social-media">Social Media</label>
                        <input type="radio" id="advertising" name="referral" value="Advertising">
                        <label for="advertising">Advertising</label>
                        <input type="radio" id="word-of-mouth" name="referral" value="Word-of-mouth">
                        <label for="word-of-mouth">Word of Mouth</label>
                        <input type="radio" id="internet-search" name="referral" value="Internet Search">
                        <label for="internet-search">Internet Search</label>
                        <input type="radio" id="other-referral" name="referral" value="Other">
                        <label for="other-referral">Other</label>
                    </div>
                    <span id="referral-error" class="error-message"></span>

                    <div id="other-input-container" style="display: none;">
                        <input type="text" id="other-referral-text" name="other-referral-text" placeholder="Please specify" maxlength="50">
                        <span id="other-referral-text-error" class="error-message"></span>
                    </div>
                    <button type="submit" name="register">Register</button>
                </form>
                <div class="welcome-message">
                    <p>Already have an account? <a class="logout-btn" href="#" id="show-login">Login here</a></p>
                </div>
            </div>

            <div id="login-form" class="login-form" style="display: none;">
                <form action="login.php" method="POST" id="login-form">
                    <input type="text" name="username" id="login-username" placeholder="Username" required maxlength="50">
                    <span id="login-username-error" class="error-message"></span>
                    
                    <input type="password" name="password" id="login-password" placeholder="Password" required maxlength="50">
                    <span id="login-password-error" class="error-message"></span>
                    
                    <button type="submit" name="login">Login</button>
                </form>
                <div class="welcome-message">
                    <p>Don't have an account? <a class="logout-btn" href="#" id="show-register">Sign up here</a></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script src="script.js"></script> 
</main>

<?php include 'snippets/footer.php'; ?>