<?php
include '../includes/auth_admin.php';
include '../includes/db.php';
include '../includes/admin_header.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status='$status' WHERE id=$order_id");
}

// Fetch orders
$query = "
SELECT 
    orders.id, 
    users.name AS user_name, 
    orders.created_at,
    orders.status,
    (
        SELECT SUM(p.price * oi.quantity) 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = orders.id
    ) AS total
FROM orders 
JOIN users ON orders.user_id = users.id 
ORDER BY orders.created_at DESC";

$result = $conn->query($query);
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">📦 Manage Orders</h2>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Date</th>
                <th>Total (₹)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></td>
                    <td>₹<?= number_format($row['total'], 2) ?></td>
                    <td>
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                            <select name="status" class="form-select form-select-sm me-2">
                                <?php foreach (['Accepted', 'On the Way', 'Delivered'] as $status): ?>
                                    <option value="<?= $status ?>" <?= ($row['status'] == $status ? 'selected' : '') ?>>
                                        <?= $status ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </td>
                    <td>
                        <span class="badge bg-info text-dark"><?= $row['status'] ?></span>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/admin_footer.php'; ?>