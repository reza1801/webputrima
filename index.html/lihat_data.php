<?php
// Mulai sesi
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Sertakan koneksi database
require 'connection.php';

// Inisialisasi variabel pencarian
$searchQuery = '';
$searchValue = '';

// Periksa jika form pencarian sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchValue = trim($_POST['search']);
    $searchQuery = " WHERE nama LIKE '%$searchValue%' OR alamat LIKE '%$searchValue%'";
}

// Ambil data dari database dengan atau tanpa query pencarian
$sql = "SELECT * FROM nasabah" . $searchQuery;
$result = $conn->query($sql);

// Mulai transaksi
$conn->begin_transaction();

try {
    // Proses penghapusan data yang memiliki sisa saldo 0
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Hitung saldo (pinjaman * 1.2)
            $saldo = $row['pinjaman'] * 1.2;
            $totalAngsuran = 0;

            // Hitung total angsuran
            for ($i = 1; $i <= 10; $i++) {
                if (!empty($row["angsuran$i"])) {
                    $totalAngsuran += $row["angsuran$i"];
                }
            }
            $sisaSaldo = $saldo - $totalAngsuran;

            // Jika sisa saldo 0, pindahkan ke arsip dan hapus dari nasabah
            if ($sisaSaldo == 0) {
                // Pindahkan data ke arsip
                $sqlInsert = "INSERT INTO arsip (nik, nama, alamat, rt_rw, pinjaman, angsuran1, angsuran2, angsuran3, angsuran4, angsuran5, angsuran6, angsuran7, angsuran8, angsuran9, angsuran10, angsuran1_date, angsuran2_date, angsuran3_date, angsuran4_date, angsuran5_date, angsuran6_date, angsuran7_date, angsuran8_date, angsuran9_date, angsuran10_date) 
                    VALUES ('{$row['nik']}', '{$row['nama']}', '{$row['alamat']}', '{$row['rt_rw']}', '{$row['pinjaman']}', '{$row['angsuran1']}', '{$row['angsuran2']}', '{$row['angsuran3']}', '{$row['angsuran4']}', '{$row['angsuran5']}', '{$row['angsuran6']}', '{$row['angsuran7']}', '{$row['angsuran8']}', '{$row['angsuran9']}', '{$row['angsuran10']}', '{$row['angsuran1_date']}', '{$row['angsuran2_date']}', '{$row['angsuran3_date']}', '{$row['angsuran4_date']}', '{$row['angsuran5_date']}', '{$row['angsuran6_date']}', '{$row['angsuran7_date']}', '{$row['angsuran8_date']}', '{$row['angsuran9_date']}', '{$row['angsuran10_date']}')";

                if ($conn->query($sqlInsert) === TRUE) {
                    // Hapus data terkait di tabel angsuran
                    $sqlDeleteNasabah = "DELETE FROM nasabah WHERE nik = '{$row['nik']}'";
                    if ($conn->query($sqlDeleteNasabah) !== TRUE) {
                        throw new Exception("Error deleting record from nasabah: " . $conn->error);
                    }

                    // Hapus data dari tabel nasabah
                    $sqlDeleteNasabah = "DELETE FROM nasabah WHERE nik = '{$row['nik']}'";
                    if ($conn->query($sqlDeleteNasabah) !== TRUE) {
                        throw new Exception("Error deleting record from nasabah: " . $conn->error);
                    }
                } else {
                    throw new Exception("Error inserting record into arsip: " . $conn->error);
                }
            }
        }

        // Commit transaksi
        $conn->commit();
    }
} catch (Exception $e) {
    // Rollback jika terjadi error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

// Ambil data dari database lagi setelah proses penghapusan
$sql = "SELECT * FROM nasabah" . $searchQuery;
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data Nasabah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background: linear-gradient(to right, #3498db, #2c3e50); /* Gradasi Biru Muda ke Biru Tua */
            color: white;
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
            background: linear-gradient(to right, #3498db, #2c3e50); /* Gradasi latar belakang tabel sesuai dengan kontainer */
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            word-wrap: break-word;
            font-size: 14px;
        }
        table th {
            background-color: #343a40;
            color: white;
        }
        table td {
            background-color: #ffffff; /* Latar belakang putih pada isi tabel */
            color: #333; /* Warna font lebih gelap untuk kontras */
        }
        h1 {
            margin-bottom: 20px;
        }
        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }
        .search-bar input {
            padding: 8px;
            width: 250px;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
            font-size: 16px;
        }
        .search-bar button {
            padding: 8px 16px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #1f618d;
        }
        .clear-search {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            margin-left: 10px;
            cursor: pointer;
        }
        .clear-search:hover {
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
        <h1>Data Nasabah</h1>

        <!-- Form Pencarian -->
        <div class="search-bar">
            <form method="POST" action="lihat_data.php">
                <input type="text" name="search" placeholder="Cari berdasarkan Nama atau Alamat" value="<?php echo htmlspecialchars($searchValue); ?>">
                <button type="submit">Cari</button>
            </form>
        </div>

        <!-- Tombol Clear Search -->
        <?php if ($searchQuery): ?>
            <a href="lihat_data.php" class="clear-search">X</a>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                // Hitung saldo (pinjaman * 1.2)
                $saldo = $row['pinjaman'] * 1.2;
                $totalAngsuran = 0;

                // Hitung total angsuran
                for ($i = 1; $i <= 10; $i++) {
                    if (!empty($row["angsuran$i"])) {
                        $totalAngsuran += $row["angsuran$i"];
                    }
                }
                $sisaSaldo = $saldo - $totalAngsuran;
                ?>

                <table>
                    <tr><th style="width: 100px;">NIK</th><td style="width: 150px;"><?php echo htmlspecialchars($row['nik']); ?></td></tr>
                    <tr><th style="width: 100px;">Nama</th><td style="width: 150px;"><?php echo htmlspecialchars($row['nama']); ?></td></tr>
                    <tr><th style="width: 100px;">Alamat</th><td style="width: 150px;"><?php echo htmlspecialchars($row['alamat']); ?></td></tr>
                    <tr><th style="width: 100px;">RT/RW</th><td style="width: 150px;"><?php echo htmlspecialchars($row['rt_rw']); ?></td></tr>
                    <tr><th style="width: 100px;">Pinjaman</th><td style="width: 150px;">Rp <?php echo number_format($row['pinjaman'], 0, ',', '.'); ?></td></tr>
                    <tr><th style="width: 100px;">Saldo</th><td style="width: 150px;">Rp <?php echo number_format($saldo, 0, ',', '.'); ?></td></tr>

                    <!-- Angsuran dan Tanggal -->
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <?php if (!empty($row["angsuran$i"])): ?>
                            <tr>
                                <th style="width: 150px;">Angsuran <?php echo $i; ?></th>
                                <td style="width: 150px;">Rp <?php echo number_format($row["angsuran$i"], 0, ',', '.'); ?></td>
                                <th style="width: 150px;">Tanggal/Waktu</th>
                                <td style="width: 150px;"><?php echo date("d-m-Y H:i", strtotime($row["angsuran{$i}_date"])); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <!-- Sisa Saldo -->
                    <tr>
                        <th style="width: 150px;">Sisa Saldo</th>
                        <td style="width: 150px;">Rp <?php echo number_format($sisaSaldo, 0, ',', '.'); ?></td>
                    </tr>
                </table>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada data nasabah.</p>
        <?php endif; ?>
    </div>
</body>
</html>
