<?php
// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek jika pengguna belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <nav class="bg-blue-700 text-white px-6 py-3 shadow flex items-center justify-between">
        <div class="font-bold text-lg">Sistem Pengumpulan Tugas</div>
        <div class="flex gap-4">
            <a href="dashboard.php" class="hover:underline">Dashboard</a>
            <a href="../../katalog.php" class="hover:underline">Katalog</a>
            <a href="../../logout.php" class="hover:underline text-red-200">Logout</a>
        </div>
    </nav>

    <div class="container mx-auto p-6 lg:p-8">