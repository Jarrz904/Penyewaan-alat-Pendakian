<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_admin.php");
    exit;
}

// Ambil statistik
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) as total FROM pemesanan WHERE status = 'diterima'"))['total'] ?? 0;
$jumlah_belum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jml FROM pemesanan WHERE status = 'belum'"))['jml'];
$jumlah_diterima = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jml FROM pemesanan WHERE status = 'diterima'"))['jml'];
$jumlah_dibatalkan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jml FROM pemesanan WHERE status = 'dibatalkan'"))['jml'];
$alat_terbanyak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT alat, COUNT(*) as jumlah FROM pemesanan WHERE status = 'diterima' GROUP BY alat ORDER BY jumlah DESC LIMIT 1")) ?? ['alat' => '-', 'jumlah' => 0];

// Proses aksi
if (isset($_GET['terima'])) {
    $id = intval($_GET['terima']);
    if ($id > 0) {
        mysqli_query($conn, "UPDATE pemesanan SET status='diterima' WHERE id=$id");
        header("Location: dashboard.php");
        exit;
    }
}

if (isset($_POST['submit_tolak'])) {
    $id = intval($_POST['id_tolak']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan_tolak']);
    if ($id > 0 && !empty($keterangan)) {
        mysqli_query($conn, "UPDATE pemesanan SET status='ditolak', keterangan_tolak='$keterangan' WHERE id=$id");
    }
    header("Location: dashboard.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if ($id > 0) {
        mysqli_query($conn, "DELETE FROM pemesanan WHERE id=$id");
        header("Location: dashboard.php");
        exit;
    }
}

if (isset($_POST['hapus_semua'])) {
    mysqli_query($conn, "DELETE FROM pemesanan");
    header("Location: dashboard.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM pemesanan ORDER BY waktu_pesan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body {
            margin: 0; padding: 0;
            background: linear-gradient(-45deg, #1abc9c, #2ecc71, #3498db, #9b59b6);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
            font-family: 'Segoe UI', sans-serif; color: #fff;
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .container {
            background: rgba(0,0,0,0.7); padding: 40px; border-radius: 10px;
            max-width: 1050px; margin: 60px auto; animation: fadeIn 0.8s ease-in;
        }
        h2 { text-align: center; color: #f9f9f9; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td {
            padding: 12px; border: 1px solid #ccc;
            text-align: center; background-color: rgba(255,255,255,0.1);
        }
        th { background-color: rgba(255,255,255,0.2); }
        a.logout, .btn-terima, .btn-hapus {
            display: inline-block; padding: 8px 16px; margin-top: 6px;
            text-decoration: none; border-radius: 6px; font-weight: bold;
        }
        .logout { background: #ff4d4d; color: white; }
        .logout:hover { background: #cc0000; }
        .btn-terima { background: #4CAF50; color: white; }
        .btn-terima:hover { background: #3e8e41; }
        .btn-hapus {
            background: #e74c3c; color: white; margin-left: 8px;
            border: none; cursor: pointer;
        }
        .btn-hapus:hover { background: #c0392b; }
        .status-diterima { color: #4CAF50; font-weight: bold; }
        .status-belum { color: #f1c40f; font-weight: bold; }
        .status-dibatalkan { color: #e74c3c; font-weight: bold; }
        .status-ditolak { color: #e67e22; font-weight: bold; }
        .wave-container {
            position: absolute; top: 0; width: 100%; height: 100px; z-index: 0;
        }
        svg { width: 100%; height: 100%; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .stats-boxes {
            display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 30px;
        }
        .stat-box {
            flex: 1; min-width: 200px; background: #ffffff22;
            padding: 20px; border-radius: 10px; text-align: center;
        }
        .stat-box h3 { margin-bottom: 10px; }
        form.hapus-semua { text-align: right; margin-top: 15px; }
    </style>
</head>
<body>

<div class="wave-container">
    <svg viewBox="0 0 120 28" preserveAspectRatio="none">
        <path d="M0,10 C30,20 60,0 120,10 L120,30 L0,30 Z" fill="#ffffff22">
            <animate attributeName="d" dur="10s" repeatCount="indefinite"
                values="M0,10 C30,20 60,0 120,10 L120,30 L0,30 Z;
                        M0,10 C30,0 60,20 120,10 L120,30 L0,30 Z;
                        M0,10 C30,20 60,0 120,10 L120,30 L0,30 Z"/>
        </path>
    </svg>
</div>

<div class="container">
    <h2>Dashboard Admin</h2>
    <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>

    <div class="stats-boxes">
        <div class="stat-box" style="background:#2ecc71aa">
            <h3>Total Pendapatan</h3>
            <p>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></p>
        </div>
        <div class="stat-box" style="background:#f1c40faa">
            <h3>Pesanan Belum</h3>
            <p><?= $jumlah_belum ?></p>
        </div>
        <div class="stat-box" style="background:#3498dbaa">
            <h3>Pesanan Diterima</h3>
            <p><?= $jumlah_diterima ?></p>
        </div>
        <div class="stat-box" style="background:#e74c3caa">
            <h3>Pesanan Dibatalkan</h3>
            <p><?= $jumlah_dibatalkan ?></p>
        </div>
        <div class="stat-box" style="background:#9b59b6aa">
            <h3>Alat Terpopuler</h3>
            <p><?= htmlspecialchars($alat_terbanyak['alat']) ?> (<?= $alat_terbanyak['jumlah'] ?>x)</p>
        </div>
    </div>

    <h3>Daftar Pesanan</h3>
    <table>
        <tr>
            <th>Username</th>
            <th>Alat</th>
            <th>Waktu</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['alat']) ?></td>
            <td><?= date('d M Y H:i:s', strtotime($row['waktu_pesan'])) ?></td>
            <td>
                <?php if ($row['status'] == 'diterima'): ?>
                    <span class="status-diterima">✅ Diterima</span>
                <?php elseif ($row['status'] == 'dibatalkan'): ?>
                    <span class="status-dibatalkan">❌ Dibatalkan oleh User</span>
                <?php elseif ($row['status'] == 'ditolak'): ?>
                    <span class="status-ditolak">🚫 Ditolak</span>
                <?php else: ?>
                    <span class="status-belum">⏳ Belum Diterima</span>
                <?php endif; ?>
            </td>
            <td>
                <?= $row['status'] === 'ditolak' && !empty($row['keterangan_tolak']) ? htmlspecialchars($row['keterangan_tolak']) : '-' ?>
            </td>
            <td>
                <?php if ($row['status'] === 'belum'): ?>
                    <a class="btn-terima" href="?terima=<?= $row['id'] ?>" onclick="return confirm('Terima pesanan ini?')">Terima</a>
                    <a class="btn-hapus" href="javascript:void(0)" onclick="openModalTolak(<?= $row['id'] ?>)">Tolak</a>
                    <a class="btn-hapus" href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin hapus pesanan ini?')">Hapus</a>
                <?php else: ?>
                    <a class="btn-hapus" href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus pesanan ini?')">Hapus</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="../logout.php" class="logout">Logout</a>
    <form method="post" class="hapus-semua" onsubmit="return confirm('Yakin ingin menghapus semua data pemesanan?')">
        <button type="submit" name="hapus_semua" class="btn-hapus">🗑️ Hapus Semua</button>
    </form>
    <a href="admin_users.php" class="btn-terima">👤 Kelola User</a>
</div>

<!-- Modal Tolak -->
<div id="modalTolak" style="display:none; position:fixed; z-index:10; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); justify-content:center; align-items:center;">
  <form method="POST" style="background:#fff; padding:30px; border-radius:10px; width:90%; max-width:400px; color:#000;">
    <h3>Alasan Penolakan</h3>
    <input type="hidden" name="id_tolak" id="id_tolak">
    <textarea name="keterangan_tolak" required placeholder="Contoh: Stok barang habis..." rows="4" style="width:100%; padding:10px;"></textarea>
    <br><br>
    <button type="submit" name="submit_tolak" style="background:#e74c3c; color:#fff; padding:10px 20px; border:none; border-radius:5px;">Tolak Sekarang</button>
    <button type="button" onclick="document.getElementById('modalTolak').style.display='none'" style="margin-left:10px;">Batal</button>
  </form>
</div>

<script>
function openModalTolak(id) {
    document.getElementById('id_tolak').value = id;
    document.getElementById('modalTolak').style.display = 'flex';
}
</script>

</body>
</html>