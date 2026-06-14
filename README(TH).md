# 🕯️ Lumière Candle Co. — Lead Capture Web App

ระบบ Lead Capture สำหรับธุรกิจเทียนหอม สร้างด้วย **PHP + MySQL + HTML/CSS/JS**

---

## 📁 โครงสร้างไฟล์

```
candle-leads/
├── index.php       ← หน้าหลัก Landing Page + HTML Form
├── save_lead.php   ← API endpoint รับ POST, validate, บันทึก DB
├── db.php          ← MySQL connection ผ่าน PDO (แก้ credentials ที่นี่)
├── setup.sql       ← SQL สำหรับสร้าง database + table
├── style.css       ← Dark Luxury Theme + animations
├── script.js       ← JS validation + AJAX submit
├── .gitignore      ← ไฟล์ที่ไม่ต้อง push ขึ้น Git
└── README.md       ← ไฟล์นี้
```

---

## 🛠️ สิ่งที่ต้องติดตั้งก่อน

| โปรแกรม | วิธีตรวจสอบ | ดาวน์โหลด |
|---------|------------|-----------|
| **PHP 8.x** | `php -v` | https://windows.php.net/download (เลือก VS17 x64 Thread Safe) |
| **MySQL 8.x** | `mysql --version` | https://dev.mysql.com/downloads/mysql/ |

> **หมายเหตุ Windows:**
> 1. หลังติดตั้ง PHP ให้เพิ่ม path ของ PHP (เช่น `C:\php`) เข้า Environment Variable `PATH`
> 2. ตรวจสอบไฟล์ `php.ini` ว่าได้เปิดใช้งาน extension ต่อไปนี้แล้ว (เอาเครื่องหมาย `;` ด้านหน้าออก):
> ```
> extension=pdo_mysql
> extension=mbstring
> extension=openssl
> ```

---

## ⚙️ วิธีรัน (แบบไม่ต้องใช้ XAMPP)

โปรเจ็คนี้ใช้ **PHP Built-in Server** ทำให้ไม่จำเป็นต้องติดตั้งและเปิดโปรแกรมอย่าง XAMPP ให้ยุ่งยาก ขอแค่มี PHP และ MySQL ก็รันได้ทันที

### ขั้นตอนที่ 1 — แก้ไข credentials ใน `db.php`

เปิดไฟล์ `db.php` แล้วแก้ค่า `DB_USER` และ `DB_PASS` ให้ตรงกับ MySQL ของคุณ:

```php
define('DB_USER', 'root');
define('DB_PASS', 'your_mysql_password');  // ← แก้ตรงนี้
```

### ขั้นตอนที่ 2 — Setup Database

**วิธีที่ 1: ใช้ MySQL Command Line**

```bash
mysql -u root -p < setup.sql
```

**วิธีที่ 2: รันใน MySQL Shell ทีละคำสั่ง**

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

> **หมายเหตุ:** `db.php` จะสร้าง database และ table อัตโนมัติถ้ายังไม่มี ดังนั้นขั้นตอนนี้เป็น optional สามารถข้ามไปรัน Server ได้เลย

### ขั้นตอนที่ 3 — รัน PHP Built-in Server

1. เปิด Terminal ในโฟลเดอร์โปรเจ็ค (`candle-leads`)
2. รันคำสั่งต่อไปนี้:

```bash
C:\php\php.exe -S localhost:8000
```
*(ถ้าตั้งค่า PATH ของ PHP ไว้แล้ว สามารถพิมพ์แค่ `php -S localhost:8000` ได้เลย)*

### ขั้นตอนที่ 4 — เปิดเว็บ

เปิดเบราว์เซอร์ไปที่: **http://localhost:8000**

