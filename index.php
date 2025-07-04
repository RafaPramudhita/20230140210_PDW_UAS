<?php
session_start();
$role = $_SESSION['role'] ?? null;
if ($role === 'mahasiswa') {
    header('Location: mahasiswa/dashboard.php');
    exit;
} elseif ($role === 'asisten') {
    header('Location: asisten/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengumpulan Tugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded shadow p-8 max-w-md w-full text-center">
        <h1 class="text-3xl font-bold mb-6">Sistem Pengumpulan Tugas</h1>
        <div class="flex flex-col gap-4">
            <a href="login.php" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Login</a>
            <a href="register.php" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Register Mahasiswa</a>
            <a href="katalog.php" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Lihat Katalog Praktikum</a>
        </div>
    </div>
</body>
</html> 