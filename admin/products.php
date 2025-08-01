<?php
include '../includes/auth_admin.php';
include '../includes/db.php';
include '../includes/admin_header.php';

$res = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>

<div class="container mt-4">
    <h3>📦 Product Management</h3>
    <a href="add_product.php" class="btn btn-primary mb-3">+ Add Product</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Price (₹)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                    <td><img src="../uploads/<?= $row['image'] ?>" height="60" /></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/admin_footer.php'; ?>