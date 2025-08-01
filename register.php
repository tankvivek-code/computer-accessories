<?php
session_start();
include "includes/db.php";

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // secure

    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $pass);
        $stmt->execute();
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5 col-md-4">
        <h2>📝 Register</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <input name="name" type="text" placeholder="Full Name" class="form-control mb-2" required>
            <input name="email" type="email" placeholder="Email" class="form-control mb-2" required>
            <input name="password" type="password" placeholder="Password" class="form-control mb-2" required>
            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>
    </div>
</body>

</html>