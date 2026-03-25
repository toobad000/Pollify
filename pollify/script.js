document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.vote-button').forEach(button => {
        button.addEventListener('click', function () {
            const pollId = this.dataset.pollId;
            const option = this.dataset.option;
    
            // Prevent double click
            if (this.disabled) return;
            this.disabled = true;
    
            fetch('vote.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `poll_id=${pollId}&option=${option}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const pollCard = button.closest('.poll-card');
    
                    // Disable all vote buttons in that poll
                    pollCard.querySelectorAll('.vote-button').forEach(btn => {
                        btn.disabled = true;
                        btn.classList.add('disabled');
                    });
    
                    const votedMsg = document.createElement('div');
                    votedMsg.className = 'voted-message';
                    votedMsg.textContent = '✅ You voted!';
                    pollCard.querySelector('.poll-stats').appendChild(votedMsg);
    
                } else {
                    alert(data.message || "Vote failed");
                    this.disabled = false; // re-enable in case of error
                }
            });
        });
    });

    // Delete poll functionality
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function () {
            if (confirm("Are you sure you want to delete this poll?")) {
                const pollId = this.dataset.pollId;
                
                fetch('delete_poll.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `poll_id=${pollId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.poll-card').remove();
                    } else {
                        alert(data.message || "Delete failed");
                    }
                });
            }
        });
    });
    
    // Basic sorting functionality 
    const sortSelect = document.getElementById('sort-method');

    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            const selected = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('sort', selected);
            window.location.href = url.toString();
        });
    }

    // ===============================
    // Login & Registration Toggle - UPDATED VERSION
    // ===============================
    
    const showLogin = document.getElementById('show-login');
    const showRegister = document.getElementById('show-register');
    const loginFormToggle = document.getElementById('login-form');
    const registerFormToggle = document.getElementById('register-form');
    const formTitle = document.getElementById('form-title');

    if (showLogin && showRegister && loginFormToggle && registerFormToggle) {
        showLogin.addEventListener('click', function (e) {
            e.preventDefault();
            registerFormToggle.style.display = 'none';
            loginFormToggle.style.display = 'block';
            if (formTitle) formTitle.textContent = '---LOGIN---';
        });

        showRegister.addEventListener('click', function (e) {
            e.preventDefault();
            loginFormToggle.style.display = 'none';
            registerFormToggle.style.display = 'block';
            if (formTitle) formTitle.textContent = '---SIGN UP---';
        });
    }


    // ===============================
    // Referral 'Other' Input Handling
    // ===============================
    const referralRadios = document.querySelectorAll("input[name='referral']");
    const otherRadio = document.getElementById("other-referral");
    const otherInputContainer = document.getElementById("other-input-container");
    const otherReferralInput = document.getElementById("other-referral-text");
    
    referralRadios.forEach(radio => {
        radio.addEventListener("change", function () {
            if (otherRadio.checked) {
                otherInputContainer.style.display = "block";
            } else {
                otherInputContainer.style.display = "none";
                otherReferralInput.value = "";
            }
        });
    });

    // ===============================
    // Registration Form Validation
    // ===============================
    const registrationForm = document.getElementById("registration-form");

    if (registrationForm) {
        registrationForm.addEventListener("submit", function (e) {
            let formValid = true;
            const fields = registrationForm.querySelectorAll("input[required]");
            
            fields.forEach((field) => {
                const errorMessage = document.getElementById(`${field.id}-error`) || 
                                   createErrorMessage(field);
                
                errorMessage.textContent = "";
                
                if (field.value.trim() === "" && field.name !== "other-referral-text") {
                    formValid = false;
                    errorMessage.textContent = `${field.placeholder} is required.`;
                }
                
                if (field.name === "password" && field.value.length < 6) {
                    formValid = false;
                    errorMessage.textContent = "Password must be at least 6 characters long.";
                }
                
                if (field.name === "confirm-password" && field.value !== document.getElementById("password").value) {
                    formValid = false;
                    errorMessage.textContent = "Passwords do not match.";
                }
            });

            if (!formValid) {
                e.preventDefault();
            }
        });
    }

    function createErrorMessage(field) {
        const errorMessage = document.createElement("div");
        errorMessage.id = `${field.id}-error`;
        errorMessage.className = "error-message";
        field.parentNode.insertBefore(errorMessage, field.nextSibling);
        return errorMessage;
    }

    // ===============================
    // Login Form Validation
    // ===============================
    const loginForm = document.getElementById("login-form");
    
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            const usernameField = document.getElementById("login-username");
            const passwordField = document.getElementById("login-password");
            let formValid = true;

            // Clear previous errors
            document.querySelectorAll("#login-form .error-message").forEach(el => el.textContent = "");
            
            if (usernameField.value.trim() === "") {
                formValid = false;
                const error = document.getElementById("login-username-error") || createErrorMessage(usernameField);
                error.textContent = "Username is required";
            }
            
            if (passwordField.value.trim() === "") {
                formValid = false;
                const error = document.getElementById("login-password-error") || createErrorMessage(passwordField);
                error.textContent = "Password is required";
            }

            if (!formValid) {
                e.preventDefault();
            }
        });
    }


    // Footer weather API implementation
    const weatherBox = document.getElementById("weather");
    const apiKey = "a09f7846c9f45845383e11f4a8e99c1d";
    const city = "Ottawa";

    function fetchWeather() {
        fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${apiKey}`)
            .then(response => response.json())
            .then(data => {
                const temp = data.main.temp;
                const condition = data.weather[0].description;
                weatherBox.innerHTML = `🌤️ Weather in ${city}: ${temp}°C, ${condition}`;
            })
            .catch(err => {
                weatherBox.innerHTML = "Failed to load weather.";
                console.error("Weather fetch failed:", err);
            });
    }

    // first call immediately
    if (weatherBox) {
        fetchWeather();
        setInterval(fetchWeather, 60000); // every 60 seconds
    }


    // Rotating hero content on index.php
    const heroSlides = document.querySelectorAll('.hero .hero-slide');
    const dotContainer = document.querySelector('.hero-dots');
    const pauseBtn = document.getElementById('hero-pause');
    let heroIndex = 0;
    let interval = null;
    let isPaused = false;

    // dot indicators
    heroSlides.forEach((_, i) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (i === 0) dot.classList.add('active');
        dot.addEventListener('click', () => {
            setSlide(i);
        });
        dotContainer.appendChild(dot);
    });

    const dots = dotContainer.querySelectorAll('.dot');

    function setSlide(index) {
        heroSlides[heroIndex].classList.remove('active');
        dots[heroIndex].classList.remove('active');
        heroIndex = index;
        heroSlides[heroIndex].classList.add('active');
        dots[heroIndex].classList.add('active');
    }

    function cycleHeroSlides() {
        let next = (heroIndex + 1) % heroSlides.length;
        setSlide(next);
    }

    function startSlider() {
        interval = setInterval(cycleHeroSlides, 5000);
    }

    function pauseSlider() {
        clearInterval(interval);
    }

    if (pauseBtn) {
        pauseBtn.addEventListener('click', () => {
            isPaused = !isPaused;
            if (isPaused) {
                pauseSlider();
                pauseBtn.textContent = "▶";
            } else {
                startSlider();
                pauseBtn.textContent = "⏸";
            }
        });
    }

    startSlider();


});