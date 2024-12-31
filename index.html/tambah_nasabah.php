<?php
// File: tambah_nasabah.php

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require 'connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $rt_rw = mysqli_real_escape_string($conn, $_POST['rt_rw']);
    $pinjaman = mysqli_real_escape_string($conn, $_POST['pinjaman']);

    // Remove non-numeric characters and format to integer
    $pinjaman = preg_replace('/[^0-9]/', '', $pinjaman);

    // Check if NIK already exists
    $check_query = "SELECT * FROM nasabah WHERE nik = '$nik'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        $existing_data = $result->fetch_assoc();
        $error = "Nasabah sudah terdaftar!<br>" .
                 "Nama: " . htmlspecialchars($existing_data['nama']) . "<br>" .
                 "Alamat: " . htmlspecialchars($existing_data['alamat']) . "<br>" .
                 "Pinjaman: Rp " . number_format($existing_data['pinjaman'], 0, ',', '.');
    } else {
        $sql = "INSERT INTO nasabah (nik, nama, alamat, rt_rw, pinjaman) VALUES ('$nik', '$nama', '$alamat', '$rt_rw', '$pinjaman')";

        if ($conn->query($sql) === TRUE) {
            $success = "Data nasabah berhasil ditambahkan!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Nasabah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background: linear-gradient(to right, #3498db, #2c3e50); /* Gradasi Biru Muda ke Biru Tua */
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
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
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
        .form-container input {
            padding: 12px;
            width: 100%;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1em;
            box-sizing: border-box; /* Menjaga input tetap rata */
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
        .success {
            color: white;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
    <script>
        // Format input as Rupiah
        function formatRupiah(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            input.value = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value).replace(/IDR\s?/, '');
        }
    </script>
</head>
<body>
    <div class="navbar">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <a href="tambah_nasabah.php">Tambah Nasabah</a>
        <a href="masukan_angsuran.php">Masukan Angsuran</a>
        <a href="lihat_data.php">Lihat Data Nasabah</a>
        <a href="arsip.php">Arsip</a>
        <a href="menu.php?logout=true" class="logout">Logout</a>
    </div>
    <div class="content">
        <div class="form-container">
            <h1>Tambah Nasabah</h1>
            <?php if (isset($success)) { echo '<p class="success">' . $success . '</p>'; } ?>
            <?php if (isset($error)) { echo '<p class="error">' . $error . '</p>'; } ?>
            <form method="POST" action="tambah_nasabah.php">
                <label for="nik">NIK:</label>
                <input type="text" id="nik" name="nik" required>

                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" required>

                <label for="alamat">Alamat:</label>
                <input type="text" id="alamat" name="alamat" required>

                <label for="rt_rw">RT/RW:</label>
                <input type="text" id="rt_rw" name="rt_rw" required>

                <label for="pinjaman">Pinjaman:</label>
                <input type="text" id="pinjaman" name="pinjaman" oninput="formatRupiah(this)" required>

                <button type="submit">Simpan</button>
            </form>
        </div>
    </div>
</body>
</html>
