# E-commerce Management Backend API

A production-ready, high-performance **RESTful API** built with **Laravel 11**. This system serves as the core engine for modern e-commerce platforms, featuring secure authentication, role-based access control, persistent cart management, and an atomic checkout system with automated stock synchronization.

---

## 🏛️ System Architecture

The backend follows the **MVC (Model-View-Controller)** pattern and leverages Laravel's robust ecosystem to ensure scalability and data integrity.

### 🗄️ Database Schema & Relationships
- **User ↔ Order:** One-to-Many (A user can track multiple historical orders).
- **Order ↔ OrderItem:** One-to-Many (One order acts as a container for multiple products).
- **Product ↔ Category/Brand:** BelongsTo (Strict categorization for optimized searching and filtering).
- **User ↔ CartItem:** One-to-Many (Database-backed persistent cart logic).

---

## 🌟 Key Technical Features

### 🔐 Multi-Layered Security
- **Authentication:** Powered by **Laravel Sanctum** for secure, token-based stateful authentication.
- **Role-Based Access Control (RBAC):** Custom `AdminMiddleware` protects sensitive administrative endpoints.
- **Authorization Checks:** Ownership verification ensures customers can only access their own orders and invoices.
- **Request Validation:** Strict validation rules for all incoming data to prevent SQL injection and malformed inputs.

### 🛒 Advanced Checkout Engine
The checkout process is designed for high reliability using **Atomic Database Transactions**:
1. **Row Locking:** Uses `lockForUpdate()` during stock verification to prevent race conditions.
2. **Transaction Integrity:** Uses `DB::transaction()` to ensure that if any step (order creation, stock update, or cart clearing) fails, the entire process is rolled back.
3. **Automated Inventory:** Real-time stock decrementing upon successful order placement.

### 📂 Media & Storage Management
- **Intelligent File Handling:** Managed via Laravel's `Storage` facade.
- **Automated Cleanup:** When a product is updated or deleted, the system automatically removes associated image files from the server to optimize disk space.

### 📄 Professional Invoicing
- **PDF Generation:** Integrated with `barryvdh/laravel-dompdf`.
- **Dynamic Invoices:** Generates itemized, professional invoices including unique invoice numbers, tax calculations, and customer details.

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

---

## ⚙️ Installation & Setup

1. **Clone & Install:**
   ```bash
   git clone [https://github.com/yourusername/ecommerce-backend.git](https://github.com/yourusername/ecommerce-backend.git)
   cd ecommerce-backend
   composer install
