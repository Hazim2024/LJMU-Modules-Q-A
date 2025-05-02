# LJMU Module Q&A

A responsive, accessible question and answer portal for LJMU modules where students post questions, staff post answers, and votes prioritize the most pressing issues.

---

## 📄 Table of Contents

- [Project Overview](#project-overview)  
- [Key Features](#key-features)  
- [Tech Stack](#tech-stack)  
- [Demo](#demo)  
- [Getting Started](#getting-started)  
- [Prerequisites](#prerequisites)  
- [Installation](#installation)  
- [Usage](#usage)  
- [Accessibility & Responsive Design](#accessibility--responsive-design)  
- [File Structure](#file-structure)  
- [Future Improvements](#future-improvements)  
- [License](#license)  

---

## 🔍 Project Overview

This is the second‑assignment implementation for the Mobile & Web Development module. We built a single‑codebase web app where:

- **Students** log in to submit, search, filter, and vote on module‑specific questions.  
- **Staff** log in to view unanswered questions and post one or more answers per question.  
- All data (questions, answers, votes) persist in JSON files on the server.  

---

## ✨ Key Features

1. **Role‑based access**  
   - Students: submit questions, vote, view questions.  
   - Staff: answer questions, view all submissions.  

2. **Live filtering & search**  
   - Filter by module, answered/unanswered, or free‑text search.  

3. **Voting system**  
   - Students can up‑vote questions; votes persist per user and determine sort order.  

4. **Responsive UI**  
   - Single codebase adapts to desktop and mobile (hamburger menu, flexible grids, carousel).  

5. **Accessibility**  
   - Keyboard navigation, `aria‑` attributes, proper labels.  

6. **Interactive effects**  
   - Hover states, button scale‑up, flash messages.  

---

## 🛠 Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript (ES6), jQuery, AJAX  
- **Backend**: PHP 8  
- **Data storage**: JSON files (`data/questions.json`, `data/answers.json`, `data/question_votes.json`)  
- **Icons**: Ionicons  
- **CSS framework**: Bootstrap 4 (grid only; custom CSS for styling)  

---


## ⚙️ Getting Started

### Prerequisites

- PHP 8.x  
- A web server (Apache, Nginx, or built‑in PHP server)  

### Installation

1. **Clone the repo**  
   ```bash
   git clone https://github.com/<YOUR_USERNAME>/ljmu-module-qa.git
   cd ljmu-module-qa
2. **Serve with PHP’s built‑in server**
   
    php -S localhost:8000
   
4. Open **http://localhost:8000/main.php** in your browser.
   
