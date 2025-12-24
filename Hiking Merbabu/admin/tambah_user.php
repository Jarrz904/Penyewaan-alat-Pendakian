<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon  = mysqli_real_escape_string($conn, $_POST['telepon']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // tanpa hash

    mysqli_query($conn, "INSERT INTO users (username, email, telepon, password) VALUES ('$username', '$email', '$telepon', '$password')");
    header("Location: admin_users.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah User Baru</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(-45deg, #1abc9c, #3498db, #9b59b6, #2ecc71);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
            font-family: 'Segoe UI', sans-serif;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .form-container {
            background: rgba(255,255,255,0.95);
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            margin: 70px auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: #2980b9;
            transform: scale(1.03);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #34495e;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Tambah User Baru</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="telepon" placeholder="Nomor Telepon" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">💾 Simpan User</button>
    </form>
    <a href="admin_users.php" class="back-link">← Kembali ke Daftar User</a>
</div>

</body>
</html>
