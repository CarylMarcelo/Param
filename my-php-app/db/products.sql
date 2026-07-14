-- 1. Create the tables
CREATE TABLE IF NOT EXISTS categories (
    category_id INT PRIMARY KEY,
    category_name VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS products (
    product_id INT PRIMARY KEY,
    category_id INT,
    product_name VARCHAR(100),
    image_path VARCHAR(255),
    status VARCHAR(20),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

CREATE TABLE IF NOT EXISTS product_variants (
    variant_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    size VARCHAR(20),
    color VARCHAR(50),
    price DECIMAL(10, 2),
    stock_quantity INT,
    status VARCHAR(20),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- 2. Insert Categories
INSERT IGNORE INTO categories (category_id, category_name) VALUES 
(1, 'Kids'), (2, 'Women'), (3, 'Men'), (4, 'Unisex');

-- 3. Insert Products with Correct Categories
INSERT IGNORE INTO products (product_id, category_id, product_name, image_path, status) VALUES 
-- Kids (1)
(1, 1, 'Kids Pocketable UV Protection Parka', 'images/prod1.avif', 'active'),
(5, 1, 'KIDS AIRism Cotton Graphic Crew Neck T-Shirt', 'images/prod5.avif', 'active'),
(6, 1, 'KIDS AIRism Cotton Crew Neck T-shirt | Long Sleeve', 'images/prod6.avif', 'active'),
(7, 1, 'KIDS Baggy Cargo Half Pants', 'images/prod7.avif', 'active'),
(8, 1, 'KIDS Wide Fit Straight Jeans', 'images/prod8.avif', 'active'),
-- Women (2)
(3, 2, 'Washed Cotton Boxy T-Shirt', 'images/prod3.avif', 'active'),
(9, 2, 'Graphic T-Shirt', 'images/prod9.avif', 'active'),
(10, 2, 'Ribbed Henley Neck T-Shirt | Long Sleeve', 'images/prod10.avif', 'active'),
(11, 2, 'Pleated Skort', 'images/prod11.avif', 'active'),
(12, 2, 'AIRism Cotton Short Sleeve T Dress', 'images/prod12.avif', 'active'),
-- Men (3)
(16, 3, 'Knitted V Neck Cardigan', 'images/prod16.avif', 'active'),
(17, 3, 'Ultra Stretch Sweat Shorts', 'images/prod17.avif', 'active'),
(18, 3, 'Straight Jeans Selvedge', 'images/prod18.avif', 'active'),
(19, 3, 'Cargo Shorts', 'images/prod19.avif', 'active'),
(20, 3, 'Tank Top', 'images/prod20.avif', 'active'),
-- Unisex (4)
(2, 4, 'Nylon Culotte', 'images/prod2.avif', 'active'),
(13, 4, 'Milano Ribbed Shirt Collar Cardigan', 'images/prod13.avif', 'active'),
(14, 4, 'Ultra Stretch Active Shorts', 'images/prod14.avif', 'active'),
(15, 4, 'AIRism Cotton Oversized Striped T-Shirt', 'images/prod15.avif', 'active'),
(4, 4, 'Washable 3D Knit Polo', 'images/prod4.avif', 'active');

-- 4. Insert Product Variants
INSERT IGNORE INTO product_variants (product_id, size, color, price, stock_quantity, status) VALUES 
(1, '130', 'Light Blue', 1490.00, 50, 'active'),
(5, '130', 'White', 490.00, 50, 'active'),
(6, '140', 'Black', 590.00, 50, 'active'),
(7, '150', 'Olive', 790.00, 50, 'active'),
(8, '160', 'Blue', 1290.00, 50, 'active'),
(3, 'L', 'White', 590.00, 100, 'active'),
(9, 'L', 'Grey', 990.00, 50, 'active'),
(10, 'L', 'White', 990.00, 50, 'active'),
(11, 'M', 'Navy', 1290.00, 50, 'active'),
(12, 'M', 'Black', 1290.00, 50, 'active'),
(16, 'M', 'Pink', 1990.00, 50, 'active'),
(17, 'L', 'Grey', 790.00, 50, 'active'),
(18, '32', 'Indigo', 1990.00, 50, 'active'),
(19, 'L', 'Khaki', 1290.00, 50, 'active'),
(20, 'S', 'White', 790.00, 50, 'active'),
(2, 'M', 'Black', 1990.00, 30, 'active'),
(13, 'M', 'Beige', 2490.00, 50, 'active'),
(14, 'L', 'Black', 1490.00, 50, 'active'),
(15, 'XL', 'Striped', 790.00, 50, 'active'),
(4, 'M', 'Blue', 2490.00, 40, 'active');