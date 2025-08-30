<?php
include '../includes/auth.php';
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
include '../includes/db.php';
$users = $conn->query("SELECT * FROM users");
?>
<?php include '../includes/admin_header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <!-- Page Title -->
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <h3 class="mb-0">👥 Users & Roles</h3>
            </div>

            <!-- Responsive Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($u = $users->fetch_assoc()): ?>
                            <tr>
                                <td class="text-truncate" style="max-width: 150px;"><?= htmlspecialchars($u['name']) ?></td>
                                <td class="text-truncate" style="max-width: 200px;"><?= htmlspecialchars($u['email']) ?>
                                </td>
                                <td>
                                    <span class="badge 
                                        <?= $u['role'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                                        <?= ucfirst(htmlspecialchars($u['role'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>