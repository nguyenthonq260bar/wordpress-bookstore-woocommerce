# The Mountain Book

An e-commerce bookstore website developed using **WordPress** and **WooCommerce** as the final project for the E-commerce course.

## Project Overview

The Mountain Book is an online bookstore that provides core e-commerce functionalities, including:

- Product management
- Product categories and tags
- Shopping cart
- Checkout process
- Order management
- Customer accounts
- Inventory management
- QR code payment (VietQR)
- Dark / Light Mode
- Live Chat support
- Book recommendation AI
- Wishlist and AJAX product search

## Technologies

- WordPress
- WooCommerce
- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- Docker Compose

## Project Structure

```text
.
├── database.sql          # Database backup
├── docker-compose.yml    # Docker configuration
└── wordpress/            # WordPress source code
```

## Installation

1. Clone this repository.

```bash
git clone git@github.com:nguyenthonq260bar/wordpress-bookstore-woocommerce.git
```

2. Import `database.sql` into MySQL.

3. Copy the `wordpress/` directory to your web server.

4. Configure the database connection in `wp-config.php`.

5. Access the website through your local server or hosting environment.

## Theme

**Thefirstdayinyourlife** (v3.5)

Custom WordPress theme developed specifically for this project.

Location:

```
wordpress/wp-content/themes/thefirstdayinyourlife/
```

## Custom Plugin

**Company WooCommerce** (v1.2.0)

Custom WooCommerce extension developed for this project.

Features include:

- AJAX Search
- Product Sidebar Filter
- Wishlist
- Testimonials
- English / Vietnamese Language Switcher
- Zalo Contact Button

Location:

```
wordpress/wp-content/plugins/company-woocommerce/
```

## Repository Contents

- Complete WordPress source code
- Database export (`database.sql`)
- Custom Theme
- Custom WooCommerce Plugin

## Author

Nguyen Thong

GitHub: https://github.com/nguyenthonq260bar
