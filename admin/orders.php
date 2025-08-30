<?php
include '../includes/auth_admin.php';
include '../includes/db.php';
include '../includes/admin_header.php';

// Handle status update securely
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch orders
$query = "
SELECT 
    orders.id, 
    users.name AS user_name, 
    orders.created_at,
    orders.status,
    COALESCE((
        SELECT SUM(p.price * oi.quantity) 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = orders.id
    ), 0) AS total
FROM orders 
JOIN users ON orders.user_id = users.id 
ORDER BY orders.created_at DESC";

$result = $conn->query($query);

// Define status options once
$statusOptions = ['Accepted', 'On the Way', 'Delivered'];
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">📦 Manage Orders</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
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
                        <td class="text-truncate" style="max-width: 120px;">
                            <?= htmlspecialchars($row['user_name']) ?>
                        </td>
                        <td><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></td>
                        <td>₹<?= number_format($row['total'], 2) ?></td>
                        <td>
                            <form method="POST" class="d-flex flex-wrap gap-2">
                                <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                <select name="status" class="form-select form-select-sm" style="min-width: 130px;">
                                    <?php foreach ($statusOptions as $status): ?>
                                        <option value="<?= $status ?>" <?= ($row['status'] == $status ? 'selected' : '') ?>>
                                            <?= $status ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark"><?= htmlspecialchars($row['status']) ?></span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>