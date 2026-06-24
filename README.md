# 🔐 Secure SaaS Invoicing System

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Stripe](https://img.shields.io/badge/Stripe-Payments-635BFF?style=for-the-badge&logo=stripe)](https://stripe.com)

A **production-ready, multi-tenant SaaS invoicing application** built with Laravel 12. This system allows businesses to manage customers, generate invoices, accept online payments via Stripe, and provides a dedicated client portal for customers to view and pay their bills.

---

## 🌟 Features

### Core Functionality
- ✅ **Multi-Tenant Architecture** - Complete data isolation using Laravel Global Scopes.
- ✅ **UUID Primary Keys** - Prevents enumeration attacks and secures database IDs.
- ✅ **Customer Management** - Full CRUD operations with optional portal access.
- ✅ **Invoice Generation** - Dynamic line items, automatic tax calculations, and PDF export.
- ✅ **Stripe Payment Gateway** - Secure online payments with webhook signature verification.
- ✅ **Client Portal** - Customers can login, view invoices, and make payments securely.
- ✅ **RESTful API** - Laravel Sanctum authentication for future mobile app integration.
- ✅ **Automated Reminders** - Daily cron jobs to send payment reminder emails.
- ✅ **Analytics Dashboard** - Real-time charts (Monthly Revenue, Status Distribution) using Chart.js.
- ✅ **Mobile Responsive** - Fully responsive UI built with Tailwind CSS.

### Security Features
- 🔒 **CSRF & XSS Protection** - Built-in Laravel security mechanisms.
- 🔒 **IDOR Prevention** - TenantScope ensures users can only access their own data.
- 🔒 **Password Hashing** - Secure bcrypt algorithm.
- 🔒 **Rate Limiting** - Throttled login attempts to prevent brute-force attacks.
-  **Webhook Signature Verification** - Ensures Stripe webhooks are authentic.

---

## 🛠️ Tech Stack

| Category | Technology |
|----------|-----------|
| **Backend Framework** | Laravel 12 |
| **Programming Language** | PHP 8.2 |
| **Frontend** | Blade Templates, Tailwind CSS, Chart.js |
| **Database** | SQLite / MySQL |
| **Authentication** | Laravel Sanctum (API), Session Auth (Web) |
| **Payment Gateway** | Stripe API |
| **PDF Generation** | DomPDF |

---

## 🚀 Installation Guide

### Step 1: Clone the Repository
git clone https://github.com/CyberPhantomX1/secure-saas-invoicing.git
cd secure-saas-invoicing

### Step 2: Install Dependencies
composer install
npm install && npm run build

### Step 3: Environment Configuration
cp .env.example .env
php artisan key:generate

### Step 4: Database Setup & Migrations
Open .env and set DB_CONNECTION=sqlite (Create an empty database.sqlite file in the database/ folder).
php artisan migrate

### Step 5: Start Server
php artisan serve

Visit: http://localhost:8000

### 📡 API Endpoints
<img width="1005" height="310" alt="image" src="https://github.com/user-attachments/assets/689ded70-a602-44b5-bcdd-9b7184adc4bb" />

### Testing (use stripe according to you).
Web App: Register, create a customer, and generate an invoice.
Stripe Payment: Use test card 4242 4242 4242 4242 (Expiry: 12/30, CVC: 123).
Client Portal: Visit /portal/login using the customer's email and portal password.

git clone https://github.com/CyberPhantomX1/secure-saas-invoicing.git
cd secure-saas-invoicing


### UI/UX and other design and work flow
<img width="1900" height="972" alt="image" src="https://github.com/user-attachments/assets/8565a5ed-eda9-4905-aa35-8670d173baac" />
<img width="1906" height="967" alt="image" src="https://github.com/user-attachments/assets/e45787ff-ddbc-4403-9dd0-84a36f8b5da8" />
<img width="1907" height="858" alt="image" src="https://github.com/user-attachments/assets/23c96b01-bbfc-4c76-80f8-11b07a89166b" />
<img width="1888" height="971" alt="image" src="https://github.com/user-attachments/assets/d164dd92-857a-40be-a7ee-d42ca31bf838" />
<img width="1887" height="965" alt="image" src="https://github.com/user-attachments/assets/d61c78ec-adb2-4ce6-938f-2a288f72f018" />
<img width="1890" height="960" alt="image" src="https://github.com/user-attachments/assets/beef5f55-507a-4b76-8751-28ac5519bfb6" />


### api testing images
<img width="1917" height="1025" alt="Screenshot 2026-06-24 234735" src="https://github.com/user-attachments/assets/62d6124f-54a7-4ada-9c5a-418f0dcf6179" />
<img width="1916" height="995" alt="Screenshot 2026-06-24 235716" src="https://github.com/user-attachments/assets/9013535a-98e4-4d05-9d8f-4a0568e668ac" />
<img width="1917" height="1017" alt="Screenshot 2026-06-25 000536" src="https://github.com/user-attachments/assets/b25b7e69-e6eb-4389-8531-8472822e3d3f" />
<img width="1917" height="1018" alt="Screenshot 2026-06-25 000550" src="https://github.com/user-attachments/assets/f6ab5912-6290-4582-bcb7-89e80fee96eb" />
<img width="1917" height="1020" alt="Screenshot 2026-06-25 000543" src="https://github.com/user-attachments/assets/7bad72e6-2542-49c1-9155-01690df3e87f" />
<img width="1917" height="1017" alt="Screenshot 2026-06-25 000557" src="https://github.com/user-attachments/assets/9c73fd5b-9348-45ad-b95a-2fafd51bbdf3" />
<img width="1917" height="1017" alt="Screenshot 2026-06-25 000613" src="https://github.com/user-attachments/assets/5221e0c9-afe7-4196-978b-3844b8555c83" />
<img width="1917" height="1020" alt="Screenshot 2026-06-25 000606" src="https://github.com/user-attachments/assets/60381d5b-7c64-4ee5-8d60-e213ea607353" />


The Secure SaaS Invoicing System represents a production-grade, enterprise-level solution engineered to modernize the complete billing and payment lifecycle for businesses. Developed using Laravel 12 with PHP 8.2, this multi-tenant SaaS platform demonstrates advanced software architecture patterns including Global Scopes for automatic data isolation, ensuring that multiple businesses can operate securely on a shared infrastructure without any data leakage.
The application goes beyond traditional invoicing by implementing a comprehensive payment ecosystem. It features dynamic invoice generation with real-time backend financial calculations (preventing client-side manipulation), professional PDF export using DomPDF, and seamless Stripe payment gateway integration with webhook signature verification for automatic payment reconciliation. A dedicated client portal empowers customers with self-service capabilities to view invoices, track payment history, and complete transactions securely.
From a technical architecture standpoint, the system prioritizes security through multiple layers: UUID primary keys eliminate enumeration attacks, TenantScope prevents IDOR vulnerabilities at the ORM level, CSRF/XSS protection is enforced throughout, and rate limiting safeguards authentication endpoints. The platform also exposes a fully-documented RESTful API secured with Laravel Sanctum's Bearer token authentication, enabling seamless mobile application integration.
Operational efficiency is achieved through automated features including daily cron-scheduled payment reminder emails, real-time analytics dashboards powered by Chart.js for revenue tracking, and database optimization through strategic indexing and query caching. The entire system follows SOLID principles, implements proper separation of concerns, and adheres to PSR-12 coding standards, making it a maintainable, scalable, and production-ready solution that demonstrates mastery of modern full-stack web development, API design, payment processing, and enterprise security practices.
