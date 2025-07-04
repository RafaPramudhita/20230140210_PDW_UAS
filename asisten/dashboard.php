<?php
// 1. Definisi Variabel untuk Template
$pageTitle = 'Dashboard';
$activePage = 'dashboard';

// 2. Panggil Header
require_once 'templates/header.php'; 
?>

<div class='w-full max-w-5xl'>
<div class="max-w-3xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-center">Dashboard Asisten</h1>
    <div class="bg-white rounded shadow p-6 text-center">
        <p class="text-lg text-gray-700">Selamat datang di Panel Asisten. Silakan gunakan menu di samping untuk mengelola praktikum, modul, laporan, dan akun pengguna.</p>
    </div>
</div>
</div>

<?php
// 3. Panggil Footer
require_once 'templates/footer.php';
?>