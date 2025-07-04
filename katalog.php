<?php
include 'config.php';
session_start();

// Ambil data praktikum
global $conn;
$praktikum = mysqli_query($conn, "SELECT * FROM praktikum");

// Cek login dan role
$user_id = $_SESSION['user_id'] ?? null;
$role = $_SESSION['role'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Mata Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6 text-center">Katalog Mata Praktikum</h1>
        <?php if(isset($_GET['msg'])): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded border border-green-300"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>
        <div class="grid gap-6">
            <?php while($row = mysqli_fetch_assoc($praktikum)): ?>
                <div class="bg-white rounded shadow p-6 flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <div class="text-xl font-semibold mb-1"><?php echo htmlspecialchars($row['nama']); ?></div>
                        <div class="text-gray-600 text-sm"><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></div>
                    </div>
                    <?php if($role === 'mahasiswa'): ?>
                        <form method="post" action="mahasiswa/daftar_praktikum.php" class="mt-4 md:mt-0 md:ml-6">
                            <input type="hidden" name="praktikum_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Daftar</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html> 