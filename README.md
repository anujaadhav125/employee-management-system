# 👨‍💼 StaffSync - HR Management System

StaffSync is a modern Human Resource Management System (HRMS) developed using **PHP**, **MySQL**, **Bootstrap 5**, **JavaScript**, and **Chart.js**. It helps organizations manage employees, departments, attendance, designations, and reports through an interactive dashboard.

---

## 🚀 Features

- 🔐 Secure Admin Login
- 📊 Interactive Dashboard
- 👨‍💼 Employee Management (Add, Edit, Delete, Search)
- 🏢 Department Management
- 💼 Designation Management
- 📅 Attendance Management
- 📈 Attendance Reports
- 📋 Employee Reports
- 📊 Department-wise Employee Chart
- 🥧 Attendance Statistics Chart
- 🎨 Responsive Modern UI
- 🔍 Search & Filter
- 📱 Mobile Responsive Design

---

## 🛠️ Technologies Used

- PHP
- MySQL
- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- Chart.js
- Bootstrap Icons
- XAMPP

---

## 📁 Project Structure

```
StaffSync/
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── attendance/
├── auth/
├── config/
├── dashboard/
├── department/
├── designation/
├── employee/
├── includes/
├── reports/
│
├── index.php
└── README.md
```

---

## ⚙️ Installation

### 1. Clone Repository

```bash
git clone https://github.com/your-username/staffsync.git
```

### 2. Move Project

Copy the project into

```
xampp/htdocs/
```

### 3. Create Database

Open phpMyAdmin

Create database

```
staffsync_db
```

### 4. Import Database

Import

```
staffsync_db.sql
```

### 5. Configure Database

Open

```
config/database.php
```

Update database credentials

```php
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','staffsync_db');
```

### 6. Start XAMPP

Start

- Apache
- MySQL

### 7. Run Project

```
http://localhost/staffsync/
```

---

## 📊 Dashboard

The dashboard provides

- Total Employees
- Total Departments
- Active Employees
- Inactive Employees
- Employees by Department Graph
- Today's Attendance Chart
- Recent Employees Table

---

## 📸 Screenshots

Add screenshots inside

```
screenshots/
```

Example

```
screenshots/dashboard.png
screenshots/employees.png
screenshots/attendance.png
screenshots/reports.png
```

---

## 📌 Future Enhancements

- Employee Profile Photo Upload
- Payroll Management
- Leave Management
- Email Notifications
- Role-based Authentication
- Export Reports to PDF & Excel

---

## 👩‍💻 Developed By

**Anuja Adhav**

Bachelor of Engineering (Information Technology)

Savitribai Phule Pune University

---

## ⭐ Support

If you like this project, don't forget to ⭐ star this repository.
