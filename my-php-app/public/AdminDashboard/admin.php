<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Param Seller Part</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="../index.php" aria-label="Param seller dashboard">
            <img src="../images/logo-header.png" alt="Param logo">
        </a>

        <nav class="top-nav" aria-label="Main navigation">
            <a href="#users">Admin Users</a>
            <a href="#stocks">Stocks</a>
            <a href="#reports">Reports</a>
            <a href="#audit">Audit Log</a>
        </nav>
    </header>

    <main class="seller-layout">
        <aside class="sidebar" aria-label="Seller navigation">
            <div class="sidebar-heading">
                <h1>Admin Dashboard</h1>
            </div>

            <form class="admin-form" id="adminForm">
                <label for="admin_name">Currently logged in</label>
                <div class="inline-fields">
                    <input id="admin_name" name="admin_name" value="System Admin" required>
                    <button type="submit">Change Admin Name</button>
                </div>
            </form>

            <nav class="side-nav">
                <a href="#dashboard">Dashboard</a>
                <a href="#users">Admin Users</a>
                <a href="#stocks">Stocks and Prices</a>
                <a href="#reports">Inventory Report</a>
                <a href="#audit">Audit Log</a>
            </nav>
        </aside>

        <section class="content">
            <div class="notice success" id="notice" hidden aria-live="polite"></div>

            <section id="dashboard" class="page-section">
                <div class="section-title">
                    <p>Param Clothing Line</p>
                    <h2>Seller Front Page</h2>
                </div>

                <div class="summary-grid">
                    <article class="summary-card">
                        <span>Total Products</span>
                        <strong id="totalProducts">4</strong>
                    </article>
                    <article class="summary-card">
                        <span>Items in Stock</span>
                        <strong id="totalStock">73</strong>
                    </article>
                    <article class="summary-card warning">
                        <span>Low Stock</span>
                        <strong id="lowStock">1</strong>
                    </article>
                    <article class="summary-card">
                        <span>Inventory Value</span>
                        <strong>PHP 0.00</strong>
                    </article>
                </div>
            </section>

            <section id="users" class="page-section">
                <div class="section-title">
                    <p>Admin Roles</p>
                    <h2>Add or Modify Users</h2>
                </div>

                <form class="form-panel" id="addUserForm">
                    <div class="form-grid">
                        <label>
                            Complete name
                            <input name="name" required>
                        </label>
                        <label>
                            Email address
                            <input type="email" name="email" required>
                        </label>
                        <label>
                            Admin role
                            <select name="role">
                                <option>Super Admin</option>
                                <option>Inventory Manager</option>
                                <option>Reports Viewer</option>
                            </select>
                        </label>
                        <label>
                            Status
                            <select name="status">
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </label>
                    </div>
                    <button type="submit">Add Admin User</button>
                </form>

                <div class="edit-list" id="userList">
                    <div class="edit-list-head" aria-hidden="true">
                        <span>Name</span>
                        <span>Email</span>
                        <span>Role</span>
                        <span>Status</span>
                        <span>Action</span>
                    </div>

                    <form class="edit-row user-row">
                        <label>
                            <span>Name</span>
                            <input name="name" value="Param Main Admin" required>
                        </label>
                        <label>
                            <span>Email</span>
                            <input type="email" name="email" value="admin@param.local" required>
                        </label>
                        <label>
                            <span>Role</span>
                            <select name="role">
                                <option selected>Super Admin</option>
                                <option>Inventory Manager</option>
                                <option>Reports Viewer</option>
                            </select>
                        </label>
                        <label>
                            <span>Status</span>
                            <select name="status">
                                <option selected>Active</option>
                                <option>Inactive</option>
                            </select>
                        </label>
                        <div class="row-actions">
                            <button type="submit" class="ghost-button">Update</button>
                            <button type="button" class="danger-button delete-user">Delete</button>
                        </div>
                    </form>

                    <form class="edit-row user-row">
                        <label>
                            <span>Name</span>
                            <input name="name" value="Inventory Staff" required>
                        </label>
                        <label>
                            <span>Email</span>
                            <input type="email" name="email" value="inventory@param.local" required>
                        </label>
                        <label>
                            <span>Role</span>
                            <select name="role">
                                <option>Super Admin</option>
                                <option selected>Inventory Manager</option>
                                <option>Reports Viewer</option>
                            </select>
                        </label>
                        <label>
                            <span>Status</span>
                            <select name="status">
                                <option selected>Active</option>
                                <option>Inactive</option>
                            </select>
                        </label>
                        <div class="row-actions">
                            <button type="submit" class="ghost-button">Update</button>
                            <button type="button" class="danger-button delete-user">Delete</button>
                        </div>
                    </form>
                </div>
            </section>

            <section id="stocks" class="page-section">
                <div class="section-title">
                    <p>Store Products</p>
                    <h2>Add or Modify Stocks</h2>
                </div>

                <form class="form-panel">
                    <div class="form-grid">
                        <label>
                            Product name
                            <input name="name" required>
                        </label>
                        <label>
                            Category
                            <select name="category">
                                <option>Women</option>
                                <option>Men</option>
                                <option>Kids</option>
                                <option>Accessories</option>
                            </select>
                        </label>
                        <label>
                            Price
                            <input type="number" name="price" min="1" step="0.01" required>
                        </label>
                        <label>
                            Stock
                            <input type="number" name="stock" min="0" step="1" required>
                        </label>
                    </div>
                    <button type="button">Add Stock Item</button>
                </form>

                <div class="edit-list">
                    <div class="edit-list-head" aria-hidden="true">
                        <span>Product</span>
                        <span>Category</span>
                        <span>Price</span>
                        <span>Stock</span>
                        <span>Action</span>
                    </div>
                </div>
            </section>

            <section id="reports" class="page-section">
                <div class="section-title">
                    <p>Reports</p>
                    <h2>Remaining Inventory</h2>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Remaining Items</th>
                                <th>Price</th>
                                <th>Total Value</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="audit" class="page-section">
                <div class="section-title">
                    <p>Reports</p>
                    <h2>Audit Log</h2>
                </div>

                <div style="margin-bottom: 16px;">
                    <button type="button" class="danger-button" id="clearAuditLog">Clear All</button>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Date and Time</th>
                                <th>Logged-in User</th>
                                <th>Activity</th>
                            </tr>
                        </thead>
                        <tbody id="auditLog">
                            <tr>
                                <td>--</td>
                                <td>System Admin</td>
                                <td>Seller dashboard opened</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
    </main>

    <footer class="site-footer">
        <img src="../images/logo-footer.png" alt="Param group logo">
        <p><strong>Disclaimer:</strong> This website is for educational purposes only and is a requirement for our final project.</p>
    </footer>

    <script src="admin.js"></script>
</body>
</html>
