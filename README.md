# E-commerce Management Backend API

A production-ready, high-performance **RESTful API** built with **Laravel 11**. This system serves as the core engine for modern e-commerce platforms, featuring secure authentication, role-based access control, persistent cart management, and an atomic checkout system with automated stock synchronization.

---

## 🏛️ System Architecture

The backend implements a **Service-Repository Pattern** and leverages Laravel's robust ecosystem to ensure scalability and data integrity.

### 🗄️ Database Schema & Relationships
*   **User ↔ Order:** One-to-Many (Persistent historical tracking).
*   **Order ↔ OrderItem:** One-to-Many (Line-item granularity for financial reporting).
*   **Product ↔ Category/Brand:** BelongsTo (Indexed for optimized filtering and search).
*   **User ↔ CartItem:** One-to-Many (Database-backed persistent shopping sessions).

---

## 🌟 Key Technical Features

### 🔐 Enterprise-Grade Security
*   **Authentication:** Powered by **Laravel Sanctum** for secure, token-based stateful authentication.
*   **RBAC (Role-Based Access Control):** Custom `AdminMiddleware` protects sensitive administrative endpoints.
*   **Resource Authorization:** Laravel **Policies** ensure customers can only access their own orders and invoices.
*   **Input Integrity:** Strict **Form Request Validation** to prevent malformed data and XSS/SQL injection.

### 🛒 High-Concurrency Checkout Engine
The checkout process is engineered for **Atomicity** and **Isolation** using Database Transactions:
1.  **Pessimistic Locking:** Utilizes `lockForUpdate()` during stock verification to prevent race conditions during flash sales.
2.  **ACID Compliance:** Wraps the entire lifecycle (order creation, stock decrement, and cart clearing) in `DB::transaction()` to ensure zero data corruption.
3.  **Inventory Logic:** Real-time stock management with automated "Out of Stock" status triggers.

### 📂 Optimized Media Handling
*   **Filesystem Abstraction:** Managed via Laravel’s `Storage` facade for easy migration from local disks to **AWS S3** or **DigitalOcean Spaces**.
*   **Garbage Collection:** Automated cleanup hooks delete associated image files when products are removed, preventing "ghost" files from bloating storage.

### 📄 Professional Invoicing
*   **PDF Engine:** Integrated with `barryvdh/laravel-dompdf`.
*   **Dynamic Generation:** Renders itemized, professional invoices with unique invoice numbers, tax calculations, and customer-specific metadata.

---

## 📂 API Endpoint Documentation

### 🔓 Public Endpoints
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `POST` | `/api/register` | Register a new user account |
| `POST` | `/api/login` | Login and receive Bearer Token |
| `GET` | `/api/products` | Browse products with advanced filtering & pagination |
| `GET` | `/api/categories`| List all product categories |

### 🛍️ Customer Endpoints (Auth Required)
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/api/cart` | Retrieve current cart items |
| `POST` | `/api/cart/add` | Add or increment product in cart |
| `DELETE`| `/api/cart/remove/{id}`| Remove specific item from cart |
| `POST` | `/api/checkout` | Process order and clear cart atomically |
| `GET` | `/api/my-orders` | View personal order history |
| `GET` | `/api/order/invoice/{id}`| Generate and download PDF invoice |

### 🛠️ Admin Endpoints (Admin Middleware Required)
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `POST` | `/api/products` | Create new product with multi-file upload |
| `PATCH` | `/api/products/{id}` | Update product details and stock |
| `GET` | `/api/admin/orders` | Global view of all orders in the system |
| `POST` | `/api/admin/order-status/{id}`| Transition order state (e.g., Pending → Shipped) |

---

## 🔮 Roadmap
*   **Payment Gateways:** Integration with **Stripe**, **SSLCommerz**, and **PayPal**.
*   **Asynchronous Processing:** Moving emails and PDF generation to **Redis Queues** for sub-second response times.
*   **Advanced Analytics:** Dedicated endpoints for sales trends, low-stock alerts, and customer lifetime value (CLV).
*   **Webhooks:** Automated notifications for order status changes via Slack or Discord.

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
[GitHub](https://github.com/pialmahmud) | [LinkedIn](https://linkedin.com/in/pialmahmud)
