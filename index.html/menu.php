<?php
// File: menu.php

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background-color: linear-gradient(to right, #3498db, #2c3e50);;
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
            font-size: 1,5em;
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
            padding: 250px;
            text-align: center;
            background: linear-gradient(to right, #3498db, #2c3e50);
            font-size: 1em;
        }
        .logout {
            margin-top: 20px;
            background-color: #dc3545;
        }
        .logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2> Hai!, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <a href="tambah_nasabah.php">Tambah Nasabah</a>
        <a href="masukan_angsuran.php">Masukan Angsuran</a>
        <a href="lihat_data.php">Lihat Data Nasabah</a>
        <a href="arsip.php">Arsip</a>
        <a href="menu.php?logout=true" class="logout">Logout</a>
    </div>
    <div class="content">
        <h1>SELAMAT DATANG DI KSP KOSPIN JASA</h>
        <p>Silakan pilih menu di sebelah kiri!</p>
        
    </div>
</body>
</html>
