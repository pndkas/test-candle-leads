# 🕯️ Lumière Candle Co. — Lead Capture Web App

Lead Capture system for a custom candle business, built with **PHP + MySQL + HTML/CSS/JS**.

---

## 📁 File Structure

```
candle-leads/
├── index.php       ← Main Landing Page + HTML Form
├── save_lead.php   ← API endpoint (Receives POST, validates, saves to DB)
├── db.php          ← MySQL connection via PDO (Update credentials here)
├── setup.sql       ← SQL script to create database and tables
├── style.css       ← Dark Luxury Theme + animations
├── script.js       ← JS validation + AJAX form submission
├── .gitignore      ← Files to ignore in Git
└── README.md       ← This file
```

---

## 🛠️ Prerequisites

| Software      | How to check      | Download                                                       |
| ------------- | ----------------- | -------------------------------------------------------------- |
| **PHP 8.x**   | `php -v`          | https://windows.php.net/download (Choose VS17 x64 Thread Safe) |
| **MySQL 8.x** | `mysql --version` | https://dev.mysql.com/downloads/mysql/                         |

> **Windows Note:**
>
> 1. After installing PHP, add the PHP path (e.g., `C:\php`) to your Environment Variable `PATH`.
> 2. Check your `php.ini` file and ensure the following extensions are enabled (remove the `;` at the beginning of the line):
>
> ```ini
> extension=pdo_mysql
> extension=mbstring
> extension=openssl
> ```

---

## ⚙️ How to Run (Without XAMPP)

This project uses the **PHP Built-in Server**, so you don't need to install or run heavy programs like XAMPP. You only need PHP and MySQL installed on your system.

### Step 1 — Update credentials in `db.php`

Open `db.php` and update `DB_USER` and `DB_PASS` to match your local MySQL setup:

```php
define('DB_USER', 'root');
define('DB_PASS', 'your_mysql_password');  // ← Update this
```

### Step 2 — Setup Database

**Option 1: Using MySQL Command Line**

```bash
mysql -u root -p < setup.sql
```

**Option 2: Running in MySQL Shell manually**

```sql
CREATE DATABASE IF NOT EXISTS candle_leads CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE candle_leads;
CREATE TABLE IF NOT EXISTS leads (
    id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(254)  NOT NULL,
    scent      ENUM('Vanilla','Lavender','Sandalwood','Citrus','Unsure') NOT NULL,
    message    TEXT          NULL,
    created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

> **Note:** `db.php` will automatically create the database and table if they do not exist, so this step is optional. You can skip directly to running the server.

### Step 3 — Run PHP Built-in Server

1. Open your Terminal in the project folder (`candle-leads`).
2. Run the following command:

```bash
C:\php\php.exe -S localhost:8000
```

_(If you have configured the PHP PATH in your environment variables, you can simply type `php -S localhost:8000`)_

### Step 4 — Open in Browser

Open your browser and navigate to: **http://localhost:8000**

> **💡 Note on restarting the project:**
> When you reopen your Code Editor (e.g., VS Code), open the Terminal and run the `C:\php\php.exe -S localhost:8000` command again. Always remember to press `Ctrl + C` to stop the previous server before starting a new one to avoid Port conflicts.

---

## 🗄️ Database Configuration

Configured in `db.php`:

```php
define('DB_HOST',    '127.0.0.1');
define('DB_PORT',    '3306');
define('DB_NAME',    'candle_leads');
define('DB_USER',    'root');
define('DB_PASS',    '');          // Your MySQL password
define('DB_CHARSET', 'utf8mb4');
```

> **🔒 Security Note:** For a real production environment, credentials should be moved out of `db.php` and stored in a `.env` file using a library like `vlucas/phpdotenv`. This prevents sensitive data from accidentally being pushed to Git.

---

## 🗃️ `leads` Table Structure

```sql
CREATE TABLE leads (
    id         INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(254)  NOT NULL,
    scent      ENUM('Vanilla','Lavender','Sandalwood','Citrus','Unsure') NOT NULL,
    message    TEXT          NULL,
    created_at DATETIME      DEFAULT CURRENT_TIMESTAMP
);
```

**Why use `ENUM` for scent?**

> It enforces data integrity at the database level. It ensures only allowed values are stored, acting as an ultimate safeguard against invalid data, even if PHP validation is bypassed.

---

## 🧪 How to Test

| Scenario              | Testing Method                                                  | Expected Result                                                                 |
| --------------------- | --------------------------------------------------------------- | ------------------------------------------------------------------------------- |
| JS validation         | Click Submit without filling out the form                       | Real-time error messages appear below each invalid field.                       |
| Email format          | Enter an invalid email format like `abc`                        | Error message regarding invalid email format appears.                           |
| PHP validation bypass | Send a direct POST request via Postman missing the `name` field | Receives HTTP 422 + JSON error response.                                        |
| Successful submission | Fill out all fields correctly and Submit                        | Success message is displayed, and a new row appears in the MySQL database.      |
| DB connection failure | Set incorrect password in `db.php`                              | Receives HTTP 500 + generic error message (stack trace is hidden for security). |

---
