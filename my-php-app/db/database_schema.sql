CREATE DATABASE IF NOT EXISTS param_db
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_general_ci;

USE param_db;

-- is_publicly_applicable controls which roles can appear on the public staff application form.
-- Customer Service and Delivery are public application roles; Administrator is not.
CREATE TABLE IF NOT EXISTS roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255),
    is_publicly_applicable TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS permissions (
    permission_id INT AUTO_INCREMENT PRIMARY KEY,
    permission_key VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_role_permissions_role
        FOREIGN KEY (role_id) REFERENCES roles(role_id),
    CONSTRAINT fk_role_permissions_permission
        FOREIGN KEY (permission_id) REFERENCES permissions(permission_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50) NULL,
    last_name VARCHAR(50) NOT NULL,
    suffix VARCHAR(20) NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    must_change_password TINYINT(1) NOT NULL DEFAULT 0,
    email_verified_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_role_id (role_id),
    CONSTRAINT fk_users_role
        FOREIGN KEY (role_id) REFERENCES roles(role_id)
) ENGINE=InnoDB;

-- token_type examples: email_verification, account_setup, password_reset.
CREATE TABLE IF NOT EXISTS auth_tokens (
    token_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_type VARCHAR(50) NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_auth_tokens_user_id (user_id),
    INDEX idx_auth_tokens_type (token_type),
    CONSTRAINT fk_auth_tokens_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- App must reject or revoke sessions when users.status is not active.
CREATE TABLE IF NOT EXISTS user_sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token_hash VARCHAR(255) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    revoked_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_sessions_user_id (user_id),
    CONSTRAINT fk_user_sessions_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS user_addresses (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    house_no VARCHAR(50) NULL,
    street VARCHAR(100) NOT NULL,
    barangay VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    province VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NULL,
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_addresses_user_id (user_id),
    CONSTRAINT fk_user_addresses_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS user_contacts (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    contact_number VARCHAR(30) NOT NULL,
    contact_type VARCHAR(30) NOT NULL DEFAULT 'Mobile',
    is_primary TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_contacts_user_id (user_id),
    CONSTRAINT fk_user_contacts_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- App-layer rule: requested_role_id must point only to roles where is_publicly_applicable = 1.
-- Do not allow public applicants to request Administrator.
CREATE TABLE IF NOT EXISTS staff_applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50) NULL,
    last_name VARCHAR(50) NOT NULL,
    suffix VARCHAR(20) NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NULL,
    requested_role_id INT NOT NULL,
    reason TEXT NULL,
    experience TEXT NULL,
    availability VARCHAR(100) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    reviewed_by INT NULL,
    reviewed_at DATETIME NULL,
    created_user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_staff_applications_requested_role_id (requested_role_id),
    INDEX idx_staff_applications_reviewed_by (reviewed_by),
    INDEX idx_staff_applications_created_user_id (created_user_id),
    CONSTRAINT fk_staff_applications_requested_role
        FOREIGN KEY (requested_role_id) REFERENCES roles(role_id),
    CONSTRAINT fk_staff_applications_reviewed_by
        FOREIGN KEY (reviewed_by) REFERENCES users(user_id),
    CONSTRAINT fk_staff_applications_created_user
        FOREIGN KEY (created_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    image_path VARCHAR(255) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_products_category_id (category_id),
    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id) REFERENCES categories(category_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product_variants (
    variant_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    size VARCHAR(30) NOT NULL,
    color VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_product_variants_product_id (product_id),
    CONSTRAINT fk_product_variants_product
        FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS carts (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_carts_user_id (user_id),
    CONSTRAINT fk_carts_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cart_items (
    cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    variant_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_cart_items_cart_id (cart_id),
    INDEX idx_cart_items_variant_id (variant_id),
    CONSTRAINT fk_cart_items_cart
        FOREIGN KEY (cart_id) REFERENCES carts(cart_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_cart_items_variant
        FOREIGN KEY (variant_id) REFERENCES product_variants(variant_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS favorites (
    favorite_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_favorites_user_id (user_id),
    INDEX idx_favorites_product_id (product_id),
    CONSTRAINT fk_favorites_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_favorites_product
        FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    delivery_address_id INT NULL,
    delivery_address_snapshot TEXT NOT NULL,
    order_status VARCHAR(30) NOT NULL DEFAULT 'pending',
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_orders_user_id (user_id),
    INDEX idx_orders_delivery_address_id (delivery_address_id),
    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id) REFERENCES users(user_id),
    CONSTRAINT fk_orders_delivery_address
        FOREIGN KEY (delivery_address_id) REFERENCES user_addresses(address_id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    variant_id INT NULL,
    product_name_snapshot VARCHAR(150) NOT NULL,
    size_snapshot VARCHAR(30) NOT NULL,
    color_snapshot VARCHAR(50) NOT NULL,
    price_snapshot DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    INDEX idx_order_items_order_id (order_id),
    INDEX idx_order_items_variant_id (variant_id),
    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_order_items_variant
        FOREIGN KEY (variant_id) REFERENCES product_variants(variant_id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_status VARCHAR(30) NOT NULL DEFAULT 'pending',
    amount DECIMAL(10,2) NOT NULL,
    reference_number VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_payments_order_id (order_id),
    CONSTRAINT fk_payments_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS deliveries (
    delivery_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    assigned_to_user_id INT NULL,
    delivery_status VARCHAR(30) NOT NULL DEFAULT 'pending',
    delivery_notes TEXT NULL,
    proof_image_path VARCHAR(255) NULL,
    assigned_at DATETIME NULL,
    delivered_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_deliveries_order_id (order_id),
    INDEX idx_deliveries_assigned_to_user_id (assigned_to_user_id),
    CONSTRAINT fk_deliveries_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_deliveries_assigned_user
        FOREIGN KEY (assigned_to_user_id) REFERENCES users(user_id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS support_concerns (
    concern_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    order_id INT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    response TEXT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'open',
    assigned_to_user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_support_customer (customer_id),
    INDEX idx_support_order (order_id),
    INDEX idx_support_assigned (assigned_to_user_id),
    CONSTRAINT fk_support_customer FOREIGN KEY (customer_id) REFERENCES users(user_id),
    CONSTRAINT fk_support_order FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE SET NULL,
    CONSTRAINT fk_support_assigned FOREIGN KEY (assigned_to_user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS refund_requests (
    refund_request_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    requested_by_user_id INT NOT NULL,
    reviewed_by_user_id INT NULL,
    executed_by_user_id INT NULL,
    reason TEXT NOT NULL,
    customer_service_notes TEXT NULL,
    admin_notes TEXT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_at DATETIME NULL,
    executed_at DATETIME NULL,
    INDEX idx_refund_requests_order_id (order_id),
    INDEX idx_refund_requests_requested_by (requested_by_user_id),
    INDEX idx_refund_requests_reviewed_by (reviewed_by_user_id),
    INDEX idx_refund_requests_executed_by (executed_by_user_id),
    CONSTRAINT fk_refund_requests_order
        FOREIGN KEY (order_id) REFERENCES orders(order_id),
    CONSTRAINT fk_refund_requests_requested_by
        FOREIGN KEY (requested_by_user_id) REFERENCES users(user_id),
    CONSTRAINT fk_refund_requests_reviewed_by
        FOREIGN KEY (reviewed_by_user_id) REFERENCES users(user_id),
    CONSTRAINT fk_refund_requests_executed_by
        FOREIGN KEY (executed_by_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS audit_logs (
    audit_log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action_name VARCHAR(100) NOT NULL,
    table_name VARCHAR(100) NULL,
    record_id INT NULL,
    details TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_audit_logs_user_id (user_id),
    CONSTRAINT fk_audit_logs_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO roles (role_name, description, is_publicly_applicable) VALUES
('Customer', 'Buyer account for shopping on the storefront', 0),
('Customer Service', 'Handles customer concerns and refund requests', 1),
('Delivery', 'Handles assigned deliveries', 1),
('Administrator', 'Manages users, products, inventory, reports, and approvals', 0);

-- Bootstrap administrator account.
-- Temporary login: admin@param.test / Admin@12345
-- The app should force this account to change password after first login.
INSERT INTO users (
    first_name,
    middle_name,
    last_name,
    suffix,
    email,
    password_hash,
    role_id,
    status,
    must_change_password,
    email_verified_at
)
SELECT
    'System',
    NULL,
    'Administrator',
    NULL,
    'admin@param.test',
    '$2y$10$EmgS6dFJ.ojxNsn5xb4yCu/sIRd4V0hd4V36G/tDODRibVCrxrOry',
    r.role_id,
    'active',
    1,
    NOW()
FROM roles r
WHERE r.role_name = 'Administrator'
AND NOT EXISTS (
    SELECT 1
    FROM users u
    WHERE u.email = 'admin@param.test'
);

INSERT IGNORE INTO permissions (permission_key, description) VALUES
('account.register', 'Register as a customer'),
('account.confirm_email', 'Confirm registered email address'),
('account.view_own', 'View own account'),
('account.manage_own', 'Manage own account'),
('account.change_password', 'Change own password'),
('cart.manage_own', 'Manage own cart'),
('products.view', 'View products in the store'),
('orders.create', 'Create checkout orders'),
('orders.view_own', 'View own orders'),
('checkout.use', 'Use checkout page'),
('payment.view', 'View payment page'),
('support.request', 'Create support request'),
('refunds.request_own', 'Request refund for own order'),
('support.view', 'View support concerns'),
('support.reply', 'Reply to support concerns'),
('orders.view_support', 'View order information needed for support'),
('customers.view_support_info', 'View limited customer information for support'),
('refunds.request', 'Flag or request a refund with notes'),
('deliveries.view_assigned', 'View assigned deliveries only'),
('deliveries.update_status', 'Update assigned delivery status'),
('deliveries.view_limited_customer_info', 'View limited customer delivery information'),
('users.manage', 'Add or modify users'),
('roles.assign', 'Assign user roles'),
('products.manage', 'Add or modify products'),
('inventory.manage', 'Add or modify stock quantities'),
('prices.manage', 'Change product prices'),
('orders.manage', 'Manage orders'),
('applications.review', 'Review staff applications'),
('refunds.review', 'Review refund requests'),
('refunds.execute', 'Execute approved refunds'),
('reports.inventory.view', 'View inventory reports'),
('reports.audit_logs.view', 'View audit log reports');

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.role_id, p.permission_id
FROM roles r
JOIN permissions p ON p.permission_key IN (
    'account.register',
    'account.confirm_email',
    'account.view_own',
    'account.manage_own',
    'account.change_password',
    'cart.manage_own',
    'products.view',
    'orders.create',
    'orders.view_own',
    'checkout.use',
    'payment.view',
    'support.request',
    'refunds.request_own'
)
WHERE r.role_name = 'Customer';

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.role_id, p.permission_id
FROM roles r
JOIN permissions p ON p.permission_key IN (
    'account.view_own',
    'account.manage_own',
    'account.change_password',
    'products.view',
    'support.view',
    'support.reply',
    'orders.view_support',
    'customers.view_support_info',
    'refunds.request'
)
WHERE r.role_name = 'Customer Service';

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.role_id, p.permission_id
FROM roles r
JOIN permissions p ON p.permission_key IN (
    'account.view_own',
    'account.manage_own',
    'account.change_password',
    'deliveries.view_assigned',
    'deliveries.update_status',
    'deliveries.view_limited_customer_info'
)
WHERE r.role_name = 'Delivery';

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.role_id, p.permission_id
FROM roles r
JOIN permissions p ON p.permission_key IN (
    'account.view_own',
    'account.manage_own',
    'account.change_password',
    'products.view',
    'support.view',
    'orders.view_support',
    'customers.view_support_info',
    'users.manage',
    'roles.assign',
    'products.manage',
    'inventory.manage',
    'prices.manage',
    'orders.manage',
    'applications.review',
    'refunds.review',
    'refunds.execute',
    'reports.inventory.view',
    'reports.audit_logs.view'
)
WHERE r.role_name = 'Administrator';

CREATE OR REPLACE VIEW publicly_applicable_roles AS
SELECT
    role_id,
    role_name,
    description
FROM roles
WHERE is_publicly_applicable = 1;

CREATE OR REPLACE VIEW role_permissions_readable AS
SELECT
    r.role_id,
    r.role_name,
    p.permission_id,
    p.permission_key,
    p.description AS permission_description
FROM role_permissions rp
JOIN roles r ON rp.role_id = r.role_id
JOIN permissions p ON rp.permission_id = p.permission_id;

CREATE OR REPLACE VIEW users_readable AS
SELECT
    u.user_id,
    CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name, u.suffix) AS complete_name,
    u.email,
    r.role_name,
    u.status,
    u.must_change_password,
    u.email_verified_at,
    u.created_at
FROM users u
JOIN roles r ON u.role_id = r.role_id;

CREATE OR REPLACE VIEW staff_applications_readable AS
SELECT
    sa.application_id,
    CONCAT_WS(' ', sa.first_name, sa.middle_name, sa.last_name, sa.suffix) AS complete_name,
    sa.email,
    sa.phone,
    requested_role.role_name AS requested_role,
    sa.status,
    CONCAT_WS(' ', reviewer.first_name, reviewer.middle_name, reviewer.last_name, reviewer.suffix) AS reviewed_by,
    sa.reviewed_at,
    sa.created_at
FROM staff_applications sa
JOIN roles requested_role ON sa.requested_role_id = requested_role.role_id
LEFT JOIN users reviewer ON sa.reviewed_by = reviewer.user_id;

-- Use this view for delivery pages so delivery users receive masked phone numbers only.
-- Permission names do not mask data by themselves; PHP queries must avoid raw contact numbers.
CREATE OR REPLACE VIEW delivery_assignments_readable AS
SELECT
    d.delivery_id,
    d.order_id,
    d.assigned_to_user_id,
    CONCAT_WS(' ', customer.first_name, customer.middle_name, customer.last_name, customer.suffix) AS customer_name,
    o.delivery_address_snapshot,
    CASE
        WHEN primary_contact.contact_number IS NULL THEN NULL
        WHEN CHAR_LENGTH(primary_contact.contact_number) <= 4 THEN primary_contact.contact_number
        ELSE CONCAT(REPEAT('*', CHAR_LENGTH(primary_contact.contact_number) - 4), RIGHT(primary_contact.contact_number, 4))
    END AS masked_phone_number,
    d.delivery_status,
    d.delivery_notes,
    d.proof_image_path,
    d.assigned_at,
    d.delivered_at,
    d.created_at
FROM deliveries d
JOIN orders o ON d.order_id = o.order_id
JOIN users customer ON o.user_id = customer.user_id
LEFT JOIN user_contacts primary_contact
    ON primary_contact.user_id = customer.user_id
    AND primary_contact.is_primary = 1;
