<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login_user.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>About - Merbabu App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin: 15px 0;
            font-size: 16px;
            color: #333;
            padding-left: 32px;
            position: relative;
        }
        li::before {
            content: '\f058'; /* fa-circle-check */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            color: #27ae60;
            position: absolute;
            left: 0;
            top: 0;
        }
        .section-title {
            margin-top: 30px;
            font-size: 20px;
            color: #34495e;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
        }
        .back-link {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 15px;
            background: #2e8b57;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        .back-link:hover {
            background: #246b45;
        }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fas fa-mountain"></i> Tentang Aplikasi Pendakian Merbabu</h1>

    <h2 class="section-title">🧭 Tujuan Aplikasi</h2>
    <ul>
        <li>Mempermudah pemesanan peralatan pendakian secara online.</li>
        <li>Menyediakan layanan real-time untuk status pesanan.</li>
        <li>Mencatat riwayat transaksi secara otomatis dan terorganisir.</li>
        <li>Memberikan pengalaman pengguna yang praktis dan ramah.</li>
    </ul>

    <h2 class="section-title">⚙️ Fitur Utama</h2>
    <ul>
        <li>Form checklist untuk memilih alat yang ingin dipinjam.</li>
        <li>Status pemesanan: Belum, Diterima, atau Dibatalkan.</li>
        <li>Riwayat peminjaman lengkap yang dapat dibatalkan 1x sehari.</li>
        <li>Admin dapat menerima dan menghapus pesanan user.</li>
    </ul>

    <h2 class="section-title">👨‍💻 Tentang Developer</h2>
    <ul>
        <li>Dikembangkan oleh Jarrz .</li>
        <li>Teknologi: PHP, MySQL, HTML, CSS, JavaScript.</li>
        <li>Fokus pada sistem sederhana, efisien, dan responsif.</li>
    </ul>

    <h2 class="section-title">📌 Informasi Tambahan</h2>
    <ul>
        <li>Aplikasi ini merupakan proyek edukasi.</li>
        <li>Update dan perbaikan sistem dilakukan secara berkala.</li>
        <li>Saran & kritik sangat kami apresiasi demi pengembangan lebih lanjut.</li>
    </ul>

    <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
</div>
<script src="js/particles.min.js"></script>
<script src="js/app.js"></script>
</body>
</body>
</html>
