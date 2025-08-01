<?php
session_start();
include '../includes/db.php';
include '../includes/auth_admin.php';

$id = $_GET['id'] ?? 0;

// Fetch existing product data
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "❌ Product not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $stock = $_POST['stock'];

    $uploadDir = "../uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if ($_FILES['image']['name']) {
        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $target = $uploadDir . basename($imageName);

        if (move_uploaded_file($imageTmp, $target)) {
            $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, image=?, stock=? WHERE id=?");
            $stmt->bind_param("sdssii", $name, $price, $desc, $imageName, $stock, $id);
        } else {
            echo "❌ Failed to upload new image.";
            exit;
        }
    } else {
        // Keep old image
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, stock=? WHERE id=?");
        $stmt->bind_param("sdsii", $name, $price, $desc, $stock, $id);
    }

    $stmt->execute();
    header("Location: products.php");
    exit;
}
?>

<?php include '../includes/admin_header.php'; ?>

<div class="container mt-5">
    <h3>✏️ Edit Product</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required
                class="form-control">
        </div>
        <div class="mb-3">
            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required
                class="form-control">
        </div>
        <div class="mb-3">
            <label>Stock:</label>
            <input type="number" name="stock" value="<?= $product['stock'] ?>" required class="form-control" min="0">
        </div>
        <div class="mb-3">
            <label>Description:</label>
            <textarea name="description"
                class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Current Image:</label><br>
            <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" width="120"><br><br>
            <label>Change Image (optional):</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="products.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/admin_footer.php'; ?>