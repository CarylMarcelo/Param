<?php
require_once 'includes/db.php';

if (!isset($_GET['id'])) {
    echo "<p>Error: No product selected.</p>";
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM products p 
                       JOIN product_variants v ON p.product_id = v.product_id 
                       WHERE p.product_id = ?");
$stmt->execute([$id]);
$variants = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$variants) {
    echo "<p>Sorry, product details are currently unavailable.</p>";
    exit;
}

$product = $variants[0]; 

function displaySizeWithUnit($size) {
    if (is_numeric($size)) {
        if ($size > 100) {
            return $size . " cm"; 
        } else {
            return $size . " inches"; 
        }
    }
    return $size;
}
?>

<div style="text-align: center;">
    <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="width: 150px; border-radius: 8px;">
    <h3 style="color: var(--primary-maroon, #800000); margin-top: 15px; margin-bottom: 20px;"><?php echo htmlspecialchars($product['product_name']); ?></h3>
</div>

<form action="addToCart.php" method="POST" style="display: flex; flex-direction: column;">
    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
    
    <div style="display: flex; gap: 15px; margin-bottom: 20px; width: 100%;">
        
        <!-- Size Container -->
        <div style="flex: 1;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px; text-align: left;">Size:</label>
            <select name="size" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                <?php 
                $sizes = array_unique(array_column($variants, 'size'));
                foreach($sizes as $size): 
                ?>
                    <option value="<?php echo htmlspecialchars($size); ?>">
                        <?php echo htmlspecialchars(displaySizeWithUnit($size)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="flex: 1;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px; text-align: left;">Color:</label>
            <select name="color" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                <?php 
                $colors = array_unique(array_column($variants, 'color'));
                foreach($colors as $color): 
                ?>
                    <option value="<?php echo htmlspecialchars($color); ?>"><?php echo htmlspecialchars($color); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
    </div>
    
    <button type="submit" style="background-color: var(--primary-maroon, #800000); color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%;">Confirm Add to Cart</button>
</form>