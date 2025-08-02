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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-header {
            border-bottom: 2px solid #dee2e6;
        }

        @media (max-width: 768px) {
            .table-responsive {
                border: none;
            }

            .table thead {
                display: none;
            }

            .table tbody td {
                display: block;
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            .table tbody td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                text-align: left;
                font-weight: 600;
                color: #6c757d;
            }

            .table tbody tr {
                margin-bottom: 1rem;
                display: block;
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                background: white;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <?php include '../includes/user_header.php'; ?>

    <div class="container mt-5">
        <div class="profile-header mb-4 pb-2">
            <h3 class="fw-bold">👤 <?= htmlspecialchars($_SESSION['user_name']) ?>'s Profile</h3>
            <p class="text-muted">Your complete order history with payment and delivery status.</p>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✅ Your order was placed successfully!</div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table align-middle table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Total (₹)</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($orders->num_rows > 0): ?>
                        <?php while ($order = $orders->fetch_assoc()): ?>
                            <tr>
                                <td data-label="#">#<?= $order['id'] ?></td>
                                <td data-label="Total">
                                    <?php
                                    $total = $conn->query("SELECT SUM(oi.quantity * p.price) AS total 
                      FROM order_items oi 
                      JOIN products p ON oi.product_id = p.id 
                      WHERE oi.order_id = {$order['id']}")->fetch_assoc()['total'] ?? 0;
                                    echo '₹' . number_format($total, 2);
                                    ?>
                                </td>
                                <td data-label="Payment"><?= ucfirst($order['payment_method']) ?></td>
                                <td data-label="Status">
                                    <?php
                                    $status = $order['status'];
                                    $badge = match ($status) {
                                        'Accepted' => 'info',
                                        'On the Way' => 'warning',
                                        'Delivered' => 'success',
                                        default => 'secondary',
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badge ?>"><?= $status ?? 'Pending' ?></span>
                                </td>
                                <td data-label="Date"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '../includes/user_footer.php'; ?>
</body>

</html>