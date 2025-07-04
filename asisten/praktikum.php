<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header('Location: ../login.php');
    exit;
}
// Handle tambah/edit/hapus
$edit = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    if (isset($_POST['id']) && $_POST['id']) {
        // Edit
        $id = intval($_POST['id']);
        mysqli_query($conn, "UPDATE praktikum SET nama='$nama', deskripsi='$deskripsi' WHERE id='$id'");
        header('Location: praktikum.php?msg=Berhasil update');
        exit;
    } else {
        // Tambah
        mysqli_query($conn, "INSERT INTO praktikum (nama, deskripsi) VALUES ('$nama', '$deskripsi')");
        header('Location: praktikum.php?msg=Berhasil tambah');
        exit;
    }
}
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM praktikum WHERE id='$id'"));
}
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM praktikum WHERE id='$id'");
    header('Location: praktikum.php?msg=Berhasil hapus');
    exit;
}
// Ambil semua praktikum
$praktikum = mysqli_query($conn, "SELECT * FROM praktikum");
include 'templates/header.php';
?>
<?php if(isset($_GET['msg'])): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded border border-green-300 shadow text-center text-lg font-semibold"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>
<div class='w-full max-w-5xl'>
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-8 text-blue-900">Kelola Mata Praktikum</h1>
        <div class="bg-white rounded shadow p-8 mb-10 border border-blue-100">
            <h2 class="text-xl font-semibold mb-4 text-blue-700"><?php echo $edit ? 'Edit Praktikum' : 'Tambah Praktikum'; ?></h2>
            <form method="post" class="space-y-4">
                <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">
                <div>
                    <label class="block font-semibold mb-1">Nama Praktikum</label>
                    <input type="text" name="nama" required class="w-full border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" value="<?php echo htmlspecialchars($edit['nama'] ?? ''); ?>">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Deskripsi</label>
                    <textarea name="deskripsi" required class="w-full border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200"><?php echo htmlspecialchars($edit['deskripsi'] ?? ''); ?></textarea>
                </div>
                <div class="flex gap-2 items-center">
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold shadow">Simpan</button>
                    <?php if($edit): ?>
                        <a href="praktikum.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-semibold">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <div class="bg-white rounded shadow p-8 border border-blue-100">
            <h2 class="text-lg font-semibold mb-4 text-blue-700">Daftar Mata Praktikum</h2>
            <div class="overflow-x-auto">
            <table class="w-full text-left border border-gray-200 rounded-lg">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="py-3 px-4 border-b font-semibold">Nama</th>
                        <th class="py-3 px-4 border-b font-semibold">Deskripsi</th>
                        <th class="py-3 px-4 border-b font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($praktikum)): ?>
                    <tr class="hover:bg-blue-50">
                        <td class="py-2 px-4 border-b align-top"><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td class="py-2 px-4 border-b align-top"><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></td>
                        <td class="py-2 px-4 border-b align-top flex flex-wrap gap-2">
                            <a href="praktikum.php?edit=<?php echo $row['id']; ?>" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 font-semibold">Edit</a>
                            <a href="praktikum.php?delete=<?php echo $row['id']; ?>" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 font-semibold" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            <a href="modul.php?praktikum_id=<?php echo $row['id']; ?>" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 font-semibold">Kelola Modul</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?> 