<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en-CA">
<?php include "snippets/head.php"; ?>

<body>
    <?php include "snippets/header.php" ?>

    <div class="hero">
        <div class="hero-content">
            <div class="hero-slide active">
                <h2>Create and Track Polls in Real-Time</h2>
                <p>Build polls in seconds. Share instantly. Watch results live as they come in.</p>
                <a href="login.php" class="cta-button">Sign in to Create a Poll</a>
            </div>
            <div class="hero-slide">
                <h2>Get Started with One Click</h2>
                <p>Join Pollify now and start gathering opinions from your audience.</p>
                <a href="login.php" class="cta-button">Get Started</a>
            </div>
            <div class="hero-slide">
                <h2>Check Out Our Ongoing Polls Here!</h2>
                <p>Explore what people are voting on right now and join the conversation.</p>
                <a href="Public.php" class="cta-button">View Public Polls</a>
            </div>

            <div class="hero-controls">
                <div class="hero-dots"></div>
            </div>
            <div class="hero-pause-wrap">
                <button id="hero-pause">⏸</button>
            </div>
        </div>
    </div>

    


    <section class="features">
        <div class="features-container">
            <div class="feature-card">
                <div class="icon">🛠️</div>
                <h3>Easy Poll Creation</h3>
                <p>Build polls with a simple, fast interface. No account needed.</p>
            </div>
            <div class="feature-card">
                <div class="icon">📊</div>
                <h3>Real-time Responses</h3>
                <p>Watch results update in real-time as people vote.</p>
            </div>
            <div class="feature-card">
                <div class="icon">🔗</div>
                <h3>Share Easily and Quickly</h3>
                <p>Get a link to send to anyone, anywhere.</p>
            </div>
            <div class="feature-card">
                <div class="icon">🔒</div>
                <h3>Simple-to-Use Privacy Controls</h3>
                <p>Choose between visibility options with a single click.</p>
            </div>
        </div>
    </section>
    <?php include "snippets/footer.php"; ?>
</body>
</html>
