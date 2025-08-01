<?php
include '../includes/auth.php';
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
?>

<?php include '../includes/admin_header.php'; ?>

<!-- Bootstrap 5.3 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .sidebar {
        min-height: 100vh;
        position: sticky;
        top: 0;
    }

    .nav-link.active {
        font-weight: bold;
        background-color: #495057;
        border-radius: 5px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 bg-dark text-white p-4 sidebar">
            <h4 class="text-center mb-4">🛠 Admin Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>"
                        href="dashboard.php">📊 Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>"
                        href="products.php">📦 Manage Products</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : '' ?>"
                        href="orders.php">📄 Manage Orders</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : '' ?>"
                        href="users.php">👥 Manage Users</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : '' ?>"
                        href="messages.php">💬 View Messages</a>
                </li>
                <li class="nav-item mt-3">
                    <a class="btn btn-danger w-100" href="../logout.php">🚪 Logout</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 p-4">
            <h3>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?> (Admin)</h3>
            <p class="text-muted">Use the sidebar to manage your business data.</p>

            <div class="row">
                <!-- Products -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border-primary shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">📦 Products</h5>
                            <p class="card-text">View, edit, or delete products.</p>
                            <a href="products.php" class="btn btn-outline-primary btn-sm">Manage</a>
                        </div>
                    </div>
                </div>

                <!-- Orders -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border-success shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">📄 Orders</h5>
                            <p class="card-text">Check and fulfill orders.</p>
                            <a href="orders.php" class="btn btn-outline-success btn-sm">Manage</a>
                        </div>
                    </div>
                </div>

                <!-- Users -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border-info shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">👥 Users</h5>
                            <p class="card-text">View all registered users.</p>
                            <a href="users.php" class="btn btn-outline-info btn-sm">View</a>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border-warning shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">💬 Messages</h5>
                            <p class="card-text">Check user feedback or messages.</p>
                            <a href="messages.php" class="btn btn-outline-warning btn-sm">View</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>