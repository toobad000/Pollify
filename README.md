# Pollify

> Real-time voting, built from scratch on the LAMP stack.

Pollify is a full-stack web application that lets users create custom polls, share them, and watch results update in real time. Built as a university project at Carleton University (2025), it covers the full lifecycle of a web application — from database design and server-side logic to frontend interactivity and secure authentication.

---

## Homepage

<div align="center">
  <img src="https://raw.githubusercontent.com/toobad000/Pollify/main/screenshots/index1.jpg" alt="Pollify Homepage - Top" width="100%"/>
  <img src="https://raw.githubusercontent.com/toobad000/Pollify/main/screenshots/index2.jpg" alt="Pollify Homepage - Bottom" width="100%"/>
</div>

---

## Features

- **Create polls** — Build custom polls with multiple answer options
- **Vote in real time** — Results update live without requiring a page refresh
- **User authentication** — Secure register/login system with session management
- **Share polls** — Each poll gets a shareable link for distribution
- **Responsive UI** — Usable across desktop and mobile browsers

---

## Public Polls & Live Results

All public polls are listed and display live vote counts and statistics as users vote. Results update in real time without requiring a page refresh.

<div align="center">
  <img src="https://raw.githubusercontent.com/toobad000/Pollify/main/screenshots/public_polls.jpg" alt="Public Polls with Vote Statistics" width="100%"/>
</div>

---

## User Authentication

Pollify includes a full authentication system — users can sign up for an account, log in securely, and access their personal polls. Passwords are hashed server-side using PHP's `password_hash` / `password_verify`. Sessions persist the logged-in state across pages.

<div align="center">
  <img src="https://raw.githubusercontent.com/toobad000/Pollify/main/screenshots/signup.jpg" alt="Sign Up Form" width="32%"/>
  <img src="https://raw.githubusercontent.com/toobad000/Pollify/main/screenshots/login.jpg" alt="Login Form" width="32%"/>
  <img src="https://raw.githubusercontent.com/toobad000/Pollify/main/screenshots/logged_in.jpg" alt="Logged In State" width="32%"/>
</div>

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

## Setup

See [SETUP.md](SETUP.md) for full build and database instructions.

---

## Project Status

Built as a university group project at Carleton University — 2025. Not actively maintained, but functional as a portfolio demonstration of full-stack LAMP development.

---

## License

For portfolio and educational purposes.
