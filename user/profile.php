<?php
session_start();
include_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$query->bind_param("i", $user_id);
$query->execute();
$orders = $query->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>👤 Profile</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include '../includes/user_header.php'; ?>

    <div class="container mt-5">
        <h3>👤 <?= htmlspecialchars($_SESSION['user_name']) ?>'s Profile</h3>
        <p class="text-muted">Here’s your complete order history with status updates:</p>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✅ Order placed successfully!</div>
        <?php endif; ?>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Order #</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders->num_rows > 0): ?>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td>₹<?= number_format($order['total_amount'], 2) ?></td>
                            <td>
                                <?php
                                $status = $order['status'];
                                $badge = 'secondary';
                                if ($status === 'Accepted')
                                    $badge = 'info';
                                elseif ($status === 'On the Way')
                                    $badge = 'warning';
                                elseif ($status === 'Delivered')
                                    $badge = 'success';
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= $status ?></span>
                            </td>
                            <td><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/user_footer.php'; ?>
</body>

</html>