> **💡 หากต้องการปิด/เปิดโปรเจ็คใหม่:**
> เมื่อเปิดโปรแกรม Editor (เช่น VS Code) ขึ้นมาใหม่ ให้เปิด Terminal รันคำสั่ง `C:\php\php.exe -S localhost:8000` อีกครั้ง และอย่าลืมกด `Ctrl + C` เพื่อปิด Server เดิมทุกครั้งหากจะรันใหม่ เพื่อป้องกันปัญหา Port ชนกัน

---

## 🗄️ Database Configuration

แก้ค่าใน [db.php](db.php):

```php
define('DB_HOST',    '127.0.0.1');
define('DB_PORT',    '3306');
define('DB_NAME',    'candle_leads');
define('DB_USER',    'root');
define('DB_PASS',    '');          // ใส่ password MySQL ของคุณ (ถ้ามี)
define('DB_CHARSET', 'utf8mb4');
```

> **🔒 Security Note:** สำหรับ production จริง ควรย้าย credentials ออกจาก `db.php` ไปเก็บใน `.env` file แทน และใช้ library เช่น `vlucas/phpdotenv` เพื่อโหลดค่า ป้องกันไม่ให้ข้อมูลสำคัญหลุดขึ้น Git โดยไม่ตั้งใจ

---

## 🗃️ โครงสร้างตาราง `leads`

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

**ทำไมใช้ `ENUM` สำหรับ scent?**
> บังคับให้เก็บแค่ค่าที่อนุญาตไว้ระดับ database ไม่ต้องพึ่ง validation ฝั่ง PHP อย่างเดียว เป็นการป้องกันข้อมูลผิดปกติตั้งแต่ชั้น schema

---

## 🧪 วิธีทดสอบ

| สถานการณ์ | วิธีทดสอบ | ผลที่คาดหวัง |
|----------|----------|-------------|
| JS validation | กด Submit โดยไม่กรอกข้อมูล | เห็น error message ใต้แต่ละ field ทันที |
| Email format | กรอกอีเมลผิดรูปแบบ เช่น `abc` | แจ้ง error "รูปแบบอีเมลไม่ถูกต้อง" |
| PHP validation bypass | ส่ง POST ตรงๆ ผ่าน Postman โดยไม่ส่ง field `name` | ได้รับ HTTP 422 + JSON error กลับมา |
| บันทึกสำเร็จ | กรอกข้อมูลครบถ้วนแล้ว Submit | ขึ้นข้อความสำเร็จ และเช็ค MySQL Workbench ว่ามีแถวใหม่ |
| DB connection ล้มเหลว | แก้ password ใน `db.php` ให้ผิด | ได้รับ HTTP 500 + ข้อความ error generic (ไม่เปิดเผย stack trace) |

---

## ✅ สิ่งที่ทำงานได้

- [x] Landing Page สวยงาม (Dark Luxury Theme)
- [x] ฟอร์ม: ชื่อ, อีเมล, กลิ่น (Scent Card UI), ข้อความ (optional)
- [x] **JavaScript validation** แบบ real-time
- [x] **PHP validation** ฝั่ง server (ป้องกัน bypass JS)
- [x] **SQL Injection protection** ด้วย PDO Prepared Statements + `ATTR_EMULATE_PREPARES => false`
- [x] บันทึกข้อมูลลง MySQL
- [x] **AJAX submit** — ไม่ reload หน้า
- [x] Responsive design (mobile + desktop)
- [x] Auto-create database และ table เมื่อรันครั้งแรก

## ❌ สิ่งที่จะปรับปรุงต่อ (What to improve next)

- [ ] CSRF Token ป้องกัน cross-site attacks
- [ ] Rate Limiting จำกัด submission ต่อ IP
- [ ] Email uniqueness check ก่อน insert
- [ ] Admin panel ดูรายการ leads
- [ ] Email confirmation ให้ผู้สมัคร
- [ ] ย้าย credentials ไปไว้ใน `.env` file

---

## 🔒 .gitignore

ไฟล์ที่ไม่ควร push ขึ้น Git:

```
# Environment & credentials
.env

# Log files
*.log

# OS files
.DS_Store
Thumbs.db
```

---