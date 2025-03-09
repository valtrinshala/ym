# General Programming Tasks

This folder contains solutions for Task 1 from the assignment. The tasks involve mathematical calculations and number
classifications.

## Task 1.1 - Factor Pair Listing

This script finds and lists all factor pairs of a given number.

### Usage

Run the following command in the terminal to execute the script:

```bash
php tasks.php 1
```

## Task 1.2 - Number Classification

This script classifies a given number as **Perfect, Deficient, or Abundant** based on the sum of its proper divisors.

### Usage

Run the following command in the terminal:

```bash
php tasks.php 2 <number>
```

## Task 1.3 - Harshad Number Checker

This script checks if a number is a **Harshad Number** (also known as a Niven number).  
A Harshad number is a number that is divisible by the sum of its digits.

### Usage

Run the following command in the terminal:

```bash
php tasks.php 3 <number>
```

# Task 2 – Web Development (Forum App)

This is a **forum application** built using **Laravel**, **Vue.js**, and **TailwindCSS**.  
It includes features like **user authentication, post creation, comments, notifications, and search**.

---

## **1. Installation Instructions**

### **Step 1: Clone the Repository**

```bash
git clone https://github.com/valtrinshala/ym-assignmnet.git
cd forum-app
```

### **Step 2: Install Dependencies**

```bash
composer install
npm install
```

### **Step 3: Set Up Environment File**

```bash
cp .env.example .env
php artisan key:generate
```

### **Step 4: Configure Database**

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forum-app
DB_USERNAME=root
DB_PASSWORD=
```

Then, run:

```bash
php artisan migrate --seed
```

### **Step 5: Compile Assets**

```bash
npm install
npm run dev
```

### **Step 6: Serve the Application**

```bash
php artisan serve
```

### **Step 7: Running Tests**

```bash
./vendor/bin/pest
```

### **Step 8: API Documentation**

The API documentation can be found at `/docs/api`.

## **2. Features**

- **User Authentication**:
    - Users can **register, login, and logout**.
    - Users can only create posts or comments **if their email is verified**.

- **Post Management**:
    - Users can **create, edit, and delete posts**.
    - Posts contain a **title, content, and creation date**.
    - Users **cannot edit or delete posts** if they have comments.

- **Comment System**:
    - Users can **add, edit, and delete comments** on posts.
    - Comments include **text, creation date, and optional edited timestamp**.

- **Post Search**:
    - Users can **search for posts by title, content, or comments**.
    - 🔹 *(Note: Search is implemented using Laravel Scout.)*

- **Email Notifications**:
    - Post authors **receive an email notification** when a new comment is added to their post.

- **Post Ordering**:
    - Posts are **ordered by creation date in descending order**.

- **Automatic Cleanup**:
    - Posts that **have not received comments for over a year are soft deleted**.
    - A scheduled job **runs daily to check and delete old posts**.

- **API Integration**:
    - The application **provides a RESTful API** for managing posts and comments.
    - All API routes are documented using **Scramble API Documentation**.

- **Feature & Unit Tests**:
    - The application includes **feature tests for posts, comments, and user authentication**.
    - **Unit tests** ensure the integrity of business logic.

- **API Documentation**:
    - API documentation is **automatically generated**.
    - Accessible at:
      ```
      http://localhost/docs/api
      ```
      🔹 *(Note: Replace `localhost` with your server address.)*

