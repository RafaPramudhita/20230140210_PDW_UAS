<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header('Location: ../login.php');
    exit;
}
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: laporan.php');
    exit;
}
// Ambil data laporan
global $conn;
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT l.*, m.judul as modul_judul, m.pertemuan_ke, p.nama as praktikum_nama, u.nama as mahasiswa_nama FROM laporan l JOIN modul m ON l.modul_id=m.id JOIN praktikum p ON m.praktikum_id=p.id JOIN users u ON l.user_id=u.id WHERE l.id='$id'"));
if (!$row) {
    header('Location: laporan.php');
    exit;
}
// Proses penilaian
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nilai = intval($_POST['nilai']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    mysqli_query($conn, "UPDATE laporan SET nilai='$nilai', feedback='$feedback', status='Dinilai' WHERE id='$id'");
    header('Location: laporan.php?msg=Berhasil menilai');
    exit;
}
include 'templates/header.php';
if(isset($_GET['msg'])): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded border border-green-300 shadow text-center text-lg font-semibold"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>
<div class='w-full max-w-5xl'>
    <div class="max-w-xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-8 text-blue-900">Penilaian Laporan</h1>
        <div class="bg-white rounded shadow p-8 mb-8 border border-blue-100">
            <div class="mb-2"><span class="font-semibold">Mahasiswa:</span> <?php echo htmlspecialchars($row['mahasiswa_nama']); ?></div>
            <div class="mb-2"><span class="font-semibold">Praktikum:</span> <?php echo htmlspecialchars($row['praktikum_nama']); ?></div>
            <div class="mb-2"><span class="font-semibold">Modul:</span> Pertemuan <?php echo $row['pertemuan_ke']; ?> - <?php echo htmlspecialchars($row['modul_judul']); ?></div>
            <div class="mb-2"><span class="font-semibold">Tanggal Kumpul:</span> <?php echo $row['tanggal_kumpul']; ?></div>
            <div class="mb-2"><span class="font-semibold">File Laporan:</span> <?php if($row['file_laporan']): ?><a href="../uploads/laporan/<?php echo $row['file_laporan']; ?>" class="text-blue-600 hover:underline" download>Download</a><?php endif; ?></div>
            <div class="mb-2"><span class="font-semibold">Status:</span> <?php echo htmlspecialchars($row['status']); ?></div>
            <?php if($row['nilai'] !== null): ?>
                <div class="mb-2"><span class="font-semibold">Nilai:</span> <?php echo $row['nilai']; ?></div>
                <div class="mb-2"><span class="font-semibold">Feedback:</span> <?php echo nl2br(htmlspecialchars($row['feedback'])); ?></div>
            <?php endif; ?>
        </div>
        <form method="post" class="bg-white rounded shadow p-8 border border-blue-100 space-y-4">
            <div>
                <label class="block font-semibold mb-1">Nilai (0-100)</label>
                <input type="number" name="nilai" min="0" max="100" required class="w-full border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" value="<?php echo $row['nilai'] ?? ''; ?>">
            </div>
            <div>
                <label class="block font-semibold mb-1">Feedback</label>
                <textarea name="feedback" class="w-full border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" required><?php echo htmlspecialchars($row['feedback'] ?? ''); ?></textarea>
            </div>
            <div class="flex gap-2 items-center">
                <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-semibold shadow">Simpan Penilaian</button>
                <a href="laporan.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-semibold">Kembali</a>
            </div>
        </form>
    </div>
</div>
<?php include 'templates/footer.php'; ?> 