<h2>Technical Stack</h2>
<ul>
    <li><strong>Framework:</strong> Laravel 11 (PHP 8.2+)</li>
    <li><strong>Database:</strong> MySQL</li>
    <li><strong>Authentication:</strong> Laravel Sanctum (Token-based)</li>
    <li><strong>Packages:</strong> barryvdh/laravel-dompdf</li>
</ul>

<h2>Key Backend Features</h2>
<h3>Security & Access Control</h3>
<ul>
    <li><strong>Sanctum Integration:</strong> Secure API authentication.</li>
    <li><strong>Admin Middleware:</strong> Role-Based Access Control (RBAC).</li>
    <li><strong>Data Validation:</strong> Strict request validation for integrity.</li>
</ul>

<h3>Inventory Management</h3>
<ul>
    <li><strong>Dynamic Relationships:</strong> Full CRUD for Products, Categories, and Brands.</li>
    <li><strong>Intelligent Filtering:</strong> Advanced search and category filters.</li>
    <li><strong>Smart Storage:</strong> Automated image upload and cleanup.</li>
</ul>

<h3>Shopping Cart & Checkout Logic</h3>
<ul>
    <li><strong>Database-Backed Cart:</strong> Persistent storage for cross-device syncing.</li>
    <li><strong>Atomic Transactions:</strong> Database transactions (DB::transaction) for reliability.</li>
    <li><strong>Stock Protection:</strong> Concurrency control using lockForUpdate().</li>
</ul>

<h2>API Reference</h2>
<table>
    <tr><th>Method</th><th>Endpoint</th><th>Description</th></tr>
    <tr><td>POST</td><td>/api/register</td><td>Create customer account</td></tr>
    <tr><td>POST</td><td>/api/login</td><td>Authenticate user</td></tr>
    <tr><td>GET</td><td>/api/cart</td><td>View cart items</td></tr>
    <tr><td>POST</td><td>/api/checkout</td><td>Process order</td></tr>
    <tr><td>GET</td><td>/api/admin/orders</td><td>View all orders (Admin)</td></tr>
</table>

<h2>Installation & Setup</h2>
<pre>
