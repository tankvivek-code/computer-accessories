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
<html>

<head>
    <title>Order #<?= $order_id ?></title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php include '../includes/user_header.php'; ?>
    <div class="container mt-5">
        <h3>🧾 Order #<?= $order_id ?></h3>
        <table class="table table-bordered text-center">
            <thead class="table-secondary">
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0;
                while ($item = $res->fetch_assoc()):
                    $sub = $item['quantity'] * $item['price'];
                    $total += $sub;
                    ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>₹<?= $item['price'] ?></td>
                        <td>₹<?= $sub ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <h4>Total: ₹<?= $total ?></h4>
        <a href="profile.php" class="btn btn-dark">← Back to Profile</a>
    </div>
    <?php include '../includes/user_footer.php'; ?>

</body>

</html>