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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow py-2">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="#">🛍️ Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="navbar-text text-white me-3">👋 Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="admin/dashboard.php" class="btn btn-warning btn-sm me-2">Admin Panel</a>
                    <?php else: ?>
                        <a href="user/dashboard.php" class="btn btn-primary btn-sm me-2">User Dashboard</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-success btn-sm me-2">Login</a>
                    <a href="register.php" class="btn btn-outline-light btn-sm">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 text-center">
        <h1 class="display-5 fw-semibold mb-3">💻 Laptop Accessories Store</h1>
        <p class="lead text-muted px-2 px-md-5">
            Explore the latest accessories. Please log in to view products and manage your cart.
        </p>
        <img src="assets/banner.jpg" alt="Store Banner" class="img-fluid rounded shadow mt-4 w-100"
            style="max-height: 350px; object-fit: cover;">
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        &copy; <?= date("Y") ?> Store. All rights reserved.
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>