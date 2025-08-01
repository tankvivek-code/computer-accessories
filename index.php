<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Store Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 py-2 shadow">
        <a class="navbar-brand fw-bold" href="#">🛍️ Store</a>
        <div class="ms-auto">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="text-white me-3">👋 Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="admin/dashboard.php" class="btn btn-warning btn-sm">Admin Panel</a>
                <?php else: ?>
                    <a href="user/dashboard.php" class="btn btn-primary btn-sm">User Dashboard</a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-outline-light btn-sm ms-2">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-success btn-sm me-2">Login</a>
                <a href="register.php" class="btn btn-outline-light btn-sm">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 text-center">
        <h1 class="display-4 mb-3">💻 Laptop Accessories Store</h1>
        <p class="lead text-muted">Explore the latest accessories. Please log in to view products and manage your cart.
        </p>
        <img src="assets/banner.jpg " alt="Store Banner" class="img-fluid rounded shadow mt-4"
            style="max-height: 350px;">
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        &copy; <?= date("Y") ?> Store. All rights reserved.
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>