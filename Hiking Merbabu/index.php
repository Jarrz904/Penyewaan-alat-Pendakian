<?php
session_start();

// Auto login via cookie
if (!isset($_SESSION['username']) && isset($_COOKIE['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['role'] = $_COOKIE['role'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Selamat Datang</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      background: url('gunung_merbabu.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
      color: white;
      min-height: 100vh;
      display: flex;
      flex-wrap: wrap;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      animation: fadeIn 1s ease-in;
      overflow-x: hidden;
      max-width: 100vw;
    }

    .main-box {
      width: 50%;
      text-align: center;
      margin: 20px;
      animation: slideRight 0.8s ease-out;
    }

    .left-box {
      width: 40%;
      background: rgba(0, 0, 0, 0.6);
      padding: 30px;
      margin: 20px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.5);
      animation: slideLeft 0.8s ease-out;
    }

    h1 {
      font-size: 36px;
      margin-bottom: 30px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
    }

    a {
      text-decoration: none;
      padding: 14px 24px;
      margin: 10px;
      border-radius: 10px;
      background: #2e8b57;
      color: white;
      font-weight: bold;
      font-size: 16px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(0,0,0,0.3);
      display: inline-block;
    }

    a:hover {
      background: #246b45;
      transform: scale(1.05);
    }

    .highlight {
      font-weight: bold;
      font-size: 18px;
      margin: 5px 0;
      color: #fff;
    }

    .left-box h2 {
      margin-top: 0;
      color: #ffd700;
    }

    .left-box .section {
      margin-bottom: 20px;
    }

    .left-box ul {
      padding-left: 20px;
    }

    .login-admin-btn {
      position: fixed;
      bottom: 20px;
      left: 20px;
      z-index: 999;
      background: #444;
      padding: 10px 16px;
      border-radius: 8px;
      font-size: 14px;
      opacity: 0.8;
      cursor: pointer;
    }

    .login-admin-btn:hover {
      opacity: 1;
      background: #333;
    }

    .exit-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 12px 20px;
      background-color: #e74c3c;
      color: white;
      font-weight: bold;
      font-size: 14px;
      border: none;
      border-radius: 30px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
      cursor: pointer;
      z-index: 9999;
      transition: all 0.3s ease;
    }

    .exit-button:hover {
      background-color: #c0392b;
      transform: scale(1.05);
    }

    .modal-exit {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
      z-index: 99999;
    }

    .modal-content {
      background: white;
      color: #333;
      padding: 20px 30px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0,0,0,0.4);
    }

    .modal-content button {
      margin: 10px 5px 0;
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .yes-btn {
      background-color: #e74c3c;
      color: white;
    }

    .no-btn {
      background-color: #999;
      color: white;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to   { opacity: 1; }
    }

    @keyframes slideLeft {
      from { transform: translateX(-50px); opacity: 0; }
      to   { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideRight {
      from { transform: translateX(50px); opacity: 0; }
      to   { transform: translateX(0); opacity: 1; }
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        padding-top: 20px;
      }

      .main-box, .left-box {
        width: 90%;
        margin: 10px;
      }

      .main-box {
        order: 1;
      }

      .left-box {
        order: 2;
      }

      h1 {
        font-size: 26px;
      }

      .login-admin-btn, .exit-button {
        font-size: 12px;
        padding: 8px 12px;
      }
    }
  </style>
</head>
<body>

  <div class="main-box">
    <h1>Selamat Datang di<br> Sistem Informasi dan Peminjaman Alat Pendakian</h1>
    <?php if (isset($_SESSION['username'])): ?>
      <p style="font-weight: bold; color: white; margin-bottom: 20px;">
          👋 Halo, <?= htmlspecialchars($_SESSION['username']) ?>!
      </p>
      <?php if ($_SESSION['role'] == 'user'): ?>
          <a href="user/dashboard.php">🏕️ Dashboard User</a>
      <?php elseif ($_SESSION['role'] == 'admin'): ?>
          <a href="admin/dashboard.php">🛠️ Dashboard Admin</a>
      <?php endif; ?>
      <a href="logout.php" onclick="return confirm('Yakin ingin keluar ?')">🚪 Keluar</a>
    <?php else: ?>
      <a href="login_user.php">Login</a>
      <a href="register.php">Registrasi</a>
    <?php endif; ?>
  </div>

  <div class="left-box">
    <div class="highlight">🗓️ Tanggal Pemberangkatan: 14–15 Juli 2025</div>
    <div class="highlight">💰 HTM: Mepo BC 250K | Mepo Salatiga 350K</div>
    <div class="section">
      <h2>✅ Include:</h2>
      <ul>
        <li>Registrasi</li>
        <li>Kebersihan Basecamp</li>
        <li>Tenda Team</li>
        <li>Porter Tenda</li>
        <li>Guide Team</li>
        <li>P3K Standar</li>
      </ul>
    </div>
    <div class="section">
      <h2>❌ Exclude:</h2>
      <ul>
        <li>Alat pribadi (carrier, SB, matras, dll)</li>
        <li>Logistik selama pendakian</li>
        <li>Obat pribadi</li>
        <li>Porter pribadi</li>
        <li>Alat salat</li>
        <li>Ojek</li>
      </ul>
    </div>
  </div>

  <?php if (!isset($_SESSION['username'])): ?>
    <div class="login-admin-btn" onclick="showPinModal()">🔐 Login Admin</div>
  <?php endif; ?>

  <!-- Modal PIN Admin -->
  <div id="pinModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:1000; justify-content:center; align-items:center;">
    <div style="background:#fff; padding:20px 30px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.3); text-align:center;">
      <h3 style="margin-top:0; color:#333;">Masukkan PIN Admin</h3>
      <input type="password" id="adminPin" placeholder="apa hayooo" style="padding:10px; width:200px; border-radius:5px; border:1px solid #ccc; font-size:16px;" />
      <br><br>
      <button onclick="verifyPin()" style="padding:8px 16px; border:none; background:#2e8b57; color:white; border-radius:5px; cursor:pointer;">Lanjut</button>
      <button onclick="closePinModal()" style="padding:8px 16px; margin-left:10px; border:none; background:#999; color:white; border-radius:5px; cursor:pointer;">Batal</button>
      <p id="pinError" style="color:red; display:none; margin-top:10px;">PIN salah!</p>
    </div>
  </div>

  <!-- Tombol Exit App -->
  <div id="exitAppBtn" class="exit-button" onclick="konfirmasiKeluar()" style="display: none;">🚪 Keluar Aplikasi</div>

  <!-- Modal Konfirmasi Keluar -->
  <div id="exitModal" class="modal-exit">
    <div class="modal-content">
      <p>Yakin ingin keluar dari aplikasi?</p>
      <button onclick="keluarAplikasi()" class="yes-btn">Ya</button>
      <button onclick="tutupModalKeluar()" class="no-btn">Batal</button>
    </div>
  </div>
<!-- Tombol Keluar Aplikasi -->

<script>
function keluarAplikasi() {
    if (typeof Android !== 'undefined' && Android.exitApp) {
        Android.exitApp(); // Fungsi panggil Java Android
    } else {
        alert("yakin mau keluar aplikasi? close sendiri mas jangan males wkwk.");
    }
}
</script>

<style>
#exitBtn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    background-color: #e74c3c;
    color: #fff;
    border: none;
    border-radius: 30px;
    padding: 12px 18px;
    font-size: 16px;
    font-weight: bold;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    cursor: pointer;
    transition: background 0.3s ease;
}
#exitBtn:hover {
    background-color: #c0392b;
}
</style>

  <script>
    function showPinModal() {
      document.getElementById('pinModal').style.display = 'flex';
      document.getElementById('adminPin').focus();
    }

    function closePinModal() {
      document.getElementById('pinModal').style.display = 'none';
      document.getElementById('adminPin').value = '';
      document.getElementById('pinError').style.display = 'none';
    }

    function verifyPin() {
      const pin = document.getElementById('adminPin').value;
      if (pin === "151002") {
        window.location.href = "login_admin.php";
      } else {
        document.getElementById('pinError').style.display = 'block';
      }
    }

    function konfirmasiKeluar() {
      document.getElementById('exitModal').style.display = 'flex';
    }

    function tutupModalKeluar() {
      document.getElementById('exitModal').style.display = 'none';
    }

    function keluarAplikasi() {
      if (typeof Android !== "undefined" && Android.exitApp) {
        Android.exitApp();
      } else {
        alert("Fitur keluar hanya tersedia di aplikasi Android.");
      }
    }

    if (typeof Android !== "undefined" && Android.exitApp) {
      document.getElementById('exitAppBtn').style.display = 'block';
    }
  </script>

</body>
</html>
