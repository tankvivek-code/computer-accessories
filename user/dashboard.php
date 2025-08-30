<?php
session_start();
include_once __DIR__ . '/../includes/auth_user.php';
include_once __DIR__ . '/../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>👤 User Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-header {
            border-bottom: 2px solid #dee2e6;
        }

        /* Product card hover */
        .product-card:hover {
            transform: translateY(-3px);
            transition: 0.3s ease;
        }

        /* Truncate table cells */
        .truncate-cell {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Mobile Responsive Table */
        @media (max-width: 768px) {
            .table thead {
                display: none;
            }

            .table tbody td {
                display: block;
                text-align: right;
                padding-left: 50%;
                position: relative;
                border: none !important;
                border-bottom: 1px solid #dee2e6 !important;
                white-space: normal;
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
                background: #fff;
                padding: 10px;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/computer-accessories/user/home.php">Accessories Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto text-center">
                    <li class="nav-item">
                        <a class="nav-link" href="/computer-accessories/user/home.php">🏠 Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/computer-accessories/user/cart.php">🛒 Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/computer-accessories/user/profile.php">👤 Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/computer-accessories/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">

        <!-- Welcome Title -->
        <h2 class="mb-3 fw-bold text-center text-primary">
            🖥️ Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
        </h2>
        <p class="text-center text-muted mb-4">
            Explore our latest computer accessories collection
        </p>

        <!-- Product Grid -->
        <div class="row g-4">
            <?php
            $res = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
            while ($row = $res->fetch_assoc()):
            ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 rounded-3 product-card">
                        <div class="bg-light d-flex justify-content-center align-items-center" style="height: 200px; overflow: hidden;">
                            <img src="/computer-accessories/uploads/<?= htmlspecialchars($row['image']) ?>"
                                class="img-fluid p-3" style="max-height: 180px; object-fit: contain;"
                                alt="<?= htmlspecialchars($row['name']) ?>">
                        </div>

                        <div class="card-body d-flex flex-column text-center text-md-start">
                            <h6 class="card-title text-dark text-truncate"><?= htmlspecialchars($row['name']) ?></h6>
                            <p class="text-success fw-bold mb-1">₹<?= number_format($row['price'], 2) ?></p>
                            <p class="text-muted small mb-2">Stock: <?= (int)$row['stock'] ?></p>

                            <div class="mt-auto d-flex flex-wrap justify-content-center justify-content-md-between gap-2">
                                <a href="product.php?id=<?= urlencode($row['id']) ?>" class="btn btn-outline-primary btn-sm flex-fill">🔍 View</a>
                                <?php if ((int)$row['stock'] > 0): ?>
                                    <a href="add_to_cart.php?id=<?= urlencode($row['id']) ?>" class="btn btn-success btn-sm flex-fill">🛒 Add</a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm flex-fill" disabled>Out of Stock</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Profile / Orders Table -->
        <?php
        $user_id = $_SESSION['user_id'];
        $query = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $query->bind_param("i", $user_id);
        $query->execute();
        $orders = $query->get_result();
        ?>

        <div class="profile-header mt-5 mb-4 pb-2">
            <h3 class="fw-bold">👤 <?= htmlspecialchars($_SESSION['user_name']) ?>'s Profile</h3>
            <p class="text-muted">Your complete order history with payment and delivery status.</p>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ Your order was placed successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
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
                            <?php
                            $total = $conn->query("
                                SELECT SUM(oi.quantity * p.price) AS total 
                                FROM order_items oi 
                                JOIN products p ON oi.product_id = p.id 
                                WHERE oi.order_id = {$order['id']}
                            ")->fetch_assoc()['total'] ?? 0;

                            $status = $order['status'] ?? 'Pending';
                            $badge = match ($status) {
                                'Accepted' => 'info',
                                'On the Way' => 'warning',
                                'Delivered' => 'success',
                                default => 'secondary',
                            };
                            ?>
                            <tr>
                                <td data-label="#">#<?= $order['id'] ?></td>
                                <td data-label="Total" class="truncate-cell">₹<?= number_format($total, 2) ?></td>
                                <td data-label="Payment" class="truncate-cell"><?= ucfirst($order['payment_method']) ?></td>
                                <td data-label="Status" class="truncate-cell"><span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($status) ?></span></td>
                                <td data-label="Date" class="truncate-cell"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
