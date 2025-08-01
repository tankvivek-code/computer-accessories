<?php include '../includes/auth.php';
if ($_SESSION['user_role'] !== 'admin')
    header("Location: ../index.php");
include '../includes/db.php';
$users = $conn->query("SELECT * FROM users");
?>
<?php include '../includes/admin_header.php'; ?>
<h3>👥 Users & Roles</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($u = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $u['name'] ?></td>
                <td><?= $u['email'] ?></td>
                <td><?= $u['role'] ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../includes/admin_footer.php'; ?>