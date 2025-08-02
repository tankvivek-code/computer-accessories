<?php
session_start();
include "includes/db.php";

$error = "";
$success = "";

// Show register success message if coming from register.php
if (isset($_SESSION['register_success'])) {
    $success = $_SESSION['register_success'];
    unset($_SESSION['register_success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

    // Prepare statement to get user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify password (use password_verify if password is hashed)
        if ($pass === $row['password'] || password_verify($pass, $row['password'])) {
            // Store session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_role'] = $row['role'];

            // ✅ Redirect all users to the homepage after login
            header("Location: index.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No user found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login | Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            font-family: "Segoe UI", sans-serif;
        }

        .login-box {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem;
        }

        .btn-primary {
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 500;
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        @media (max-width: 576px) {
            .login-box {
                padding: 1.5rem;
                border-radius: 15px;
            }

            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-box">
            <div class="login-title text-center">🔐 Login to Your Account</div>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input id="email" name="email" type="email" class="form-control" placeholder="Enter your email"
                        required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input id="password" name="password" type="password" class="form-control"
                        placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>

                <p class="mt-4 text-center small">
                    Don't have an account? <a href="register.php" class="text-decoration-none">Register here</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>