<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_admin.php");
    exit;
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen User</title>
    <style>
        * {
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(-45deg, #3498db, #2ecc71, #9b59b6, #1abc9c);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
            margin: 0;
            padding: 40px;
            color: #333;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            animation: fadeIn 0.8s ease;
            max-width: 1000px;
            margin: auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
        }

        th {
            background: #2980b9;
            color: #fff;
        }

        tr:nth-child(even) {
            background: #ecf0f1;
        }

        tr:hover {
            background: #dff9fb;
            transform: scale(1.01);
        }

        .button {
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
            margin: 3px;
            transition: transform 0.2s;
        }

        .button:hover {
            transform: scale(1.05);
        }

        .add {
            background: #27ae60;
            color: white;
        }

        .add:hover { background: #219150; }

        .edit {
            background: #f39c12;
            color: white;
        }

        .edit:hover { background: #d68910; }

        .delete {
            background: #e74c3c;
            color: white;
        }

        .delete:hover { background: #c0392b; }

        .back {
            background: #34495e;
            color: white;
        }

        .back:hover { background: #2c3e50; }

        .btn-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            .btn-group {
                flex-direction: column;
                align-items: stretch;
            }
        }
        @media (max-width: 768px) {
    body {
        padding: 10px;
    }

    .container {
        padding: 15px;
    }

    .btn-group {
        flex-direction: column;
        align-items: flex-start;
    }

    table {
        font-size: 14px;
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    .button {
        font-size: 14px;
        padding: 6px 10px;
    }

    h2 {
        font-size: 18px;
        text-align: left;
    }

    th, td {
        padding: 8px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <div class="btn-group">
            <h2>Manajemen User Terdaftar</h2>
            <div>
                <a href="dashboard.php" class="button back">← Kembali ke Dashboard</a>
                <a href="tambah_user.php" class="button add">+ Tambah User</a>
            </div>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
            <?php while($u = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['telepon']) ?></td>
                <td>
                    <a class="button edit" href="edit_user.php?id=<?= $u['id'] ?>">Edit</a>
                    <a class="button delete" href="hapus_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>