<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$praktikum_id = $_GET['id'] ?? null;
if (!$praktikum_id) {
    header('Location: dashboard.php');
    exit;
}
// Ambil info praktikum
global $conn;
$praktikum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM praktikum WHERE id='$praktikum_id'"));
if (!$praktikum) {
    header('Location: dashboard.php');
    exit;
}
// Ambil modul-modul
$modul = mysqli_query($conn, "SELECT * FROM modul WHERE praktikum_id='$praktikum_id' ORDER BY pertemuan_ke ASC");
include 'templates/header_mahasiswa.php';
?>
<?php if(isset($_GET['msg'])): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded border border-green-300"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>
<div class="max-w-3xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($praktikum['nama']); ?></h1>
    <div class="mb-6 text-gray-700"><?php echo nl2br(htmlspecialchars($praktikum['deskripsi'])); ?></div>
    <h2 class="text-xl font-semibold mb-3">Daftar Modul & Materi</h2>
    <div class="space-y-6">
        <?php while($m = mysqli_fetch_assoc($modul)): ?>
            <div class="bg-white rounded shadow p-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <div class="font-semibold">Pertemuan <?php echo $m['pertemuan_ke']; ?>: <?php echo htmlspecialchars($m['judul']); ?></div>
                        <?php if($m['file_materi']): ?>
                            <a href="../uploads/materi/<?php echo $m['file_materi']; ?>" class="text-blue-600 hover:underline text-sm" download>Download Materi</a>
                        <?php endif; ?>
                    </div>
                </div>
                <form class="mt-3" method="post" action="upload_laporan.php" enctype="multipart/form-data">
                    <input type="hidden" name="modul_id" value="<?php echo $m['id']; ?>">
                    <div class="flex flex-col md:flex-row md:items-center gap-2">
                        <input type="file" name="file_laporan" required class="border rounded px-2 py-1">
                        <button type="submit" class="px-4 py-1 bg-green-600 text-white rounded hover:bg-green-700">Upload Laporan</button>
                    </div>
                </form>
                <?php
                // Cek laporan mahasiswa untuk modul ini
                $lap = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM laporan WHERE modul_id='{$m['id']}' AND user_id='$user_id'"));
                if ($lap): ?>
                    <div class="mt-2 text-sm">
                        <span class="font-semibold">Status:</span> <?php echo htmlspecialchars($lap['status']); ?>
                        <?php if($lap['file_laporan']): ?>
                            | <a href="../uploads/laporan/<?php echo $lap['file_laporan']; ?>" class="text-blue-600 hover:underline" download>Lihat Laporan</a>
                        <?php endif; ?>
                        <?php if($lap['nilai'] !== null): ?>
                            <br><span class="font-semibold">Nilai:</span> <?php echo $lap['nilai']; ?>
                            <br><span class="font-semibold">Feedback:</span> <?php echo nl2br(htmlspecialchars($lap['feedback'])); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php include 'templates/footer_mahasiswa.php'; ?> 