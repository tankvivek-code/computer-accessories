<?php
include '../includes/auth_admin.php';
include '../includes/db.php';
include '../includes/admin_header.php';

$res = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>

<div class="container mt-4 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h3 class="mb-0">📦 Product Management</h3>
                <a href="add_product.php" class="btn btn-primary">+ Add Product</a>
            </div>

            <!-- Responsive Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Price (₹)</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $res->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <img src="../uploads/<?= htmlspecialchars($row['image']) ?>"
                                        alt="<?= htmlspecialchars($row['name']) ?>" class="img-fluid rounded shadow-sm"
                                        style="max-height: 60px; max-width: 60px;" />
                                </td>
                                <td class="text-truncate" style="max-width: 150px;">
                                    <?= htmlspecialchars($row['name']) ?>
                                </td>
                                <td>₹<?= number_format((float) $row['price'], 2) ?></td>
                                <td><?= (int) $row['stock'] ?></td>
                                <td>
                                    <div
                                        class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-2">
                                        <a href="edit_product.php?id=<?= $row['id'] ?>"
                                            class="btn btn-sm btn-warning w-100 w-md-auto">Edit</a>
                                        <a href="delete_product.php?id=<?= $row['id'] ?>"
                                            class="btn btn-sm btn-danger w-100 w-md-auto"
                                            onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>