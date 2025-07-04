<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header('Location: ../login.php');
    exit;
}
$praktikum_id = $_GET['praktikum_id'] ?? null;
if (!$praktikum_id) {
    header('Location: praktikum.php');
    exit;
}
// Handle tambah/edit/hapus modul
$edit = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $pertemuan_ke = intval($_POST['pertemuan_ke']);
    $file_materi = null;
    if (isset($_FILES['file_materi']) && $_FILES['file_materi']['name']) {
        $allowed = ['pdf','docx'];
        $filename = $_FILES['file_materi']['name'];
        $tmp = $_FILES['file_materi']['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $upload_dir = '../uploads/materi/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $newname = 'materi_' . $praktikum_id . '_' . $pertemuan_ke . '_' . time() . '.' . $ext;
            move_uploaded_file($tmp, $upload_dir . $newname);
            $file_materi = $newname;
        }
    }
    if (isset($_POST['id']) && $_POST['id']) {
        // Edit
        $id = intval($_POST['id']);
        if ($file_materi) {
            mysqli_query($conn, "UPDATE modul SET judul='$judul', pertemuan_ke='$pertemuan_ke', file_materi='$file_materi' WHERE id='$id'");
        } else {
            mysqli_query($conn, "UPDATE modul SET judul='$judul', pertemuan_ke='$pertemuan_ke' WHERE id='$id'");
        }
        header('Location: modul.php?praktikum_id=' . $praktikum_id . '&msg=Berhasil update');
        exit;
    } else {
        // Tambah
        mysqli_query($conn, "INSERT INTO modul (praktikum_id, judul, pertemuan_ke, file_materi) VALUES ('$praktikum_id', '$judul', '$pertemuan_ke', '$file_materi')");
        header('Location: modul.php?praktikum_id=' . $praktikum_id . '&msg=Berhasil tambah');
        exit;
    }
}
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM modul WHERE id='$id'"));
}
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM modul WHERE id='$id'");
    header('Location: modul.php?praktikum_id=' . $praktikum_id . '&msg=Berhasil hapus');
    exit;
}
// Ambil semua modul untuk praktikum ini
$modul = mysqli_query($conn, "SELECT * FROM modul WHERE praktikum_id='$praktikum_id' ORDER BY pertemuan_ke ASC");
include 'templates/header.php';
?>
<div class='w-full max-w-5xl'>
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-8 text-blue-900">Kelola Modul/Pertemuan</h1>
        <?php if(isset($_GET['msg'])): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded border border-green-300 shadow text-center text-lg font-semibold"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>
        <div class="bg-white rounded shadow p-8 mb-10 border border-blue-100">
            <h2 class="text-xl font-semibold mb-4 text-blue-700"><?php echo $edit ? 'Edit Modul' : 'Tambah Modul'; ?></h2>
            <form method="post" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">
                <div>
                    <label class="block font-semibold mb-1">Judul Modul</label>
                    <input type="text" name="judul" required class="w-full border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" value="<?php echo htmlspecialchars($edit['judul'] ?? ''); ?>">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Pertemuan Ke</label>
                    <input type="number" name="pertemuan_ke" required class="w-full border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" value="<?php echo htmlspecialchars($edit['pertemuan_ke'] ?? ''); ?>">
                </div>
                <div>
                    <label class="block font-semibold mb-1">File Materi (PDF/DOCX)</label>
                    <input type="file" name="file_materi" class="w-full border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200">
                    <?php if($edit && $edit['file_materi']): ?>
                        <a href="../uploads/materi/<?php echo $edit['file_materi']; ?>" class="text-blue-600 hover:underline text-sm ml-2" download>Lihat Materi</a>
                    <?php endif; ?>
                </div>
                <div class="flex gap-2 items-center">
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold shadow">Simpan</button>
                    <?php if($edit): ?>
                        <a href="modul.php?praktikum_id=<?php echo $praktikum_id; ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-semibold">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <div class="bg-white rounded shadow p-8 border border-blue-100">
            <h2 class="text-lg font-semibold mb-4 text-blue-700">Daftar Modul</h2>
            <div class="overflow-x-auto">
            <table class="w-full text-left border border-gray-200 rounded-lg">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="py-3 px-4 border-b font-semibold">Pertemuan</th>
                        <th class="py-3 px-4 border-b font-semibold">Judul</th>
                        <th class="py-3 px-4 border-b font-semibold">Materi</th>
                        <th class="py-3 px-4 border-b font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($modul)): ?>
                    <tr class="hover:bg-blue-50">
                        <td class="py-2 px-4 border-b align-top"><?php echo $row['pertemuan_ke']; ?></td>
                        <td class="py-2 px-4 border-b align-top"><?php echo htmlspecialchars($row['judul']); ?></td>
                        <td class="py-2 px-4 border-b align-top">
                            <?php if($row['file_materi']): ?>
                                <a href="../uploads/materi/<?php echo $row['file_materi']; ?>" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 font-semibold" download>Lihat</a>
                            <?php endif; ?>
                        </td>
                        <td class="py-2 px-4 border-b align-top flex flex-wrap gap-2">
                            <a href="modul.php?praktikum_id=<?php echo $praktikum_id; ?>&edit=<?php echo $row['id']; ?>" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 font-semibold">Edit</a>
                            <a href="modul.php?praktikum_id=<?php echo $praktikum_id; ?>&delete=<?php echo $row['id']; ?>" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 font-semibold" onclick="return confirm('Yakin hapus?')">Hapus</a>
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