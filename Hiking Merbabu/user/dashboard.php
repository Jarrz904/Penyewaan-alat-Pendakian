<?php
session_start();
// Baris ini sudah benar, pastikan server Anda mendukung 'Asia/Jakarta'
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login_user.php");
    exit;
}

include '../includes/db.php';
$username = $_SESSION['username'];

$harga_per_alat = [
    "Carrier" => 25000,
    "Sleeping Bag" => 15000,
    "Matras" => 10000,
    "Tenda 2 Orang" => 40000,
    "Tenda 4 Orang" => 60000,
    "Tenda 6 Orang" => 80000,
    "Senter atau Headlamp" => 5000,
    "Jas Hujan" => 7000,
    "Alat Memasak" => 10000,
    "Trash Bag" => 2000,
    "Sandal atau Sepatu Gunung" => 15000
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alat']) && count($_POST['alat']) > 0) {
    $today = date('Y-m-d');
    $cek = $conn->prepare("SELECT COUNT(*) FROM pemesanan WHERE username = ? AND DATE(waktu_pesan) = ? AND status = 'belum'");
    $cek->bind_param("ss", $username, $today);
    $cek->execute();
    $cek->bind_result($sudah_pesan);
    $cek->fetch();
    $cek->close();

    if ($sudah_pesan == 0) {
        $alat = $_POST['alat'];
        if (!empty($_POST['ukuran_tenda'])) {
            $alat = array_filter($alat, fn($item) => $item !== 'Tenda');
            $alat[] = $_POST['ukuran_tenda'];
        }
        $alat_str = implode(', ', $alat);
        $total_harga = 0;
        foreach ($alat as $item) {
            if (isset($harga_per_alat[$item])) {
                $total_harga += $harga_per_alat[$item];
            }
        }

        // --- PERBAIKAN 1: Menggunakan waktu dari PHP, bukan NOW() dari MySQL ---
        $waktu_sekarang_wib = date('Y-m-d H:i:s'); // Format datetime SQL
        $stmt = $conn->prepare("INSERT INTO pemesanan (username, alat, waktu_pesan, status, total_harga) VALUES (?, ?, ?, 'belum', ?)");
        // Tipe parameter diubah dari "ssi" menjadi "sssi" karena ada tambahan string waktu
        $stmt->bind_param("sssi", $username, $alat_str, $waktu_sekarang_wib, $total_harga);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: dashboard.php");
    exit;
}

if (isset($_GET['batal'])) {
    $id_batal = intval($_GET['batal']);
    $today = date('Y-m-d');
    $cek = $conn->prepare("SELECT COUNT(*) FROM pemesanan WHERE username = ? AND DATE(waktu_pesan) = ? AND status = 'dibatalkan'");
    $cek->bind_param("ss", $username, $today);
    $cek->execute();
    $cek->bind_result($jumlah_batal);
    $cek->fetch();
    $cek->close();

    if ($jumlah_batal > 0) {
        echo "<script>alert('Anda hanya bisa membatalkan pesanan 1x per hari.'); window.location.href='dashboard.php';</script>";
        exit;
    }
    $stmt = $conn->prepare("UPDATE pemesanan SET status = 'dibatalkan' WHERE id = ? AND username = ? AND status = 'belum'");
    $stmt->bind_param("is", $id_batal, $username);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Pemesanan berhasil dibatalkan.'); window.location.href='dashboard.php';</script>";
    exit;
}

$status_pesanan = null;
$waktu_pesan = null; // Inisialisasi variabel
$latest = mysqli_query($conn, "SELECT status, waktu_pesan FROM pemesanan WHERE username='$username' ORDER BY waktu_pesan DESC LIMIT 1");
if ($row = mysqli_fetch_assoc($latest)) {
    $status_pesanan = $row['status'];
    $waktu_pesan = $row['waktu_pesan'];
}
$riwayat = mysqli_query($conn, "SELECT id, alat, waktu_pesan, status, total_harga, keterangan_tolak FROM pemesanan WHERE username='$username' ORDER BY waktu_pesan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard Pendaki</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&display=swap">
<style>  * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { margin: 0; background: #121212; color: #fff; }
    .toggle-btn { position: fixed; top: 15px; left: 15px; font-size: 24px; color: white; background: #1b5e20; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; z-index: 1002; }
    .overlay { position: fixed; display: none; width: 100%; height: 100%; top: 0; left: 0; background: rgba(0, 0, 0, 0.5); z-index: 1001; }
    .overlay.show { display: block; }
    .sidebar { position: fixed; left: 0; top: 0; height: 100vh; width: 220px; background: #263238; padding: 20px; transform: translateX(0); transition: transform 0.3s ease; z-index: 1003; }
    .sidebar h2 { text-align: center; margin-bottom: 30px; color: #fff; }
    .sidebar ul { list-style: none; padding: 0; }
    .sidebar ul li { margin: 20px 0; }
    .sidebar ul li a { color: #cfd8dc; text-decoration: none; transition: color 0.3s; }
    .sidebar ul li a:hover { color: #ffca28; font-weight: bold; }
    .sidebar.active { transform: translateX(0); }
    @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .sidebar.active { transform: translateX(0); } }
    .main { margin-left: 240px; padding: 30px; }
    @media (max-width: 768px) { .main { margin-left: 0; padding: 20px; padding-top: 70px; } }
    .status { padding: 15px; background: #37474f; border-left: 5px solid #ffd54f; margin-bottom: 20px; border-radius: 6px; }
    form label { display: block; margin: 8px 0; }
    select, input[type="checkbox"] { margin-right: 8px; }
    button[type="submit"] { background: #4caf50; color: white; padding: 10px 18px; border: none; border-radius: 6px; font-weight: bold; margin-top: 15px; transition: background 0.3s; }
    button[type="submit"]:hover { background: #66bb6a; }
    table { width: 100%; margin-top: 20px; border-collapse: collapse; background: #1e1e1e; border-radius: 10px; overflow: hidden; }
    table th, table td { padding: 10px; border-bottom: 1px solid #444; }
    table th { background-color: #1b5e20; color: white; }
    td.belum { background-color: #fff3cd; color: #856404; }
    td.diterima { background-color: #d4edda; color: #155724; }
    td.dibatalkan { background-color: #f8d7da; color: #721c24; }
    td.ditolak { background-color: #fbeaea; color: #a94442; }
    a.batal { color: #ef5350; font-weight: bold; text-decoration: none; }
    a.batal:hover { text-decoration: underline; }</style>
</head>
<body>

<button class="toggle-btn" id="toggleBtn">☰</button>
<div class="overlay" id="overlay"></div>

<div class="sidebar" id="sidebar">
    <h2>Menu</h2>
    <ul>
        <li><a href="dashboard.php">🏠 Dashboard</a></li>
        <li><a href="about.php">ℹ️ Tentang</a></li>
        <li><a href="../logout.php">🚪 Logout</a></li>
    </ul>
</div>

<div class="main">
    <h1>👋 Selamat Datang, <?= htmlspecialchars($username) ?></h1>

    <?php if (!empty($status_pesanan)) : ?>
    <div class="status">
        <?php
            $ikon = '⏳'; $teks = 'BELUM';
            if ($status_pesanan == 'diterima') { $ikon = '✅'; $teks = 'DITERIMA'; }
            elseif ($status_pesanan == 'dibatalkan') { $ikon = '❌'; $teks = 'DIBATALKAN'; }
            elseif ($status_pesanan == 'ditolak') { $ikon = '🚫'; $teks = 'DITOLAK'; }
        ?>
        <?= $ikon ?> Status Terakhir: <strong><?= $teks ?></strong><br>
        <!-- PERBAIKAN 2: Menyeragamkan format waktu ke UTC untuk JavaScript -->
        🕒 Waktu: <strong><span class="waktu" data-time="<?= gmdate('Y-m-d\TH:i:s\Z', strtotime($waktu_pesan)) ?>"></span></strong>
    </div>
    <?php endif; ?>

    <h2>Checklist Peralatan</h2>
    <form method="post">
        <label><input type="checkbox" name="alat[]" value="Carrier"> Carrier (Rp 25.000)</label>
        <label><input type="checkbox" name="alat[]" value="Sleeping Bag"> Sleeping Bag (Rp 15.000)</label>
        <label><input type="checkbox" name="alat[]" value="Matras"> Matras (Rp 10.000)</label>
        <label><input type="checkbox" name="alat[]" value="Tenda" id="tendaCheckbox"> Tenda</label>
        <select name="ukuran_tenda" id="ukuranTenda" disabled>
            <option value="" disabled selected>- Pilih Ukuran Tenda -</option>
            <option value="Tenda 2 Orang">Tenda 2 Orang (Rp 40.000)</option>
            <option value="Tenda 4 Orang">Tenda 4 Orang (Rp 60.000)</option>
            <option value="Tenda 6 Orang">Tenda 6 Orang (Rp 80.000)</option>
        </select>
        <label><input type="checkbox" name="alat[]" value="Senter atau Headlamp"> Senter atau Headlamp (Rp 5.000)</label>
        <label><input type="checkbox" name="alat[]" value="Jas Hujan"> Jas Hujan (Rp 7.000)</label>
        <label><input type="checkbox" name="alat[]" value="Alat Memasak"> Alat Memasak (Rp 10.000)</label>
        <label><input type="checkbox" name="alat[]" value="Trash Bag"> Trash Bag (Rp 2.000)</label>
        <label><input type="checkbox" name="alat[]" value="Sandal atau Sepatu Gunung"> Sandal atau Sepatu Gunung (Rp 15.000)</label>
        <button type="submit">Simpan</button>
    </form>
    <h2>📜 Riwayat Pemesanan</h2>
    <table>
        <tr><th>🛠️ Alat</th><th>🕒 Waktu</th><th>💰 Total</th><th>📌 Status</th><th>📝 Keterangan</th><th>🔧 Aksi</th></tr>
        <?php while ($r = mysqli_fetch_assoc($riwayat)) :
            $status = $r['status'];
            $keterangan = $status == 'ditolak' ? $r['keterangan_tolak'] : '-';
            $label = match($status) {
                'diterima' => ['✅ DITERIMA', 'diterima'],
                'dibatalkan' => ['❌ DIBATALKAN', 'dibatalkan'],
                'ditolak' => ['🚫 DITOLAK', 'ditolak'],
                default => ['⏳ BELUM DIPROSES', 'belum'],
            };
        ?>
        <tr>
            <td><?= htmlspecialchars($r['alat']) ?></td>
            <!-- PERBAIKAN 2 (lagi): Format waktu yang konsisten -->
            <td><span class="waktu" data-time="<?= gmdate('Y-m-d\TH:i:s\Z', strtotime($r['waktu_pesan'])) ?>"></span></td>
            <td>Rp <?= number_format($r['total_harga'], 0, ',', '.') ?></td>
            <td class="<?= $label[1] ?>"><?= $label[0] ?></td>
            <td><?= htmlspecialchars($keterangan) ?></td>
            <td><?= $status == 'belum' ? "<a class='batal' href='?batal={$r['id']}' onclick=\"return confirm('Batalkan pesanan ini?')\">Batalkan</a>" : '-' ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
// Sidebar
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
document.getElementById('toggleBtn').addEventListener('click', () => {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('show');
});
overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('show');
});

// Validasi tenda
const form = document.querySelector('form');
if (form) {
    form.addEventListener('submit', function(e) {
        const checkbox = document.getElementById('tendaCheckbox');
        const ukuran = document.getElementById('ukuranTenda');
        if (checkbox && checkbox.checked && ukuran.value === "") {
            e.preventDefault();
            alert("Silakan pilih ukuran tenda.");
        }
    });
}

document.getElementById('tendaCheckbox')?.addEventListener('change', function() {
    document.getElementById('ukuranTenda').disabled = !this.checked;
});

// --- PERBAIKAN 3: JavaScript sudah benar, tapi kita tambahkan format yang lebih baik ---
document.addEventListener('DOMContentLoaded', function() {
    const waktuElems = document.querySelectorAll('.waktu');
    waktuElems.forEach(function(el) {
        const utcTime = el.getAttribute('data-time');
        // Jika data-time kosong, jangan proses
        if (!utcTime) return;

        const date = new Date(utcTime);
        const formatter = new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Jakarta',
            year: 'numeric',
            month: 'long', // 'long' lebih mudah dibaca (e.g., "Juni")
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
        
        // Ganti koma pemisah tanggal dan jam menjadi spasi
        el.innerText = formatter.format(date).replace(',', '');
    });
});
</script>

</body>
</html>
