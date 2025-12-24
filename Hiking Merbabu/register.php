<?php
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $password = trim($_POST['password']);

    // Cek apakah username atau email sudah digunakan
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username atau email sudah digunakan.";
    } else {
        // Simpan password langsung (tanpa hash)
        $query = mysqli_query($conn, "INSERT INTO users (username, email, telepon, password)
                                      VALUES ('$username', '$email', '$telepon', '$password')");

        if ($query) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'user';

            // Simpan cookie agar tetap login meski aplikasi ditutup
            setcookie('username', $username, time() + (86400 * 30), "/");
            setcookie('role', 'user', time() + (86400 * 30), "/");

            header("Location: user/dashboard.php");
            exit;
        } else {
            $error = "Gagal mendaftar.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('img/gunung_merbabu.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            animation: fadeIn 1s ease-in;
        }

        .register-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.8s ease-out;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background-color: #2e8b57;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #246b45;
        }

        .error-message {
            margin-top: 10px;
            color: red;
            text-align: center;
            font-size: 14px;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 12px;
            text-decoration: none;
            color: #2e8b57;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
<div class="register-box">
    <h2>Registrasi User</h2>
    <?php if (isset($error)) echo "<div class='error-message'>$error</div>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="telepon" placeholder="Nomor Telepon" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Daftar</button>
    </form>
    <a href="login_user.php">Sudah punya akun? Login</a>
    <a href="index.php">← Kembali ke Beranda</a>
</div>
</body>
</html>
