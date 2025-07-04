<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
// Ambil daftar praktikum yang diikuti
$praktikum = mysqli_query($conn, "SELECT p.id, p.nama, p.deskripsi FROM pendaftaran pd JOIN praktikum p ON pd.praktikum_id=p.id WHERE pd.user_id='$user_id'");
include 'templates/header_mahasiswa.php';
?>
<div class="max-w-3xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Praktikum Saya</h1>
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
                <a href="praktikum_detail.php?id=<?php echo $row['id']; ?>" class="mt-4 md:mt-0 md:ml-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php include 'templates/footer_mahasiswa.php'; ?>