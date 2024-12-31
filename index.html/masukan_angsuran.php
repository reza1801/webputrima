<?php
// File: masukan_angsuran.php

// Mulai sesi
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Masukkan koneksi database
require 'connection.php';

// Inisialisasi variabel
$nasabahData = null;
$nik = null;
$saldo = 0;
$remainingBalance = 0;
$message = ""; // Variabel pesan error atau sukses

// Ambil data untuk NIK dalam dropdown
$nikQuery = "SELECT nik, nama FROM nasabah";
$nikResult = $conn->query($nikQuery);
if (!$nikResult) {
    die("Query gagal: " . $conn->error); // Error debug
}

// Proses form pengisian angsuran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['angsuran'])) {
    // Proses data angsuran
    $nik = $_POST['nik'];
    $angsuran = $_POST['angsuran'];

    // Menghapus "Rp" dan format angsuran sebagai angka
    $angsuran = str_replace(['Rp', '.', ' '], '', $angsuran); // Hapus "Rp", koma, dan spasi
    $angsuran = (float)$angsuran; // Ubah ke float

    // Ambil tanggal dan waktu untuk angsuran
    $tanggalAngsuran = date('Y-m-d H:i:s');

    // Ambil data nasabah dan hitung saldo
    $nasabahQuery = "SELECT * FROM nasabah WHERE nik = '$nik'";
    $nasabahResult = $conn->query($nasabahQuery);

    if ($nasabahResult && $nasabahResult->num_rows > 0) {
        $nasabahData = $nasabahResult->fetch_assoc();

        // Hitung saldo berdasarkan pinjaman dan angsuran
        $saldo = $nasabahData['pinjaman'] * 1.2;
        $totalAngsuran = 0;

        for ($i = 1; $i <= 10; $i++) {
            if (!empty($nasabahData["angsuran$i"])) {
                $totalAngsuran += $nasabahData["angsuran$i"];
            }
        }

        // Hitung sisa saldo
        $remainingBalance = $saldo - $totalAngsuran;
    }

    // Cek apakah angsuran melebihi saldo yang tersisa
    if ($angsuran > $remainingBalance) {
        $message = "Angsuran tidak boleh melebihi sisa saldo yang ada. Sisa saldo: Rp " . number_format($remainingBalance, 0, ',', '.');
    } else {
        // Tentukan kolom angsuran mana yang akan diperbarui berdasarkan input angsuran
        $updateQuery = "";

        // Cek kolom angsuran mana yang kosong (misalnya jika angsuran1 null, perbarui itu)
        for ($i = 1; $i <= 10; $i++) {
            $colAngsuran = "angsuran$i";
            $colDate = "angsuran{$i}_date";

            // Cek apakah kolom angsuran kosong (null)
            $checkQuery = "SELECT $colAngsuran FROM nasabah WHERE nik = '$nik'";
            $checkResult = $conn->query($checkQuery);

            if (!$checkResult) {
                die("Query gagal: " . $conn->error); // Error debug
            }

            $checkRow = $checkResult->fetch_assoc();

            if (empty($checkRow[$colAngsuran])) {
                // Masukkan angsuran dan tanggal saat ini ke kolom yang kosong
                $updateQuery = "UPDATE nasabah 
                                SET $colAngsuran = '$angsuran', $colDate = '$tanggalAngsuran' 
                                WHERE nik = '$nik'";
                break; // Berhenti setelah menemukan kolom angsuran yang kosong
            }
        }

        // Jika query update dibuat, jalankan
        if ($updateQuery !== "" && $conn->query($updateQuery) === TRUE) {
            $message = "Angsuran berhasil ditambahkan!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Jika NIK dipilih, ambil data nasabah terkait
if (isset($_GET['nik'])) {
    $nik = $_GET['nik'];
    $nasabahQuery = "SELECT * FROM nasabah WHERE nik = '$nik'";
    $nasabahResult = $conn->query($nasabahQuery);

    if (!$nasabahResult) {
        die("Query gagal: " . $conn->error); // Error debug
    }

    if ($nasabahResult->num_rows > 0) {
        $nasabahData = $nasabahResult->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masukkan Angsuran</title>
    <style>
        /* Styling dan layout yang digunakan pada halaman ini */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background: linear-gradient(to bottom, #a0c4ff, #3f72af, #1a3d6e); /* Gradasi Biru Muda ke Biru Tua */
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
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
            background: linear-gradient(to right, #3498db, #2c3e50); /* Gradasi latar belakang form */
        }
        .form-container h1 {
            margin-bottom: 20px;
            color: white;
            font-size: 2em;
        }
        .form-container input, .form-container select {
            padding: 12px;
            width: 100%;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1em;
            box-sizing: border-box;
        }
        .form-container button {
            padding: 12px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            width: 100%;
        }
        .form-container button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <a href="tambah_nasabah.php">Tambah Nasabah</a>
        <a href="masukan_angsuran.php">Masukkan Angsuran</a>
        <a href="lihat_data.php">Lihat Data Nasabah</a>
        <a href="arsip.php">Arsip</a>
        <a href="menu.php?logout=true" class="logout">Logout</a>
    </div>

    <div class="content">
        <div class="form-container">
            <h1>Masukkan Angsuran</h1>
            <?php if (!empty($message)) { echo "<p>$message</p>"; } ?>

            <!-- Form untuk input angsuran -->
            <form method="POST" action="masukan_angsuran.php">
                <!-- Dropdown NIK -->
                <select name="nik" id="nik" onchange="fetchData()">
                    <option value="">Pilih NIK</option>
                    <?php while ($row = $nikResult->fetch_assoc()): ?>
                        <option value="<?php echo $row['nik']; ?>" <?php echo (isset($nik) && $nik == $row['nik']) ? 'selected' : ''; ?>>
                            <?php echo $row['nik']; ?> - <?php echo $row['nama']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <!-- Data auto-filled berdasarkan NIK yang dipilih -->
                <?php if ($nasabahData): ?>
                    <input type="text" id="nama" name="nama" placeholder="Nama" value="<?php echo htmlspecialchars($nasabahData['nama']); ?>" readonly>
                    <input type="text" id="alamat" name="alamat" placeholder="Alamat" value="<?php echo htmlspecialchars($nasabahData['alamat']); ?>" readonly>
                    <input type="text" id="rt_rw" name="rt_rw" placeholder="RT/RW" value="<?php echo htmlspecialchars($nasabahData['rt_rw']); ?>" readonly>
                    <input type="text" id="pinjaman" name="pinjaman" placeholder="Pinjaman" value="<?php echo 'Rp ' . number_format($nasabahData['pinjaman'], 0, ',', '.'); ?>" readonly>
                <?php endif; ?>

                <!-- Input Angsuran -->
                <input type="text" name="angsuran" id="angsuran" placeholder="Masukkan Angsuran (Rp)" required>

                <button type="submit">Masukkan Angsuran</button>
            </form>
        </div>
    </div>

    <script>
        function fetchData() {
            var nik = document.getElementById('nik').value;
            if (nik) {
                // Redirect ke halaman yang sama dengan parameter NIK di URL
                window.location.href = "masukan_angsuran.php?nik=" + nik;
            }
        }

        // Format input menjadi Rupiah
        document.getElementById('angsuran').addEventListener('input', function(e) {
            var value = e.target.value.replace(/[^0-9]/g, '');
            var formattedValue = formatRupiah(value);
            e.target.value = formattedValue;
        });

        function formatRupiah(amount) {
            var number_string = amount.replace(/[^0-9]/g, ''),
                split = number_string.split(','),
                remainder = split[0].length % 3,
                rupiah = split[0].substr(0, remainder),
                thousands = split[0].substr(remainder).match(/\d{3}/gi);

            if (thousands) {
                separator = remainder ? '.' : '';
                rupiah += separator + thousands.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }
    </script>
</body>
</html>
