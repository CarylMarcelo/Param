<?php
require_once __DIR__ . '/../config/database.php';

// Backs the "Stocks and Prices" and "Remaining Inventory" sections.
// The dashboard form only collects name/category/price/stock, so each
// stock item is stored as one product + one default variant row.
class ProductController
{
    public static function listStock(): array
    {
        $stmt = getDbConnection()->query(
            "SELECT p.product_id, p.product_name, c.category_name,
                    v.variant_id, v.price, v.stock_quantity
             FROM products p
             JOIN categories c ON p.category_id = c.category_id
             JOIN product_variants v ON v.product_id = p.product_id
             ORDER BY p.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public static function inventoryReport(): array
    {
        $stmt = getDbConnection()->query(
            "SELECT p.product_name, c.category_name, v.stock_quantity,
                    v.price, (v.stock_quantity * v.price) AS total_value
             FROM products p
             JOIN categories c ON p.category_id = c.category_id
             JOIN product_variants v ON v.product_id = p.product_id
             ORDER BY p.product_name"
        );
        return $stmt->fetchAll();
    }

    public static function summary(): array
    {
        $db = getDbConnection();

        $totalProducts = (int) $db->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $totalStock    = (int) $db->query('SELECT COALESCE(SUM(stock_quantity), 0) FROM product_variants')->fetchColumn();
        $lowStock      = (int) $db->query('SELECT COUNT(*) FROM product_variants WHERE stock_quantity <= 5')->fetchColumn();
        $inventoryValue = (float) $db->query('SELECT COALESCE(SUM(stock_quantity * price), 0) FROM product_variants')->fetchColumn();

        return [
            'total_products'  => $totalProducts,
            'total_stock'     => $totalStock,
            'low_stock'       => $lowStock,
            'inventory_value' => round($inventoryValue, 2),
        ];
    }

    public static function createStockItem(array $input): array
    {
        $db = getDbConnection();
        $db->beginTransaction();

        try {
            $categoryId = self::findOrCreateCategory($input['category'] ?? 'Uncategorized');

            $stmt = $db->prepare(
                'INSERT INTO products (category_id, product_name, status) VALUES (:cat, :name, :status)'
            );
            $stmt->execute([
                'cat'    => $categoryId,
                'name'   => $input['name'] ?? '',
                'status' => 'active',
            ]);
            $productId = (int) $db->lastInsertId();

            $stmt = $db->prepare(
                "INSERT INTO product_variants (product_id, size, color, price, stock_quantity)
                 VALUES (:product_id, 'One Size', 'Default', :price, :stock)"
            );
            $stmt->execute([
                'product_id' => $productId,
                'price'      => $input['price'] ?? 0,
                'stock'      => $input['stock'] ?? 0,
            ]);

            $db->commit();
            return ['product_id' => $productId];
        } catch (Exception $e) {
            $db->rollBack();
            http_response_code(500);
            return ['error' => $e->getMessage()];
        }
    }

    public static function deleteStockItem(int $productId): array
    {
        $stmt = getDbConnection()->prepare('DELETE FROM products WHERE product_id = :id');
        $stmt->execute(['id' => $productId]);
        return ['success' => true];
    }

    private static function findOrCreateCategory(string $categoryName): int
    {
        $db = getDbConnection();

        $stmt = $db->prepare('SELECT category_id FROM categories WHERE category_name = :name LIMIT 1');
        $stmt->execute(['name' => $categoryName]);
        $existing = $stmt->fetchColumn();
        if ($existing) {
            return (int) $existing;
        }

        $stmt = $db->prepare('INSERT INTO categories (category_name) VALUES (:name)');
        $stmt->execute(['name' => $categoryName]);
        return (int) $db->lastInsertId();
    }
}
