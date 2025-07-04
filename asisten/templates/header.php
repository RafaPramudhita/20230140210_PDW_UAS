<?php
// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek jika pengguna belum login atau bukan asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}
// Menu aktif
$current = basename($_SERVER['PHP_SELF']);
function menu_active($file) {
    global $current;
    return $current === $file ? 'bg-gradient-to-r from-blue-700 to-blue-600 text-white shadow-lg font-semibold' : 'text-blue-100 hover:bg-blue-700/80 hover:text-white';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Asisten</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="flex min-h-screen">
    <aside class="w-64 bg-gradient-to-b from-blue-900 via-blue-800 to-blue-700 text-white flex flex-col py-10 px-6 border-r-2 border-blue-900 shadow-2xl">
        <div class="mb-10 flex flex-col items-center">
            <div class="font-extrabold text-2xl mb-2 tracking-wider uppercase drop-shadow">Sistem Pengumpulan Tugas</div>
            <div class="text-xs text-blue-200 mb-3">Asisten: <?php echo htmlspecialchars($_SESSION['nama']); ?></div>
            <div class="w-20 h-20 rounded-full bg-blue-600 flex items-center justify-center text-3xl font-extrabold text-white shadow-xl border-4 border-white mb-2">
                <span><?php echo strtoupper(substr($_SESSION['nama'],0,2)); ?></span>
            </div>
        </div>
        <nav class="flex-1">
            <ul class="space-y-1">
                <li>
                    <a href="dashboard.php" class="flex items-center gap-3 px-5 py-3 rounded-xl transition-all duration-200 <?php echo menu_active('dashboard.php'); ?> border border-transparent">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0v6m0 0H7m6 0h6"/></svg>
                        <span class="ml-1 text-base">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="praktikum.php" class="flex items-center gap-3 px-5 py-3 rounded-xl transition-all duration-200 <?php echo menu_active('praktikum.php'); ?> border border-transparent">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6"/></svg>
                        <span class="ml-1 text-base">Kelola Modul</span>
                    </a>
                </li>
                <li>
                    <a href="laporan.php" class="flex items-center gap-3 px-5 py-3 rounded-xl transition-all duration-200 <?php echo menu_active('laporan.php'); ?> border border-transparent">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                        <span class="ml-1 text-base">Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="akun.php" class="flex items-center gap-3 px-5 py-3 rounded-xl transition-all duration-200 <?php echo menu_active('akun.php'); ?> border border-transparent">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.797.657 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="ml-1 text-base">Akun</span>
                    </a>
                </li>
                <li class="mt-8">
                    <hr class="border-blue-700 mb-3">
                    <a href="../logout.php" class="flex items-center gap-3 px-5 py-3 rounded-xl font-semibold bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white transition-all duration-200 border border-transparent shadow-lg">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/></svg>
                        <span class="ml-1 text-base">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    <main class="flex-1 flex justify-center items-start p-6 lg:p-10 bg-gray-50 min-h-screen">

</main>
</body>
</html>