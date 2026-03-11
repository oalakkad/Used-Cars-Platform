# AutoMarket - Used Car Marketplace

## Overview
A premium, production-level used car marketplace platform built with PHP, MySQL, HTML/CSS/JS.

## Requirements
- PHP 8.0+
- MySQL 5.7+
- Apache/Nginx web server

## Setup

### 1. Database
1. Import the database schema:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
2. Update database credentials in `includes/config/db.php` if needed.

### 2. Web Server
Point your web server's document root to the `public/` directory.

For local development with XAMPP/WAMP:
- Place the project in your `htdocs` folder
- Access via `http://localhost/Used%20Cars%20Website/public/`

### 3. File Uploads
Ensure the `public/assets/uploads/` directory is writable:
```bash
chmod 755 public/assets/uploads/
```

## Demo Account
- **Email:** demo@automarket.com
- **Password:** password123

## Features
- 🔍 Advanced search & filtering
- 🏷️ Brand browsing
- 📸 Multi-image car listings
- ❤️ Favorites system
- 📊 User dashboard
- 🌙 Dark/Light mode
- 📱 Fully responsive
- 🔒 Secure authentication

## Tech Stack
- **Backend:** PHP 8 (MVC), MySQL
- **Frontend:** HTML5, CSS3 (Custom Variables), Vanilla JS
- **Libraries:** FontAwesome, Swiper.js, Google Fonts (Inter, Outfit)
