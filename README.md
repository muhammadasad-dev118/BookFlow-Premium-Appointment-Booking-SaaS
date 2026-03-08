<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

# BookFlow — Premium Appointment Booking SaaS

BookFlow is an advanced, multi-tenant SaaS platform built for modern service-oriented businesses. It provides a seamless, high-performance experience for managing appointments, staff availability, and customer relationships, all wrapped in a premium, glassmorphism-inspired dark interface.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Vite](https://img.shields.io/badge/Vite-6.x-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Stripe](https://img.shields.io/badge/Stripe-v3-6772E5?logo=stripe&logoColor=white)](https://stripe.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

---

## 🌟 Key Features

### 🏢 Multi-Tenant Architecture
- **Isolated Workspaces**: Every business operates in a secure, isolated environment using custom session-based tenant context.
- **Dynamic Onboarding**: Simple registration flow that automatically provisions a new business tenant and owner account.

### 🎨 Premium Custom Dashboard
- **Glassmorphism UI**: A high-end, high-contrast dark theme designed for professional environments.
- **Real-time Analytics**: Interactive charts (Chart.js) for revenue trends and service distribution.
- **Resource Management**: Custom CRUD interfaces for Services, Staff, Customers, and Appointments.

### 📅 Advanced Booking Engine
- **Availability Logic**: Intelligent engine that prevents scheduling conflicts and respects staff business hours.
- **Multi-step Wizard**: A frictionless, Livewire-powered booking experience for end customers.
- **Secure Payments**: Integrated Stripe Checkout for pre-paid bookings.

### 💳 SaaS Monetization
- **Tiered Subscriptions**: Built-in Basic and Pro plans with specialized feature gating.
- **Billing Portal**: Seamless integration with Stripe Customer Portal for subscription management.

### 🛡️ Enterprise-Grade Security
- **Strict Headers**: Comprehensive Content Security Policy (CSP) and security headers.
- **Tenant Scoping**: Global model scopes ensure cross-tenant data leakage is impossible.

---

## 🚀 Tech Stack

- **Framework**: [Laravel 12](https://laravel.com)
- **Frontend**: [Livewire](https://livewire.laravel.com), [Alpine.js](https://alpinejs.dev), [Tailwind CSS](https://tailwindcss.com)
- **Charts**: [Chart.js](https://www.chartjs.org/)
- **Payments**: [Stripe](https://stripe.com) via [Laravel Cashier](https://laravel.com/docs/billing)
- **Persistence**: MySQL / PostgreSQL
- **Build Tool**: Vite

---

## 🛠️ Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL

### Step-by-Step Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/bookflow.git
   cd bookflow
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Edit `.env` and set your database and Stripe credentials.*

4. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Start the server**
   ```bash
   php artisan serve
   ```

---

## 📸 Screenshots

| Admin Statistics | Business Dashboard |
|---|---|
| ![Admin](file:///C:/Users/sharjah laptop/.gemini/antigravity/brain/fae67166-9e8e-4389-8b91-7e61007566ad/dashboard_admin_1772950826630.png) | ![Tenant](file:///C:/Users/sharjah laptop/.gemini/antigravity/brain/fae67166-9e8e-4389-8b91-7e61007566ad/final_dashboard_check_1772953185851.png) |

---

## 📜 License

The BookFlow platform is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
*Developed with ❤️ by **Muhammad Asad** for modern businesses.*
