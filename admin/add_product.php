<?php
session_start();
include '../includes/db.php';
include '../includes/auth_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $stock = $_POST['stock'];

    $uploadDir = "../uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imageName = basename($_FILES['image']['name']);
    $imageTmp = $_FILES['image']['tmp_name'];
    $target = $uploadDir . $imageName;

    if (move_uploaded_file($imageTmp, $target)) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, description, image, stock) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdssi", $name, $price, $desc, $imageName, $stock);
        $stmt->execute();
        header("Location: products.php");
        exit;
    } else {
        $error = "❌ Failed to upload image.";
    }
}
?>

<?php include '../includes/admin_header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <h3 class="mb-4 text-center">🆕 Add New Product</h3>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Product Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter product name"
                                required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price (₹)</label>
                                <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stock Quantity</label>
                                <input type="number" name="stock" class="form-control" min="0" placeholder="0" required>
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="4"
                                placeholder="Write short description..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Image</label>
                            <input type="file" name="image" accept="image/*" class="form-control" required>
                        </div>

                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mt-4">
                            <button type="submit" class="btn btn-success w-100 w-md-auto">➕ Add Product</button>
                            <a href="products.php" class="btn btn-secondary w-100 w-md-auto">↩️ Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>