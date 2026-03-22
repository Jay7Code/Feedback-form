# John Hay Hotels - Forest Wing Guest Feedback System

A comprehensive, responsive, and elegant guest feedback system tailored for the **Forest Wing** of **John Hay Hotels**. This system captures detailed guest experiences across different hotel departments and provides powerful analytics and reporting for administrators.

## 🌟 Key Features

1. **Elegant Guest Feedback Form**
   - Captures comprehensive guest details (Name, Room Number, Dates of Stay, Nationality, Mode of Reservation).
   - Rating scales (1 to 5) for Front of House, Guestrooms, and Food & Beverage.
   - Text boxes for open-ended comments and suggestions.
   - Automatically sends a visually stunning **"Thank You" email** to the guest upon successful submission.

2. **Admin Dashboard (`/admin`)**
   - **Analytics**: Visual charts created with Chart.js to track NPS (Net Promoter Score) trends, demographic breakdowns (Purpose of Stay, Nationality), and departmental performance (FOH, F&B).
   - **Feedback Management**: View detailed submissions, filter by date ranges, and export records directly to CSV.
   - **Printable Reports**: Generate beautiful summary reports for specific date ranges, featuring data tables and top recognized staff, optimized for desktop and print.

3. **Super Admin Panel (`/superadmin`)**
   - Dedicated portal for IT/Management to create, activate, deactivate, or reset passwords for Admin accounts.

---

## 💻 Tech Stack

- **Frontend**: HTML5, CSS3 (Glassmorphism design), Tailwind CSS, Vanilla JavaScript, Chart.js.
- **Backend**: PHP 8+
- **Database**: MySQL/MariaDB (interacting via secure `mysqli` prepared statements)
- **Emails**: PHPMailer (SMTP integration)

---

## ⚙️ Installation & Setup

### 1. Web Server Setup
Deploy the project folder (`feedback-form`) into your local web server's root directory (e.g., `C:\xampp\htdocs\feedback-form` if using XAMPP).

### 2. Database Configuration
Open the `config.php` file in the root directory and ensure the database credentials match your local or production MySQL server:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hotel_feedback_db');
```

### 3. Database Initialization
To automatically create the required database schema, tables, and default accounts, open your browser and navigate to:
`http://localhost/feedback-form/setup_database.php`

Once the database is set up successfully, **delete or rename `setup_database.php`** to prevent accidental resets in a production environment.

### 4. Email Server (PHPMailer) Configuration
To enable the automated "Thank You" emails, configure your SMTP server credentials in `email/config.php`:
```php
define('SMTP_HOST', 'cpanel10wh.jpt1.cloud.z.com');
define('SMTP_USERNAME', 'noreply.johnhayhotels@theforestwing.com');
define('SMTP_PASSWORD', 'YOUR_ACTUAL_PASSWORD_HERE');
define('SMTP_PORT', 465); // or 587 depending on your host
define('SMTP_FROM_NAME', 'John Hay Hotels - Forest Wing');
```

---

## 🚀 How to Operate the System

### For Guests
Provide guests with the link to the root directory mapping:
- **URL**: `http://localhost/feedback-form/index.php`
They will be greeted with the feedback form. Upon completion, the data is pushed to the secure database and an email is fired via the SMTP credentials.

### For Administrators
Admins use this portal to view feedbacks and generate analytics / reports.
- **URL**: `http://localhost/feedback-form/admin/login.php`
- **Default Login**: 
  - **Username**: `admin`
  - **Password**: `password123`
*(Note: It is highly recommended to change this password or create a personal admin account via the Super Admin panel).*

### For Super Admins (IT / Management)
Super admins control who has access to the Admin Dashboard.
- **URL**: `http://localhost/feedback-form/superadmin/login.php`
- **Default Login**: 
  - **Username**: `superadmin`
  - **Password**: `SuperAdmin@2024!`
*(Note: This is defined in `config.php` as a hardcoded secure entry point).*

---

## 📁 Project Structure Breakdown

```
feedback-form/
│
├── index.php                 # The main guest feedback form
├── submit_feedback.php       # Processes the POST request and sends the email
├── config.php                # Database credentials & Super Admin constants
├── setup_database.php        # Migration script to build the DB
│
├── email/
│   └── config.php            # SMTP Variables for the Thank You email
│
├── admin/                    # Admin Dashboard
│   ├── index.php             # Feedback table & CSV Export
│   ├── analytics.php         # Chart.js Visualizations
│   ├── reports.php           # Printable & filterable reporting system
│   ├── login.php             # Admin Authentication
│   └── api/                  # JSON endpoints for charts and reports
│
├── superadmin/               # Super Admin Dashboard
│   ├── index.php             # Admin account management
│   ├── login.php             # Super Admin Authentication
│   └── api/                  # JSON endpoints for creating/editing admins
│
├── phpmailer/                # PHPMailer Library dependencies
└── img/                      # Logos and backgrounds
```

## 🔒 Security Best Practices for Production
1. Remove `setup_database.php` once initialized.
2. Change the default `admin` password immediately.
3. Keep your `email/config.php` and `config.php` out of public exposure if using advanced routing.
4. Ensure you have an active SSL certificate (HTTPS) installed on the domain to securely transmit user form data and login credentials.
