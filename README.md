# Pollify

> Real-time voting, built from scratch on the LAMP stack.

Pollify is a full-stack web application that lets users create custom polls, share them, and watch results update in real time. Built as a university project at Carleton University (2025), it covers the full lifecycle of a web application — from database design and server-side logic to frontend interactivity and secure authentication.

---

## Screenshots

<!-- Add a screenshot of the homepage here -->
<!-- Example: ![Pollify Homepage](screenshots/homepage.png) -->

*Screenshot coming soon.*

---

## Features

- **Create polls** — Build custom polls with multiple answer options
- **Vote in real time** — Results update live without requiring a page refresh
- **User authentication** — Secure register/login system with session management
- **Share polls** — Each poll gets a shareable link for distribution
- **Responsive UI** — Usable across desktop and mobile browsers

---

## Tech Stack

| Layer | Technology |
|---|---|
| OS / Server | Linux, Apache |
| Backend | PHP |
| Database | MySQL |
| Frontend | HTML, CSS, JavaScript |
| Version Control | Git |

---

## Architecture Overview

Pollify follows a standard LAMP architecture:

- **Apache** serves the application and handles routing
- **PHP** processes all business logic — poll creation, vote submission, result aggregation
- **MySQL** stores users, polls, options, and votes with relational integrity (foreign keys, constraints)
- **JavaScript** handles real-time result rendering on the client side, polling the server for updated vote counts
- **Sessions & hashing** are used for authentication — passwords are hashed server-side before storage

---

## What I Built & Learned

This project was my first end-to-end full-stack application. Key things I worked on:

- **Database design** — Normalized schema with tables for users, polls, options, and votes; designed to prevent duplicate votes per user
- **Server-side auth** — Built registration and login flows with PHP sessions and password hashing (`password_hash` / `password_verify`)
- **Real-time updates** — Implemented a lightweight polling mechanism in JavaScript to fetch and re-render vote counts without a full page reload
- **Separation of concerns** — Kept data access, business logic, and presentation reasonably separated across PHP files

---

## Getting Started

### Prerequisites

- Apache web server
- PHP 7.4+
- MySQL 5.7+

### Setup

```bash
# Clone the repository
git clone https://github.com/YOUR_USERNAME/Pollify.git

# Move into your Apache web root (e.g. /var/www/html)
cp -r Pollify/ /var/www/html/pollify

# Import the database schema
mysql -u root -p < db/schema.sql

# Configure your database credentials
cp config/config.example.php config/config.php
# Edit config.php with your MySQL credentials
```

Then visit `http://localhost/pollify` in your browser.

---

## Project Status

Built as a university group project at Carleton University — 2025. Not actively maintained, but functional as a portfolio demonstration of full-stack LAMP development.

---

## License

For portfolio and educational purposes.
