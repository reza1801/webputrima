<?php
// File: arsip.php

// Mulai sesi
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Sertakan koneksi database
require 'connection.php';

// Cek apakah ada pencarian
$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// Ambil data arsip dengan pencarian jika ada
$sql = "SELECT * FROM arsip WHERE nama LIKE ? OR alamat LIKE ?"; 
$stmt = $conn->prepare($sql);
$searchTerm = '%' . $search . '%';
$stmt->bind_param('ss', $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah query berhasil dijalankan
if (!$result) {
    // Jika query gagal, tampilkan pesan error
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Data Nasabah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background: linear-gradient(to bottom, #a0c4ff, #3f72af, #1a3d6e);
        }
        .navbar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .navbar h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        .navbar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            margin-bottom: 10px;
            background-color: #495057;
            border-radius: 5px;
        }
        .navbar a:hover {
            background-color: #6c757d;
        }
        .content {
            flex: 1;
            padding: 20px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            box-shadow: 100px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            table-layout: fixed; /* Menetapkan lebar kolom tetap */
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            word-wrap: break-word; /* Membungkus kata yang panjang */
            white-space: normal; /* Membungkus teks */
        }
        table th {
            background-color: #343a40;
            color: white;
        }
        h1 {
            margin-bottom: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        .search-container input[type="text"] {
            padding: 8px;
            width: 200px;
            margin-right: 10px;
        }
        .search-container button {
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #0056b3;
        }
        .reset-search {
            background-color: #dc3545;
            margin-left: 10px;
            padding: 10px 12px;
            font-size: 18px;
            border-radius: 50%;
            text-align: center;
            cursor: pointer;
        }
        .reset-search:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <a href="tambah_nasabah.php">Tambah Nasabah</a>
        <a href="masukan_angsuran.php">Masukan Angsuran</a>
        <a href="lihat_data.php">Lihat Data Nasabah</a>
        <a href="arsip.php">Arsip</a>
        <a href="menu.php?logout=true" class="logout">Logout</a>
    </div>

    <div class="content">
        <h1>Data Arsip Nasabah</h1>

        <!-- Form Pencarian -->
        <div class="search-container">
            <form method="POST" action="arsip.php">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari berdasarkan nama atau alamat">
                <button type="submit">Cari</button>
                <?php if ($search): ?>
                    <a href="arsip.php" class="reset-search">X</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabel Data Arsip -->
        <div class="table-wrapper">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>RT/RW</th>
                        <th>Pinjaman</th>
                        <th>Saldo</th>
                        <th>tanggal</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nik']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                            <td><?php echo htmlspecialchars($row['rt_rw']); ?></td>
                            <td>Rp <?php echo number_format($row['pinjaman'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($row['pinjaman'] * 1.2, 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>Tidak ada data arsip.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
