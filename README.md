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
- [Future Improvements](#future-improvements)  


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

- **Frontend**: HTML5, CSS3, JavaScript (ES6), jQuery and AJAX  
- **Backend**: PHP 8  
- **Data storage**: JSON files 
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
   git clone https://github.com/Hazim2024/ljmu-module-qa.git
   cd ljmu-module-qa
2. **Serve with PHP’s built‑in server eg.**
   ```bash
    php -S localhost:8000
   
3. Open **your local host address** in your browser.

⚙️ Usage
1. Log in as Student or Staff.

2. Student workflow:

   - Submit a new question (choose module, title, description).

   - Vote on existing questions to raise priority.

   - Filter by module/status or search by keyword.

3. Staff workflow:

   - View unanswered questions.

   - Post one or more answers per question.

♿ Accessibility & Responsive Design
- Keyboard: All controls reachable via Tab/Enter.

- Screen readers: ARIA roles, labels, aria-expanded, aria-controls.


- Responsive:

   - Grid of cards on desktop

   - Hamburger menu & vertical nav on mobile (< 736 px)

   - Slider shows 3→2→1 cards depending on width


🚧 Future Improvements

- Add Registeration system
  
- Add pagination for large question sets

- Rich‑text editing for questions/answers

- User profile pages & notifications
