# Param Clothing Line

Param is a framework-free PHP and MySQL ecommerce project with one shared login and role-based areas for buyers, administrators, Customer Service, and Delivery staff.

## Current areas

- Administrator dashboard: users, products, inventory, reports, staff applications, and audit logs
- Customer Service dashboard: concerns, replies, limited order information, and refund escalation
- Delivery dashboard: assigned deliveries, masked customer contact details, status updates, notes, and proof paths
- Buyer storefront: reserved at `my-php-app/public/index.php` and scheduled for the next implementation phase

## Local setup

1. Start Apache and MySQL in XAMPP.
2. Import `my-php-app/db/database_schema.sql` into MySQL.
3. Configure database access in `my-php-app/src/config/database.php`.
4. Copy the mail settings from `.env.example` into local environment variables or a gitignored `src/config/mail.local.php`.
5. Open `/Param/my-php-app/public/login.php` through Apache.

Development administrator: `admin@param.test` / `Admin@12345`. Change this temporary password before deployment.

## Security

Never commit `mail.local.php`, SMTP credentials, production database passwords, or generated account-setup links. Protected pages and APIs enforce active sessions, CSRF checks, and database-backed RBAC permissions.
