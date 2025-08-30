<?php
session_start();
include_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: profile.php");
    exit();
}

$order_id = (int) $_GET['id'];
$uid = $_SESSION['user_id'];

$q = $conn->prepare("
    SELECT oi.*, p.name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    JOIN orders o ON oi.order_id = o.id
    WHERE oi.order_id = ? AND o.user_id = ?
");
$q->bind_param("ii", $order_id, $uid);
$q->execute();
$res = $q->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order #<?= $order_id ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .order-card {
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
        }

        @media (max-width: 576px) {
            .order-card {
                padding: 1rem;
            }

            h3 {
                font-size: 1.25rem;
            }

            h5 {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <?php include '../includes/user_header.php'; ?>

    <div class="container py-5">
        <div class="order-card mx-auto" style="max-width: 900px;">
            <h3 class="mb-4 text-center">
                🧾 Order <span class="text-primary">#<?= $order_id ?></span>
            </h3>

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>🛒 Product</th>
                            <th>📦 Qty</th>
                            <th>💰 Price</th>
                            <th>📊 Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0;
                        while ($item = $res->fetch_assoc()):
                            $sub = $item['quantity'] * $item['price'];
                            $total += $sub; ?>
                            <tr>
                                <td class="text-start"><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>₹<?= number_format($item['price'], 2) ?></td>
                                <td class="text-success fw-bold">₹<?= number_format($sub, 2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-3">
                <h5>Total Amount:
                    <span class="text-success fw-bold">₹<?= number_format($total, 2) ?></span>
                </h5>
            </div>

            <div class="mt-4 text-center">
                <a href="profile.php" class="btn btn-outline-dark">
                    ← Back to Profile
                </a>
            </div>
        </div>
    </div>

    <?php include '../includes/user_footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>