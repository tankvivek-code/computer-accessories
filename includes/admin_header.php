<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel | Computer Accessories</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Admin CSS -->
    <link href="../assets/admin.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <!-- Brand/logo -->
            <a class="navbar-brand fw-bold" href="#">🛠️ Admin Panel</a>

            <!-- Mobile Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
                aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible Navigation -->
            <div class="collapse navbar-collapse justify-content-end" id="adminNavbar">
                <ul class="navbar-nav mb-2 mb-lg-0 align-items-lg-center gap-2">
                    <li class="nav-item">
                        <span class="nav-link text-light">👤
                            <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-btn-outline-secondary text-capitalize" href="../admin/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>