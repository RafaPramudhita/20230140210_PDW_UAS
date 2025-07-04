<?php
include '../config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$praktikum_id = $_POST['praktikum_id'] ?? null;

if (!$praktikum_id) {
    header('Location: ../katalog.php?msg=Praktikum tidak valid');
    exit;
}

// Cek apakah sudah terdaftar
global $conn;
$cek = mysqli_query($conn, "SELECT * FROM pendaftaran WHERE user_id='$user_id' AND praktikum_id='$praktikum_id'");
if (mysqli_num_rows($cek) > 0) {
    header('Location: ../katalog.php?msg=Sudah terdaftar di praktikum ini');
    exit;
}

// Daftar ke praktikum
mysqli_query($conn, "INSERT INTO pendaftaran (user_id, praktikum_id) VALUES ('$user_id', '$praktikum_id')");
header('Location: ../katalog.php?msg=Berhasil mendaftar praktikum');
exit; 