# E-commerce Management API

A production-ready, high-performance **RESTful API** built with **Laravel 11**. This system serves as the core engine for modern e-commerce platforms, featuring secure authentication, role-based access control, persistent cart management, and an atomic checkout system with automated stock synchronization. In a professional setting, an e-commerce backend isn't just about showing products; it's about trust and data integrity

---

## 🏛️ System Architecture

The backend development is complete, using a secure RESTful API with high-efficiency modules for scalability and data integrity.

### 🗄️ 1. Database Layer (Persistence & Integrity)
*   **Finalized Schema:** Migrations and Seeders are fully optimized for a three-tier hierarchy:
    *   **Admin (ID: 1):** Full system control.
    *   **Manager (ID: 2):** Operational oversight.
    *   **User (ID: 3):** Standard customer access.
*   **Data Management:** Developed advanced seeding scripts that ensure a clean data reset via `TRUNCATE` and temporary foreign key check bypasses for consistent development environments.
*   **Relationships:** Optimized One-to-Many and BelongsTo associations between Users, Orders, Products, and Categories.

### 🔐 2. Security Layer (Hierarchical RBAC)
*   **Authentication:** Powered by **Laravel Sanctum** for secure, token-based stateful authentication.
*   **Upgraded Middleware:** The `AdminMiddleware` has been evolved into a hierarchical permission model:
    *   **Operational Access:** Both **Admins** and **Managers** are granted access to core operational routes (Orders and Inventory Management).
    *   **Role Restriction:** Destructive `DELETE` actions are strictly reserved for the **Admin** role only, preventing accidental data loss by staff.
*   **Resource Authorization:** Uses Laravel Policies to ensure customers can only access their personal order data and invoices.

### 🛒 3. Business Logic & Checkout Engine
*   **Atomic Transactions:** Implemented `DB::transaction` for checkouts to ensure data integrity—order creation, stock updates, and cart clearing succeed or fail as a single unit.
*   **Inventory Protection:** Automated stock management utilizing `lockForUpdate()` to handle high-concurrency scenarios and prevent overselling.
*   **Document Automation:** Dynamic PDF invoice generation integrated with secure download streaming for professional customer fulfillment.

### 📂 4. Storage & Media Management
*   **Automated Handling:** Integrated server-side file handling for product imagery via Laravel's `Storage` facade.
*   **Disk Optimization:** Implemented automated cleanup routines that remove associated files from the `public` disk during product updates or deletions to maintain a lean server footprint.  

---

## 📂 API Access Levels

The API architecture features professional RESTful routing categorized by access level:

| Role | Operational Access | Destructive Actions (DELETE) | Reporting Access |
| :--- | :--- | :--- | :--- |
| **Admin** | ✅ Yes | ✅ Yes | ✅ Yes |
| **Manager** | ✅ Yes | ❌ No | ✅ Yes |
| **User** | ❌ No | ❌ No | ❌ No |

---

## 📂 API Endpoint Documentation

### 🔓 Public Endpoints
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| POST | `/api/register` | Register a new user account |
| POST | `/api/login` | Login and receive Bearer Token |
| GET | `/api/products` | Browse products with search & pagination |
| GET | `/api/categories`| List all product categories |

### 🛍️ Customer Endpoints (Auth Required)
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| GET | `/api/cart` | Retrieve current cart items |
| POST | `/api/cart/add` | Add or increment product in cart |
| DELETE| `/api/cart/remove/{id}`| Remove specific item from cart |
| POST | `/api/checkout` | Process order and clear cart |
| GET | `/api/my-orders` | View personal order history |
| GET | `/api/order/invoice/{id}`| Download PDF invoice |

### 🛠️ Admin Endpoints (Admin Middleware Required)
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| POST | `/api/products` | Create new product with image upload |
| POST | `/api/categories` | Manage product categories |
| GET | `/api/admin/orders` | View all orders in the system |
| POST | `/api/admin/order-status/{id}`| Update order (e.g., Pending to Shipped) |
| GET | `/api/reports/sales` | Generate sales reports with date filters |
| GET | `/api/reports/sales/pdf`| **Export Sales Report as PDF** |
| GET | `/api/reports/stock` | View real-time stock and low-stock alerts |

---

## 🔮 Roadmap
*   **Payment Gateways:** Integration with **Stripe**, **SSLCommerz**, and **PayPal**.
*   **Queued Jobs:** Moving email notifications and PDF generation to **Redis Queues** to minimize latency.
*   **Webhooks:** Automated status notifications for Slack or third-party logistics.

---

## 🌟 TECHNICAL STACK SUMMARY:

"The Backend API for the E-commerce Management System is officially 'Production Ready'. 

- Framework: Laravel 11 (REST API Architecture)
- Security: Hierarchical RBAC (Admin, Manager, User) with Method-Level Access Control.
- Database: Optimized MySQL schema with Transactional Integrity (ACID compliant).
- Features: Automated Stock Control, Persistent Cart Logic, and Multi-Role Invoicing.
- Documentation: Complete README.md and PDF Technical Guides generated.

The system is now fully prepared for Frontend Integration with Vue 3 / Inertia.js."

---

## 📜 License

This project is open-source and available under the **MIT License**.

Copyright (c) 2026 **Pial Mahmud**

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

---

### 👨‍💻 Developer
**Pial Mahmud**  
*Full-Stack Software Engineer*  
[GitHub](https://github.com/mahmudpial) | [LinkedIn](https://linkedin.com/in/pialmahmud)
