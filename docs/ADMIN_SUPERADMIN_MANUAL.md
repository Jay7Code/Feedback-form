# Guest Feedback System - Admin & Superadmin Manual
**John Hay Hotels | Forest Wing**

This manual provides detailed instructions for administrative staff and system overseers on how to use the Guest Feedback System to monitor performance, analyze guest sentiments, and manage user accounts.

---

## 📖 Table of Contents
1. [Introduction](#introduction)
2. [Admin Dashboard Guide](#admin-dashboard-guide)
    - [Login & Security](#login--security)
    - [Dashboard & Real-time Stats](#dashboard--real-time-stats)
    - [Filtering & Searching Feedback](#filtering--searching-feedback)
    - [Advanced Analytics & Trends](#advanced-analytics--trends)
    - [Custom Reports & Printing](#custom-reports--printing)
3. [Superadmin Panel Guide](#superadmin-panel-guide)
    - [Managing Administrator Accounts](#managing-administrator-accounts)
    - [Security Controls & Password Resets](#security-controls--password-resets)
4. [Troubleshooting & Support](#troubleshooting--support)

---

## 🌟 Introduction
The **John Hay Hotels - Forest Wing Guest Feedback System** is designed to transform guest opinions into actionable insights. 
- **Admins** focus on hospitality data, monitoring department scores (Front Office, Housekeeping, Dining), and responding to guest needs.
- **Superadmins** handle the system's "back-office" by managing who has access to this data.

---

## 🛠️ Admin Dashboard Guide

### Login & Security
Access the Admin Panel at `/admin/`.

#### First-Time Login / Mandatory Password Change
For security, all new accounts or accounts reset by a Superadmin are required to change their password upon their first login.
1. Enter your temporary credentials.
2. A secure popup will appear: **"Update Password"**.
3. Create a new password (minimum 6 characters).
4. Click **Save & Continue** to access the dashboard.

> [!TIP]
> Use a mix of letters, numbers, and symbols for a stronger password.

### Dashboard & Real-time Stats
The **Dashboard** is your command center. It provides:
- **Total Feedback**: All submissions to date.
- **Avg. Satisfaction**: The current running average (out of 5).
- **Latest Submission**: The most recent feedback date.
- **Feedback Table**: A paginated list of all guest responses.

### Filtering & Searching Feedback
Need to find a specific guest or stay? Use the filter bar:
- **Search**: Enter a Name, Email, or Room Number.
- **Date Range**: Select "Date From" and "Date To" to focus on a specific holiday or event period.
- **Room Filter**: Quickly see all feedback for a specific room.
- **Clear**: Reset all filters to view the full list.
- **Export CSV**: Download the current (filtered) list as a spreadsheet for use in Excel.

### Advanced Analytics & Trends
Navigate to the **Analytics** tab for visual insights:
- **Time Periods**: Toggle between *Today, This Week, This Month, Quarter,* and *All Time*.
- **Satisfaction Trend**: A line chart showing how scores change over time.
- **Department Performance**: Horizontal bar charts comparing **Front of House** and **Food & Beverage** services.
- **Demographics**: View **Guest Type** (First stay vs Returning), **Purpose of Stay**, and **Nationality Distribution**.

### Custom Reports & Printing
The **Reports** tab is designed for high-level meetings and physical records.
1. Select a **Quick Preset** (e.g., Yesterday or 7 Days) or a custom range.
2. Click **Generate Report**.
3. **Print Report**: Generates a professional, printer-friendly version with:
    - **Top Recognized Staff**: Employees mentioned positively by name.
    - **Score Breakdowns**: Detailed tables for every category.
    - **Guest Comments**: A paginated list of all text-based feedback.
4. **View Details**: Use the "View Details" buttons on the screen to open detailed popups without printing.

---

## 👑 Superadmin Panel Guide
Access the Superadmin Panel at `/superadmin/`. This area is restricted to system owners.

### Managing Administrator Accounts
The main interface allows you to oversee the entire administrative team:
- **Create New Admin**: Click the gold button to add a new staff member. You must provide their full name, a unique username, and a temporary password.
- **Admin Table**: View a list of all admins, their status (Active/Inactive), and when their account was created.

### Security Controls & Password Resets
Superadmins hold the key to system access:
- **Toggle Status**: Use the **Deactivate** button to immediately revoke an admin's access (e.g., if they leave the company). Use **Activate** to restore it.
- **Reset Password**: If an admin forgets their password, click **Reset Password** to assign them a new temporary one. 
    - *Note: The admin will be forced to change this password on their next login.*

---

## 🔍 Troubleshooting & Support

### "Access Denied" Error
- Ensure you are logged into the correct panel (Admin vs Superadmin).
- Sessions expire after long periods of inactivity. Please try logging in again.

### Charts Not Loading
- Check your internet connection. The analytics engine requires an active connection to load visualization libraries.
- Try refreshing the page.

### Password Change Required Loop
- If you are repeatedly asked to change your password, ensure the new password meets the 6-character requirement and that the passwords match exactly.

---
*© 2026 John Hay Hotels - Forest Wing. All Rights Reserved.*
